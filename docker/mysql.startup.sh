#!/bin/bash
. /tmp/secrets.sh

echo "Updating /etc/hosts ..."
echo "$CONTAINER_IP_NGINX   $HOST_NAME_NGINX" >> /etc/hosts
echo "$CONTAINER_IP_MYSQL   $HOST_NAME_MYSQL" >> /etc/hosts
echo "$CONTAINER_IP_PHP8    $HOST_NAME_PHP8" >> /etc/hosts
echo "$CONTAINER_IP_PHP7    $HOST_NAME_PHP7" >> /etc/hosts

echo 'Resetting permissions'
chown -R mysql /repo/mysql
chmod -R 755 /repo/mysql

echo "Starting MySQL / MariaDB ..."
if [[ ! -f "/repo/mysql" ]]; then
    mkdir -f /repo/mysql
    /usr/bin/mysql_install_db  --user=mysql --ldata=/var/lib/mysql --datadir=/repo/mysql
    /usr/bin/mysqld --user=mysql --datadir=/repo/mysql/
    sleep 3
    /repo/docker/mysql_init_db.sh
else
    /usr/bin/mysqld --user=mysql --datadir=/repo/mysql/
fi

status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start mysql: $status"
  exit $status
fi

echo "Started mysql succesfully"
while sleep 60; do
  ps |grep mysql |grep -v grep
  PROCESS_STATUS=$?
  if [ -f $PROCESS_STATUS ]; then
    echo "mysql has already exited."
    exit 1
  fi
done
