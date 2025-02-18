FROM php:8.1-apache

ARG ENABLE_XDEBUG=false
ARG uid=1008
ARG gid=1008

## Update system
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get autoremove --purge -y && \
    rm -rf /var/lib/apt/lists/* && \
    # Create user for Apache
    groupadd -g ${gid} localUser && \
    useradd -l -u ${uid} -g ${gid} -m -s /bin/bash localUser && \
    usermod -a -G www-data localUser

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN if [ "$ENABLE_XDEBUG" = "true" ]; then echo ************ XDEBUG ENABLED **********; \
else echo ------------ XDEBUG DISABLED ==========; fi

# Install required PHP extensions for AFUP website and other management package
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        nodejs \
        npm \
    && \
    install-php-extensions \
    	@composer \
        apcu \
        opcache \
        pdo_mysql \
        mysqli \
        zip \
        gd \
        intl \
        pcntl \
    && \
    # Install Yarn
    npm install -g yarn && \
    if [ "$ENABLE_XDEBUG" = "true" ]; then pecl install xdebug-2.6.1 && install-php-extensions xdebug; fi && \
    apt-get autoremove --purge -y && \
    rm -rf /var/lib/apt/lists/*

# Configuration of Apache and PHP
COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY apache.crt /etc/apache2/ssl/apache.crt
COPY apache.key /etc/apache2/ssl/apache.key
RUN sed --in-place "s/User \${APACHE_RUN_USER}/User localUser/" /etc/apache2/apache2.conf && \
    sed --in-place "s/Group \${APACHE_RUN_GROUP}/Group localUser/" /etc/apache2/apache2.conf && \
    a2ensite 000-default && \
    a2enmod rewrite ssl && \
    echo "date.timezone=Europe/Paris" >> "/usr/local/etc/php/php.ini"
