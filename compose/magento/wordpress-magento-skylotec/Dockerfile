FROM php:7.4-apache

LABEL author=m.johann@digitalexperts.de

ENV XDEBUG_PORT 9000

RUN apt update && \
    apt install -y
    
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN echo "Calling composer"

USER app:app

RUN composer
