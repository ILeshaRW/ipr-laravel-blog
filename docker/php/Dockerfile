FROM php:8.1-fpm AS application
ENV TZ=UTC

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    libxml2-dev \
    cron \
    zip \
    unzip \
    vim \
    nano \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pgsql pdo pdo_pgsql mbstring exif pcntl bcmath gd soap \
    && docker-php-ext-enable pdo_pgsql

#RUN pecl install redis && docker-php-ext-enable redis

# Get Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy php.ini
COPY ./docker/php/conf.d/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/conf.d/php.ini /usr/local/etc/php/conf.d/docker-fpm.ini
COPY ./docker/php/conf.d/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copy folders here
COPY --chown=www-data:www-data ./app /var/www/html/app
COPY --chown=www-data:www-data ./bootstrap /var/www/html/bootstrap
COPY --chown=www-data:www-data ./config /var/www/html/config
COPY --chown=www-data:www-data ./database /var/www/html/database
COPY --chown=www-data:www-data ./public /var/www/html/public
COPY --chown=www-data:www-data ./resources /var/www/html/resources
COPY --chown=www-data:www-data ./routes /var/www/html/routes
COPY --chown=www-data:www-data ./storage /var/www/html/storage
COPY --chown=www-data:www-data ./tests /var/www/html/tests

# Copy files
COPY --chown=www-data:www-data ./artisan /var/www/html/artisan
COPY --chown=www-data:www-data ./composer.json /var/www/html/composer.json
COPY --chown=www-data:www-data ./phpunit.xml /var/www/html/phpunit.xml
COPY --chown=www-data:www-data ./.env /var/www/html/.env

# Set working directory
WORKDIR /var/www/html

RUN chown www-data:www-data /var/www/html
USER www-data
RUN composer install

ARG user=www-data
ENV USER_START=$user

FROM application AS develop
ARG user
ARG uid
ENV USER_START=$user
USER root

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Install XDebug
RUN pecl install xdebug-3.1.2 \
    && docker-php-ext-enable xdebug

COPY ./docker/php/conf.d/docker-php-ext-xdebug.ini $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini
USER ${user}
