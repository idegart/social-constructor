#!/bin/sh
chown www-data:www-data public
sudo -u www-data php artisan storage:link
