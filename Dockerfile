FROM php:8.2-cli

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libssl-dev \
    pkg-config

# Installer l'extension MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Installer d'autres extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer pnpm
RUN npm install -g pnpm

WORKDIR /app

# Copier les fichiers du projet
COPY . .

# Mettre à jour les dépendances MongoDB pour compatibilité PHP 8.2
RUN composer update mongodb/mongodb mongodb/laravel-mongodb --with-all-dependencies

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Installer les dépendances Node et builder
RUN pnpm install
RUN pnpm run build

# Exposer le port
EXPOSE $PORT

# Commande de démarrage
CMD php artisan serve --host=0.0.0.0 --port=$PORT