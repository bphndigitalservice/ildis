#!/usr/bin/env bash

timezone=$(echo "$1")

function info {
  echo " "
  echo "--> $1"
  echo " "
}

info "Provision-script user: $(whoami)"

export DEBIAN_FRONTEND=noninteractive

info "Set timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Add PPA for PHP 7.4"
apt-get update
apt-get install -y software-properties-common
add-apt-repository -y ppa:ondrej/php
apt-get update

info "Install PHP 7.4 and extensions"
apt-get install -y \
  php7.4 php7.4-cli php7.4-common php7.4-curl php7.4-mbstring \
  php7.4-intl php7.4-mysql php7.4-xml php7.4-fpm php7.4-gd php7.4-zip \
  php7.4-xdebug unzip nginx mysql-server

info "Configure MySQL"
sed -i "s/^bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
systemctl restart mysql

mysql -uroot <<EOF
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';
CREATE USER 'root'@'%' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';
FLUSH PRIVILEGES;
EOF
echo "Done!"

info "Configure PHP-FPM for vagrant user"
sed -i 's/user = www-data/user = vagrant/' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/' /etc/php/7.4/fpm/pool.d/www.conf

cat << EOF > /etc/php/7.4/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_autostart=1
xdebug.remote_port=9000
xdebug.remote_connect_back=1
EOF

systemctl enable php7.4-fpm
systemctl restart php7.4-fpm

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/' /etc/nginx/nginx.conf

info "Enable app.conf"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf || true

info "Create initial databases"
mysql -uroot <<EOF
CREATE DATABASE IF NOT EXISTS yii2practical;
CREATE DATABASE IF NOT EXISTS yii2practical_test;
EOF

info "Install Composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
