#!/usr/bin/env bash
#
# ILDIS Pasang Sekali Klik
# Pasang ILDIS dengan satu perintah:
#   curl -fsSL https://raw.githubusercontent.com/bphndigitalservice/ildis/main/install.sh | bash
#
# Penggunaan:
#   ./install.sh                  Pasang interaktif
#   ./install.sh --non-interactive  Gunakan env var, tanpa prompt
#   ./install.sh --update          Perbarui instalasi yang ada
#   ./install.sh --help            Tampilkan bantuan

set -euo pipefail

# ── Configuration ──────────────────────────────────────────────────────────
GITHUB_REPO="bphndigitalservice/ildis"
GHCR_IMAGE="ghcr.io/${GITHUB_REPO}"
COMPOSE_FILE="docker-compose.yml"
ENV_FILE=".env"
VERSION_FILE="VERSION"
BACKUP_DIR="backups"
DEFAULT_PORT=8080
if mkdir -p "/opt/ildis" 2>/dev/null && rmdir "/opt/ildis" 2>/dev/null; then
    DEFAULT_INSTALL_DIR="/opt/ildis"
else
    DEFAULT_INSTALL_DIR="$(pwd)/ildis"
fi
MIN_DISK_MB=1024
HEALTH_RETRIES=12
HEALTH_INTERVAL=5
MYSQL_HEALTH_RETRIES=30
MYSQL_HEALTH_INTERVAL=2

REVERSE_PROXY=false
SSL_MODE="none"
SSL_DOMAIN=""
SSL_EMAIL=""
SSL_CERT_PATH="ssl/server.crt"
SSL_KEY_PATH="ssl/server.key"
ADMIN_USERNAME=""
ADMIN_PASSWORD=""
DB_PORT_EXPOSE=""

COMPOSE_CMD=""
CONTAINER_RUNTIME=""

# ── Colors ──────────────────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

# ── Helper functions ────────────────────────────────────────────────────────
info()    { echo -e "${BLUE}[INFO]${NC} $*"; }
success() { echo -e "${GREEN}[OK]${NC} $*"; }
warn()    { echo -e "${YELLOW}[PERINGATAN]${NC} $*"; }
fail()    { echo -e "${RED}[GAGAL]${NC} $*"; exit 1; }

generate_random_key() {
    if command -v openssl &>/dev/null; then
        openssl rand -base64 32 | tr -d '=\n'
    elif [ -r /dev/urandom ]; then
        head -c 32 /dev/urandom | base64 | tr -d '=\n'
    else
        echo "RANDOM_KEY_$(date +%s)_$$"
    fi
}

confirm() {
    local prompt="$1"
    local default="${2:-n}"
    if [ "${NON_INTERACTIVE}" = true ]; then
        echo "$default"
        return
    fi
    local choices=""
    if [ "$default" = "y" ]; then
        choices="[Y/n]"
    else
        choices="[y/N]"
    fi
    read -rp "${prompt} ${choices} " REPLY </dev/tty
    REPLY="${REPLY:-$default}"
    [[ "$REPLY" =~ ^[Yy]$ ]]
}

# Petunjuk mengubah reCAPTCHA setelah instalasi (ditampilkan di akhir install & di komentar .env)
print_recaptcha_env_help() {
    local env_path="${1:-${INSTALL_DIR}/${ENV_FILE}}"
    echo ""
    echo -e "${BOLD}reCAPTCHA (login backend/CMS):${NC}"
    echo "  File:     ${env_path}"
    echo "  Variabel: RECAPTCHA_ENABLED, RECAPTCHA_SITE_KEY, RECAPTCHA_SECRET_KEY"
    echo ""
    echo "  Nonaktifkan (lokal/dev, tanpa Google):"
    echo "    RECAPTCHA_ENABLED=false"
    echo "    RECAPTCHA_SITE_KEY="
    echo "    RECAPTCHA_SECRET_KEY="
    echo ""
    echo "  Aktifkan (production):"
    echo "    RECAPTCHA_ENABLED=true"
    echo "    RECAPTCHA_SITE_KEY=<kunci situs dari Google reCAPTCHA v3>"
    echo "    RECAPTCHA_SECRET_KEY=<kunci rahasia>"
    echo ""
    echo "  Setelah mengubah .env, muat ulang container aplikasi:"
    echo "    ${COMPOSE_CMD} -f ${INSTALL_DIR}/${COMPOSE_FILE} --env-file ${env_path} up -d app"
    echo ""
}

prompt_value() {
    local prompt="$1"
    local default="$2"
    local is_secret="${3:-false}"
    if [ "${NON_INTERACTIVE}" = true ]; then
        echo "$default"
        return
    fi
    if [ "$is_secret" = true ]; then
        read -rsp "${prompt} [generated]: " REPLY </dev/tty
        echo ""
        REPLY="${REPLY:-$default}"
    else
        read -rp "${prompt} [${default}]: " REPLY </dev/tty
        REPLY="${REPLY:-$default}"
    fi
    echo "$REPLY"
}

# ── Compose command wrapper ──────────────────────────────────────────────────
# Normalizes differences between docker compose, podman compose, and podman-compose.
run_compose() {
    if [ "${COMPOSE_CMD}" = "podman-compose" ]; then
        # podman-compose: load env manually, no --env-file flag
        set -a
        # shellcheck disable=SC1090
        [ -f "${INSTALL_DIR}/${ENV_FILE}" ] && source "${INSTALL_DIR}/${ENV_FILE}" 2>/dev/null || true
        set +a
        podman-compose -f "${INSTALL_DIR}/${COMPOSE_FILE}" "$@"
    else
        # docker compose and podman compose: use --env-file flag
        ${COMPOSE_CMD} -f "${INSTALL_DIR}/${COMPOSE_FILE}" --env-file "${INSTALL_DIR}/${ENV_FILE}" "$@"
    fi
}

run_compose_update() {
    local compose_file="${1}"
    local env_file="${2}"
    shift 2
    if [ "${COMPOSE_CMD}" = "podman-compose" ]; then
        set -a
        # shellcheck disable=SC1090
        [ -f "${env_file}" ] && source "${env_file}" 2>/dev/null || true
        set +a
        podman-compose -f "${compose_file}" "$@"
    else
        ${COMPOSE_CMD} -f "${compose_file}" --env-file "${env_file}" "$@"
    fi
}

# ── Parse arguments ─────────────────────────────────────────────────────────
INSTALL_DIR="${INSTALL_DIR:-}"
ACTION="install"
NON_INTERACTIVE=false
DB_TYPE_OVERRIDE=""

while [[ $# -gt 0 ]]; do
    case "$1" in
        --non-interactive|-n)
            NON_INTERACTIVE=true
            shift
            ;;
        --update|-u)
            ACTION="update"
            shift
            ;;
        --dir|-d)
            INSTALL_DIR="$2"
            shift 2
            ;;
        --db-type|-t)
            DB_TYPE_OVERRIDE="$2"
            shift 2
            ;;
        --reverse-proxy)
            REVERSE_PROXY=true
            shift
            ;;
        --ssl-mode)
            SSL_MODE="$2"
            shift 2
            ;;
        --ssl-domain)
            SSL_DOMAIN="$2"
            shift 2
            ;;
        --ssl-email)
            SSL_EMAIL="$2"
            shift 2
            ;;
        --admin-username)
            ADMIN_USERNAME="$2"
            shift 2
            ;;
        --admin-password)
            ADMIN_PASSWORD="$2"
            shift 2
            ;;
        --db-port)
            DB_PORT_EXPOSE="$2"
            shift 2
            ;;
        --help|-h)
            ACTION="help"
            shift
            ;;
        *)
            echo "Opsi tidak dikenal: $1"
            echo "Jalankan './install.sh --help' untuk penggunaan."
            exit 1
            ;;
    esac
done

