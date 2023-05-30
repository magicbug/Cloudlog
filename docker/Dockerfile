FROM php:7.4-apache
RUN touch /usr/local/etc/php/conf.d/uploads.ini \
&& echo “file_uploads = On” >> /usr/local/etc/php/conf.d/uploads.ini \
&& echo “memory_limit = 64M” >> /usr/local/etc/php/conf.d/uploads.ini \
&& echo “upload_max_filesize = 64M” >> /usr/local/etc/php/conf.d/uploads.ini \
&& echo “post_max_size = 64M” >> /usr/local/etc/php/conf.d/uploads.ini \
&& echo “max_execution_time = 60” >> /usr/local/etc/php/conf.d/uploads.ini
RUN apt-get update \
&& apt-get install -y git curl libxml2-dev libonig-dev
RUN docker-php-ext-install mysqli
#RUN docker-php-ext-install curl
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install xml
#RUN docker-php-ext-install openssl
WORKDIR /var/www/html
COPY ../ /var/www/html
RUN rm -rf /var/www/html/docker/ \ 
&& chown -R root:www-data ./application/config/ \
&& chown -R root:www-data ./application/logs/ \
&& chown -R root:www-data ./assets/qslcard/ \
&& chown -R root:www-data ./backup/ \
&& chown -R root:www-data ./updates/ \
&& chown -R root:www-data ./uploads/ \
&& chown -R root:www-data ./images/eqsl_card_images/ \
&& chown -R root:www-data ./assets/json/ \
&& chmod -R g+rw ./application/config/ \
&& chmod -R g+rw ./application/logs/ \
&& chmod -R g+rw ./assets/qslcard/ \
&& chmod -R g+rw ./backup/ \
&& chmod -R g+rw ./updates/ \
&& chmod -R g+rw ./uploads/ \
&& chmod -R g+rw ./images/eqsl_card_images/ \
&& chmod -R g+rw ./assets/json/
