# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Instala extensiones PHP necesarias para PostgreSQL
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql

# Exponer el puerto 80 para Apache
EXPOSE 80
