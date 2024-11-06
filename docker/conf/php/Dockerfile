FROM php:8.2-fpm

# Mettre à jour et installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg-dev \
    libpng-dev \
    libmcrypt-dev \
    git \
    zip \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql


# Installer et configurer opcache
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"
RUN docker-php-ext-install opcache
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Installer et configurer Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
RUN { \
        echo 'xdebug.mode=debug'; \
        echo 'xdebug.start_with_request=yes'; \
        echo 'xdebug.client_host=172.17.0.1'; \
        echo 'xdebug.client_port=9003'; \
        echo 'xdebug.log=/var/log/xdebug.log'; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Configurations PHP supplémentaires
RUN echo "file_uploads = On\n" \
         "memory_limit = 500M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini
RUN echo "date.timezone = \"Europe/Paris\"" > /usr/local/etc/php/conf.d/date.ini

# Installer les outils supplémentaires
RUN apt-get install -y git xvfb libfontconfig wkhtmltopdf



# Installer Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

   # Installer Symfony CLI
   RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' |  bash \
       && apt install symfony-cli

COPY install-node.sh /install-node.sh
RUN bash /install-node.sh

