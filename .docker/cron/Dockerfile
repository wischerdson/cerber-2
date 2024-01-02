FROM php:8.2-fpm-alpine3.16

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions \
	&& install-php-extensions gd exif bcmath zip redis \
	&& docker-php-ext-install pdo_mysql

# Installing Supercronic

ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.2.26/supercronic-linux-amd64 \
    SUPERCRONIC=supercronic-linux-amd64 \
    SUPERCRONIC_SHA1SUM=7a79496cf8ad899b99a719355d4db27422396735 \
	APP_SERVICES_CACHE=.runtime/cache/services.php \
	APP_PACKAGES_CACHE=.runtime/cache/packages.php \
	APP_CONFIG_CACHE=.runtime/cache/config.php \
	APP_ROUTES_CACHE=.runtime/cache/routes-v7.php \
	APP_EVENTS_CACHE=.runtime/cache/events.php

RUN curl -fsSLO "$SUPERCRONIC_URL" \
	&& echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
	&& chmod +x "$SUPERCRONIC" \
	&& mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
	&& ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic

RUN echo "* * * * * php /app/artisan schedule:run" > /etc/crontabs/root

ENTRYPOINT ["supercronic"]
CMD ["/etc/crontabs/root"]
