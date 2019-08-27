FROM node:8-alpine as echo
WORKDIR /app
# https://github.com/npm/uid-number/issues/3 костыль отсюда
RUN npm config set unsafe-perm true \
    && npm install -g laravel-echo-server \
    && npm config set unsafe-perm false
ADD ./docker/app/echo-server-prod.json /app/laravel-echo-server.json    
ADD ./docker/app/echoserver.sh /app/echoserver.sh
RUN chmod +x /app/echoserver.sh
ENTRYPOINT /app/echoserver.sh

FROM node:8-alpine as front
RUN apk add git libpng-dev bash g++ make bash libtool automake autoconf nasm && rm -fr /var/cache/apk/*
WORKDIR /app
COPY package.json package-lock.json webpack.mix.js /app/
RUN npm ci
COPY ./public /app/public
COPY ./resources /app/resources
RUN npm run prod

FROM php:7.3.6-fpm as app
ENTRYPOINT /app/docker/app/entrypoint_app.sh
WORKDIR /app

RUN apt-get update && \
    apt-get install -y --no-install-recommends git sudo cron libjpeg62-turbo-dev libfreetype6-dev libmcrypt-dev libzip-dev libxml2-dev libpq-dev
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    nice docker-php-ext-install -j$(nproc) gd zip opcache pdo_pgsql tokenizer xml pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer global require hirak/prestissimo --no-plugins --no-scripts --no-progress
COPY storage /app/storage_tmpl/
COPY composer.* /app/
COPY database/ /app/database/
RUN composer install -n --ansi --no-scripts --no-progress
COPY ./ /app/
RUN rm -rf /app/public/
COPY --from=front /app/public/ /app/public/
RUN rm -f /usr/local/etc/php-fpm.d/docker.conf
COPY docker/app/www.conf /usr/local/etc/php-fpm.d/
COPY docker/app/custom.php.ini $PHP_INI_DIR/conf.d/
COPY docker/app/crontab /etc/cron.d/
RUN chmod +x /app/docker/app/entrypoint*.sh && chown -R www-data:www-data /app/storage_tmpl/ /app/bootstrap/cache

FROM nginx:stable-alpine
WORKDIR /app/
COPY --from=app /app/public/ /app/public/
COPY docker/app/vhost.conf /etc/nginx/conf.d/default.conf
