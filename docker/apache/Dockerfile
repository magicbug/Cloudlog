FROM php:7.4-apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN apt-get update
RUN apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++

RUN docker-php-ext-install \
    bz2 \
    intl \
    bcmath \
    opcache \
    calendar \
    pdo_mysql \
    mysqli

# 2. set up document root for apache
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# 4. start with base php config, then add extensions
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# 5. Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN composer self-update

COPY . /var/www/html/
# 6. we need a user with the same UID/GID with host user
# so when we execute CLI commands, all the host file's ownership remains intact
# otherwise command from inside container will create root-owned files and directories
ARG uid
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

EXPOSE 80
