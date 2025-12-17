# Certificate Management System

A robust, secure, and user-friendly web application for managing applicant certificates, trustee verifications, and administrative workflows. Built with modern web technologies to ensure scalability and ease of use.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)

## 🚀 Features

-   **Public Application Portal**: accessible form for applicants to submit details and upload certificates.
-   **Trustee Verification Workflow**: Automated email workflows for trustees to verify applicant claims securely.
-   **Admin Dashboard**: comprehensive panel to view, approve, rejection, and manage applications.
-   **Certificate Generation**: Automated PDF generation for verified applicants.
-   **Role-Based Access Control**: Secure admin and user permissions using Spatie Permissions.
-   **Dark Mode Support**: Fully responsive UI with native dark mode integration.

---

## 🛠️ Technical Stack

-   **Backend**: Laravel 12.x (PHP 8.2+)
-   **Frontend**: Blade Templates, Alpine.js, TailwindCSS 4.0
-   **Database**: SQLite (Default), compatible with MySQL/PostgreSQL
-   **Build Tool**: Vite
-   **Containerization**: Docker & Docker Compose
-   **Queue & Cache**: Redis
-   **PDF Generation**: DomPDF

---

## 💻 Installation Guide

Follow these steps to set up the project on a new machine.

### Prerequisites

Ensure you have the following installed:
-   [PHP 8.2+](https://www.php.net/downloads)
-   [Composer](https://getcomposer.org/)
-   [Node.js & NPM](https://nodejs.org/)
-   [Git](https://git-scm.com/)

### Method 1: Local Setup (Recommended for Development)

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/yourusername/certificate-management-system.git
    cd certificate-management-system
    ```

2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Install Frontend Dependencies**
    ```bash
    npm install
    ```

4.  **Environment Configuration**
    Copy the example environment file and configure it:
    ```bash
    cp .env.example .env
    ```
    *Update the `.env` file with your database or mail configuration if needed. By default, it is configured for SQLite.*

5.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

6.  **Database Setup**
    Create the SQLite database file and run migrations:
    ```bash
    touch database/database.sqlite
    php artisan migrate --seed
    ```
    *The `--seed` flag populates the database with initial roles and admin users.*

7.  **Start the Server**
    You need to run two commands in separate terminal windows:

    *Terminal 1 (Vite Build server):*
    ```bash
    npm run dev
    ```

    *Terminal 2 (Laravel Server):*
    ```bash
    php artisan serve
    ```

    Access the application at: `http://localhost:8000`

---

### Method 2: Docker Setup (Production/Isolated Env)

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/yourusername/certificate-management-system.git
    cd certificate-management-system
    ```

2.  **Environment Configuration**
    ```bash
    cp .env.example .env
    ```

3.  **Build and Start Containers**
    ```bash
    docker-compose up -d --build
    ```

4.  **Install Dependencies inside Container**
    ```bash
    docker-compose exec app composer install
    docker-compose exec app php artisan key:generate
    docker-compose exec app php artisan migrate --seed
    ```

5.  **Access Application**
    -   **Web App**: `http://localhost:8080`
    -   **MailCatcher** (Email Testing): `http://localhost:1080`

---

## 🔑 Default Credentials

If the database seeder was run, use the following credentials to log in as an Admin:

-   **Email**: `admin@restaurant.com`
-   **Password**: `password`

---

## 🧪 Running Tests

To run the automated test suite:

```bash
php artisan test
```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
