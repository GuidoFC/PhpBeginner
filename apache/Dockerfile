FROM php:8.2-apache
WORKDIR '/var/www/html'

# Habilitar el módulo rewrite de Apache
RUN a2enmod rewrite

# Instalar Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Instalar extensiones de PHP
RUN docker-php-ext-install mysqli pdo_mysql

# Instalar dependencias adicionales
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql

# Copiar los archivos de la carpeta ./apache al contenedor
COPY ./apache /var/www/html
