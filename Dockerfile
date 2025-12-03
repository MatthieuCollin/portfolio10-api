FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip pdo pdo_mysql \
    && docker-php-ext-enable pdo pdo_mysql

RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/app

# Copier les fichiers de l'application
COPY . /var/www/app/

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-scripts --no-autoloader

# Configurer Apache pour pointer vers le dossier public de Symfony
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/app/public|g' /etc/apache2/sites-available/000-default.conf

# Ajouter la configuration du répertoire dans Apache
RUN echo '<Directory /var/www/app/public/>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</Directory>' >> /etc/apache2/sites-available/000-default.conf

# Définir les bonnes permissions
RUN chown -R www-data:www-data /var/www/app

EXPOSE 80

CMD ["apache2-foreground"]