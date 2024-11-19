#!/bin/bash
. /tmp/secrets.sh
echo "Starting MySQL / MariaDB ..."
export FN=/repo/data/post_codes.sql
/usr/bin/mysql -uroot -v -e "CREATE DATABASE IF NOT EXISTS $DB_NAM;"
/usr/bin/mysql -uroot -v -e "CREATE USER IF NOT EXISTS '$DB_USR'@'$DB_HOST' IDENTIFIED BY '$DB_PWD';"
/usr/bin/mysql -uroot -v -e "GRANT ALL PRIVILEGES ON *.* TO '$DB_USR'@'$DB_HOST';"
/usr/bin/mysql -uroot -v -e "CREATE USER IF NOT EXISTS '$DB_USR'@'$HOST_NAME_PHP8' IDENTIFIED BY '$DB_PWD';"
/usr/bin/mysql -uroot -v -e "GRANT ALL PRIVILEGES ON *.* TO '$DB_USR'@'$HOST_NAME_PHP8';"
/usr/bin/mysql -uroot -v -e "CREATE USER IF NOT EXISTS '$DB_USR'@'$HOST_NAME_PHP7' IDENTIFIED BY '$DB_PWD';"
/usr/bin/mysql -uroot -v -e "GRANT ALL PRIVILEGES ON *.* TO '$DB_USR'@'$HOST_NAME_PHP7';"
/usr/bin/mysql -uroot -v -e "FLUSH PRIVILEGES;"
/usr/bin/mysql -uroot -v -e "SOURCE $FN;" $DB_NAM
