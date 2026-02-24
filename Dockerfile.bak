FROM php:8.2-apache

# Installer les dépendances système requises pour les extensions PHP
RUN apt-get update && apt-get install -y \
    libbson-1.0 \
    libmongoc-1.0-0 \
    libonig-dev \
    libzip-dev \
    unzip \
    git \
    libssl-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP (PDO MySQL, etc)
RUN docker-php-ext-install pdo pdo_mysql mbstring zip

# Installer l'extension MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Activer mod_rewrite d'Apache
RUN a2enmod rewrite

# Configuration d'Apache pour autoriser les .htaccess
RUN echo "<Directory /var/www/html>\n\
    AllowOverride All\n\
</Directory>\n"\
>> /etc/apache2/apache2.conf

# Copier le code source de l'application
COPY . /var/www/html/

# Donner les droits appropriés
RUN chown -R www-data:www-data /var/www/html
