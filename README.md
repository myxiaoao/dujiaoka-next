# 🦄 Dujiaoka NEXT

> A modernized (brand new) version upgraded from Laravel 6 + dcat-admin to Laravel 12 + Filament 4

## ✨ Features

### 🎨 Frontend Upgrade
- **Modern UI**: Tailwind CSS 4 + Flux UI Components
- **Full-stack Components**: Livewire 3 for seamless interactions
- **Dark Mode**: Complete Dark Mode support
- **User Experience**: Product category navigation, one-click card copy, real-time search

### 🔧 Backend Upgrade
- **Admin Panel**: Filament 4 modern backend
- **PHP 8.3+**: Strict type declarations, constructor property promotion
- **Laravel 12**: Latest Laravel framework features
- **Code Standards**: Laravel Pint automatic formatting

### 📦 Core Features
- ✅ Product Management (categories, products, inventory, wholesale pricing)
- ✅ Order Management (automatic/manual delivery, order inquiry)
- ✅ Card/Key Management (import/export, reusable cards)
- ✅ Coupon System (product associations, usage limits)
- ✅ 34 Payment Gateways (Alipay, WeChat, PayPal, Stripe, Cryptocurrency, etc.)
- ✅ Email Notification System (5 email templates)
- ✅ Multi-channel Push Notifications (Telegram, Server Chan, Bark, WeCom)
- ✅ Data Statistics Dashboard
- ✅ System Configuration Management (automatic cache recovery)

## 🚀 Quick Start

### New Installation

```bash
# 1. Clone the project
git clone https://github.com/myxiaoao/dujiaoka-next.git
cd dujiaoka-next

# 2. Install PHP dependencies
composer install

# 3. Install frontend dependencies
npm install

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Configure database and cache (edit .env file)
# Database configuration
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=dujiaoka_next
# DB_USERNAME=root
# DB_PASSWORD=

# Redis cache configuration (Required! System config is stored in Redis)
# CACHE_STORE=redis
# REDIS_HOST=127.0.0.1
# REDIS_PORT=6379
# REDIS_PASSWORD=null

# 6. Run migrations and seed data
php artisan migrate --seed

# --seed will automatically initialize:
# - EmailTemplateSeeder: 5 email templates
# - PaySeeder: 34 payment gateway configurations
# - SystemSettingSeeder: System default configurations

# 7. Create admin account
php artisan make:filament-user

# 8. Build frontend assets
npm run build

# 9. Start development server
composer dev
# Or start separately:
# php artisan serve
# php artisan queue:listen
# npm run dev

# Access frontend: http://localhost:8000
# Access backend: http://localhost:8000/admin
```

### Development Test Data

Generate test data during development for debugging and demonstration:

```bash
# Generate test data (products, orders, cards, coupons, etc.)
php artisan db:seed --class=TestDataSeeder

# Clear test data (use before going live)
php artisan test-data:clear

# Force clear (without confirmation)
php artisan test-data:clear --force
```

For detailed instructions, see [Test Data Documentation](docs/TEST_DATA.md)

### Upgrade from Legacy System

```bash
# Automated upgrade (recommended)
php artisan dujiaoka:upgrade \
  --host=localhost \
  --database=old_dujiaoka \
  --username=root \
  --password=your_password \
  --old-path=/path/to/old/dujiaoka

# The upgrade command will automatically:
# - Migrate all data tables (products, orders, cards, coupons, etc.)
# - Copy uploaded files (images, attachments)
# - Preserve user data and payment configurations

# Detailed documentation
# - docs/UPGRADE_GUIDE.md
# - docs/development-logs/UPGRADE_QUICKSTART.md
# - docs/development-logs/MIGRATION_SUMMARY.md
```

## 📚 Documentation

### 📖 Core Documentation
- **[Complete Upgrade Guide](docs/UPGRADE_GUIDE.md)** - Detailed steps for upgrading from Laravel 6
- **[Frontend Features](docs/FRONTEND_FEATURES.md)** - Frontend pages and features
- **[Configuration Guide](docs/CONFIGURATION.md)** - System configuration instructions
- **[Database Seeding](docs/DATABASE_SEEDING.md)** - Seeders explained
- **[Test Data Guide](docs/TEST_DATA.md)** - Development test data
- **[Deployment Guide](docs/DEPLOYMENT_GUIDE.md)** - Production deployment

### 🔧 Developer Documentation
- **[CLAUDE.md](CLAUDE.md)** - AI-assisted development guide
- **[Flux UI Migration Guide](docs/FLUX_MIGRATION_GUIDE.md)** - Livewire 3 + Flux UI frontend migration
- **[Development Logs](docs/development-logs/)** - Detailed migration process documentation
- **[More Documentation](docs/)** - Documentation center

## 🛠 Tech Stack

### Backend
- **PHP** >= 8.3 (strict types, constructor property promotion)
- **Laravel** 12.x (latest LTS)
- **Filament** 4.x (modern admin panel)
- **Livewire** 3.x (full-stack components)
- **MySQL** >= 5.7 / MariaDB >= 10.3
- **Redis** (required for system configuration storage)

### Frontend
- **Tailwind CSS** 4.x (atomic CSS)
- **Flux UI** (Livewire free components)
- **Alpine.js** 3.x (lightweight JS framework, built into Livewire)
- **Vite** (frontend build tool)

### Tools
- **Laravel Pint** (code formatting)
- **Pest** (testing framework)
- **Larastan** (static analysis, optional)

## 🎯 Core Improvements

### Comparison with Original System (dujiaoka)

| Feature | Original System | Next Version | Improvement |
|---------|----------------|--------------|-------------|
| **Backend Framework** | Laravel 6 | Laravel 12+ | ✅ 6 major version leap |
| **Admin Panel** | Dcat Admin | Filament 4 | ✅ Modern UI/UX |
| **Frontend Framework** | Bootstrap 4 + jQuery | Tailwind CSS 4 + Livewire 3 | ✅ Seamless interactions |
| **PHP Version** | 7.4+ | 8.3+ | ✅ 30%+ performance boost |
| **Theme System** | 3 themes | Single modern theme + dark mode | ✅ Unified experience |
| **Card Display** | ❌ Email only | ✅ Direct page display + copy | ✅ Enhanced UX |
| **Category Navigation** | Tab switching | Livewire real-time filtering | ✅ Performance optimization |
| **Code Standards** | Mixed style | PSR-12 + Laravel Pint | ✅ Unified standards |
| **Installation Method** | Web installer wizard | Command line installation | ✅ More secure |

## 🤝 Contributing

Issues and Pull Requests are welcome!

## 📄 License

Inherits the license from the original [dujiaoka](https://github.com/assimon/dujiaoka) project

## 🙏 Acknowledgments

- Original author [assimon](https://github.com/assimon) for creating the Dujiaoka project
- [Laravel](https://laravel.com) framework
- [Filament](https://filamentphp.com) admin panel
- [Livewire](https://livewire.laravel.com) full-stack framework

## 📝 Disclaimer

Dujiaoka NEXT is a free and open-source product, intended for educational and learning purposes only.
It must not be used for any purposes that violate the laws and regulations of the `People's Republic of China (including Taiwan Province)` or `the user's jurisdiction`.
As the author, I have only completed the code development and open-source activities `(open-source means anyone can download and use)`, and have never participated in any user's operations or profit-making activities.
Furthermore, I do not know what purposes users will use the `program source code` for, therefore any legal liability arising from the user's use shall be borne by the user themselves.
