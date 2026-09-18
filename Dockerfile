# syntax=docker/dockerfile:1
FROM node:22-alpine AS styles
WORKDIR /build
COPY package.json ./
# One pinned build tool. Node is not part of the runtime image.
RUN npm install --ignore-scripts --no-audit --no-fund
COPY resources/scss ./resources/scss
RUN mkdir -p public/assets && npm run build

FROM php:8.5-fpm-alpine AS app
RUN apk add --no-cache oniguruma unzip \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS oniguruma-dev \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring \
    && apk del .build-deps
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts --no-autoloader
COPY . .
COPY --from=styles /build/public/assets/app.css ./public/assets/app.css
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-blog.ini
COPY docker/fpm.conf /usr/local/etc/php-fpm.d/zz-blog.conf
RUN composer dump-autoload --no-dev --classmap-authoritative --no-scripts \
    && mkdir -p storage/smarty/compile storage/smarty/cache \
    && chown -R www-data:www-data storage
USER www-data
CMD ["php-fpm"]

FROM nginx:1.28-alpine AS web
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=app /app/public /app/public
