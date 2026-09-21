<p align="center">
  <h1 align="center">🚀 Modern E-Commerce Platform</h1>
</p>

<p align="center">
  Aplikasi e-commerce modern berfitur lengkap yang dibangun dengan ekosistem Laravel terkini, dirancang untuk performa tinggi, tampilan elegan, serta sistem pembayaran dan pengiriman otomatis.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-v12.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/Livewire-v3.x-purple?style=for-the-badge&logo=livewire" alt="Livewire">
  <img src="https://img.shields.io/badge/Filament-v3.x-orange?style=for-the-badge&logo=filament" alt="Filament">
  <img src="https://img.shields.io/badge/TailwindCSS-v3.x-blue?style=for-the-badge&logo=tailwindcss" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Midtrans-Payment-green?style=for-the-badge" alt="Midtrans">
  <img src="https://img.shields.io/badge/RajaOngkir-Shipping-yellow?style=for-the-badge" alt="RajaOngkir">
</p>

---

## 📋 Fitur Utama

- **🛒 Interactive Shopping Cart:** Panel keranjang belanja mengambang (*slide-over*) interaktif menggunakan Livewire dan *Alpine.js*.
- **📦 Cek Ongkir Real-time:** Integrasi dengan API **RajaOngkir** untuk pemilihan provinsi, kota, dan kalkulasi ongkos kirim berbagai ekspedisi secara akurat.
- **💳 Payment Gateway Otomatis:** Integrasi **Midtrans Snap** (mendukung QRIS, Virtual Account, E-Wallet) lengkap dengan sistem *Webhook* otomatis untuk verifikasi status lunas (*Paid*) dan pemotongan stok secara *real-time*.
- **⚡ Single-Page Feel:** Navigasi cepat dan dinamis berkat kekuatan reaktifitas Livewire v3.
- **🎨 Modern UI/UX:** Antarmuka bersih dan elegan yang dibangun menggunakan **Tailwind CSS**.
- **👑 Advanced Admin Dashboard:** Manajemen produk, kategori, dan pesanan berbasis **Filament v3** yang powerful.

---

## 🛠️ Tech Stack

- **Framework:** Laravel 12
- **Frontend & Reaktifitas:** Livewire v3 & Alpine.js
- **Styling:** Tailwind CSS
- **Admin Panel:** Filament v3
- **Payment Gateway:** Midtrans PHP Library
- **Shipping API:** RajaOngkir API

---

## ⚙️ Panduan Instalasi (Installation Guide)

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek ini di komputer lokal Anda:

### 1. Clone Repositori & Masuk Direktori
Pastikan Anda sudah mengklon atau menempatkan file proyek di direktori kerja lokal, lalu buka terminal pada folder tersebut:
```Bash
cd nama-folder-proyek
```

### 2. Install Dependensi PHP (Composer)
Jalankan perintah berikut untuk mengunduh pustaka PHP yang dibutuhkan:
```Bash
composer install
```

### 3. Install Dependensi JavaScript (NPM)
```
npm install
```

### 4. Konfigurasi Environment (.env)
Salin file contoh konfigurasi environment:
```bash
cp .env.example .env
```
Generate kunci enkripsi aplikasi:
```bash
php artisan key:generate
```
### 5. Konfigurasi Database & API Keys
Buka file .env menggunakan teks editor pilihan Anda, kemudian sesuaikan konfigurasi database serta kunci API pihak ketiga:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=

# Konfigurasi RajaOngkir
RAJAONGKIR_API_KEY=masukkan_api_key_rajaongkir_anda_disini

# Konfigurasi Midtrans (Sandbox / Production)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxx
MIDTRANS_IS_PRODUCTION=false
```
### 6. Migrasi & Seed Database
Jalankan migrasi database beserta data awal:
```bash
php artisan migrate --seed
```
### 7. Buat User Admin Filament
Untuk mengakses panel admin, buat akun administrator baru melalui terminal:
```bash
php artisan make:filament-user
```
### 8. Jalankan Server Lokal
Buka dua tab terminal terpisah untuk menjalankan server backend dan asset compiler secara bersamaan:

Tab 1 (Laravel Server):
```bash
Bash
php artisan serve
Tab 2 (Vite Asset Bundler):
```
```bash
Bash
npm run dev
```
Aplikasi kini sudah bisa diakses melalui browser pada alamat:
```bash
Frontend Toko: http://127.0.0.1:8000

Panel Admin: http://127.0.0.1:8000/admin
```

## 🔒 Konfigurasi Webhook Midtrans (Opsional untuk Localhost)
Jika Anda ingin menguji notifikasi pembayaran otomatis di komputer lokal, gunakan Ngrok untuk membuka jalur publik:

Jalankan Ngrok: ngrok http 8000

Salin URL HTTPS yang dihasilkan Ngrok (contoh: https://xxxx.ngrok-free.app).

Masuk ke Dashboard Midtrans Sandbox > Settings > Configuration.

Masukkan URL Webhook: https://xxxx.ngrok-free.app/api/midtrans/webhook

## 📄 License
Proyek ini bersifat open-source di bawah lisensi MIT license.
