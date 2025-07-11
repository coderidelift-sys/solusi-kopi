# ☕ Solusi Kopi - Sistem Manajemen Restoran

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

Sistem manajemen restoran/kafe modern dengan fitur QRIS ordering dan payment gateway terintegrasi. Aplikasi ini dirancang untuk memudahkan proses pemesanan, pembayaran, dan manajemen operasional restoran.

## 🚀 Fitur Utama

### 📱 QRIS Ordering System
- **QR Code per Meja**: Setiap meja memiliki QR code unik untuk akses menu
- **Guest Checkout**: Pelanggan dapat memesan tanpa registrasi
- **Live Menu**: Menu real-time dengan status ketersediaan produk
- **Cart Management**: Keranjang belanja dengan perhitungan otomatis

### 💳 Payment Gateway
- **QRIS Integration**: Pembayaran via QRIS dengan Midtrans
- **Cash Payment**: Opsi pembayaran tunai
- **Payment Status Tracking**: Monitoring status pembayaran real-time
- **Payment History**: Riwayat pembayaran lengkap

### 👥 Role-Based Access Control
- **Admin**: Akses penuh ke semua fitur manajemen
- **Kasir**: Manajemen order dan pembayaran
- **User**: Akses terbatas untuk pelanggan terdaftar

### 📊 Dashboard & Analytics
- **Real-time Dashboard**: KPI dan statistik real-time
- **Order Management**: Manajemen pesanan dengan status tracking
- **Reporting System**: Laporan penjualan dan analitik
- **Export Data**: Export data dalam berbagai format

### 🏪 Outlet Management
- **Multi-Outlet**: Dukungan untuk multiple outlet
- **Table Management**: Manajemen meja dengan QR code
- **Product Catalog**: Katalog produk dengan kategori
- **Promotion System**: Sistem promosi dan diskon

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 10.x**: Framework PHP modern
- **PHP 8.1+**: Versi PHP terbaru
- **MySQL/PostgreSQL**: Database management
- **Laravel Sanctum**: API authentication
- **Spatie Permission**: Role dan permission management

### Frontend
- **Livewire 3.x**: Real-time components
- **Materialize CSS**: UI framework
- **DataTables**: Advanced table management
- **SweetAlert2**: Beautiful notifications
- **QR Code Generator**: QR code generation

### Payment & Integration
- **Midtrans**: Payment gateway integration
- **QRIS**: QR payment system
- **Social Login**: Google & Facebook authentication

## 📋 Prerequisites

Sebelum menjalankan aplikasi, pastikan sistem Anda memenuhi persyaratan berikut:

- **PHP 8.1** atau lebih tinggi
- **Composer** untuk dependency management
- **Node.js & NPM** untuk asset compilation
- **MySQL/PostgreSQL** database
- **Web Server** (Apache/Nginx)

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/your-username/solusi-kopi.git
cd solusi-kopi
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=solusi_kopi
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Midtrans Configuration
Tambahkan konfigurasi Midtrans di `.env`:
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 6. Database Migration & Seeding
```bash
php artisan migrate
php artisan db:seed
```

### 7. Asset Compilation
```bash
npm run dev
# atau untuk production
npm run build
```

### 8. Storage Setup
```bash
php artisan storage:link
```

## 👤 Default Users

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

### Admin
- **Email**: admin@mail.com
- **Password**: password
- **Role**: Admin (akses penuh)

### Kasir
- **Email**: kasir@mail.com
- **Password**: password
- **Role**: Kasir (manajemen order)

### User
- **Email**: user@mail.com
- **Password**: password
- **Role**: User (pelanggan)

## 🏗️ Struktur Aplikasi

### Core Models
```
app/Models/
├── User.php              # User management dengan roles
├── Order.php             # Order management
├── OrderItem.php         # Order items
├── Payment.php           # Payment tracking
├── Product.php           # Product catalog
├── Category.php          # Product categories
├── Outlet.php            # Outlet management
├── Table.php             # Table management
└── Promotion.php         # Promotion system
```

