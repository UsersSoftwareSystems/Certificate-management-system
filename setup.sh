#!/bin/bash

# Install Node.js dependencies
npm install

# Build assets
npm run build

# Set correct permissions
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
