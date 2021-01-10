FROM mysql

ENV MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
ENV MYSQL_DATABASE=${MYSQL_DATABASE}

COPY ./install/assets/install.sql /docker-entrypoint-initdb.d/install.sql

EXPOSE 3306