show_help() {
    cat <<'EOF'
ILDIS Pasang Sekali Klik — Pasang ILDIS dengan Docker/Podman

Penggunaan:
  ./install.sh                      Pasang interaktif
  ./install.sh --non-interactive    Gunakan env var, tanpa prompt
  ./install.sh --update             Perbarui instalasi yang ada
  ./install.sh --dir /opt/ildis    Tentukan direktori instalasi
  ./install.sh --db-type mariadb   Atur tipe DB (mariadb|mysql|external)
  ./install.sh --reverse-proxy     ILDIS di belakang reverse proxy
  ./install.sh --ssl-mode none     Mode SSL: none, letsencrypt, manual
  ./install.sh --ssl-domain example.com  Domain untuk Traefik/SSL
  ./install.sh --ssl-email user@example.com  Email untuk Let's Encrypt
  ./install.sh --admin-username admin   Username superadmin
  ./install.sh --admin-password secret   Password superadmin
  ./install.sh --db-port 3306           Ekspos port database ke host
  ./install.sh --help               Tampilkan bantuan ini

Container runtime:
  Docker Compose (docker compose) — dideteksi otomatis
  Podman Compose (podman compose) — dideteksi otomatis
  podman-compose                   — dideteksi otomatis

Variabel lingkungan (untuk --non-interactive):
  INSTALL_DIR               Direktori instalasi (bawaan: /opt/ildis)
  PORT                      Port aplikasi (bawaan: 8080)
  PUBLIC_DOMAIN             URL publik (bawaan: http://localhost:8080)
  DB_TYPE                   Tipe database: mariadb, mysql, external
  DB_HOST                   Host database (untuk external)
  DB_USER                   Pengguna database
  DB_PASSWORD               Kata sandi database
  DB_DATABASE               Nama database (bawaan: ildis_v4)
  DB_DATABASE_PORT           Port database (bawaan: 3306)
  BEHIND_REVERSE_PROXY      true|false — di belakang reverse proxy (bawaan: false)
  SSL_MODE                  none|letsencrypt|manual — mode SSL (bawaan: none)
  SSL_DOMAIN                Domain untuk Traefik dan sertifikat
  SSL_EMAIL                 Email untuk Let's Encrypt
  SSL_CERT_PATH             Path sertifikat SSL relatif terhadap INSTALL_DIR (bawaan: ssl/server.crt)
  SSL_KEY_PATH              Path kunci privat SSL relatif terhadap INSTALL_DIR (bawaan: ssl/server.key)
  ADMIN_USERNAME            Username superadmin (bawaan: admin)
  ADMIN_PASSWORD            Password superadmin (wajib)
  DB_PORT_EXPOSE            Port host untuk ekspos database (contoh: 3306, kosongkan untuk tidak ekspos)
  RECAPTCHA_ENABLED         true|false — aktifkan reCAPTCHA di login backend (bawaan: false)
  RECAPTCHA_SITE_KEY        Kunci situs reCAPTCHA v3 (wajib jika RECAPTCHA_ENABLED=true)
  RECAPTCHA_SECRET_KEY      Kunci rahasia reCAPTCHA (wajib jika RECAPTCHA_ENABLED=true)

Contoh:
  curl -fsSL https://raw.githubusercontent.com/bphndigitalservice/ildis/main/install.sh | bash
  ./install.sh --non-interactive --db-type external
  ./install.sh --update
EOF
    exit 0
}

if [ "$ACTION" = "help" ]; then
    show_help
fi

# ── Prerequisite checks ─────────────────────────────────────────────────────
check_prerequisites() {
    info "Memeriksa prasyarat..."

    COMPOSE_CMD=""
    CONTAINER_RUNTIME=""
    if command -v docker &>/dev/null && docker compose version &>/dev/null 2>&1; then
        COMPOSE_CMD="docker compose"
        CONTAINER_RUNTIME="docker"
        success "Docker Compose ditemukan"
    elif command -v podman &>/dev/null; then
        if podman compose version &>/dev/null 2>&1; then
            COMPOSE_CMD="podman compose"
            CONTAINER_RUNTIME="podman"
            success "Podman Compose ditemukan"
        elif command -v podman-compose &>/dev/null; then
            COMPOSE_CMD="podman-compose"
            CONTAINER_RUNTIME="podman"
            success "podman-compose ditemukan"
        fi
    fi

    if [ -z "${COMPOSE_CMD}" ]; then
        echo ""
        echo -e "${RED}Tidak ditemukan Docker Compose maupun Podman Compose.${NC}"
        echo ""
        echo "Pasang salah satu:"
        echo "  Docker:       https://docs.docker.com/engine/install/"
        echo "  Podman:       https://podman.io/getting-started/installation"
        echo "  podman-compose: pip install podman-compose"
        fail "Docker Compose atau Podman Compose diperlukan."
    fi

    local available_kb available_mb
    available_kb=$(df -k . | awk 'NR==2 {print $4}')
    available_mb=$((available_kb / 1024))
    if [ "${available_mb}" -lt "${MIN_DISK_MB}" ]; then
        fail "Ruang disk tidak cukup: ${available_mb}MB tersedia, ${MIN_DISK_MB}MB diperlukan."
    fi
    success "Ruang disk: ${available_mb}MB tersedia"

    local port="${PORT:-${DEFAULT_PORT}}"
    if command -v ss &>/dev/null; then
        if ss -tlnp 2>/dev/null | grep -q ":${port} "; then
            warn "Port ${port} tampaknya sedang digunakan."
            warn "Anda dapat mengubahnya dengan PORT= saat menjalankan skrip."
        fi
    elif command -v lsof &>/dev/null; then
        if lsof -i ":${port}" &>/dev/null 2>&1; then
            warn "Port ${port} tampaknya sedang digunakan."
        fi
    fi

    success "Prasyarat OK (${CONTAINER_RUNTIME})"
}

# ── Interactive wizard ───────────────────────────────────────────────────────
run_wizard() {
    echo ""
    echo -e "${BOLD}╔══════════════════════════════════════════╗${NC}"
    echo -e "${BOLD}║        Panduan Instalasi ILDIS            ║${NC}"
    echo -e "${BOLD}╚══════════════════════════════════════════╝${NC}"
    echo ""

    INSTALL_DIR=$(prompt_value "Direktori instalasi" "${INSTALL_DIR:-${DEFAULT_INSTALL_DIR}}")
    echo ""

    PORT=$(prompt_value "Port aplikasi" "${PORT:-${DEFAULT_PORT}}")
    echo ""

    echo -e "${BOLD}Konfigurasi jaringan:${NC}"
    if confirm "  Apakah ILDIS di belakang reverse proxy (Nginx/Apache/Traefik lain)?" "n"; then
        REVERSE_PROXY=true
    else
        REVERSE_PROXY=false
    fi
    echo ""

    if [ "${REVERSE_PROXY}" = false ]; then
        echo -e "${BOLD}Konfigurasi SSL/TLS:${NC}"
        echo "  1) Tidak ada (HTTP saja)"
        echo "  2) Let's Encrypt (otomatis, perlu domain publik)"
        echo "  3) Manual (sertifikat sendiri)"
        echo ""
        local ssl_choice
        ssl_choice=$(prompt_value "Pilih mode SSL (1/2/3)" "1")
        case "$ssl_choice" in
            2) SSL_MODE="letsencrypt" ;;
            3) SSL_MODE="manual" ;;
            *) SSL_MODE="none" ;;
        esac

        if [ "${SSL_MODE}" = "letsencrypt" ] || [ "${SSL_MODE}" = "manual" ]; then
            SSL_DOMAIN=$(prompt_value "  Domain" "${SSL_DOMAIN:-}")
            if [ -z "${SSL_DOMAIN}" ]; then
                fail "Domain diperlukan untuk SSL."
            fi
        fi

        if [ "${SSL_MODE}" = "letsencrypt" ]; then
            SSL_EMAIL=$(prompt_value "  Email untuk Let's Encrypt" "${SSL_EMAIL:-}")
            if [ -z "${SSL_EMAIL}" ]; then
                fail "Email diperlukan untuk Let's Encrypt."
            fi
        fi

        if [ "${SSL_MODE}" = "manual" ]; then
            SSL_CERT_PATH=$(prompt_value "  Path sertifikat SSL (relatif terhadap ${INSTALL_DIR})" "${SSL_CERT_PATH}")
            SSL_KEY_PATH=$(prompt_value "  Path kunci privat SSL (relatif terhadap ${INSTALL_DIR})" "${SSL_KEY_PATH}")
        fi

        if [ "${SSL_MODE}" = "none" ]; then
            SSL_DOMAIN=$(prompt_value "  Domain (opsional, tekan Enter untuk localhost)" "${SSL_DOMAIN:-localhost}")
        fi
        echo ""
    fi

    if [ "${REVERSE_PROXY}" = true ]; then
        PUBLIC_DOMAIN=$(prompt_value "URL domain publik (dari reverse proxy)" "${PUBLIC_DOMAIN:-http://localhost:${PORT}}")
    elif [ "${SSL_MODE}" = "letsencrypt" ] || [ "${SSL_MODE}" = "manual" ]; then
        PUBLIC_DOMAIN="https://${SSL_DOMAIN}"
    elif [ "${SSL_MODE}" = "none" ] && [ "${SSL_DOMAIN}" != "localhost" ] && [ -n "${SSL_DOMAIN}" ]; then
        PUBLIC_DOMAIN="http://${SSL_DOMAIN}"
    else
        PUBLIC_DOMAIN=$(prompt_value "URL domain publik" "${PUBLIC_DOMAIN:-http://localhost:${PORT}}")
    fi
    echo ""

    echo -e "${BOLD}Konfigurasi database:${NC}"
    echo "  1) MariaDB 10.11 (disarankan, bawaan)"
    echo "  2) MySQL 8.0"
    echo "  3) Database eksternal"
    echo ""
    if [ -n "${DB_TYPE_OVERRIDE}" ]; then
        DB_TYPE="${DB_TYPE_OVERRIDE}"
    else
        local db_choice
        db_choice=$(prompt_value "Pilih tipe database (1/2/3)" "1")
        case "$db_choice" in
            2) DB_TYPE="mysql" ;;
            3) DB_TYPE="external" ;;
            *) DB_TYPE="mariadb" ;;
        esac
    fi

    DB_PASSWORD="${DB_PASSWORD:-}"
    if [ "${DB_TYPE}" = "external" ]; then
        echo ""
        echo -e "${CYAN}Database eksternal dipilih. Masukkan detail koneksi:${NC}"
        DB_HOST=$(prompt_value "  Host database" "${DB_HOST:-}")
        DB_DATABASE_PORT=$(prompt_value "  Port database" "${DB_DATABASE_PORT:-3306}")
        DB_USER=$(prompt_value "  Pengguna database" "${DB_USER:-ildis}")
        DB_DATABASE=$(prompt_value "  Nama database" "${DB_DATABASE:-ildis_v4}")
        DB_PASSWORD=$(prompt_value "  Kata sandi database" "" "true")
        if [ -z "${DB_PASSWORD}" ]; then
            fail "Kata sandi database diperlukan untuk database eksternal."
        fi
    else
        local db_label="MariaDB 10.11"
        [ "${DB_TYPE}" = "mysql" ] && db_label="MySQL 8.0"
        echo ""
        echo -e "${CYAN}${db_label} dipilih.${NC}"

        if [ -z "${DB_PASSWORD}" ]; then
            DB_PASSWORD=$(generate_random_key)
            echo -e "  Kata sandi database dibuat: ${YELLOW}${DB_PASSWORD}${NC}"
            echo "  Simpan kata sandi ini di tempat yang aman!"
        fi
        DB_USER=$(prompt_value "  Pengguna database" "${DB_USER:-ildis}")
        DB_DATABASE=$(prompt_value "  Nama database" "${DB_DATABASE:-ildis_v4}")
    fi
    echo ""

    echo -e "${BOLD}Superadmin (akun pertama):${NC}"
    ADMIN_USERNAME=$(prompt_value "  Nama pengguna superadmin" "${ADMIN_USERNAME:-admin}")
    while true; do
        read -rsp "  Kata sandi superadmin: " ADMIN_PASSWORD </dev/tty
        echo ""
        if [ -z "${ADMIN_PASSWORD}" ]; then
            echo -e "  ${RED}Kata sandi tidak boleh kosong.${NC}"
            continue
        fi
        if [ "${#ADMIN_PASSWORD}" -lt 8 ]; then
            echo -e "  ${RED}Kata sandi minimal 8 karakter.${NC}"
            continue
        fi
        local pw_confirm
        read -rsp "  Konfirmasi kata sandi: " pw_confirm </dev/tty
        echo ""
        if [ "${ADMIN_PASSWORD}" != "${pw_confirm}" ]; then
            echo -e "  ${RED}Kata sandi tidak cocok.${NC}"
            continue
        fi
        break
    done
    echo ""

    if [ "${DB_TYPE}" != "external" ]; then
        echo -e "${BOLD}Port Database (opsional):${NC}"
        echo "  Ekspos database ke host agar bisa diakses dari luar container (mis. dengan MySQL Workbench)."
        echo "  Kosongkan jika tidak perlu."
        DB_PORT_EXPOSE=$(prompt_value "  Port database ke host (contoh: 3306, kosongkan untuk tidak ekspos)" "${DB_PORT_EXPOSE:-}")
        echo ""
    fi

    echo -e "${BOLD}reCAPTCHA v3 (halaman login backend/CMS):${NC}"
    echo "  Untuk instal lokal/dev, pilih tidak — menghindari error grecaptcha tanpa kunci Google."
    if confirm "  Aktifkan reCAPTCHA pada login backend?" "n"; then
        RECAPTCHA_ENABLED=true
        RECAPTCHA_SITE_KEY=$(prompt_value "  Kunci situs reCAPTCHA" "${RECAPTCHA_SITE_KEY:-}")
        RECAPTCHA_SECRET_KEY=$(prompt_value "  Kunci rahasia reCAPTCHA" "${RECAPTCHA_SECRET_KEY:-}" "true")
        if [ -z "${RECAPTCHA_SITE_KEY}" ] || [ -z "${RECAPTCHA_SECRET_KEY}" ]; then
            warn "Kunci reCAPTCHA kosong — reCAPTCHA dinonaktifkan."
            RECAPTCHA_ENABLED=false
            RECAPTCHA_SITE_KEY=""
            RECAPTCHA_SECRET_KEY=""
        fi
    else
        RECAPTCHA_ENABLED=false
        RECAPTCHA_SITE_KEY=""
        RECAPTCHA_SECRET_KEY=""
    fi
    echo ""

    COOKIE_VALIDATION_KEY_BE="${COOKIE_VALIDATION_KEY_BE:-$(generate_random_key)}"
    COOKIE_VALIDATION_KEY_FE="${COOKIE_VALIDATION_KEY_FE:-$(generate_random_key)}"

    YII_ENV="${YII_ENV:-prod}"
    YII_DEBUG="${YII_DEBUG:-false}"

    echo ""
    echo -e "${BOLD}════════════════════════════════════════════${NC}"
    echo -e "${BOLD}Ringkasan Instalasi:${NC}"
    echo -e "${BOLD}════════════════════════════════════════════${NC}"
    echo "  Direktori:   ${INSTALL_DIR}"
    echo "  Port:        ${PORT}"
    echo "  Domain:      ${PUBLIC_DOMAIN}"
    echo "  Database:    ${DB_TYPE}"
    if [ "${DB_TYPE}" = "external" ]; then
        echo "  Host DB:     ${DB_HOST}:${DB_DATABASE_PORT}"
    fi
    echo "  Pengguna DB: ${DB_USER}"
    echo "  Nama DB:     ${DB_DATABASE}"
    if [ -n "${DB_PORT_EXPOSE}" ] && [ "${DB_TYPE}" != "external" ]; then
        echo "  Port DB ke host: ${DB_PORT_EXPOSE}:3306"
    fi
    echo "  Superadmin:  ${ADMIN_USERNAME}"
    if [ "${REVERSE_PROXY}" = true ]; then
        echo "  Reverse proxy: ya (SSL ditangani reverse proxy)"
    else
        echo "  Reverse proxy: tidak (Traefik digunakan)"
        echo "  SSL:         ${SSL_MODE}"
        if [ "${SSL_MODE}" != "none" ]; then
            echo "  Domain:     ${SSL_DOMAIN}"
        fi
        if [ "${SSL_MODE}" = "letsencrypt" ]; then
            echo "  Email LE:    ${SSL_EMAIL}"
        fi
    fi
    if [ "${RECAPTCHA_ENABLED}" = true ]; then
        echo "  reCAPTCHA:   aktif"
    else
        echo "  reCAPTCHA:   nonaktif (ubah di .env bila diperlukan)"
    fi
    echo ""

    if [ "${NON_INTERACTIVE}" = false ]; then
        if ! confirm "Lanjutkan instalasi?" "y"; then
            echo "Instalasi dibatalkan."
            exit 0
        fi
    fi
}

