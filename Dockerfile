# Use the official image for PHP and Apache
FROM php:7.4-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Install system dependencies, including git and libxml2
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libxml2-dev \
    libzip-dev \
    zlib1g-dev \
    libpng-dev \
    libonig-dev \
    default-mysql-client \
    curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install gd \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install zip \
    && docker-php-ext-install xml \
    && a2enmod rewrite

# # Copy .env, config.php, and database.php into the docker image
# COPY .env ./ 
# COPY install/config/config.php install/config/
# COPY install/config/database.php install/config/

# # Add a script that reads the .env file and replaces the variables in the config.php and database.php files
# ADD script.sh ./
# RUN chmod +x script.sh

# # Run the script at build time
# RUN ./script.sh

# # Create the application/config directory
# RUN mkdir -p application/config


# Expose port 80
EXPOSE 80