FROM php:8.2-cli

RUN apt-get update && apt-get install -y git curl nodejs npm
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN npm install -g pnpm

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN pnpm install && pnpm run build

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]