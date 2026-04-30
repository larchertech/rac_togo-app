FROM php:8.3-fpm-alpine

# Installer les dépendances système
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nginx \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    gd \
    zip \
    bcmath \
    opcache \
    pcntl

# Installer Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurer le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de configuration
COPY php.ini /usr/local/etc/php/conf.d/custom.ini
COPY nginx.conf /etc/nginx/http.d/default.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Exposer le port
EXPOSE 8000

# Commande de démarrage
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
