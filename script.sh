#!/bin/bash

# App root (Dockerfile WORKDIR); ensures ./.env resolves even if the process cwd differs
cd /var/www/html || exit 1

# Named volume on /var/www/html starts empty and hides image layers — copy stock tree once.
if [ ! -f "index.php" ] && [ -d "/usr/local/share/cloudlog-stock" ]; then
    echo "Copying Cloudlog into /var/www/html from image (first run or empty volume)..."
    cp -a /usr/local/share/cloudlog-stock/. /var/www/html/
fi

CONFIG_FILE="install/config/config.php"
DATABASE_FILE="install/config/database.php"
DEST_DIR="application/config"

# Load variables: prefer files on the bind mount; otherwise use env already injected by Compose (env_file)
if [ -f "./.env" ]; then
    # shellcheck disable=SC1091
    source "./.env"
elif [ -f "./.env.sample" ]; then
    # shellcheck disable=SC1091
    source "./.env.sample"
elif [ -n "${MYSQL_DATABASE:-}" ] && [ -n "${MYSQL_USER:-}" ] && [ -n "${MYSQL_PASSWORD:-}" ] && \
     [ -n "${MYSQL_HOST:-}" ] && [ -n "${BASE_LOCATOR:-}" ] && [ -n "${WEBSITE_URL:-}" ] && [ -n "${DIRECTORY:-}" ]; then
    :
else
    echo ".env file not found under /var/www/html and required variables are not set in the environment."
    echo "Add .env next to docker-compose.yml (bind-mounted as /var/www/html) or pass variables via Compose env_file / environment."
    exit 1
fi

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

# Normalize filesystem root (avoids //var/www/html when DIRECTORY is already absolute)
APP_ROOT="${DIRECTORY%/}"
case "$APP_ROOT" in
    /*) ;;
    *) APP_ROOT="/${APP_ROOT}" ;;
esac

# Check if destination directory exists, if not create it
if [ ! -d "$DEST_DIR" ]; then
    mkdir -p "$DEST_DIR"
fi

# Check if configuration has already been processed
if [ -f "$DEST_DIR/database.php" ] && [ -f "$DEST_DIR/config.php" ]; then
    echo "Configuration files already exist, skipping processing..."
else
    if [ ! -f "$DATABASE_FILE" ] || [ ! -f "$CONFIG_FILE" ]; then
        echo "ERROR: Installer templates missing under /var/www/html (install/config/)."
        echo "Rebuild the web image so it includes the full Cloudlog tree, or bind-mount a git checkout."
        exit 1
    fi

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

    sed -i "s|%DATABASE%|${CLEAN_DATABASE}|g" "$DATABASE_FILE"
    sed -i "s|%USERNAME%|${CLEAN_USER}|g" "$DATABASE_FILE"
    sed -i "s|%PASSWORD%|${CLEAN_PASSWORD}|g" "$DATABASE_FILE"
    sed -i "s|%HOSTNAME%|${CLEAN_HOST}|g" "$DATABASE_FILE"
    sed -i "s|%baselocator%|${CLEAN_LOCATOR}|g" "$CONFIG_FILE"
    sed -i "s|%websiteurl%|${CLEAN_URL}|g" "$CONFIG_FILE"
    sed -i "s|%directory%|${CLEAN_DIRECTORY}|g" "$CONFIG_FILE"

    # Move the files to the destination directory
    mv "$CONFIG_FILE" "$DEST_DIR"
    mv "$DATABASE_FILE" "$DEST_DIR"

    # Remove installer templates from the bind-mounted tree (under app root, not filesystem /install)
    rm -rf ./install
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

# Writable paths (create if missing, then fix ownership)
mkdir -p \
    "${APP_ROOT}/application/config" \
    "${APP_ROOT}/application/cache" \
    "${APP_ROOT}/application/logs" \
    "${APP_ROOT}/assets/qslcard" \
    "${APP_ROOT}/backup" \
    "${APP_ROOT}/updates" \
    "${APP_ROOT}/uploads" \
    "${APP_ROOT}/images/eqsl_card_images" \
    "${APP_ROOT}/assets/json"

chown -R root:www-data \
    "${APP_ROOT}/application/config" \
    "${APP_ROOT}/application/cache" \
    "${APP_ROOT}/application/logs" \
    "${APP_ROOT}/assets/qslcard" \
    "${APP_ROOT}/backup" \
    "${APP_ROOT}/updates" \
    "${APP_ROOT}/uploads" \
    "${APP_ROOT}/images/eqsl_card_images" \
    "${APP_ROOT}/assets/json"

chmod -R g+rw \
    "${APP_ROOT}/application/config" \
    "${APP_ROOT}/application/cache" \
    "${APP_ROOT}/application/logs" \
    "${APP_ROOT}/assets/qslcard" \
    "${APP_ROOT}/backup" \
    "${APP_ROOT}/updates" \
    "${APP_ROOT}/uploads" \
    "${APP_ROOT}/images/eqsl_card_images" \
    "${APP_ROOT}/assets/json"

# Start Apache in the foreground
exec apache2-foreground
