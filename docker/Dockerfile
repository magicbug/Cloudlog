FROM php:7.4-apache                                                                                                                                         
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
RUN cd /var/www/html \
&& echo "Setting root as owner of the folder..." \
&& chown -R root:root /var/www/html \
&& echo "Setting permissions to the install folder" \
&& chmod 777 /var/www/html/install \
&& echo "Make sure everything is fine" \
&& dir -ls
