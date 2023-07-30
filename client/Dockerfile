FROM php:8.1-fpm-alpine

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo pdo_mysql sockets

# Install Node.js and npm
RUN apk add --no-cache nodejs npm

# Copy the application files to the container
WORKDIR /app
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Install front-end dependencies using npm
RUN npm install

# Build the front-end assets
RUN npm run build

EXPOSE 8000

# Start the Laravel application
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
