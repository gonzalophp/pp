FROM php:8.4-apache-bookworm 

RUN apt-get update
RUN apt-get -y install zip git zlib1g-dev libpng-dev rsync acl
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd

RUN curl -o composer-setup.php https://getcomposer.org/installer 
RUN php composer-setup.php
RUN rm composer-setup.php
RUN mv composer.phar /usr/local/bin/composer

RUN mkdir -p /var/www/pension
WORKDIR /var/www/pension
ADD . /var/www/pension

COPY docker/apache/001-pension.conf /etc/apache2/sites-available
RUN a2enmod rewrite
RUN a2dissite 000-default
RUN a2ensite 001-pension
RUN service apache2 restart


# RUN composer require -n symfony/symfony:^7.3
# RUN composer config --no-plugins allow-plugins.symfony/runtime true
# RUN composer require -n symfony/runtime

RUN mkdir -p /var/www/pension/var 
RUN setfacl -R -m u:www-data:rwx /var/www/pension/var 
RUN composer install