FROM docker.io/library/mariadb:10.11

COPY init.sql /docker-entrypoint-initdb.d/
