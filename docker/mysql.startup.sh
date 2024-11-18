#!/bin/bash
. /tmp/secrets.sh

echo "Updating /etc/hosts ..."
echo "$CONTAINER_IP_NGINX   $HOST_NAME_NGINX" >> /etc/hosts
echo "$CONTAINER_IP_MYSQL   $HOST_NAME_MYSQL" >> /etc/hosts
echo "$CONTAINER_IP_PHP8    $HOST_NAME_PHP8" >> /etc/hosts
echo "$CONTAINER_IP_PHP7    $HOST_NAME_PHP7" >> /etc/hosts

echo 'Resetting permissions'
chown -R mysql /home/vagrant/mysql
chmod -R 755 /home/vagrant/mysql

if [[ ! -f "/home/vagrant/mysql/ibdata1" ]]; then
    /usr/bin/mysql_install_db  --user=mysql --ldata=/var/lib/mysql --datadir=/home/vagrant/mysql
fi

echo "Starting MySQL / MariaDB ..."
/usr/bin/mysqld --user=mysql --datadir=/home/vagrant/mysql/

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
