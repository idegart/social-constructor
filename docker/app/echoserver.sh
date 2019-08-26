#!/bin/sh

if [ "$APP_ENV" = "local" ];then
    cat /app/laravel-echo-server.json | sed "s/api-auction.toyota.ru/${REVIEW_HOST}/" > /app/laravel-echo-server-tmp.json
    mv /app/laravel-echo-server-tmp.json /app/laravel-echo-server.json
fi

laravel-echo-server start

