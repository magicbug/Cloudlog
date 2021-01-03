FROM mysql


ENV MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
ENV MYSQL_DATABASE=${MYSQL_DATABASE}

COPY ./docker/mysql/cloudlog_database.sql /docker-entrypoint-initdb.d/init.sql

EXPOSE 3306
