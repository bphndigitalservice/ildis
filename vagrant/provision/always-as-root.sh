#!/usr/bin/env bash

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: $(whoami)"

# Ganti ke versi PHP sesuai yang di-install dari Ondřej
PHP_VERSION="7.4"  # atau "8.0", tergantung yang kamu install

info "Restart web stack"
systemctl restart php${PHP_VERSION}-fpm
systemctl restart nginx
systemctl restart mysql
