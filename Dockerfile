FROM php:7.2-apache
COPY ./ /var/www/html/

RUN cd ~
RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN composer self-update 2.0.8

CMD php artisan serve --host=0.0.0.0
EXPOSE 8000