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

# Installer l'extension MongoDB (version compatible)
RUN pecl install mongodb-1.15.3 && docker-php-ext-enable mongodb

# Installer d'autres extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer pnpm
RUN npm install -g pnpm

WORKDIR /app

# Copier les fichiers du projet
COPY . .

# Rendre le script exécutable
RUN chmod +x start.sh

# Installer les dépendances PHP (IGNORER la vérification de version)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-mongodb

# Installer les dépendances Node et builder
RUN pnpm install
RUN pnpm run build

# Exposer le port
EXPOSE $PORT

# Commande de démarrage avec le script
CMD ["./start.sh"]