#!/bin/sh
./docker/app/entrypoint.sh
chown www-data:www-data /app/storage/
sudo -E -u www-data cp -R /app/storage_tmpl/* /app/storage/

sudo -E -u www-data php artisan migrate --force || exit 1

php-fpm
