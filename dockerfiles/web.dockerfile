FROM ubuntu:18.04

ENV DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www/Laravel

RUN apt-get update && \
    apt-get install -y git \
                   zip \
                   curl \
                   apt-utils \
                   software-properties-common

RUN add-apt-repository -y ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y apache2 \
                git \
                vim \
                php \
                php-curl \
                php-mbstring \
                php-cli \
                php-xml \
                php-zip \
                php-mysql \
                php-gd \
                php-bcmath \
                php-json \
                php-readline \
                php-tokenizer \
                libapache2-mod-php\
                supervisor\
                composer

# Apache設置
COPY web/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Install Composer and extensions
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set work folder permission
RUN usermod -u 1000 www-data

# Install NPM
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt update && apt install -y nodejs

# Setting supervisor
RUN mkdir -p /var/log/supervisor
#COPY supervisor/schedule.conf /etc/supervisor/conf.d
COPY supervisor/websockets.conf /etc/supervisor/conf.d
COPY supervisor/queue.conf /etc/supervisor/conf.d


EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
