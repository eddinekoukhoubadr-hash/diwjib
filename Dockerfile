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

# CRÉER le fichier .env directement
RUN echo "APP_NAME=DIWJIB" > .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_KEY=base64:fkhiKAjskaqWPNQ6E7DyJHptxAWAneMvZ/kzHrFExq4=" >> .env && \
    echo "APP_DEBUG=true" >> .env && \
    echo "APP_URL=https://diwjib-production.up.railway.app" >> .env && \
    echo "DB_CONNECTION=mongodb" >> .env && \
    echo "DB_URI=mongodb+srv://diwjib_db_pedrojesam:6v8UckuxDHlqrHtA@pedrojesam-diwjib.yuayso9.mongodb.net/diwjib?retryWrites=true&w=majority" >> .env && \
    echo "ORS_API_KEY=eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6ImE0MWU3YTY5Mzk5MDQ0NzQ5NGFlMTY3MWIzZjQ4YzM4IiwiaCI6Im11cm11cjY0In0=" >> .env && \
    echo "RECAPTCHA_SITE_KEY=6LeptIkrAAAAAO9FsN3Zh_P08aSv5xMGAnQrGhHe" >> .env && \
    echo "RECAPTCHA_SECRET_KEY=6LeptIkrAAAAAAlsJpdZbQ5v7Tldog2rs6bOyTq1" >> .env && \
    echo "ADMIN_ACCESS_CODE=12345" >> .env

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Installer les dépendances Node et builder
RUN pnpm install
RUN pnpm run build

# Commande de démarrage
CMD php -S 0.0.0.0:8000 -t public