#!/bin/sh
./docker/app/entrypoint.sh
sudo -E -u www-data php artisan queue:work --daemon --queue=queue_notifications
