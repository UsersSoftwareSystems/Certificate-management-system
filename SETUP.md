# Certificate Management System

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A comprehensive certificate management system built with Laravel, Vue.js, and Docker.

## 🚀 Getting Started

### Prerequisites

- Docker and Docker Compose
- Git
- Node.js 20+ (for local development)

### 🛠 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/Certificate-management-system.git
   cd Certificate-management-system
   ```

2. **Set up environment**
   ```bash
   cp .env.example .env
   # Update .env with your configuration
   ```

3. **Start Docker containers**
   ```bash
   docker-compose up -d --build
   ```

4. **Install PHP dependencies**
   ```bash
   docker-compose exec app composer install
   ```

5. **Generate application key**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Set up database**
   ```bash
   touch database/database.sqlite
   docker-compose exec app php artisan migrate --seed
   ```

7. **Install and build frontend assets**
   ```bash
   npm install
   npm run build
   ```

8. **Set permissions**
   ```bash
   sudo chown -R $USER:$USER .
   sudo chmod -R 775 storage bootstrap/cache
   ```

## 🌐 Access the Application

- **Main Application**: http://localhost:8080
- **Mailcatcher**: http://localhost:1080

## 🔑 Default Admin Credentials

- **Email**: test@example.com
- **Password**: test

## 🛠 Development

### Running the development server

```bash
# Start the development server
npm run dev

# Watch for changes
npm run watch
```

### Running tests

```bash
docker-compose exec app php artisan test
```

## 🔧 Common Issues & Solutions

### Permission Issues

```bash
sudo chown -R $USER:$USER .
sudo chmod -R 775 storage bootstrap/cache
```

### Node.js Version Issues

```bash
# Install Node.js 20+
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# Rebuild assets
npm install
npm run build
```

### Vite Manifest Not Found

```bash
npm install
npm run build
```

### Docker Container Issues

```bash
docker-compose down
docker-compose up -d --build
```

## 📦 Production Deployment

1. Update `.env` with production settings:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=your-production-url.com
   ```

2. Optimize the application:
   ```bash
   docker-compose exec app php artisan config:cache
   docker-compose exec app php artisan route:cache
   docker-compose exec app php artisan view:cache
   ```

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel
- Vue.js
- Tailwind CSS
- Docker

---

<div align="center">
  Made with ❤️ by Your Name
</div>
