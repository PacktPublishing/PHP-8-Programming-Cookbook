#!/bin/bash
. /tmp/secrets.sh

echo "Updating /etc/hosts ..."
echo "$CONTAINER_IP_NGINX   $HOST_NAME_NGINX" >> /etc/hosts
echo "$CONTAINER_IP_PHP8    $HOST_NAME_PHP8" >> /etc/hosts
echo "$CONTAINER_IP_PHP7    $HOST_NAME_PHP7" >> /etc/hosts

echo "Linking config fikes ..."
mv -f /etc/nginx/http.d/default.conf /etc/nginx/http.d/default.conf.old
ln -s -f /tmp/default.conf /etc/nginx/http.d/default.conf
ln -s -f /repo /var/www/html

echo "Starting nginx ..."
/usr/sbin/nginx
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start nginx: $status"
  exit $status
fi
echo "Started nginx succesfully"

while sleep 60; do
  ps |grep nginx |grep -v grep
  PROCESS_STATUS=$?
  if [ -f $PROCESS_STATUS ]; then
    echo "nginx has already exited."
    exit 1
  fi
done
