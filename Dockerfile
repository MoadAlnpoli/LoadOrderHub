FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    nginx

RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .
# تأكد من أن الـ Working Directory هي الصحيحة (/var/www)
# تثبيت Node.js (إذا لم تكن موجودة في الصورة الأساسية)
# لنفترض أنك تستخدم صورة php-fpm، سنحتاج لتثبيت Node يدوياً.
# إذا كانت صورتك الأساسية تحتوي على Node، تخطى السطر التالي.
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs

# ثم بناء الـ Assets
RUN npm install
RUN npm run build
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=80
