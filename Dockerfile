FROM php:8.1-cli

WORKDIR /var/www/html

# Copia todos los archivos a la imagen Docker
COPY . .

# Exponer puerto 8080 y usar el servidor embebido de PHP
CMD [ "php", "-S", "0.0.0.0:8080", "-t", "public" ]
