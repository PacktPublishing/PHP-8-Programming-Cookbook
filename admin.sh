#!/bin/bash
. ./docker/secrets.sh
DIR=`pwd`
export USAGE="Usage: init.sh up|down|build|ls|init|shell [--show]"
if [[ -z "$1" ]]; then
    echo $USAGE
    exit 1
elif [[ "$1" = "up" ||  "$1" = "start" ]]; then
    if [[ -f "$2" && "$2" = "--show" ]]; then
        docker-compose up
    else
        docker-compose up -d
    fi
    docker exec $CONTAINER_NAME /bin/bash -c "/etc/init.d/mysql start"
    docker exec $CONTAINER_NAME /bin/bash -c "/repo/docker/restore.sh"
elif [[ "$1" = "down" ||  "$1" = "stop" ]]; then
    docker exec $CONTAINER_NAME /bin/bash -c "/etc/init.d/mysql start"
    docker exec $CONTAINER_NAME /bin/bash -c "/repo/docker/backup.sh"
    docker-compose down
    echo "Resetting permissions ..."
    sudo chown -R $USER:$USER *
    sudo chmod -R 775 *
elif [[ "$1" = "build" ]]; then
    docker-compose build $2
elif [[ "$1" = "ls" ]]; then
    docker container ls
elif [[ "$1" = "init" ]]; then
    docker exec $CONTAINER_NAME /bin/bash -c "/etc/init.d/mysql restart"
    docker exec $CONTAINER_NAME /bin/bash -c "/etc/init.d/php$PHP_VER-fpm restart"
    docker exec $CONTAINER_NAME /bin/bash -c "/etc/init.d/nginx restart"
elif [[ "$1" = "shell" ]]; then
    docker exec -it $CONTAINER_NAME /bin/bash
else
    echo $USAGE
    exit 1
fi
exit 0
