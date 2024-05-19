# Gunakan image resmi PHP dengan Apache
FROM php:8.2-apache

# Setel direktori kerja di dalam container
WORKDIR /var/www/html

# Salin file composer untuk menginstall dependency jika menggunakan composer
COPY composer.json composer.lock /var/www/html/

# Jalankan perintah composer install jika menggunakan composer
RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-enable zip \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable mysqli pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-scripts --no-autoloader

# Salin semua file dari direktori saat ini ke direktori kerja di dalam container
COPY . /var/www/html

# Install dependensi setelah file disalin
RUN composer dump-autoload --optimize

# Ganti document root Apache ke direktori public
RUN sed -i -r 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/public/g' /etc/apache2/sites-available/000-default.conf \
    && sed -i -r 's/<Directory \/var\/www\/>/<Directory \/var\/www\/html\/public\/>/g' /etc/apache2/apache2.conf \
    && sed -i -r 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Aktifkan modul rewrite Apache
RUN a2enmod rewrite

# Ekspos port 80 untuk mengakses aplikasi melalui browser
EXPOSE 80
