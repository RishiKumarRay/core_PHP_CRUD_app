# image pulled from DockerHub
FROM php:8.1.1-fpm

# install necessary system dependencies to run composer
RUN apt-get update \
    && apt-get install -y sudo \
    && apt-get install -y git \
    && apt-get install -y zip \
    && apt-get install -y unzip

# get latest Composer and making it available in the path
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# create system user ("php_crud_app" with uid 1000) to run Composer commands
RUN useradd -G www-data,root -u 1000 -d /home/php_crud_app php_crud_app
RUN mkdir -p /home/php_crud_app/.composer && \
    chown -R php_crud_app:php_crud_app /home/php_crud_app

# copy existing application directory contents
COPY . /php_crud_app
# copy existing application directory permissions
COPY --chown=php_crud_app:php_crud_app . /php_crud_app

# changing user (because cannot run Composer as root)
USER php_crud_app

WORKDIR /php_crud_app

# installing Composer deps, the vendor folder will only be populated inside the container
# RUN composer install

EXPOSE 9000