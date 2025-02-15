# Utiliser une image PHP avec les extensions nécessaires
FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    nginx \
    sqlite3 \
    libsqlite3-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_sqlite

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers de l'application Laravel
COPY . .

# Création des dossiers nécessaires
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache

# Attribution des permissions correctes
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Changer les permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copier la configuration Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Exposer les ports pour Nginx et PHP-FPM
EXPOSE 80

# Lancer Nginx et PHP-FPM
CMD service nginx start && php-fpm
