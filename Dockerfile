# Pake image PHP resmi versi 8.2 (Bisa disesuaikan sama versi PHP Laravel lu)
FROM php:8.2-apache

# Update sistem dan install ekstensi yang dibutuhin Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    zip \
    curl \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install pgsql \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur folder kerja
WORKDIR /var/www/html

# Pindahkan file project lu ke dalem Docker
COPY . .

# Install dependency Laravel via Composer
RUN composer install --no-dev --optimize-autoloader

# Ganti folder root Apache ke folder 'public' punya Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Nyalain mod_rewrite buat routing Laravel
RUN a2enmod rewrite

# Ganti izin akses folder biar Laravel bisa nulis file (kayak log & cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Buka port 80
EXPOSE 80

# Mulai server web Apache
CMD ["apache2-foreground"]