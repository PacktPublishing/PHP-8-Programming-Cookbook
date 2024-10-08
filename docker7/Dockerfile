# OS=one of ubuntu, centos, or debian
ARG OS=ubuntu

# OS_VERSION=OS version to use; e.g., 20.04, 8, and 10
ARG OS_VERSION=24.04

# PHP version to use; can be 5.6, 7.1, 7.2, 7.3, 7.4, or 8.0.
# Ability to build a version is dependent on having ZendPHP credentials that
# authorize use of that version.
ARG ZENDPHP_VERSION=7.4

# BASE_IMAGE=cli or fpm
ARG BASE_IMAGE=cli
# Append -arm after $BASE_IMAGE if you wish to use the ARM variant
FROM cr.zend.com/zendphp/${ZENDPHP_VERSION}:${OS}-${OS_VERSION}-${BASE_IMAGE}
MAINTAINER doug@unlikelysource.com
RUN \
    echo "Installing misc tools ..." && \
    apt-get update && \
    apt-get install -y less vim net-tools wget unzip apt-utils curl tree
RUN \
    echo "Adding/enabling PHP extensions ..." && \
    zendphpctl ext install gd && \
    zendphpctl ext install simplexml && \
    zendphpctl ext install intl && \
    zendphpctl ext install mbstring && \
    zendphpctl ext install mysql && \
    zendphpctl ext install curl
WORKDIR /repo
