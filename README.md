# Accord Music Store

![Accord Music Store](public/assets/images/logo.png)

## Overview

Accord Music Store is a modern e-commerce platform specializing in pre-order management for vinyl records and music merchandise. Built with PHP 7.4+ and following modern development practices, it offers a responsive, user-friendly interface for music enthusiasts.

## Features

- 🎵 Pre-order management system
- 🛍️ Product catalog with detailed views
- 🔍 Advanced search functionality
- 📱 Responsive design
- 🌐 Multi-language support (Danish/English)
- 🔒 Secure payment integration
- 📊 Analytics integration
- 🤖 AI-powered artist biographies

## Technology Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript
- **Server:** Apache/Nginx
- **Dependencies:** Composer
- **AI Integration:** OpenAI API

## Project Structure
```
accord-music-store/
├── public/                 # Web root
│   ├── index.php          # Front controller
│   ├── assets/            # Public assets (CSS, JS, images)
│   └── .htaccess         # Apache configuration
├── src/                   # Application source code
│   ├── Config/           # Configuration classes
│   ├── Controllers/      # Controller classes
│   ├── Models/           # Model classes
│   ├── Services/         # Business logic services
│   └── Helpers/          # Helper functions
├── templates/            # View templates
│   ├── layout/          # Layout templates
│   ├── product/         # Product templates
│   └── error/           # Error pages
├── database/            # Database files
│   ├── migrations/      # Database migrations
│   └── seeds/           # Database seeders
├── storage/             # Application storage
│   ├── logs/           # Application logs
│   ├── cache/          # Cache files
│   └── uploads/        # User uploads
├── tests/              # Test suites
│   ├── Unit/          # Unit tests
│   └── Integration/   # Integration tests
├── vendor/            # Composer dependencies
└── config/           # Configuration files
```

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Apache/Nginx web server
- Node.js (for frontend asset compilation)

## Installation

1. **Clone the repository:**
```bash
git clone https://github.com/your-username/accord-music-store.git
cd accord-music-store
```

2. **Install PHP dependencies:**
```bash
composer install
```

3. **Configure environment:**
```bash
cp .env.example .env
# Edit .env with your settings
```

4. **Set up database:**
```bash
# Create database and user
mysql -u root -p
CREATE DATABASE accord_db;
CREATE USER 'accord_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON accord_db.* TO 'accord_user'@'localhost';
FLUSH PRIVILEGES;

# Run migrations and seeds
mysql -u root -p accord_db < database/migrations/001_create_products.sql
mysql -u root -p accord_db < database/seeds/initial_data.sql
```

5. **Configure web server:**
   - For Apache: Ensure mod_rewrite is enabled
   - For Nginx: Configure URL rewriting according to the provided nginx.conf

6. **Set permissions:**
```bash
chmod -R 755 public/
chmod -R 777 storage/
```

7. **Start development server:**
```bash
php -S localhost:8000 -t public
```

## Development

### Coding Standards
- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Document all methods and classes
- Write unit tests for new features

### Git Workflow
1. Create feature branch from develop
2. Make changes and commit
3. Write/update tests
4. Create pull request
5. Code review
6. Merge to develop

### Testing
```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit --testsuite unit
```

## Deployment

1. **Prepare for deployment:**
```bash
# Update dependencies
composer install --no-dev --optimize-autoloader

# Clear cache
php artisan cache:clear
```

2. **Environment setup:**
   - Update .env for production
   - Configure error reporting
   - Set up SSL certificates

3. **Database:**
   - Run migrations
   - Verify data integrity

4. **Server configuration:**
   - Configure web server
   - Set up cron jobs
   - Configure backup system

## Monitoring

- Application logs: `/storage/logs/`
- Error tracking: Sentry/Bugsnag
- Performance monitoring: New Relic
- Server monitoring: Datadog

## Contributing

1. Fork the repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## Support

- Technical issues: Create GitHub issue
- Security vulnerabilities: security@accordmusic.com
- General inquiries: support@accordmusic.com

## License

Proprietary - All rights reserved. © 2024 Accord Music Store 