# ── Generate .env file ──────────────────────────────────────────────────────
generate_env() {
    info "Membuat ${ENV_FILE}..."

    cat > "${INSTALL_DIR}/${ENV_FILE}" <<EOF
# Konfigurasi Lingkungan ILDIS
# Dibuat oleh install.sh pada $(date -Iseconds 2>/dev/null || date)
# Periksa dan perbarui sesuai kebutuhan.

# ── Aplikasi ──
YII_ENV=${YII_ENV}
YII_DEBUG=${YII_DEBUG}
PORT=${PORT:-${DEFAULT_PORT}}
PUBLIC_DOMAIN=${PUBLIC_DOMAIN}

# ── Database ──
DB_TYPE=${DB_TYPE}
DB_HOST=${DB_HOST:-mysql}
DB_USER=${DB_USER}
DB_PASSWORD=${DB_PASSWORD}
DB_DATABASE=${DB_DATABASE}
DB_DATABASE_PORT=${DB_DATABASE_PORT:-3306}

# ── Validasi cookie (dibuat otomatis) ──
COOKIE_VALIDATION_KEY_BE=${COOKIE_VALIDATION_KEY_BE}
COOKIE_VALIDATION_KEY_FE=${COOKIE_VALIDATION_KEY_FE}

# ── reCAPTCHA (login backend/CMS) ──
# RECAPTCHA_ENABLED: true | false
# Ubah kapan saja, lalu: docker compose --env-file .env up -d app
RECAPTCHA_ENABLED=${RECAPTCHA_ENABLED:-false}
RECAPTCHA_SITE_KEY=${RECAPTCHA_SITE_KEY:-}
RECAPTCHA_SECRET_KEY=${RECAPTCHA_SECRET_KEY:-}

# ── Tag image kontainer ──
ILDIS_IMAGE_TAG=${ILDIS_IMAGE_TAG:-latest}

# ── Reverse proxy dan SSL ──
BEHIND_REVERSE_PROXY=${REVERSE_PROXY}
SSL_MODE=${SSL_MODE}
SSL_DOMAIN=${SSL_DOMAIN}
SSL_CERT_PATH=${SSL_CERT_PATH}
SSL_KEY_PATH=${SSL_KEY_PATH}

# ── Let's Encrypt ──
SSL_EMAIL=${SSL_EMAIL}

# ── Superadmin ──
# ADMIN_USERNAME dan ADMIN_PASSWORD tidak disimpan di .env untuk keamanan.
# Superadmin dibuat saat instalasi melalui perintah console.

# ── Port database ke host ──
# Kosongkan jika tidak perlu ekspos database ke luar container.
# Contoh: DB_PORT_EXPOSE=3306
DB_PORT_EXPOSE=${DB_PORT_EXPOSE}
EOF

    chmod 600 "${INSTALL_DIR}/${ENV_FILE}"
    success "${ENV_FILE} dibuat"
}

