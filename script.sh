#!/bin/bash

# Define the file path for .env and the file to modify
if [ -f "./.env" ]; then
    ENV_FILE="./.env"
else
    ENV_FILE="./.env.sample"
fi
CONFIG_FILE="install/config/config.php"
DATABASE_FILE="install/config/database.php"
DEST_DIR="application/config"

# Check if .env file exists
if [ ! -f "$ENV_FILE" ]; then
    echo ".env file not found!"
    exit 1
fi

# Read the .env file
source $ENV_FILE

# Check if MYSQL_DATABASE is set
if [ -z "${MYSQL_DATABASE}" ]; then
    echo "MYSQL_DATABASE is not set in .env file!"
    exit 1
fi
# Check if MYSQL_USER is set
if [ -z "${MYSQL_USER}" ]; then
    echo "MYSQL_USER is not set in .env file!"
    exit 1
fi
# Check if MYSQL_PASSWORD is set
if [ -z "${MYSQL_PASSWORD}" ]; then
    echo "MYSQL_PASSWORD is not set in .env file!"
    exit 1
fi
# Check if MYSQL_HOST is set
if [ -z "${MYSQL_HOST}" ]; then
    echo "MYSQL_HOST is not set in .env file!"
    exit 1
fi
# Check if BASE_LOCATOR is set
if [ -z "${BASE_LOCATOR}" ]; then
    echo "BASE_LOCATOR is not set in .env file!"
    exit 1
fi
# Check if WEBSITE_URL is set
if [ -z "${WEBSITE_URL}" ]; then
    echo "WEBSITE_URL is not set in .env file!"
    exit 1
fi
# Check if DIRECTORY is set
if [ -z "${DIRECTORY}" ]; then
    echo "DIRECTORY is not set in .env file!"
    exit 1
fi

# Check if destination directory exists, if not create it
if [ ! -d "$DEST_DIR" ]; then
    mkdir -p $DEST_DIR
fi

# Check if configuration has already been processed
if [ -f "$DEST_DIR/database.php" ] && [ -f "$DEST_DIR/config.php" ]; then
    echo "Configuration files already exist, skipping processing..."
else
    echo "Processing configuration files..."
    
    # Use sed with a different delimiter (`|`) to avoid conflicts with special characters
    # Strip any trailing whitespace/newlines from variables before using them
    CLEAN_DATABASE=$(echo "${MYSQL_DATABASE}" | tr -d '\r\n')
    CLEAN_USER=$(echo "${MYSQL_USER}" | tr -d '\r\n')
    CLEAN_PASSWORD=$(echo "${MYSQL_PASSWORD}" | tr -d '\r\n')
    CLEAN_HOST=$(echo "${MYSQL_HOST}" | tr -d '\r\n')
    CLEAN_LOCATOR=$(echo "${BASE_LOCATOR}" | tr -d '\r\n')
    CLEAN_URL=$(echo "${WEBSITE_URL}" | tr -d '\r\n')
    CLEAN_DIRECTORY=$(echo "${DIRECTORY}" | tr -d '\r\n')
    
    sed -i "s|%DATABASE%|${CLEAN_DATABASE}|g" $DATABASE_FILE
    sed -i "s|%USERNAME%|${CLEAN_USER}|g" $DATABASE_FILE
    sed -i "s|%PASSWORD%|${CLEAN_PASSWORD}|g" $DATABASE_FILE
    sed -i "s|%HOSTNAME%|${CLEAN_HOST}|g" $DATABASE_FILE
    sed -i "s|%baselocator%|${CLEAN_LOCATOR}|g" $CONFIG_FILE
    sed -i "s|%websiteurl%|${CLEAN_URL}|g" $CONFIG_FILE
    sed -i "s|%directory%|${CLEAN_DIRECTORY}|g" $CONFIG_FILE

    # Move the files to the destination directory
    mv $CONFIG_FILE $DEST_DIR
    mv $DATABASE_FILE $DEST_DIR

    # Delete the /install directory
    rm -rf /install
fi

echo "Replacement complete."

# Wait for database to be ready
echo "Waiting for database to be ready..."
echo "Connecting to: Host=$MYSQL_HOST, User=$MYSQL_USER, Database=$MYSQL_DATABASE"

# Give the database a moment, then test connection once
sleep 2
if mariadb -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" -D"$MYSQL_DATABASE" -e "SELECT 1;" >/dev/null 2>&1; then
    echo "Database is ready!"
else
    echo "Database connection failed, but continuing anyway since healthcheck passed..."
fi

# Set Permissions
chown -R root:www-data /var/www/html/application/config/
chown -R root:www-data /var/www/html/application/logs/
chown -R root:www-data /var/www/html/assets/qslcard/
chown -R root:www-data /var/www/html/backup/
chown -R root:www-data /var/www/html/updates/
chown -R root:www-data /var/www/html/uploads/
chown -R root:www-data /var/www/html/images/eqsl_card_images/
chown -R root:www-data /var/www/html/assets/json

chmod -R g+rw /var/www/html/application/config/
chmod -R g+rw /var/www/html/application/logs/
chmod -R g+rw /var/www/html/assets/qslcard/
chmod -R g+rw /var/www/html/backup/
chmod -R g+rw /var/www/html/updates/
chmod -R g+rw /var/www/html/uploads/
chmod -R g+rw /var/www/html/images/eqsl_card_images/
chmod -R g+rw /var/www/html/assets/json

# Start Apache in the foreground
exec apache2-foreground