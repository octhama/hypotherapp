# Étape 1 : Utilisation de l'image officielle PHP avec les extensions nécessaires
FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du répertoire de travail
WORKDIR /var/www

# Copie des fichiers du projet Laravel
COPY . .

# Création des dossiers nécessaires
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache

# Attribution des permissions correctes
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Installation des dépendances PHP de Laravel
RUN composer install --no-dev --optimize-autoloader

# Configuration des permissions pour le dossier SQLite
RUN touch /var/www/database/database.sqlite && \
    chown -R www-data:www-data /var/www/database

# Copie du fichier de configuration Nginx
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# Exposition des ports pour le service
EXPOSE 80

# Commande pour démarrer le serveur PHP et Nginx
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
