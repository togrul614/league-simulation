FROM php:8.0-fpm

# Cleaning and updating packages
RUN apt-get autoremove && apt-get autoclean && apt-get update

# Installing libraries
RUN apt-get install -y graphicsmagick jpegoptim \
    libpq-dev zlib1g-dev libicu-dev libzip-dev procps \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN printf "\n" | pecl install imagick
RUN docker-php-ext-enable imagick

# Installing php-redis extension via pecl.
RUN pecl install -o -f redis && rm -rf /tmp/pear

# Enabling && configuring php extensions
RUN docker-php-ext-enable redis
RUN docker-php-ext-configure intl
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli bcmath intl  -j$(nproc) gd zip

# Installing php extensions
RUN docker-php-ext-configure exif
RUN docker-php-ext-install exif
RUN docker-php-ext-enable exif

# Copy configuration files
COPY ./php.ini /usr/local/etc/php/conf.d/uploads.ini

# Installing composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --quiet

# Creating user for container.
RUN groupadd -g 1000 appuser && useradd -r -u 1000 -g appuser appuser

# Creating user && composer-cache directories.
RUN mkdir -p /home/appuser/.composer/cache/repo/https---asset-packagist.org \
    && mkdir /home/appuser/.composer/cache/files

# Giving proper permissions to user.
RUN chown -R appuser:appuser /home/appuser && chmod -R 775 /home/appuser

RUN rm -rf /home/appuser/storage/logs && mkdir -p /home/appuser/storage/logs

# Running container as user
USER appuser

ENV COMPOSER_NO_INTERACTION=1

WORKDIR /app
