FROM php:8.2-cli

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git curl nodejs npm

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer pnpm
RUN npm install -g pnpm

WORKDIR /app
COPY . .

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN pnpm install && pnpm run build

# SOLUTION : Utiliser le serveur PHP interne
CMD php -S 0.0.0.0:$PORT -t public