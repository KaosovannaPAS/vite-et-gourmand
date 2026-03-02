FROM php:8.2-apache

# Met à jour les paquets et installe les extensions requises pour TiDB/MySQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Active le module Apache rewrite (utile pour les futurs routages API complexes)
RUN a2enmod rewrite

# Copie le code source local dans le dossier web du conteneur
COPY . /var/www/html/

# Donne les droits appropriés à l'utilisateur Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
