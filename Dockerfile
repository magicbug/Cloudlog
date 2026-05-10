FROM php:7.4-apache

WORKDIR /var/www/html

# Single layer: APT cleanup + PHP extensions (curl used by application PHP code)
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
        libcurl4-openssl-dev \
        libxml2-dev \
        libzip-dev \
        zlib1g-dev \
        libpng-dev \
        libonig-dev \
        default-mysql-client \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install -j"$(nproc)" curl pdo_mysql mysqli gd mbstring zip xml \
    && a2enmod rewrite

COPY . /var/www/html/

# Duplicate for seeding an empty Docker volume mounted over /var/www/html (Compose cloudlog_html)
RUN cp -a /var/www/html /usr/local/share/cloudlog-stock

COPY script.sh /usr/local/bin/startup.sh
RUN sed -i 's/\r$//' /usr/local/bin/startup.sh && chmod +x /usr/local/bin/startup.sh

RUN printf '%s\n' \
        'upload_max_filesize = 30M' \
        'post_max_size = 35M' \
        'memory_limit = 64M' \
        'max_execution_time = 300' \
        'max_input_time = 300' \
        > /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 80
