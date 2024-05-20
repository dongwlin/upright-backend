FROM php:8.3-fpm-alpine

RUN docker-php-ext-install pdo_mysql

RUN apk update && apk add --no-cache nginx unzip git && rm -rf /var/lib/apt/lists/*

RUN curl -sS -O https://mirrors.aliyun.com/composer/composer.phar && mv composer.phar /usr/local/bin/composer && chmod +x /usr/local/bin/composer

RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

COPY . /var/www/html

RUN cd /var/www/html && composer install --no-dev --prefer-dist --optimize-autoloader

COPY ./config/nginx.conf /etc/nginx/http.d/default.conf

RUN chown -R www-data:www-data /var/www/html/* && chmod -R 755 /var/www/html/*

EXPOSE 80

CMD ["sh", "-c", "nginx -g 'daemon off;' & php-fpm"]