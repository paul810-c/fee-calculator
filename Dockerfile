FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && \
    apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    default-mysql-client && \
    docker-php-ext-install zip intl pdo pdo_mysql && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copy project files into the container
COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
