FROM php:8.0-apache                                                                                                                                         
RUN touch /usr/local/etc/php/conf.d/uploads.ini \                                                                                                           
&& echo "file_uploads = On" >> /usr/local/etc/php/conf.d/uploads.ini \
&& echo "memory_limit = 64M" >> /usr/local/etc/php/conf.d/uploads.ini \
&& echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/uploads.ini \
&& echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/uploads.ini \
&& echo "max_execution_time = 60" >> /usr/local/etc/php/conf.d/uploads.ini
RUN apt-get update \
&& apt-get install -y git curl libxml2-dev libonig-dev
RUN docker-php-ext-install mysqli mbstring xml
#RUN docker-php-ext-install curl
#RUN docker-php-ext-install openssl
RUN rm -rf /var/www/html/docker/
COPY ./ /var/www/html/
WORKDIR /var/www/html
# Setting permissions as: https://github.com/magicbug/Cloudlog/wiki/Installation
RUN cd /var/www/html \
&& echo "Setting root as owner of the html folder" \
&& chown -R root:www-data /var/www/html
RUN echo "Setting permissions to the install folder" \
&& chmod -R g+rw ./application/config/ \
&& chmod -R g+rw ./application/logs/ \
&& chmod -R g+rw ./assets/qslcard/ \
&& chmod -R g+rw ./backup/ \
&& chmod -R g+rw ./updates/ \
&& chmod -R g+rw ./uploads/ \
&& chmod -R g+rw ./images/eqsl_card_images/ \
&& chmod -R g+rw ./assets/json/ \
&& chmod -R 777 /var/www/html/install