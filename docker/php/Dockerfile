FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libxslt-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    git

RUN docker-php-ext-install -j$(nproc) iconv mbstring pdo_mysql exif pcntl bcmath gd intl

# чтобы composer не спрашивал разрешения по ключам
RUN mkdir ~/.ssh
RUN touch ~/.ssh/known_hosts
RUN ssh-keyscan -H github.com >> ~/.ssh/known_hosts

# расширение для работы с redis
RUN pecl install redis \
    && docker-php-ext-enable redis

ENV TZ=Asia/Almaty
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# без этого будет создаваться ненужная директория /var/www/html
WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# делаем чтобы пользователь www-data был как основной пользователь на хосте
RUN usermod -u 1000 www-data \
  && groupmod -g 1000 www-data

CMD (/sync-cnf.sh &) && update-ca-certificates && php-fpm