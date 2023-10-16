FROM php:7.4-fpm

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get install -y \
  cron \
  git \
  gzip \
  libfreetype6-dev \
  libicu-dev \
  libjpeg62-turbo-dev \
  libmcrypt-dev \
  libpng-dev \
  libxslt1-dev \
  lsof \
  lsb-release \
  vim \
  zip \
  wget \
  gnupg \
  libonig-dev \
  libzip-dev

RUN echo mysql-apt-config mysql-apt-config/enable-repo select mysql-8.0 | debconf-set-selections

# RUN wget https://dev.mysql.com/get/mysql-apt-config_0.8.20-1_all.deb && \
#     dpkg -i mysql-apt-config_0.8.20-1_all.deb && \
#     apt-get update && \
#     apt-get install -y mysql-client
#

RUN docker-php-ext-install \
  bcmath \
  gd \
  intl \
  mbstring \
  opcache \
  pdo_mysql \
  soap \
  xsl \
  zip

RUN pecl channel-update pecl.php.net \
  && pecl install xdebug-3.1.6 \
  && docker-php-ext-enable xdebug \
  && sed -i -e 's/^zend_extension/\;zend_extension/g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN apt-get install -y libssh2-1-dev \
    && curl -L -o ssh2.tar.gz https://pecl.php.net/get/ssh2-1.2.tgz \
    && tar -xzf ssh2.tar.gz \
    && cd ssh2-1.2 \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable ssh2

RUN curl -sS https://getcomposer.org/installer | \
  php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 app \
 && useradd -g 1000 -u 1000 -d /var/www -s /bin/bash app

# RUN apt-get install -y gnupg \
#   && curl -sL https://deb.nodesource.com/setup_8.x | bash - \
#   && apt-get install -y nodejs \
#   && mkdir /var/www/.config /var/www/.npm \
#   && chown app:app /var/www/.config /var/www/.npm \
#   && ln -s /var/www/html/node_modules/grunt/bin/grunt /usr/bin/grunt

RUN printf '* *\t* * *\tapp\t%s/usr/local/bin/php /var/www/html/update/cron.php\n' >> /etc/crontab
RUN printf '* *\t* * *\tapp\t%s/usr/local/bin/php /var/www/html/bin/magento cron:run\n' >> /etc/crontab
RUN printf '* *\t* * *\tapp\t%s/usr/local/bin/php /var/www/html/bin/magento setup:cron:run\n#\n' >> /etc/crontab

COPY conf/www.conf /usr/local/etc/php-fpm.d/
COPY conf/php.ini /usr/local/etc/php/
COPY conf/php-fpm.conf /usr/local/etc/
COPY bin/cronstart /usr/local/bin/

RUN mkdir /sock
RUN chown -R app:app /usr/local/etc/php/conf.d /sock

USER app:app

VOLUME /var/www

WORKDIR /var/www/html

EXPOSE 9001
