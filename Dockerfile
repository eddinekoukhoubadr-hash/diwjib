FROM php:8.2-cli

<<<<<<< HEAD
RUN apt-get update && apt-get install -y git curl nodejs npm
=======
# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git curl nodejs npm \
    libssl-dev pkg-config

# Installer MongoDB 1.15.3
RUN pecl install mongodb-1.15.3 && docker-php-ext-enable mongodb

# Installer Composer
>>>>>>> e8865197fbb1698a784f2246c85b3024c3d132fc
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN npm install -g pnpm

WORKDIR /app
COPY . .

<<<<<<< HEAD
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN pnpm install && pnpm run build

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
=======
# ✅ NE PAS créer .env - utiliser les variables Railway directement
# Les variables sont déjà disponibles via getenv()

# Installer les dépendances (ignorer les vérifications MongoDB)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN pnpm install && pnpm run build

CMD php -S 0.0.0.0:8000 -t public
>>>>>>> e8865197fbb1698a784f2246c85b3024c3d132fc
