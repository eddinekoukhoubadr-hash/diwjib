FROM php:8.2-cli

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git curl nodejs npm \
    libssl-dev pkg-config

# Installer MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer pnpm
RUN npm install -g pnpm

WORKDIR /app
COPY . .

# SOLUTION : Copier .env.production vers .env
RUN cp .env.production .env

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Installer les dépendances Node et builder
RUN pnpm install
RUN pnpm run build

# Générer la clé Laravel (sécurité)
RUN php artisan key:generate

# Commande de démarrage
CMD php -S 0.0.0.0:8000 -t public