#!/bin/bash
. ./docker/secrets.sh
DIR=`pwd`
export USAGE="Usage: init.sh up|down|build|ls|init|shell [php7|php8|nginx|mysql] -- 2nd argument is the container (used with shell option)"
# If you're using docker instead of podman change "podman" to "docker"
export DOCKER=podman
export COMPOSE=podman-compose
if [[ -z "$1" ]]; then
    echo $USAGE
    exit 1
elif [[ "$1" = "up" ||  "$1" = "start" ]]; then
    $COMPOSE up -d
elif [[ "$1" = "down" ||  "$1" = "stop" ]]; then
    $COMPOSE down
    echo "Resetting permissions ..."
    sudo chown -R $USER:$USER *
    sudo chmod -R 775 *
elif [[ "$1" = "build" ]]; then
    $COMPOSE build
elif [[ "$1" = "ls" ]]; then
    $DOCKER container ls
elif [[ "$1" = "init" ]]; then
    $DOCKER exec $CONTAINER_MYSQL /repo/docker/mysql_init_db.sh
elif [[ "$1" = "shell" ]]; then
    if [[ "$2" = "php7" ]]; then
        $DOCKER exec -it $CONTAINER_PHP7 /bin/bash
    elif [[ "$2" = "nginx" ]]; then
        $DOCKER exec -it $CONTAINER_NGINX /bin/bash
    elif [[ "$2" = "mysql" ]]; then
        $DOCKER exec -it $CONTAINER_MYSQL /bin/bash
    else
        $DOCKER exec -it $CONTAINER_PHP8 /bin/bash
    fi
else
    echo $USAGE
    exit 1
fi
exit 0
