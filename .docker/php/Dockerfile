FROM php:8.2-fpm-alpine3.16

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions \
	&& install-php-extensions gd exif bcmath zip redis \
	&& docker-php-ext-install pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

ENV APP_SERVICES_CACHE=.runtime/cache/services.php \
	APP_PACKAGES_CACHE=.runtime/cache/packages.php \
	APP_CONFIG_CACHE=.runtime/cache/config.php \
	APP_ROUTES_CACHE=.runtime/cache/routes-v7.php \
	APP_EVENTS_CACHE=.runtime/cache/events.php
