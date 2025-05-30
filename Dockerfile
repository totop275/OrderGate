FROM dunglas/frankenphp:1.2-php8.3-bookworm

WORKDIR /app

RUN install-php-extensions \
    pcntl \
    curl \
    xml \
    gd \
    pdo_mysql \
    zip \
    mbstring \
    opcache

# Install cron, supervisor, and procps
RUN apt-get update && apt-get install -y cron supervisor procps

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install nodejs 16.15.0+
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# Set up cron job
RUN echo "* * * * * /usr/local/bin/php /app/artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/laravel-cron \
    && chmod 0644 /etc/cron.d/laravel-cron \
    && crontab /etc/cron.d/laravel-cron

# Copy supervisor configuration
COPY supervisor-configuration.conf /etc/supervisor/conf.d/supervisord.conf