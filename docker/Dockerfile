FROM php:8.4-apache-bookworm 

RUN apt-get update
RUN apt-get -y install zip wget git zlib1g-dev libpng-dev rsync
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd

RUN mkdir -p /var/www/pension
ADD . /var/www/pension
WORKDIR /var/www/pension

ENV PATH="${PATH}:/root/.symfony/bin"

# RUN wget https://get.symfony.com/cli/installer -O - | bash
# ENV PATH="${PATH}:/root/.symfony/bin"
RUN echo ${PATH}
# RUN symfony composer install

COPY docker/apache/001-pension.conf /etc/apache2/sites-available
RUN a2enmod rewrite
RUN a2dissite 000-default
RUN a2ensite 001-pension
RUN service apache2 restart