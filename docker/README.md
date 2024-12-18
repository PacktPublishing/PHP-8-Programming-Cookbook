# Manual Build Procedure:
To build the images "manually" (without pulling from Docker Hub):
1. Build unlikelysource/php_src_base:
```
podman build -t unlikelysource/php_src_base -f Dockerfile.PHP_SRC_BASE
```
2. Build the PHP 7, PHP 8, and nginx containers:
```
cd ..
podman-compose build
```

# PHP_SRC_BASE Image
Contains the following libraries:
* curl git bash nano
* build-base autoconf re2c make pkgconf bison libc-dev curl-dev libxml2 libxml2-dev  tidyhtml tidyhtml-dev
* sqlite sqlite-dev bzip2 bzip2-dev openssl openssl-dev icu icu-dev oniguruma oniguruma-dev
* libffi libffi-dev libpng libpng-dev readline readline-dev libsodium libsodium-dev libzip libzip-dev
Also contains the PHP source code from https://github.com/php/php-src
* Located in: `/tmp/php-src`

# PHP 8 Container

## Location of PHP files
* shared extensions:     /usr/local/php8/lib/php/extensions/no-debug-non-zts-20230901/
* PHP CLI binary:        /usr/local/php8/bin/
* PHP CLI man page:      /usr/local/php8/php/man/man1/
* PHP FPM binary:        /usr/local/php8/sbin/
* PHP FPM defconfig:     /usr/local/php8/etc/
* PHP FPM man page:      /usr/local/php8/php/man/man8/
* PHP FPM status page:   /usr/local/php8/php/php/fpm/
* PHP CGI binary:        /usr/local/php8/bin/
* PHP CGI man page:      /usr/local/php8/php/man/man1/
* build environment:     /usr/local/php8/lib/php/build/
* header files:          /usr/local/php8/include/php/
* helper programs:       /usr/local/php8/bin/
* php.ini file:          /usr/local/php8/etc/php.ini

The following settings are other than default:
* `error_log` : /var/log/php/error.log
* `display_errors' : On

## Configure Command
```
./configure \
    --prefix=/usr/local/php8 \
    --disable-phpdbg \
    --enable-opcache  \
    --enable-bcmath \
    --enable-calendar \
    --enable-fpm \
    --enable-gd \
    --enable-intl \
    --enable-mbstring \
    --enable-mysqlnd  \
    --enable-simplexml  \
    --enable-soap  \
    --enable-sockets  \
    --enable-xmlreader  \
    --enable-xmlwriter  \
    --with-config-file-path=/usr/local/php8/etc \
    --with-config-file-scan-dir=/usr/local/php8/etc/conf.d \
    --with-mysqli \
    --with-pdo-mysql \
    --with-openssl \
    --with-readline \
    --with-sodium \
    --with-zip \
    --with-bz2 \
    --with-curl \
    --with-ffi \
    --with-openssl  \
    --with-tidy \
    --with-zlib
```
# PHP 7 Container
Problem building with OpenSSL:
* https://stackoverflow.com/questions/74988278/how-to-compile-php-7-4-33-correctly-with-openssl-1-1-1-on-ubuntu-22-04

Otherwise the file locations and settings are the same except:
* Instead of `php8` look for `php7` in all file and directory paths

# Adding Extensions
To add an extension, you can use the `pecl` command:
* 

Otherwise, for core extensions, proceed as follows:
* Shell into the `php8` or `php7` container
```
# example: php8
cd /path/to/cookbook
./admin.sh shell php8
```
* Make sure that build scripts are executable
```
chmod +x /opt/php-src/scripts/*
```
* Change to the PHP source extensions directory
```
cd /opt/php-src/ext
```
* Locate the desired extension and change to its directory
* Run `phpize`
```
/opt/php-src/scripts/phpize --with-php-config=/opt/php-src/scripts/php-config
```
* Run `configure`
```
/opt/php-src/scripts/configure --prefix=/usr/local/php8
```
* Build and install
```
make
make test
make install
```
