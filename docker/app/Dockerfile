FROM php:8.1-fpm

RUN apt-get update \
 && apt-get upgrade -y \
 && apt-get install -y ca-certificates \
 && update-ca-certificates \
 && apt-get install -y \
      git \
      unzip \
      zip \
      openssl \
      libpng-dev \
      libzip-dev \
      wkhtmltopdf \
      postgresql-client \
      vim

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP Extensions
RUN curl -fsSL -o - \
          https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
          | sh -s \
            gd \
            intl \
            opcache \
            pdo \
            pdo_mysql \
            pdo_pgsql \
            redis \
            xdebug \
            zip \
            amqp \
            soap \
            bcmath \
            imagick

# Create all cofig files
RUN { \
      echo "memory_limit = 1024M"; \
      echo "post_max_size = 21M"; \
      echo "upload_max_filesize = 20M"; \
      echo "expose_php = 0"; \
      echo "session.use_strict_mode = 1"; \
      echo "zend.detect_unicode = 0"; \
      echo "realpath_cache_size = 4096K"; \
      echo "realpath_cache_ttl = 600"; \
      echo "opcache.interned_strings_buffer = 16"; \
      echo "opcache.max_accelerated_files = 20000"; \
      echo "opcache.memory_consumption = 256"; \
      echo "opcache.enable_file_override = 1"; \
      echo 'max_execution_time = 210'; \
      echo 'session.save_handler = redis'; \
      echo 'session.save_path = "tcp://redissessions:6380"'; \
    } > ${PHP_INI_DIR}/conf.d/app.ini \
 && { \
      echo "xdebug.mode=develop,debug,coverage"; \
      echo "xdebug.client_host=172.17.0.1"; \
      echo "xdebug.client_port=9003"; \
      echo "xdebug.discover_client_host=1"; \
    } > ${PHP_INI_DIR}/conf.d/xdebug.ini

RUN mkdir /var/www/documents \
 && chown www-data:www-data /var/www/documents

RUN rm -f /etc/localtime \
 && ln -s /usr/share/zoneinfo/Europe/Warsaw /etc/localtime
WORKDIR /var/www/backend
#      echo "xdebug.client_host=172.17.0.1"; \