FROM serversideup/php:8.3-fpm-nginx as base

# Install necessary PHP extensions
USER root
RUN install-php-extensions intl bcmath gd exif

FROM base as composer
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist


FROM node:20 as static-assets
WORKDIR /app
COPY . .
COPY --from=composer --chown=www-data:www-data /var/www/html .
RUN npm install
RUN npm run build

FROM base
WORKDIR /var/www/html

COPY --from=composer --chown=www-data:www-data /var/www/html .
COPY --chown=www-data:www-data . .
COPY .docker/prod/http.conf /etc/nginx/site-opts.d/http.conf.template
USER www-data

RUN composer dump-autoload -o

COPY --from=static-assets --chown=www-data:www-data /app/public/build ./public/build

RUN php artisan optimize
RUN php artisan filament:optimize

HEALTHCHECK --interval=21s --timeout=20s --retries=10 \
    CMD [ "sh", "-c", "curl --insecure --silent --location --show-error --fail http://localhost:8080$HEALTHCHECK_PATH || exit 1" ]
