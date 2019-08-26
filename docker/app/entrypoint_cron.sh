#!/bin/sh
./docker/app/entrypoint.sh
env > /etc/environment
cron -f
