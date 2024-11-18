#!/bin/bash
. /tmp/secrets.sh

echo "Updating /etc/hosts ..."
echo "$CONTAINER_IP_NGINX   $HOST_NAME_NGINX" >> /etc/hosts
echo "$CONTAINER_IP_MYSQL   $HOST_NAME_MYSQL" >> /etc/hosts
echo "$CONTAINER_IP_PHP8    $HOST_NAME_PHP8" >> /etc/hosts
echo "$CONTAINER_IP_PHP7    $HOST_NAME_PHP7" >> /etc/hosts

echo "Setting permissions for PHP user ..."
cd $REPO_DIR
chgrp -R -f nobody *
chmod -R -f 775 *

# Start PHP-FPM
/usr/local/php8/sbin/php-fpm
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start php-fpm: $status"
  exit $status
fi
echo "Started php-fpm succesfully"

while sleep 60; do
  ps |grep php-fpm |grep -v grep
  PROCESS_STATUS=$?
  if [ -f $PROCESS_STATUS ]; then
    echo "php-fpm has already exited."
    exit 1
  fi
done


