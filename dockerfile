
FROM php:8.5-apache
RUN a2enmod headers

COPY ./my-httpd.conf /etc/apache2/conf-available/no-cache.conf
COPY . /var/www/html/

RUN a2enconf no-cache