### Controllers
```
app/Http/Controllers/
├── DashboardController.php        # Dashboard analytics
├── OrderController.php           # Order processing
├── Console/                      # Admin console
│   ├── UserController.php        # User management
│   ├── OrderManagementController.php
│   ├── ProductController.php     # Product management
│   ├── CategoryController.php    # Category management
│   ├── OutletController.php      # Outlet management
│   └── TableController.php       # Table management
└── Auth/                        # Authentication
```

### Livewire Components
```
app/Livewire/
└── MenuLivewire.php             # Real-time menu system
```

## 🔐 Role & Permissions

### Admin Role
- ✅ Manajemen pengguna
- ✅ Manajemen outlet
- ✅ Manajemen produk & kategori
- ✅ Manajemen meja
- ✅ Manajemen order
- ✅ Akses laporan & analytics
- ✅ Manajemen promosi

### Kasir Role
- ✅ View dashboard
- ✅ Manajemen order
- ✅ Update status pembayaran
- ✅ Akses laporan terbatas
- ✅ Manajemen produk (view/edit)

### User Role
- ✅ View dashboard
- ✅ Riwayat order
- ✅ Update profil

## 💳 Payment Flow

### QRIS Payment Process
1. **Order Creation**: Pelanggan membuat pesanan
2. **Payment Selection**: Pilih metode QRIS
3. **QR Generation**: Sistem generate QR code
4. **Payment Processing**: Midtrans memproses pembayaran
5. **Status Update**: Status otomatis terupdate
6. **Order Fulfillment**: Pesanan diproses

### Payment Status
- `pending`: Menunggu pembayaran
- `paid`: Sudah dibayar
- `failed`: Pembayaran gagal
- `cancelled`: Dibatalkan

## 📊 Dashboard Features

### Admin Dashboard
- **KPI Cards**: Omzet hari ini, bulan ini, total order
- **Revenue Chart**: Grafik omzet 7 hari terakhir
- **Order Status**: Distribusi status pesanan
- **Top Products**: Produk terlaris
- **Recent Orders**: Pesanan terbaru

### Kasir Dashboard
- **Order Queue**: Antrian pesanan
- **Payment Status**: Status pembayaran
- **Quick Actions**: Aksi cepat untuk order

## 🗄️ Database Schema

### Core Tables
- `users`: User management dengan roles
- `orders`: Order management
- `order_items`: Order detail items
- `payments`: Payment tracking
- `products`: Product catalog
- `categories`: Product categories
- `outlets`: Outlet management
- `tables`: Table management
- `promotions`: Promotion system

### Permission Tables (Spatie)
- `roles`: Role definitions
- `permissions`: Permission definitions
- `model_has_roles`: User-role relationships
- `model_has_permissions`: User-permission relationships

## 🔧 Configuration

### Midtrans Setup
Lihat file `MIDTRANS_SETUP.md` untuk panduan lengkap setup Midtrans.

### Environment Variables
```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=solusi_kopi
DB_USERNAME=root
DB_PASSWORD=

# Midtrans
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_IS_PRODUCTION=false

# App
APP_NAME="Solusi Kopi"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
```

## 🧪 Testing

### Unit Tests
```bash
php artisan test
```

### Feature Tests
```bash
php artisan test --testsuite=Feature
```

## 📦 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Set Midtrans to production mode
- [ ] Configure web server (Apache/Nginx)
- [ ] Set up SSL certificate
- [ ] Configure backup strategy

### Docker Support
Aplikasi mendukung deployment dengan Docker. Lihat folder `docker/` untuk konfigurasi.

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Support

Untuk dukungan teknis atau pertanyaan, silakan hubungi:
- **Email**: support@solusikopi.com
- **Documentation**: [docs.solusikopi.com](https://docs.solusikopi.com)

## 🔄 Changelog

### v1.0.0 (2024-01-XX)
- ✅ QRIS ordering system
- ✅ Midtrans payment integration
- ✅ Role-based access control
- ✅ Real-time dashboard
- ✅ Multi-outlet support
- ✅ Order management system
- ✅ Product catalog management
- ✅ Table management with QR codes

---

**Solusi Kopi** - Sistem manajemen restoran modern untuk bisnis F&B yang berkembang.