# ── Generate docker-compose.yml ─────────────────────────────────────────────
generate_compose() {
    info "Membuat ${COMPOSE_FILE}..."

    mkdir -p "${INSTALL_DIR}/logs/nginx"

    local DB_IMAGE=""
    local DB_CONTAINER_NAME=""
    local DB_HEALTHCHECK=""

    if [ "${DB_TYPE}" = "mariadb" ]; then
        DB_IMAGE="mariadb:10.11"
        DB_CONTAINER_NAME="ildis_mariadb"
        DB_HEALTHCHECK='      test: [ "CMD-SHELL", "healthcheck.sh --connect --innodb_initialized" ]
      interval: 10s
      timeout: 5s
      retries: 5'
    elif [ "${DB_TYPE}" = "mysql" ]; then
        DB_IMAGE="mysql:8.0"
        DB_CONTAINER_NAME="ildis_mysql"
        DB_HEALTHCHECK='      test: [ "CMD-SHELL", "mysqladmin ping -h localhost -u root -p$${MYSQL_ROOT_PASSWORD}" ]
      interval: 10s
      timeout: 5s
      retries: 5'
    fi

    local app_ports=""
    local app_labels=""
    local app_networks=""
    local traefik_service=""
    local traefik_volumes=""
    local traefik_ports=""
    local network_def=""
    local extra_volumes=""
    local db_ports=""

    if [ -n "${DB_PORT_EXPOSE}" ] && [ "${DB_TYPE}" != "external" ]; then
        db_ports="
    ports:
      - \"${DB_PORT_EXPOSE}:3306\""
    fi

    extra_volumes="
      - ./nginx/default.conf:/etc/nginx/http.d/default.conf:ro
      - ./logs/nginx:/var/log/nginx
      - app_logs:/var/www/runtime/logs"

    if [ "${REVERSE_PROXY}" = true ]; then
        app_ports="    ports:
      - \"\${PORT:-8080}:80\""
    else
        mkdir -p "${INSTALL_DIR}/logs/traefik"
        mkdir -p "${INSTALL_DIR}/ssl"

        app_labels="    labels:
      - \"traefik.enable=true\"
      - \"traefik.http.routers.ildis.rule=Host(\`\${SSL_DOMAIN}\`)\"
      - \"traefik.http.routers.ildis.entrypoints=web\"
      - \"traefik.http.services.ildis.loadbalancer.server.port=80\""
        app_networks="
    networks:
      - ildis_net"

        if [ "${SSL_MODE}" = "letsencrypt" ]; then
            app_labels="${app_labels}
      - \"traefik.http.routers.ildis-secure.rule=Host(\`\${SSL_DOMAIN}\`)\"
      - \"traefik.http.routers.ildis-secure.entrypoints=websecure\"
      - \"traefik.http.routers.ildis-secure.tls.certresolver=letsencrypt\"
      - \"traefik.http.routers.ildis-secure.service=ildis\""

            traefik_ports="    ports:
      - \"80:80\"
      - \"443:443\""
            traefik_volumes="
      - ./traefik/traefik.yml:/etc/traefik/traefik.yml:ro
      - ./traefik/config.yml:/etc/traefik/config/dynamic.yml:ro
      - /var/run/docker.sock:/var/run/docker.sock:ro
      - traefik_acme:/etc/traefik/acme
      - ./logs/traefik:/var/log/traefik"
        elif [ "${SSL_MODE}" = "manual" ]; then
            app_labels="${app_labels}
      - \"traefik.http.routers.ildis-secure.rule=Host(\`\${SSL_DOMAIN}\`)\"
      - \"traefik.http.routers.ildis-secure.entrypoints=websecure\"
      - \"traefik.http.routers.ildis-secure.service=ildis\"
      - \"traefik.http.routers.ildis-secure.tls=true\""

            traefik_ports="    ports:
      - \"80:80\"
      - \"443:443\""
            traefik_volumes="
      - ./traefik/traefik.yml:/etc/traefik/traefik.yml:ro
      - ./traefik/config.yml:/etc/traefik/config/dynamic.yml:ro
      - /var/run/docker.sock:/var/run/docker.sock:ro
      - ./ssl:/etc/traefik/certs:ro
      - ./logs/traefik:/var/log/traefik"
        else
            traefik_ports="    ports:
      - \"80:80\""
            traefik_volumes="
      - ./traefik/traefik.yml:/etc/traefik/traefik.yml:ro
      - ./traefik/config.yml:/etc/traefik/config/dynamic.yml:ro
      - /var/run/docker.sock:/var/run/docker.sock:ro
      - ./logs/traefik:/var/log/traefik"
        fi

        traefik_service="
  traefik:
    image: traefik:v3.0
    container_name: ildis_traefik
    restart: unless-stopped
    command:
      - \"--providers.docker=true\"
      - \"--providers.docker.exposedbydefault=false\"
      - \"--providers.file.filename=/etc/traefik/config/dynamic.yml\"
      - \"--entrypoints.web.address=:80\"
    volumes:${traefik_volumes}
    networks:
      - ildis_net
${traefik_ports}"

        network_def="
networks:
  ildis_net:
    driver: bridge"
    fi

    if [ "${REVERSE_PROXY}" = true ]; then
        if [ "${DB_TYPE}" = "external" ]; then
            cat > "${INSTALL_DIR}/${COMPOSE_FILE}" <<'COMPOSEEOF'
services:
  app:
COMPOSEEOF
            echo "    image: ghcr.io/bphndigitalservice/ildis:${ILDIS_IMAGE_TAG:-latest}" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
            cat >> "${INSTALL_DIR}/${COMPOSE_FILE}" <<'COMPOSEEOF'
    container_name: ildis_app
    restart: unless-stopped
COMPOSEEOF
            echo "    ports:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
            echo "      - \"\${PORT:-8080}:80\"" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
            cat >> "${INSTALL_DIR}/${COMPOSE_FILE}" <<'COMPOSEEOF'
    volumes:
      - runtime:/var/www/runtime
      - backend-assets:/var/www/backend/web/assets
      - frontend-assets:/var/www/frontend/web/assets
      - backend-uploads:/var/www/backend/web/uploads
      - frontend-uploads:/var/www/frontend/web/uploads
      - feed_data:/var/www/feed
COMPOSEEOF
            echo "${extra_volumes}" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
            cat >> "${INSTALL_DIR}/${COMPOSE_FILE}" <<'COMPOSEEOF'
    environment:
      - S6_KEEP_ENV=1
      - YII_ENV=${YII_ENV:-prod}
      - YII_DEBUG=${YII_DEBUG:-false}
      - DB_HOST=${DB_HOST}
      - DB_USER=${DB_USER}
      - DB_PASSWORD=${DB_PASSWORD}
      - DB_DATABASE=${DB_DATABASE:-ildis_v4}
      - DB_DATABASE_PORT=${DB_DATABASE_PORT:-3306}
      - PUBLIC_DOMAIN=${PUBLIC_DOMAIN:-http://localhost:8080}
      - COOKIE_VALIDATION_KEY_BE=${COOKIE_VALIDATION_KEY_BE}
      - COOKIE_VALIDATION_KEY_FE=${COOKIE_VALIDATION_KEY_FE}
      - RECAPTCHA_ENABLED=${RECAPTCHA_ENABLED:-false}
      - RECAPTCHA_SITE_KEY=${RECAPTCHA_SITE_KEY:-}
      - RECAPTCHA_SECRET_KEY=${RECAPTCHA_SECRET_KEY:-}
      - PHP_DISPLAY_ERRORS=Off
      - PHP_ERROR_REPORTING='E_ALL & ~E_DEPRECATED'
    healthcheck:
      test: [ "CMD", "curl", "-f", "http://localhost/" ]
      interval: 30s
      timeout: 10s
      retries: 3

COMPOSEEOF
            cat >> "${INSTALL_DIR}/${COMPOSE_FILE}" <<'COMPOSEEOF'
  cron:
COMPOSEEOF
            echo "    image: ghcr.io/bphndigitalservice/ildis-cron:${ILDIS_IMAGE_TAG:-latest}" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
            cat >> "${INSTALL_DIR}/${COMPOSE_FILE}" <<'COMPOSEEOF'
    container_name: ildis_cron
    restart: unless-stopped
    volumes:
      - feed_data:/var/www/feed
    environment:
      - YII_ENV=${YII_ENV:-prod}
      - YII_DEBUG=${YII_DEBUG:-false}
      - DB_HOST=${DB_HOST}
      - DB_USER=${DB_USER}
      - DB_PASSWORD=${DB_PASSWORD}
      - DB_DATABASE=${DB_DATABASE:-ildis_v4}
      - DB_DATABASE_PORT=${DB_DATABASE_PORT:-3306}
    stop_grace_period: 30s
    healthcheck:
      test: ["CMD", "test", "-f", "/var/www/feed/document.json"]
      interval: 60s
      timeout: 10s
      retries: 3
      start_period: 120s

volumes:
  runtime:
  backend-assets:
  frontend-assets:
  backend-uploads:
  frontend-uploads:
  feed_data:
  app_logs:
COMPOSEEOF
        else
            cat > "${INSTALL_DIR}/${COMPOSE_FILE}" <<EOF
services:
  db:
    image: ${DB_IMAGE}
    container_name: ${DB_CONTAINER_NAME}
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: \${DB_PASSWORD}
      MYSQL_DATABASE: \${DB_DATABASE:-ildis_v4}
      MYSQL_USER: \${DB_USER:-ildis}
      MYSQL_PASSWORD: \${DB_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql
    healthcheck:
${DB_HEALTHCHECK}
${db_ports}
  app:
    image: ghcr.io/bphndigitalservice/ildis:\${ILDIS_IMAGE_TAG:-latest}
    container_name: ildis_app
    restart: unless-stopped
    depends_on:
      db:
        condition: service_healthy
    ports:
      - "\${PORT:-8080}:80"
    volumes:
      - runtime:/var/www/runtime
      - backend-assets:/var/www/backend/web/assets
      - frontend-assets:/var/www/frontend/web/assets
      - backend-uploads:/var/www/backend/web/uploads
      - frontend-uploads:/var/www/frontend/web/uploads
      - feed_data:/var/www/feed${extra_volumes}
    environment:
      - S6_KEEP_ENV=1
      - YII_ENV=\${YII_ENV:-prod}
      - YII_DEBUG=\${YII_DEBUG:-false}
      - DB_HOST=db
      - DB_USER=\${DB_USER:-ildis}
      - DB_PASSWORD=\${DB_PASSWORD}
      - DB_DATABASE=\${DB_DATABASE:-ildis_v4}
      - DB_DATABASE_PORT=\${DB_DATABASE_PORT:-3306}
      - PUBLIC_DOMAIN=\${PUBLIC_DOMAIN:-http://localhost:8080}
      - COOKIE_VALIDATION_KEY_BE=\${COOKIE_VALIDATION_KEY_BE}
      - COOKIE_VALIDATION_KEY_FE=\${COOKIE_VALIDATION_KEY_FE}
      - RECAPTCHA_ENABLED=\${RECAPTCHA_ENABLED:-false}
      - RECAPTCHA_SITE_KEY=\${RECAPTCHA_SITE_KEY:-}
      - RECAPTCHA_SECRET_KEY=\${RECAPTCHA_SECRET_KEY:-}
      - PHP_DISPLAY_ERRORS=Off
      - PHP_ERROR_REPORTING='E_ALL & ~E_DEPRECATED'
    healthcheck:
      test: [ "CMD", "curl", "-f", "http://localhost/" ]
      interval: 30s
      timeout: 10s
      retries: 3

  cron:
    image: ghcr.io/bphndigitalservice/ildis-cron:\${ILDIS_IMAGE_TAG:-latest}
    container_name: ildis_cron
    restart: unless-stopped
    depends_on:
      db:
        condition: service_healthy
    volumes:
      - feed_data:/var/www/feed
    environment:
      - YII_ENV=\${YII_ENV:-prod}
      - YII_DEBUG=\${YII_DEBUG:-false}
      - DB_HOST=db
      - DB_USER=\${DB_USER:-ildis}
      - DB_PASSWORD=\${DB_PASSWORD}
      - DB_DATABASE=\${DB_DATABASE:-ildis_v4}
      - DB_DATABASE_PORT=\${DB_DATABASE_PORT:-3306}
    stop_grace_period: 30s
    healthcheck:
      test: ["CMD", "test", "-f", "/var/www/feed/document.json"]
      interval: 60s
      timeout: 10s
      retries: 3
      start_period: 120s

volumes:
  mysql_data:
  runtime:
  backend-assets:
  frontend-assets:
  backend-uploads:
  frontend-uploads:
  feed_data:
  app_logs:
EOF
        fi
    else
        if [ "${DB_TYPE}" = "external" ]; then
            cat > "${INSTALL_DIR}/${COMPOSE_FILE}" <<COMPOSEEOF
services:${traefik_service}
  app:
    image: ghcr.io/bphndigitalservice/ildis:\${ILDIS_IMAGE_TAG:-latest}
    container_name: ildis_app
    restart: unless-stopped
${app_labels}${app_networks}
    volumes:
      - runtime:/var/www/runtime
      - backend-assets:/var/www/backend/web/assets
      - frontend-assets:/var/www/frontend/web/assets
      - backend-uploads:/var/www/backend/web/uploads
      - frontend-uploads:/var/www/frontend/web/uploads
      - feed_data:/var/www/feed${extra_volumes}
    environment:
      - S6_KEEP_ENV=1
      - YII_ENV=\${YII_ENV:-prod}
      - YII_DEBUG=\${YII_DEBUG:-false}
      - DB_HOST=\${DB_HOST}
      - DB_USER=\${DB_USER}
      - DB_PASSWORD=\${DB_PASSWORD}
      - DB_DATABASE=\${DB_DATABASE:-ildis_v4}
      - DB_DATABASE_PORT=\${DB_DATABASE_PORT:-3306}
      - PUBLIC_DOMAIN=\${PUBLIC_DOMAIN:-http://localhost:8080}
      - COOKIE_VALIDATION_KEY_BE=\${COOKIE_VALIDATION_KEY_BE}
      - COOKIE_VALIDATION_KEY_FE=\${COOKIE_VALIDATION_KEY_FE}
      - RECAPTCHA_ENABLED=\${RECAPTCHA_ENABLED:-false}
      - RECAPTCHA_SITE_KEY=\${RECAPTCHA_SITE_KEY:-}
      - RECAPTCHA_SECRET_KEY=\${RECAPTCHA_SECRET_KEY:-}
      - PHP_DISPLAY_ERRORS=Off
      - PHP_ERROR_REPORTING='E_ALL & ~E_DEPRECATED'
    healthcheck:
      test: [ "CMD", "curl", "-f", "http://localhost/" ]
      interval: 30s
      timeout: 10s
      retries: 3

  cron:
    image: ghcr.io/bphndigitalservice/ildis-cron:\${ILDIS_IMAGE_TAG:-latest}
    container_name: ildis_cron
    restart: unless-stopped
    volumes:
      - feed_data:/var/www/feed
    environment:
      - YII_ENV=\${YII_ENV:-prod}
      - YII_DEBUG=\${YII_DEBUG:-false}
      - DB_HOST=\${DB_HOST}
      - DB_USER=\${DB_USER}
      - DB_PASSWORD=\${DB_PASSWORD}
      - DB_DATABASE=\${DB_DATABASE:-ildis_v4}
      - DB_DATABASE_PORT=\${DB_DATABASE_PORT:-3306}
    stop_grace_period: 30s
    healthcheck:
      test: ["CMD", "test", "-f", "/var/www/feed/document.json"]
      interval: 60s
      timeout: 10s
      retries: 3
      start_period: 120s
COMPOSEEOF
        else
            cat > "${INSTALL_DIR}/${COMPOSE_FILE}" <<EOF
services:
  db:
    image: ${DB_IMAGE}
    container_name: ${DB_CONTAINER_NAME}
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: \${DB_PASSWORD}
      MYSQL_DATABASE: \${DB_DATABASE:-ildis_v4}
      MYSQL_USER: \${DB_USER:-ildis}
      MYSQL_PASSWORD: \${DB_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql
    healthcheck:
${DB_HEALTHCHECK}
${db_ports}
    networks:
      - ildis_net
${traefik_service}
  app:
    image: ghcr.io/bphndigitalservice/ildis:\${ILDIS_IMAGE_TAG:-latest}
    container_name: ildis_app
    restart: unless-stopped
    depends_on:
      db:
        condition: service_healthy
${app_labels}${app_networks}
    volumes:
      - runtime:/var/www/runtime
      - backend-assets:/var/www/backend/web/assets
      - frontend-assets:/var/www/frontend/web/assets
      - backend-uploads:/var/www/backend/web/uploads
      - frontend-uploads:/var/www/frontend/web/uploads
      - feed_data:/var/www/feed${extra_volumes}
    environment:
      - S6_KEEP_ENV=1
      - YII_ENV=\${YII_ENV:-prod}
      - YII_DEBUG=\${YII_DEBUG:-false}
      - DB_HOST=db
      - DB_USER=\${DB_USER:-ildis}
      - DB_PASSWORD=\${DB_PASSWORD}
      - DB_DATABASE=\${DB_DATABASE:-ildis_v4}
      - DB_DATABASE_PORT=\${DB_DATABASE_PORT:-3306}
      - PUBLIC_DOMAIN=\${PUBLIC_DOMAIN:-http://localhost:8080}
      - COOKIE_VALIDATION_KEY_BE=\${COOKIE_VALIDATION_KEY_BE}
      - COOKIE_VALIDATION_KEY_FE=\${COOKIE_VALIDATION_KEY_FE}
      - RECAPTCHA_ENABLED=\${RECAPTCHA_ENABLED:-false}
      - RECAPTCHA_SITE_KEY=\${RECAPTCHA_SITE_KEY:-}
      - RECAPTCHA_SECRET_KEY=\${RECAPTCHA_SECRET_KEY:-}
      - PHP_DISPLAY_ERRORS=Off
      - PHP_ERROR_REPORTING='E_ALL & ~E_DEPRECATED'
    healthcheck:
      test: [ "CMD", "curl", "-f", "http://localhost/" ]
      interval: 30s
      timeout: 10s
      retries: 3

  cron:
    image: ghcr.io/bphndigitalservice/ildis-cron:\${ILDIS_IMAGE_TAG:-latest}
    container_name: ildis_cron
    restart: unless-stopped
    depends_on:
      db:
        condition: service_healthy
    volumes:
      - feed_data:/var/www/feed
    environment:
      - YII_ENV=\${YII_ENV:-prod}
      - YII_DEBUG=\${YII_DEBUG:-false}
      - DB_HOST=db
      - DB_USER=\${DB_USER:-ildis}
      - DB_PASSWORD=\${DB_PASSWORD}
      - DB_DATABASE=\${DB_DATABASE:-ildis_v4}
      - DB_DATABASE_PORT=\${DB_DATABASE_PORT:-3306}
    stop_grace_period: 30s
    healthcheck:
      test: ["CMD", "test", "-f", "/var/www/feed/document.json"]
      interval: 60s
      timeout: 10s
      retries: 3
      start_period: 120s
    networks:
      - ildis_net
EOF
        fi

        echo "${network_def}" >> "${INSTALL_DIR}/${COMPOSE_FILE}"

        echo "" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        echo "volumes:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        if [ "${DB_TYPE}" != "external" ]; then
            echo "  mysql_data:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        fi
        echo "  runtime:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        echo "  backend-assets:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        echo "  frontend-assets:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        echo "  backend-uploads:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        echo "  frontend-uploads:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        echo "  feed_data:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        echo "  app_logs:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        if [ "${SSL_MODE}" = "letsencrypt" ]; then
            echo "  traefik_acme:" >> "${INSTALL_DIR}/${COMPOSE_FILE}"
        fi
    fi

    success "${COMPOSE_FILE} dibuat"
}

# ── Patch production image for Yii migrations ─────────────────────────────────
# GHCR images may ship with migrationPath-only config and older migration files.
patch_app_for_migrations() {
    info "Menyesuaikan migrasi di container (wajib untuk image production saat ini)..."

    # Skip jika image sudah contain fix (marker file exists)
    if run_compose exec -T app test -f /var/www/.ildis_patched 2>/dev/null; then
        info "Image sudah di-patch, melewati..."
        return 0
    fi

    warn "Runtime patching masih diperlukan. Update ke image terbaru untuk menghilangkan kebutuhan patching."

    run_compose exec -T app sed -i \
        "s/'autoInstallTables' => true/'autoInstallTables' => false/" \
        /var/www/frontend/config/main.php 2>/dev/null || true

    local app_main="/var/www/console/config/main.php"
    if ! run_compose exec -T app grep -q "migrationNamespaces" "${app_main}" 2>/dev/null; then
        run_compose exec -T app sed -i \
            "s|'migrationPath' => '@console/migrations',|'migrationNamespaces' => ['console\\\\migrations'],\n            'migrationPath' => null,|" \
            "${app_main}" || warn "Gagal memperbarui konfigurasi migrate di ${app_main}"
    fi

    local report_mig="/var/www/console/migrations/m250514_121356_create_table_report.php"
    if run_compose exec -T app test -f "${report_mig}" 2>/dev/null; then
        run_compose exec -T app sed -i \
            's/$this->string(100)->notNull()/$this->text()->notNull()/g; s/$this->string()->notNull()/$this->text()->notNull()/g' \
            "${report_mig}" 2>/dev/null || true
    fi

    local visitor_files=(
        m260507_000001_create_table_visitor_log.php
        m260507_000002_create_table_visitor_stats.php
        m260507_000003_insert_visitor_report_menu.php
    )
    local f
    for f in "${visitor_files[@]}"; do
        run_compose exec -T app sh -c "
            file=/var/www/console/migrations/${f}
            if [ -f \"\$file\" ] && ! grep -q 'namespace console' \"\$file\"; then
                sed -i '1a\\
\\
namespace console\\\\migrations;\\
' \"\$file\"
            fi
        " 2>/dev/null || true
    done

    local script_dir repo_mig cid
    script_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
    repo_mig="${script_dir}/console/migrations"
    if [ -d "${repo_mig}" ]; then
        cid="$(run_compose ps -q app 2>/dev/null | head -1)"
        if [ -n "${cid}" ]; then
            for f in m250514_121345_create_table_pcounter_save.php \
                m250514_121346_create_table_pcounter_users.php \
                m250514_121356_create_table_report.php "${visitor_files[@]}"; do
                if [ -f "${repo_mig}/${f}" ]; then
                    docker cp "${repo_mig}/${f}" "${cid}:/var/www/console/migrations/${f}" 2>/dev/null || true
                fi
            done
        fi
    fi

    patch_recaptcha_support

    # Tandai bahwa patch sudah di-apply (untuk image lama yang tidak punya marker)
    run_compose exec -T app touch /var/www/.ildis_patched 2>/dev/null || true

    success "Penyesuaian migrasi selesai"
}

# Image GHCR belum membaca RECAPTCHA_ENABLED — salin/sisipkan patch login backend.
patch_recaptcha_support() {
    info "Menyesuaikan reCAPTCHA di container (RECAPTCHA_ENABLED dari .env)..."

    local cid script_dir copied=false
    cid="$(run_compose ps -q app 2>/dev/null | head -1)"
    [ -z "${cid}" ] && return 0

    script_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
    local rel
    for rel in common/config/params.php common/models/LoginForm.php backend/views/site/login.php; do
        if [ -f "${script_dir}/${rel}" ]; then
            if docker cp "${script_dir}/${rel}" "${cid}:/var/www/${rel}" 2>/dev/null; then
                copied=true
            fi
        fi
    done

    if [ "${copied}" = false ]; then
        local patch_php="${script_dir}/scripts/patch-recaptcha-container.php"
        if [ -f "${patch_php}" ]; then
            docker cp "${patch_php}" "${cid}:/tmp/patch-recaptcha-container.php" 2>/dev/null || true
            run_compose exec -T app php /tmp/patch-recaptcha-container.php 2>/dev/null \
                || warn "Patch reCAPTCHA fallback gagal — jalankan install dari folder repo lengkap."
        else
            warn "File patch reCAPTCHA tidak ada. Clone repo penuh lalu jalankan scripts/patch-docker-migrations.sh"
        fi
    fi
}

# ── Generate nginx config ────────────────────────────────────────────────────
generate_nginx_config() {
    info "Membuat nginx/default.conf..."

    mkdir -p "${INSTALL_DIR}/nginx"

    local real_ip_directives=""
    local hsts_header=""
    local csp_value="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://cdn.jsdelivr.net https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com http://cdn.jsdelivr.net https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' https: data: https://fonts.gstatic.com; object-src 'none';"
    local proxy_headers=""

    if [ "${REVERSE_PROXY}" = true ]; then
        real_ip_directives="
    real_ip_header X-Forwarded-For;
    real_ip_recursive on;
    set_real_ip_from 172.16.0.0/12;
    set_real_ip_from 10.0.0.0/8;
    set_real_ip_from 192.168.0.0/16;"
        proxy_headers="
    proxy_set_header X-Forwarded-Proto \$http_x_forwarded_proto;"
    fi

    if [ "${SSL_MODE}" = "letsencrypt" ] || [ "${SSL_MODE}" = "manual" ]; then
        hsts_header="
    add_header Strict-Transport-Security \"max-age=31536000; includeSubDomains\" always;"
        csp_value="${csp_value} upgrade-insecure-requests;"
    elif [ "${REVERSE_PROXY}" = true ]; then
        hsts_header="
    add_header Strict-Transport-Security \"max-age=31536000; includeSubDomains\" always;"
    fi

    cat > "${INSTALL_DIR}/nginx/default.conf" <<NGINXEOF
server {
    listen 80;
    server_name _;

    access_log /var/log/nginx/ildis_access.log;
    error_log  /var/log/nginx/ildis_error.log;

    absolute_redirect off;

    root /var/www;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "geolocation=self" always;
    add_header Content-Security-Policy "${csp_value}" always;${hsts_header}${real_ip_directives}

    location ~ /\.(ht|svn|git|env|DS_Store) {
        deny all;
    }

    location ~* \.(bak|bat|config|sql|fla|md|psd|ini|log|sh|inc|swp|dist)$ {
        deny all;
    }

    location ^~ /backend/ {
        try_files \$uri \$uri/ /backend/index.php\$is_args\$args;

        location ~ \.php\$ {
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
            fastcgi_pass 127.0.0.1:9000;
            try_files \$uri =404;
        }${proxy_headers}
    }

    location = /backend {
        return 301 /backend/;
    }

    location / {
        try_files \$uri \$uri/ /index.php\$is_args\$args;
    }

    location ~ \.php\$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        try_files \$uri =404;
    }${proxy_headers}
}
NGINXEOF

    chmod 644 "${INSTALL_DIR}/nginx/default.conf"
    success "nginx/default.conf dibuat"
}

# ── Generate Traefik config ──────────────────────────────────────────────────
generate_traefik_config() {
    info "Membuat konfigurasi Traefik..."

    mkdir -p "${INSTALL_DIR}/traefik"
    mkdir -p "${INSTALL_DIR}/logs/traefik"

    local entrypoints_websecure=""
    local acme_config=""

    if [ "${SSL_MODE}" = "letsencrypt" ] || [ "${SSL_MODE}" = "manual" ]; then
        entrypoints_websecure="
  websecure:
    address: \":443\""
    fi

    if [ "${SSL_MODE}" = "letsencrypt" ]; then
        acme_config="
certificatesResolvers:
  letsencrypt:
    acme:
      email: \"${SSL_EMAIL}\"
      storage: \"/etc/traefik/acme/acme.json\"
      tlsChallenge: {}"
    fi

    cat > "${INSTALL_DIR}/traefik/traefik.yml" <<TRAEFIKEOF
api:
  insecure: false

providers:
  docker:
    exposedByDefault: false
  file:
    filename: "/etc/traefik/config/dynamic.yml"

entryPoints:
  web:
    address: ":80"${entrypoints_websecure}

log:
  filePath: "/var/log/traefik/traefik.log"
  level: INFO

accessLog:
  filePath: "/var/log/traefik/access.log"
  bufferingSize: 100${acme_config}
TRAEFIKEOF

    local dynamic_tls=""

    if [ "${SSL_MODE}" = "letsencrypt" ]; then
        dynamic_tls="
http:
  routers:
    ildis:
      entryPoints:
        - web
      rule: \"Host(\`${SSL_DOMAIN}\`)\"
      middlewares:
        - redirect-to-https
      service: ildis
    ildis-secure:
      entryPoints:
        - websecure
      rule: \"Host(\`${SSL_DOMAIN}\`)\"
      tls:
        certResolver: letsencrypt
      service: ildis
  services:
    ildis:
      loadBalancer:
        servers:
          - url: \"http://ildis_app:80\"
  middlewares:
    redirect-to-https:
      redirectScheme:
        scheme: https
        permanent: true"
    elif [ "${SSL_MODE}" = "manual" ]; then
        dynamic_tls="
http:
  routers:
    ildis:
      entryPoints:
        - web
      rule: \"Host(\`${SSL_DOMAIN}\`)\"
      middlewares:
        - redirect-to-https
      service: ildis
    ildis-secure:
      entryPoints:
        - websecure
      rule: \"Host(\`${SSL_DOMAIN}\`)\"
      tls:
        certificates:
          - certFile: \"/etc/traefik/certs/${SSL_CERT_PATH##*/}\"
            keyFile: \"/etc/traefik/certs/${SSL_KEY_PATH##*/}\"
      service: ildis
  services:
    ildis:
      loadBalancer:
        servers:
          - url: \"http://ildis_app:80\"
  middlewares:
    redirect-to-https:
      redirectScheme:
        scheme: https
        permanent: true"
    else
        dynamic_tls="
http:
  routers:
    ildis:
      entryPoints:
        - web
      rule: \"Host(\`${SSL_DOMAIN}\`)\"
      service: ildis
  services:
    ildis:
      loadBalancer:
        servers:
          - url: \"http://ildis_app:80\""
    fi

    cat > "${INSTALL_DIR}/traefik/config.yml" <<DYNAMICEOF
${dynamic_tls}
DYNAMICEOF

    chmod 644 "${INSTALL_DIR}/traefik/traefik.yml"
    chmod 644 "${INSTALL_DIR}/traefik/config.yml"
    success "Konfigurasi Traefik dibuat"
}

# ── Create superadmin ────────────────────────────────────────────────────────
create_superadmin() {
    info "Membuat akun superadmin..."

    local create_ok=false
    if run_compose exec -T app php /var/www/yii user/create \
        --username="${ADMIN_USERNAME}" \
        --password="${ADMIN_PASSWORD}" \
        --role=superadmin \
        --non-interactive=1 2>&1; then
        create_ok=true
    elif run_compose exec -T app php /var/www/yii user/create \
        --username="${ADMIN_USERNAME}" \
        --password="${ADMIN_PASSWORD}" \
        --role=superadmin \
        --non-interactive=1 -n 2>&1; then
        create_ok=true
    fi

    if [ "${create_ok}" = true ]; then
        success "Superadmin '${ADMIN_USERNAME}' berhasil dibuat"
    else
        warn "Gagal membuat superadmin secara otomatis."
        warn "Buat manual dengan:"
        warn "  ${COMPOSE_CMD} -f ${INSTALL_DIR}/${COMPOSE_FILE} exec app php yii user/create --username=${ADMIN_USERNAME} --role=superadmin --non-interactive=1"
    fi
}

# ── Install ──────────────────────────────────────────────────────────────────
do_install() {
    if ! mkdir -p "${INSTALL_DIR}" 2>/dev/null; then
        local fallback_dir="$(pwd)/ildis"
        warn "Tidak dapat membuat ${INSTALL_DIR} — menggunakan ${fallback_dir}."
        INSTALL_DIR="${fallback_dir}"
        mkdir -p "${INSTALL_DIR}"
    fi

    generate_env
    generate_compose
    generate_nginx_config

    if [ "${REVERSE_PROXY}" = false ]; then
        generate_traefik_config
        mkdir -p "${INSTALL_DIR}/ssl"
    fi

    info "Mengunduh image ILDIS..."
    if ! run_compose pull 2>&1; then
        fail "Gagal mengunduh image. Periksa koneksi jaringan dan pastikan image tersedia di ${GHCR_IMAGE}."
    fi
    success "Image berhasil diunduh"

    info "Memulai ILDIS..."
    if ! run_compose up -d 2>&1; then
        fail "Gagal memulai container. Periksa log: ${COMPOSE_CMD} -f ${INSTALL_DIR}/${COMPOSE_FILE} logs"
    fi

    if [ "${DB_TYPE}" != "external" ]; then
        info "Menunggu database siap..."
        local db_ready=false
        for i in $(seq 1 "${MYSQL_HEALTH_RETRIES}"); do
            if run_compose exec -T db healthcheck.sh --connect --innodb_initialized &>/dev/null 2>&1 || \
               run_compose exec -T db mysqladmin ping -h localhost -u root &>/dev/null 2>&1; then
                db_ready=true
                break
            fi
            info "Database belum siap, mencoba ulang... ($i/${MYSQL_HEALTH_RETRIES})"
            sleep "${MYSQL_HEALTH_INTERVAL}"
        done
        if [ "${db_ready}" = false ]; then
            warn "Database belum siap dalam waktu yang ditentukan. Container mungkin perlu penanganan manual."
        else
            success "Database siap"
        fi
    fi

    patch_app_for_migrations

    info "Menghentikan app sementara untuk migrasi database..."
    run_compose stop app 2>/dev/null || true

    info "Menjalankan migrasi database..."
    local migrate_ok=false
    if run_compose run --rm --no-deps -T app php /var/www/yii migrate/up --interactive=0 2>&1; then
        migrate_ok=true
    elif run_compose start app 2>/dev/null && sleep 3 && \
        run_compose exec -T app php /var/www/yii migrate/up --interactive=0 2>&1; then
        migrate_ok=true
    fi
    if [ "${migrate_ok}" = true ]; then
        success "Migrasi database berhasil diterapkan"
    else
        warn "Migrasi gagal. Jalankan: bash $(dirname "${BASH_SOURCE[0]}")/scripts/patch-docker-migrations.sh && migrate manual."
    fi

    info "Menyalakan kembali aplikasi..."
    run_compose up -d app 2>/dev/null || true

    local app_port="${PORT:-${DEFAULT_PORT}}"
    info "Menunggu aplikasi ILDIS di port ${app_port}..."
    local app_ready=false
    for i in $(seq 1 "${HEALTH_RETRIES}"); do
        if curl -sf "http://localhost:${app_port}/" >/dev/null 2>&1; then
            app_ready=true
            break
        fi
        info "Aplikasi belum siap, mencoba ulang... ($i/${HEALTH_RETRIES})"
        sleep "${HEALTH_INTERVAL}"
    done

    if [ "${app_ready}" = false ]; then
        echo ""
        echo -e "${YELLOW}Container ILDIS berjalan tetapi aplikasi belum merespons.${NC}"
        echo "Periksa log: ${COMPOSE_CMD} -f ${INSTALL_DIR}/${COMPOSE_FILE} logs app"
        echo "URL: http://localhost:${app_port}"
    else
        success "ILDIS merespons di http://localhost:${app_port}"
    fi

    create_superadmin

    echo ""
    echo -e "${GREEN}╔══════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║       ILDIS Berhasil Dipasang!            ║${NC}"
    echo -e "${GREEN}╚══════════════════════════════════════════╝${NC}"
    echo ""

    local frontend_url=""
    local backend_url=""
    local ssl_info=""

    if [ "${REVERSE_PROXY}" = true ]; then
        frontend_url="${PUBLIC_DOMAIN}"
        backend_url="${PUBLIC_DOMAIN}/backend"
        ssl_info="dikelola reverse proxy"
    elif [ "${SSL_MODE}" = "letsencrypt" ]; then
        frontend_url="https://${SSL_DOMAIN}"
        backend_url="https://${SSL_DOMAIN}/backend"
        ssl_info="Let's Encrypt (otomatis)"
    elif [ "${SSL_MODE}" = "manual" ]; then
        frontend_url="https://${SSL_DOMAIN}"
        backend_url="https://${SSL_DOMAIN}/backend"
        ssl_info="sertifikat manual"
    else
        if [ -n "${SSL_DOMAIN}" ] && [ "${SSL_DOMAIN}" != "localhost" ]; then
            frontend_url="http://${SSL_DOMAIN}"
            backend_url="http://${SSL_DOMAIN}/backend"
        else
            frontend_url="http://localhost:${app_port}"
            backend_url="http://localhost:${app_port}/backend"
        fi
        ssl_info="tidak ada (HTTP)"
    fi

    echo "  Frontend:      ${frontend_url}"
    echo "  Backend/CMS:   ${backend_url}"
    echo "  Superadmin:    ${ADMIN_USERNAME}"
    if [ -n "${DB_PORT_EXPOSE}" ] && [ "${DB_TYPE}" != "external" ]; then
        echo "  DB (host):     localhost:${DB_PORT_EXPOSE}"
    fi
    echo ""
    echo "  Direktori:     ${INSTALL_DIR}"
    echo "  Konfigurasi:   ${INSTALL_DIR}/${ENV_FILE}"
    echo "  Compose:       ${INSTALL_DIR}/${COMPOSE_FILE}"
    echo "  Nginx:         ${INSTALL_DIR}/nginx/default.conf"

    if [ "${REVERSE_PROXY}" = false ]; then
        echo "  Traefik:       ${INSTALL_DIR}/traefik/"
        echo "  SSL:           ${ssl_info}"
        echo "  Domain:        ${SSL_DOMAIN}"
        if [ "${SSL_MODE}" = "letsencrypt" ]; then
            echo "  Email LE:      ${SSL_EMAIL}"
        fi
    else
        echo "  SSL:           ${ssl_info}"
    fi

    echo ""
    echo "  Log:"
    echo "    Nginx:     ${INSTALL_DIR}/logs/nginx/"
    echo "    Aplikasi:  ${COMPOSE_CMD} -f ${INSTALL_DIR}/${COMPOSE_FILE} exec app cat /var/www/runtime/logs/app.log"
    if [ "${REVERSE_PROXY}" = false ]; then
        echo "    Traefik:   ${INSTALL_DIR}/logs/traefik/"
    fi

    echo ""
    echo "  Perintah berguna:"
    echo "    ${COMPOSE_CMD} -f ${INSTALL_DIR}/${COMPOSE_FILE} logs -f       # Ikuti log"
    echo "    ${COMPOSE_CMD} -f ${INSTALL_DIR}/${COMPOSE_FILE} down          # Hentikan container"
    echo "    ${COMPOSE_CMD} -f ${INSTALL_DIR}/${COMPOSE_FILE} pull          # Perbarui image"
    echo ""
    echo -e "  ${CYAN}Untuk memperbarui ILDIS, jalankan: ./install.sh --update${NC}"

    if [ "${SSL_MODE}" = "manual" ]; then
        local cert_file="${INSTALL_DIR}/${SSL_CERT_PATH}"
        local key_file="${INSTALL_DIR}/${SSL_KEY_PATH}"
        if [ ! -f "${cert_file}" ] || [ ! -f "${key_file}" ]; then
            echo ""
            echo -e "${YELLOW}⚠ PERINGATAN: Sertifikat SSL belum ditemukan!${NC}"
            echo "  Taruh file SSL ke:"
            echo "    ${cert_file}"
            echo "    ${key_file}"
            echo "  Lalu restart Traefik:"
            echo "    ${COMPOSE_CMD} -f ${INSTALL_DIR}/${COMPOSE_FILE} restart traefik"
        fi
    fi

    print_recaptcha_env_help "${INSTALL_DIR}/${ENV_FILE}"

    # Safety net: ensure install.sh is available on disk for future updates
    if [ ! -f "${INSTALL_DIR}/install.sh" ]; then
        cp "${BASH_SOURCE[0]}" "${INSTALL_DIR}/install.sh" 2>/dev/null && chmod +x "${INSTALL_DIR}/install.sh" && success "install.sh disalin ke ${INSTALL_DIR}/"
    fi
}

# ── Update ───────────────────────────────────────────────────────────────────
do_update() {
    local compose_file="${INSTALL_DIR}/${COMPOSE_FILE}"
    local env_file="${INSTALL_DIR}/${ENV_FILE}"

    if [ ! -f "${compose_file}" ] || [ ! -f "${env_file}" ]; then
        fail "Instalasi ILDIS tidak ditemukan di ${INSTALL_DIR}. Jalankan ./install.sh tanpa --update untuk pemasangan baru."
    fi

    info "Memeriksa pembaruan install.sh..."
    local self_update_url="https://raw.githubusercontent.com/${GITHUB_REPO}/main/install.sh"
    local self_path
    self_path="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/$(basename "${BASH_SOURCE[0]}")"
    local new_self="${INSTALL_DIR}/install.sh.new"

    if command -v curl &>/dev/null; then
        if curl -fsSL "${self_update_url}" -o "${new_self}" 2>/dev/null && [ -s "${new_self}" ]; then
            local old_md5 new_md5
            if command -v md5sum &>/dev/null; then
                old_md5=$(md5sum "${self_path}" 2>/dev/null | awk '{print $1}')
                new_md5=$(md5sum "${new_self}" 2>/dev/null | awk '{print $1}')
            elif command -v md5 &>/dev/null; then
                old_md5=$(md5 -q "${self_path}" 2>/dev/null)
                new_md5=$(md5 -q "${new_self}" 2>/dev/null)
            else
                old_md5=""
                new_md5="different"
            fi
            if [ "${old_md5}" != "${new_md5}" ]; then
                chmod +x "${new_self}"
                mv "${new_self}" "${self_path}"
                success "install.sh diperbarui, menjalankan versi terbaru..."
                exec "${self_path}" --update --dir "${INSTALL_DIR}"
            else
                rm -f "${new_self}"
                success "install.sh sudah terbaru"
            fi
        else
            rm -f "${new_self}" 2>/dev/null || true
            warn "Tidak dapat mengunduh pembaruan install.sh. Melanjutkan dengan versi saat ini."
        fi
    else
        warn "curl tidak ditemukan. Melewatkan pembaruan install.sh."
    fi

    # Ensure install.sh is saved to installation directory for future updates
    local self_path
    self_path="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/$(basename "${BASH_SOURCE[0]}")"
    if [ ! -f "${INSTALL_DIR}/install.sh" ]; then
        cp "${self_path}" "${INSTALL_DIR}/install.sh" 2>/dev/null && chmod +x "${INSTALL_DIR}/install.sh" && success "install.sh disalin ke ${INSTALL_DIR}/"
    fi

    info "Memuat konfigurasi yang ada..."
    while IFS='=' read -r key value; do
        case "$key" in
            ''|\#*) continue ;;
        esac
        [[ "$key" =~ ^[A-Za-z_][A-Za-z0-9_]*$ ]] && export "$key=$value"
    done < "${env_file}"

    REVERSE_PROXY="${BEHIND_REVERSE_PROXY:-false}"
    SSL_MODE="${SSL_MODE:-none}"
    SSL_DOMAIN="${SSL_DOMAIN:-}"
    SSL_EMAIL="${SSL_EMAIL:-}"
    SSL_CERT_PATH="${SSL_CERT_PATH:-ssl/server.crt}"
    SSL_KEY_PATH="${SSL_KEY_PATH:-ssl/server.key}"

    local current_version="unknown"
    if [ -f "${INSTALL_DIR}/${VERSION_FILE}" ]; then
        current_version=$(tr -d '[:space:]' < "${INSTALL_DIR}/${VERSION_FILE}")
    fi

    local latest_version="${ILDIS_IMAGE_TAG:-latest}"
    if [ "${latest_version}" = "latest" ]; then
        info "Memeriksa versi terbaru..."
        if command -v curl &>/dev/null; then
            local release_info
            release_info=$(curl -sf "https://api.github.com/repos/${GITHUB_REPO}/releases/latest" 2>/dev/null || echo "{}")
            latest_version=$(echo "${release_info}" | grep -o '"tag_name":"[^"]*"' | head -1 | sed 's/"tag_name":"//;s/"$//')
            [ -z "${latest_version}" ] && latest_version="latest"
        fi
    fi

    echo ""
    echo -e "${BOLD}Pembaruan ILDIS${NC}"
    echo "  Versi saat ini: ${current_version}"
    echo "  Versi target:   ${latest_version}"
    echo ""

    if [ "${NON_INTERACTIVE}" = false ]; then
        if ! confirm "Lanjutkan pembaruan?" "y"; then
            echo "Pembaruan dibatalkan."
            exit 0
        fi
    fi

    local timestamp
    timestamp=$(date +%Y%m%d_%H%M%S)
    local backup_file="${INSTALL_DIR}/${BACKUP_DIR}/ildis_${timestamp}.sql.gz"
    mkdir -p "${INSTALL_DIR}/${BACKUP_DIR}"

    info "Membuat cadangan database..."
    local db_host="${DB_HOST:-db}"
    local db_user="${DB_USER:-root}"
    local db_pass="${DB_PASSWORD:-}"
    local db_name="${DB_DATABASE:-ildis_v4}"
    local db_port="${DB_DATABASE_PORT:-3306}"

    local backup_success=false
    if [ "${DB_TYPE:-mariadb}" != "external" ]; then
        if ${COMPOSE_CMD} -f "${compose_file}" exec -T db sh -c \
            "MYSQL_PWD=\"${db_pass}\" mysqldump -h localhost -u \"${db_user}\" -P \"${db_port}\" --single-transaction --routines --triggers \"${db_name}\"" 2>/dev/null | gzip > "${backup_file}"; then
            backup_success=true
        fi
    else
        warn "Database eksternal terdeteksi — melewatkan cadangan otomatis."
        warn "Pastikan Anda memiliki cadangan sebelum melanjutkan."
    fi

    if [ "${backup_success}" = true ]; then
        success "Cadangan database disimpan: ${backup_file} ($(du -h "${backup_file}" | cut -f1))"
    elif [ "${DB_TYPE:-mariadb}" != "external" ]; then
        warn "Cadangan database gagal. Melanjutkan tanpa cadangan."
        warn "Anda dapat membuat cadangan manual dengan:"
        warn "  ${COMPOSE_CMD} -f ${compose_file} exec -T db mysqldump ..."
        if [ "${NON_INTERACTIVE}" = false ]; then
            if ! confirm "Lanjutkan tanpa cadangan?" "n"; then
                echo "Pembaruan dibatalkan."
                exit 0
            fi
        fi
    fi

    info "Mengunduh image terbaru..."
    if ! run_compose_update "${compose_file}" "${env_file}" pull 2>&1; then
        fail "Gagal mengunduh image."
    fi
    success "Image diperbarui"

    if [ -f "${INSTALL_DIR}/nginx/default.conf" ]; then
        generate_nginx_config
    fi
    if [ -d "${INSTALL_DIR}/traefik" ]; then
        generate_traefik_config
    fi

    info "Menghentikan cron container (mencegah write setengah jadi)..."
    run_compose_update "${compose_file}" "${env_file}" stop cron 2>/dev/null || true

    info "Menghentikan aplikasi..."
    run_compose_update "${compose_file}" "${env_file}" stop app 2>/dev/null || true

    info "Memulai ulang container ILDIS..."
    if ! run_compose_update "${compose_file}" "${env_file}" up -d --force-recreate app 2>&1; then
        fail "Gagal memulai ulang container."
    fi

    if [ "${DB_TYPE:-mariadb}" != "external" ]; then
        info "Menunggu database..."
        local db_ready=false
        for i in $(seq 1 "${MYSQL_HEALTH_RETRIES}"); do
            if run_compose_update "${compose_file}" "${env_file}" exec -T db healthcheck.sh --connect --innodb_initialized &>/dev/null 2>&1 || \
               run_compose_update "${compose_file}" "${env_file}" exec -T db mysqladmin ping -h localhost &>/dev/null 2>&1; then
                db_ready=true
                break
            fi
            sleep "${MYSQL_HEALTH_INTERVAL}"
        done
        [ "${db_ready}" = true ] && success "Database siap" || warn "Database belum siap"
    fi

    local app_port="${PORT:-${DEFAULT_PORT}}"
    info "Menunggu aplikasi di port ${app_port}..."

    local app_ready=false
    for i in $(seq 1 "${HEALTH_RETRIES}"); do
        if curl -sf "http://localhost:${app_port}/" >/dev/null 2>&1; then
            app_ready=true
            break
        fi
        sleep "${HEALTH_INTERVAL}"
    done

    if [ "${app_ready}" = false ]; then
        echo ""
        echo -e "${YELLOW}Aplikasi tidak merespons setelah pembaruan.${NC}"
        echo "Periksa log: ${COMPOSE_CMD} -f ${compose_file} logs app"
    else
        success "Aplikasi merespons"
    fi

    local patch_needed=false
    if ! run_compose_update "${compose_file}" "${env_file}" exec -T app test -f /var/www/.ildis_patched 2>/dev/null; then
        patch_needed=true
    fi

    patch_app_for_migrations

    if [ "${patch_needed}" = true ]; then
        info "Restarting app after patching..."
        run_compose_update "${compose_file}" "${env_file}" restart app 2>/dev/null || true
        sleep 3
    fi

    info "Menjalankan migrasi database..."
    if run_compose_update "${compose_file}" "${env_file}" exec -T app php yii migrate/up --interactive=0 2>&1; then
        success "Migrasi diterapkan"
    else
        warn "Perintah migrasi mengembalikan non-zero. Mungkin normal jika tidak ada yang tertunda."
    fi

    info "Memulai cron container..."
    run_compose_update "${compose_file}" "${env_file}" up -d --force-recreate cron 2>&1 || true

    echo "${latest_version}" > "${INSTALL_DIR}/${VERSION_FILE}"

    echo ""
    echo -e "${GREEN}╔══════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║       ILDIS Berhasil Diperbarui!          ║${NC}"
    echo -e "${GREEN}╚══════════════════════════════════════════╝${NC}"
    echo ""
    echo "  Versi sebelumnya: ${current_version}"
    echo "  Versi baru:        ${latest_version}"
    if [ "${backup_success}" = true ]; then
        echo "  File cadangan:     ${backup_file}"
    fi
    echo "  URL:                http://localhost:${app_port}"
    echo ""
}

# ── Detect existing installation ─────────────────────────────────────────────
detect_existing_install() {
    local dir="${INSTALL_DIR:-${DEFAULT_INSTALL_DIR}}"
    if [ -f "${dir}/${COMPOSE_FILE}" ] && [ -f "${dir}/${ENV_FILE}" ]; then
        echo "${dir}"
        return 0
    fi
    if [ -f "${COMPOSE_FILE}" ] && [ -f "${ENV_FILE}" ]; then
        echo "$(pwd)"
        return 0
    fi
    return 1
}

# ── Main ────────────────────────────────────────────────────────────────────
main() {
    echo ""
    echo -e "${BOLD}ILDIS — Sistem Informasi Dokumentasi Hukum Indonesia${NC}"
    echo ""

    # Self-save: if running via pipe (curl | bash), save to disk before continuing
    SELF_SOURCE="${BASH_SOURCE[0]:-}"
    if [ ! -f "${SELF_SOURCE}" ] || [ ! -s "${SELF_SOURCE}" ]; then
        SAVE_DIR="${INSTALL_DIR:-${DEFAULT_INSTALL_DIR}}"
        mkdir -p "${SAVE_DIR}"
        SAVE_PATH="${SAVE_DIR}/install.sh"
        info "Menyimpan install.sh ke ${SAVE_PATH}..."
        if curl -fsSL "https://raw.githubusercontent.com/${GITHUB_REPO}/main/install.sh" -o "${SAVE_PATH}" 2>/dev/null && [ -s "${SAVE_PATH}" ]; then
            chmod +x "${SAVE_PATH}"
            success "install.sh disimpan"
            exec "${SAVE_PATH}" "$@"
        else
            rm -f "${SAVE_PATH}" 2>/dev/null || true
            warn "Tidak dapat mengunduh install.sh. Melanjutkan dari pipe."
        fi
    fi

    if [ "${ACTION}" = "update" ]; then
        # Detect container runtime for update mode
        COMPOSE_CMD=""
        CONTAINER_RUNTIME=""
        if command -v docker &>/dev/null && docker compose version &>/dev/null 2>&1; then
            COMPOSE_CMD="docker compose"
            CONTAINER_RUNTIME="docker"
        elif command -v podman &>/dev/null; then
            if podman compose version &>/dev/null 2>&1; then
                COMPOSE_CMD="podman compose"
                CONTAINER_RUNTIME="podman"
            elif command -v podman-compose &>/dev/null; then
                COMPOSE_CMD="podman-compose"
                CONTAINER_RUNTIME="podman"
            fi
        fi
        if [ -z "${COMPOSE_CMD}" ]; then
            fail "Tidak ditemukan Docker Compose maupun Podman Compose. Pasang salah satu sebelum memperbarui."
        fi

        local existing_dir
        existing_dir=$(detect_existing_install) || true
        if [ -n "${existing_dir}" ]; then
            INSTALL_DIR="${existing_dir}"
        elif [ -z "${INSTALL_DIR}" ]; then
            INSTALL_DIR="${DEFAULT_INSTALL_DIR}"
        fi
        info "Memperbarui ILDIS di ${INSTALL_DIR}"
        do_update
        return
    fi

    check_prerequisites

    if [ "${NON_INTERACTIVE}" = true ]; then
        INSTALL_DIR="${INSTALL_DIR:-${DEFAULT_INSTALL_DIR}}"
        PORT="${PORT:-${DEFAULT_PORT}}"
        PUBLIC_DOMAIN="${PUBLIC_DOMAIN:-http://localhost:${PORT}}"
        DB_TYPE="${DB_TYPE_OVERRIDE:-mariadb}"
        DB_USER="${DB_USER:-ildis}"
        DB_DATABASE="${DB_DATABASE:-ildis_v4}"
        DB_DATABASE_PORT="${DB_DATABASE_PORT:-3306}"
        DB_PASSWORD="${DB_PASSWORD:-$(generate_random_key)}"
        DB_HOST="${DB_HOST:-mysql}"
        YII_ENV="${YII_ENV:-prod}"
        YII_DEBUG="${YII_DEBUG:-false}"
        COOKIE_VALIDATION_KEY_BE="${COOKIE_VALIDATION_KEY_BE:-$(generate_random_key)}"
        COOKIE_VALIDATION_KEY_FE="${COOKIE_VALIDATION_KEY_FE:-$(generate_random_key)}"
        RECAPTCHA_ENABLED="${RECAPTCHA_ENABLED:-false}"
        RECAPTCHA_SITE_KEY="${RECAPTCHA_SITE_KEY:-}"
        RECAPTCHA_SECRET_KEY="${RECAPTCHA_SECRET_KEY:-}"
        REVERSE_PROXY="${BEHIND_REVERSE_PROXY:-false}"
        SSL_MODE="${SSL_MODE:-none}"
        SSL_DOMAIN="${SSL_DOMAIN:-}"
        SSL_EMAIL="${SSL_EMAIL:-}"
        SSL_CERT_PATH="${SSL_CERT_PATH:-ssl/server.crt}"
        SSL_KEY_PATH="${SSL_KEY_PATH:-ssl/server.key}"
        ADMIN_USERNAME="${ADMIN_USERNAME:-admin}"
        if [ -z "${ADMIN_PASSWORD}" ]; then
            fail "ADMIN_PASSWORD wajib diisi untuk mode non-interactive."
        fi
        if [ "${REVERSE_PROXY}" = false ] && [ "${SSL_MODE}" = "letsencrypt" ]; then
            if [ -z "${SSL_DOMAIN}" ] || [ -z "${SSL_EMAIL}" ]; then
                fail "SSL_DOMAIN dan SSL_EMAIL wajib diisi untuk mode Let's Encrypt."
            fi
        fi
        if [ "${REVERSE_PROXY}" = false ] && [ "${SSL_MODE}" = "manual" ]; then
            if [ -z "${SSL_DOMAIN}" ]; then
                fail "SSL_DOMAIN wajib diisi untuk mode manual SSL."
            fi
        fi
    else
        run_wizard
    fi

    do_install
}

main "$@"