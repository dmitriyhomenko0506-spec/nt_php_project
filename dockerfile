# Используем стабильную версию (8.5 еще не существует)
FROM php:8.5-apache
RUN a2enmod headers

# Копируем ваш файл настроек внутрь образа
COPY ./my-httpd.conf /etc/apache2/conf-available/no-cache.conf

# Активируем егоdocker-compose up -d --build
RUN a2enconf no-cache