FROM php:8.2-cli

# Installer les dépendances système POUR MongoDB
RUN apt-get update && apt-get install -y \
    git curl nodejs npm \
    libssl-dev pkg-config

# INSTALLER MONGODB EXTENSION CORRECTEMENT
RUN pecl install mongodb && docker-php-ext-enable mongodb
# Forcer les variables d'environnement
ENV APP_ENV=production
ENV APP_DEBUG=true
ENV DB_CONNECTION=mongodb
ENV DB_URI=mongodb+srv://diwjib_db_pedrojesam:6v8UckuxDHlqrHtA@pedrojesam-diwjib.yuayso9.mongodb.net/diwjib?retryWrites=true&w=majority
# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer pnpm
RUN npm install -g pnpm

WORKDIR /app
COPY . .

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN pnpm install && pnpm run build

# Commande de démarrage
CMD php -S 0.0.0.0:8000 -t public