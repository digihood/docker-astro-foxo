FROM wordpress:php8.1
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite headers
