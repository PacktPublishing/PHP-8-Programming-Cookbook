FROM alpine:latest
ENV PHP8=php-8.4.0beta5
ENV PHP7=php-7.4.33
RUN \
    echo "Installed basic utilities ..." && \
    apk add curl git bash 
RUN \
    echo "Installed libs needed by PHP ..." && \
    apk add gcc autoconf re2c make pkgconf bison libc-dev curl-dev libxml2 libxml2-dev sqlite sqlite-dev bzip2 bzip2-dev openssl openssl-dev
RUN \
    echo "Installing PHP 8 ..." && \
    /bin/bash && \
    cd /opt && \
    git clone https://github.com/php/php-src && \
    cd php-src && \
    git checkout $PHP8 && \
    ./buildconf --force && \
    ./configure --prefix=/usr/local/php8 && \
    make && \
    make install && \
    ln -s /usr/local/php8/bin/php /usr/local/bin/php8
RUN \
    echo "Installing PHP 7 ..." && \
    /bin/bash && \
    cd /opt && \
    git clone https://github.com/php/php-src && \
    cd php-src && \
    git checkout $PHP8 && \
    ./buildconf --force && \
    ./configure --prefix=/usr/local/php7 && \
    make && \
    make install && \
    ln -s /usr/local/php7/bin/php /usr/local/bin/php7

