FROM php:8.4-fpm-alpine3.20

RUN curl -fsSL -o /usr/local/bin/install-php-extensions \
	https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
	&& chmod +x /usr/local/bin/install-php-extensions \
	&& install-php-extensions gd bcmath zip redis xdebug pdo_mysql pcntl

RUN apk add --no-cache busybox \
	&& touch /var/log/cron.log \
	&& echo "* * * * * php /app/artisan schedule:run" >> /etc/crontab \
	&& crontab /etc/crontab

WORKDIR /app

CMD ["crond", "-f", "-l", "2"]
