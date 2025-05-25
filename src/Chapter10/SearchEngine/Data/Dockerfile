FROM mysql:8.0
ENV MYSQL_ROOT_PASSWORD=password
ENV MYSQL_DATABASE=cookbook
ENV MYSQL_USER=chef
ENV MYSQL_PASSWORD=password

COPY carseed.sql /docker-entrypoint-initdb.d/
EXPOSE 3306
CMD ["mysqld"]