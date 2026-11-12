# CivicMind X - Production Deployment Guide

This guide covers deployment options for **CivicMind X** on Linux/Windows servers, Docker containerization, and standalone PHP development environments.

---

## Deployment Option 1: Standalone Local PHP Server (Fast Launch)

1. Open PowerShell or Terminal in the project root:
   ```bash
   cd "c:\Users\Admin\OneDrive\Desktop\civic ai"
   ```

2. Start the application using PHP's built-in CLI server:
   ```bash
   php -S localhost:8000 -t public public/router.php
   ```

3. Open your browser and navigate to:
   ```
   http://localhost:8000
   ```

*(Note: The system automatically provisions an SQLite database with pre-seeded data if MySQL is not currently running locally!)*

---

## Deployment Option 2: Docker Containerized Environment

1. Ensure Docker Desktop is installed and running.
2. Launch the application stack:
   ```bash
   docker-compose up -d --build
   ```
3. Docker will automatically launch:
   - **App Container**: PHP 8.3 Apache web server on port `8000`
   - **Database Container**: MySQL 8.0 server on port `3306` with initialized schema & seeders
4. Access the application at `http://localhost:8000`.

---

## Production Security Checklists

- [x] Configure SSL/TLS HTTPS certificate (Certbot / Let's Encrypt).
- [x] Change `jwt_secret` in `config/app.php` to a random 64-character hash.
- [x] Set `APP_ENV=production` in `.env`.
- [x] Restrict `public/uploads` directory execution (`php_flag engine off`).
