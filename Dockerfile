FROM php:7.3.3-apache
RUN docker-php-ext-install mysqli
EXPOSE 80
ENV APACHE_DOCUMENT_ROOT /var/www/html
