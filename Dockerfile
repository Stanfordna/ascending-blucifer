# =============================================================================
# Maggie Chamberlain Dietitian Website - Production Dockerfile
# Multi-stage Alpine build with PHP 8.3, Python 3.11, Nginx
# =============================================================================

# -----------------------------------------------------------------------------
# Stage 1: Composer Dependencies
# -----------------------------------------------------------------------------
FROM composer:2.7 AS composer-build

WORKDIR /app

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install dependencies without dev packages
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

# Copy application code
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev

# -----------------------------------------------------------------------------
# Stage 2: Frontend Build
# -----------------------------------------------------------------------------
FROM node:20-alpine AS frontend-build

WORKDIR /app

# Copy package files
COPY package.json package-lock.json* ./

# Install dependencies
RUN npm ci --silent

# Copy frontend source files
COPY resources/ ./resources/
COPY vite.config.js ./
COPY tailwind.config.js* ./
COPY postcss.config.js* ./

# Build production assets
RUN npm run build

# -----------------------------------------------------------------------------
# Stage 3: Production Runtime
# -----------------------------------------------------------------------------
FROM alpine:3.19 AS runtime

LABEL maintainer="Stan Chamberlain"
LABEL description="Maggie Chamberlain Dietitian Website"

# Environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Install runtime dependencies
RUN apk add --no-cache \
    # PHP and extensions
    php83 \
    php83-fpm \
    php83-pdo \
    php83-pdo_sqlite \
    php83-sqlite3 \
    php83-mbstring \
    php83-xml \
    php83-dom \
    php83-curl \
    php83-openssl \
    php83-json \
    php83-phar \
    php83-tokenizer \
    php83-fileinfo \
    php83-ctype \
    php83-session \
    php83-gd \
    php83-bcmath \
    php83-pcntl \
    php83-posix \
    # Python for AI/ML and image processing
    python3 \
    py3-pip \
    # Web server and process manager
    nginx \
    supervisor \
    # Utilities
    curl \
    sqlite \
    && ln -s /usr/bin/php83 /usr/bin/php \
    && ln -s /usr/sbin/php-fpm83 /usr/sbin/php-fpm

# Create application user
RUN addgroup -g 1000 www \
    && adduser -u 1000 -G www -h /var/www -D www

# Configure PHP-FPM
COPY docker/php-fpm.conf /etc/php83/php-fpm.d/www.conf

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx-site.conf /etc/nginx/http.d/default.conf

# Configure Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application from composer build
COPY --from=composer-build /app/vendor ./vendor
COPY --from=composer-build /app .

# Copy built frontend assets
COPY --from=frontend-build /app/public/build ./public/build

# Create required directories
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    database

# Create SQLite database file
RUN touch database/database.sqlite

# Set permissions
RUN chown -R www:www /var/www/html \
    && chmod -R 755 storage bootstrap/cache \
    && chmod 664 database/database.sqlite

# Create nginx directories
RUN mkdir -p /run/nginx

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
