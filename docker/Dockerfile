# OS=one of ubuntu, centos, or debian
ARG OS=ubuntu

# OS_VERSION=OS version to use; e.g., 20.04, 8, and 10
ARG OS_VERSION=24.04

# PHP version to use; can be 5.6, 7.1, 7.2, 7.3, 7.4, or 8.0.
# Ability to build a version is dependent on having ZendPHP credentials that
# authorize use of that version.
ARG ZENDPHP_VERSION=8.3

# BASE_IMAGE=cli or fpm
ARG BASE_IMAGE=fpm
# Append -arm after $BASE_IMAGE if you wish to use the ARM variant
FROM cr.zend.com/zendphp/${ZENDPHP_VERSION}:${OS}-${OS_VERSION}-${BASE_IMAGE}
MAINTAINER doug@unlikelysource.com
COPY secrets.sh /tmp/secrets.sh
COPY nginx.default.conf /tmp/default.conf
COPY update_nginx_conf.php /tmp/update_nginx_conf.php
COPY startup.sh /tmp/startup.sh
COPY backup.sh /tmp/backup.sh
COPY restore.sh /tmp/restore.sh
RUN chmod +x /tmp/*.sh
RUN \
    echo "Installing misc tools ..." && \
    apt-get update && \
    apt-get install -y less vim net-tools wget unzip apt-utils curl
RUN \
    echo "Adding/enabling PHP extensions ..." && \
    zendphpctl ext install gd && \
    zendphpctl ext install simplexml && \
    zendphpctl ext install intl && \
    zendphpctl ext install mbstring && \
    zendphpctl ext install mysql && \
    zendphpctl ext install curl
RUN \
    echo "Installing Mariadb (MySQL open source equivalent) ..." && \
    apt-get install -y mysql-server && \
    /etc/init.d/mysql start && \
    sleep 3 && \
    . /tmp/secrets.sh && \
    export DB_NAME_TEST="$DB_NAM"_test && \
	mysql -uroot -ppassword -v -e "CREATE DATABASE IF NOT EXISTS $DB_NAM;" && \
	mysql -uroot -ppassword -v -e "CREATE USER IF NOT EXISTS '$DB_USR'@'$DB_HOST' IDENTIFIED BY '$DB_PWD';" && \
	mysql -uroot -ppassword -v -e "GRANT ALL PRIVILEGES ON *.* TO '$DB_USR'@'$DB_HOST';" && \
	mysql -uroot -ppassword -v -e "CREATE DATABASE IF NOT EXISTS $DB_NAME_TEST;" && \
	mysql -uroot -ppassword -v -e "CREATE USER IF NOT EXISTS 'test'@'$DB_HOST' IDENTIFIED BY 'password';" && \
	mysql -uroot -ppassword -v -e "GRANT ALL PRIVILEGES ON $DB_NAME_TEST.* TO 'test'@'$DB_HOST';" && \
	mysql -uroot -ppassword -v -e "FLUSH PRIVILEGES;"
RUN \
    echo "Installing nginx ..." && \
    apt-get update && \
    apt-get install -y nginx
RUN \
    echo "Installing Adminer (database admin tool) ..." && \
    . /tmp/secrets.sh && \
    cd /tmp && \
    curl -L https://github.com/vrana/adminer/releases/download/v$DB_ADMIN_VER/adminer-$DB_ADMIN_VER.php -o /tmp/adminer.php && \
    mv /tmp/adminer.php /var/www/html
RUN \
    echo "Updating config files and permissions ..." && \
    rm -f /etc/nginx/sites-enabled/default* && \
    php /tmp/update_nginx_conf.php && \
    cp /tmp/default.conf /etc/nginx/sites-available/default.conf && \
    ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default && \
    chown -R www-data /var/www
WORKDIR /repo
ENTRYPOINT /tmp/startup.sh
