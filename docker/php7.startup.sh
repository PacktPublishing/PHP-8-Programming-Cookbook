#!/bin/bash
. /tmp/secrets.sh

echo "Updating /etc/hosts ..."
echo "$CONTAINER_IP_NGINX   $HOST_NAME_NGINX" >> /etc/hosts
echo "$CONTAINER_IP_PHP8    $HOST_NAME_PHP8" >> /etc/hosts
echo "$CONTAINER_IP_PHP7    $HOST_NAME_PHP7" >> /etc/hosts

echo "Setting permissions for PHP user ..."
cd $REPO_DIR
chgrp -R -f nobody *
chmod -R -f 775 *
cd

while sleep 60; do  
  cd
  PROCESS_STATUS=`pwd`
  if [ $PROCESS_STATUS -ne "/root" ]; then
    echo "container has already exited."
    exit 1
  fi
done


