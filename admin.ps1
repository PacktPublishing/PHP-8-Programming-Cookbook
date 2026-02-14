# Converted from admin.sh Bash script to PowerShell
# Note: This script assumes Docker/Podman is installed and configured on Windows.
# Sudo commands (chown/chmod) are not applicable on Windows; they are commented out.
# Interactive shell exec may require a compatible terminal (e.g., Windows Terminal or cmd).

# Source the secrets file (assuming it's converted to PowerShell or compatible)
. .\docker\secrets.ps1

$DIR = $PWD.Path
$USAGE = "Usage: .\admin.ps1 up|down|build|ls|init|shell [php|nginx|mysql] -- 2nd argument is the container (used with shell option)"

# If using Docker instead of Podman, change "podman" to "docker"
$DOCKER = "podman"
$COMPOSE = "podman-compose"

if (-not $args[0]) {
    Write-Host $USAGE
    exit 1
} elseif ($args[0] -eq "up" -or $args[0] -eq "start") {
    & $COMPOSE up -d
} elseif ($args[0] -eq "down" -or $args[0] -eq "stop") {
    & $COMPOSE down
    Write-Host "Resetting permissions ..."
    takeown /R /F *
} elseif ($args[0] -eq "build") {
    & $COMPOSE build
} elseif ($args[0] -eq "ls") {
    & $DOCKER container ls
} elseif ($args[0] -eq "init") {
    & $DOCKER exec $CONTAINER_MYSQL /repo/docker/mysql_init_db.sh
} elseif ($args[0] -eq "shell") {
    if ($args[1] -eq "php") {
        & $DOCKER exec -it $CONTAINER_PHP8 /bin/bash
    } elseif ($args[1] -eq "nginx") {
        & $DOCKER exec -it $CONTAINER_NGINX /bin/bash
    } elseif ($args[1] -eq "mysql") {
        & $DOCKER exec -it $CONTAINER_MYSQL /bin/bash
    } else {
        & $DOCKER exec -it $CONTAINER_PHP8 /bin/bash
    }
} else {
    Write-Host $USAGE
    exit 1
}
exit 0
