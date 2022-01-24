# stage: msmtp
FROM ubuntu:20.04 as msmtp
RUN apt-get update && apt-get install -y msmtp msmtp-mta
COPY ./msmtprc /etc/msmtprc

# stage: PHP extensions and pecl
FROM php:8.1.1-fpm as ext_pecl
# to get the list of existing extensions in this image => docker run -it --rm php:8.1.1-fpm php -m
# installing additional PHP extensions using 'docker-php-ext-install' followed by the name of the extension
RUN docker-php-ext-install pdo_mysql

# stage: system deps and Composer
FROM ext_pecl as composer_deps
RUN apt-get update \
    && apt-get install -y sudo \
    && apt-get install -y git \
    && apt-get install -y zip \
    && apt-get install -y unzip
# get latest Composer and making it available in the path
COPY --from=composer:2.2.4 /usr/bin/composer /usr/bin/composer
# create system user ("php_crud_app" with uid 1000) to run Composer commands
RUN useradd -G www-data,root -u 1000 -d /home/php_crud_app php_crud_app
RUN mkdir /home/php_crud_app && \
    mkdir -p /home/php_crud_app/.composer && \
    chown -R php_crud_app:php_crud_app /home/php_crud_app
COPY ./composer.json /php_crud_app/composer.json
COPY ./composer.lock /php_crud_app/composer.lock
RUN chown -R php_crud_app:php_crud_app /php_crud_app
# changing user (because cannot run Composer as root)
USER php_crud_app
WORKDIR /php_crud_app
RUN mkdir vendor
# installing Composer deps, the vendor folder will only be populated inside the container
RUN composer install

# stage: app specific
FROM ext_pecl
# copy email settings
COPY --from=msmtp /etc/msmtprc /etc/msmtprc
# create system user ("php_crud_app" with uid 1000)
RUN useradd -G www-data,root -u 1000 -d /home/php_crud_app php_crud_app
RUN mkdir /home/php_crud_app && \
    chown -R php_crud_app:php_crud_app /home/php_crud_app
# copy existing application directory contents
COPY . /php_crud_app
# copy existing application directory permissions
COPY --chown=php_crud_app:php_crud_app . /php_crud_app
COPY --from=composer_deps /php_crud_app/vendor /php_crud_app/vendor
# changing user (because cannot run Composer as root)
USER php_crud_app
WORKDIR /php_crud_app

EXPOSE 9000