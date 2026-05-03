
FROM php:8.5-apache
RUN a2enmod headers

# Копируем ваш файл настроек внутрь образа
COPY ./my-httpd.conf /etc/apache2/conf-available/no-cache.conf

COPY . /var/www/html/

# Активируем его docker-compose up -d --build
RUN a2enconf no-cache