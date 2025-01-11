FROM php:8.2-cli

# Set the working directory
WORKDIR /app

# Install required packages, including libzip, git, zip, and unzip
RUN apt-get update && \
    apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libicu-dev && \
    docker-php-ext-install zip intl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copy project files into the container
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer