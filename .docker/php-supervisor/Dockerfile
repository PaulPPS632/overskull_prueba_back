FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    supervisor \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

RUN mkdir -p /var/log/supervisor && chown -R www:www /var/log/supervisor

WORKDIR /var/www/html

COPY --chown=www:www . /var/www/html
COPY ./.docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
