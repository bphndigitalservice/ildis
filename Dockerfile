# ============================================
# Stage 1: Build dependencies
# ============================================
FROM composer:2 AS builder

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

COPY . .


# ============================================
# Stage 2: Production runtime
# ============================================
# Pin PHP 8.3.36+ to mitigate PHP2Shell (CVE-2026-109618, CVE-2026-109619).
# Digest locks the multi-arch index so rebuilds cannot silently regress.
FROM php:8.3.36-fpm-alpine@sha256:81cb692948e6de4275f7e107c50a34e963a440660f2c5b8502d11071f7d30338 AS production

WORKDIR /var/www

# Install system dependencies and build tools
RUN apk add --no-cache \
    nginx \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    icu \
    icu-libs \
    gcc \
    make \
    musl-dev

# Download and install s6-overlay v3.2.2.0
RUN curl -L -o /tmp/s6-overlay-noarch.tar.xz https://github.com/just-containers/s6-overlay/releases/download/v3.2.2.0/s6-overlay-noarch.tar.xz \
    && curl -L -o /tmp/s6-overlay-x86_64.tar.xz https://github.com/just-containers/s6-overlay/releases/download/v3.2.2.0/s6-overlay-x86_64.tar.xz \
    && tar -C / -Jxf /tmp/s6-overlay-noarch.tar.xz \
    && tar -C / -Jxf /tmp/s6-overlay-x86_64.tar.xz \
    && rm /tmp/s6-overlay-*.tar.xz

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    zip \
    gd \
    intl \
    calendar

# Use PHP production ini — disables display_errors so warnings go to log, not HTTP output
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# PHP-FPM clears env vars by default (clear_env=yes), which breaks getenv() for
# Docker-injected environment variables. Disable it so runtime env vars work without .env.
RUN echo '[www]' > /usr/local/etc/php-fpm.d/zz-docker-env.conf \
    && echo 'clear_env = no' >> /usr/local/etc/php-fpm.d/zz-docker-env.conf

# Copy application files
COPY --from=builder /app /var/www

# Initialize Yii2 configuration
RUN php init --env=Production --overwrite=n

# Setup permissions - create directories first
RUN mkdir -p /var/www/runtime \
    && mkdir -p /var/www/console/runtime \
    && mkdir -p /var/www/backups \
    && mkdir -p /var/www/backend/web/assets \
    && mkdir -p /var/www/backend/web/uploads \
    && mkdir -p /var/www/frontend/web/assets \
    && mkdir -p /var/www/frontend/web/uploads \
    && chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/runtime \
    && chmod -R 775 /var/www/console/runtime \
    && chmod -R 775 /var/www/backups \
    && chmod -R 775 /var/www/backend/web/assets \
    && chmod -R 775 /var/www/frontend/web/assets

# Configure Nginx (use a file to avoid shell-escaping issues)
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Create s6-overlay v3 service directories
# Each service needs: a directory, a type file, and a run script
# Then they must be listed in a bundle so s6-rc activates them on boot

RUN mkdir -p /etc/s6-overlay/s6-rc.d/php-fpm \
    && mkdir -p /etc/s6-overlay/s6-rc.d/nginx \
    && mkdir -p /etc/s6-overlay/s6-rc.d/user/contents.d

# php-fpm service
RUN printf '#!/bin/sh\nexec php-fpm\n' > /etc/s6-overlay/s6-rc.d/php-fpm/run \
    && chmod 755 /etc/s6-overlay/s6-rc.d/php-fpm/run \
    && echo 'longrun' > /etc/s6-overlay/s6-rc.d/php-fpm/type

# nginx service
RUN printf '#!/bin/sh\nexec nginx -g "daemon off;"\n' > /etc/s6-overlay/s6-rc.d/nginx/run \
    && chmod 755 /etc/s6-overlay/s6-rc.d/nginx/run \
    && echo 'longrun' > /etc/s6-overlay/s6-rc.d/nginx/type

# Register services in the default user bundle so they start on boot
RUN touch /etc/s6-overlay/s6-rc.d/user/contents.d/php-fpm \
    && touch /etc/s6-overlay/s6-rc.d/user/contents.d/nginx

# Add container init script to run database migrations on startup
# This runs once before services start, then applies pending migrations
RUN mkdir -p /etc/s6-overlay/s6-rc.d/ildis-init
COPY docker/ildis-init.sh /etc/s6-overlay/s6-rc.d/ildis-init/up
RUN chmod 755 /etc/s6-overlay/s6-rc.d/ildis-init/up \
    && echo 'oneshot' > /etc/s6-overlay/s6-rc.d/ildis-init/type \
    && touch /etc/s6-overlay/s6-rc.d/user/contents.d/ildis-init \
    && mkdir -p /etc/s6-overlay/s6-rc.d/php-fpm/dependencies.d \
    && touch /etc/s6-overlay/s6-rc.d/php-fpm/dependencies.d/ildis-init

RUN touch /var/www/.ildis_patched

EXPOSE 80

ENTRYPOINT ["/init"]