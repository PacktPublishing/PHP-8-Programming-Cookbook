@echo off
SET USAGE="Usage: init.sh up|down|build|ls|shell [php7|php8|nginx|mysql] -- 2nd argument is the container (used with shell option)"
# If you're using %DOCKER%, change these two env vars
# replace "podman" with "%DOCKER%"
SET DOCKER=podman
SET COMPOSE=podman-compose
SET INIT=0
CALL secrets.cmd

IF "%~1"=="" GOTO :done

:lets_go
IF "%1"=="up" GOTO :up
IF "%1"=="start" GOTO :up
GOTO :opt2
:up
%COMPOSE% up -d
GOTO:EOF

:opt2
IF "%1" =="down" GOTO :down
IF "%1"=="stop" GOTO :down
GOTO :opt3
:down
%COMPOSE% down
takeown /R /F *
GOTO:EOF

:opt3
IF "%1"=="build" GOTO :build
GOTO :opt4
:build
%COMPOSE% build
GOTO:EOF

:opt4
IF "%1"=="ls" GOTO :ls
GOTO :opt5
:ls
%DOCKER% container ls
GOTO:EOF

:opt5
IF "%1"=="shell" GOTO :shell
GOTO :done
:shell
IF "$2" == "php7" GOTO :shell_php7
IF "$2" == "nginx" GOTO :shell_nginx
IF "$2" == "mysql" GOTO :shell_mysql

%DOCKER% exec -it %CONTAINER_PHP8% /bin/bash
GOTO:EOF

:shell_php7
%DOCKER% exec -it %CONTAINER_PHP7% /bin/bash
GOTO:EOF

:shell_nginx
%DOCKER% exec -it %CONTAINER_NGINX% /bin/bash
GOTO:EOF

:shell_mysql
%DOCKER% exec -it %CONTAINER_MYSQL% /bin/bash
GOTO:EOF

:done
echo "Done"
echo %USAGE%
echo "You entered %1 and %1"
GOTO:EOF
