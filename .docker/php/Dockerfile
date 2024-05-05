FROM php:8.2-fpm-alpine3.19

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions \
	&& install-php-extensions gd exif bcmath zip redis \
	&& docker-php-ext-install pdo_mysql

RUN docker-php-ext-configure pcntl --enable-pcntl \
	&& docker-php-ext-install pcntl

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
