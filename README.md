# Sistem Keuangan Nagari Paninjauan

Sistem pengelolaan keuangan desa untuk Nagari Paninjauan, Kecamatan X Koto, Kabupaten Tanah Datar, Sumatera Barat. Dibangun dengan PHP + Bootstrap 5.

## Fitur

- **Login Admin** — autentikasi username & password
- **Dashboard** — statistik total anggaran, realisasi, dan jumlah data
- **Profil Nagari** — edit data profil desa (nama, alamat, visi, misi, kontak)
- **Keuangan** — CRUD data keuangan (pendapatan, belanja, pembiayaan) per tahun

## Kebutuhan

- PHP >= 8.0
- MySQL / MariaDB
- Apache (XAMPP) atau PHP Built-in Server

## Instalasi

### Cara 1: XAMPP (Windows)

1. Clone atau download repo ini ke folder `htdocs`

```bash
cd C:\xampp\htdocs
git clone https://github.com/Suwardi87/keuangan-nagari-paninjauan.git
```

2. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`)

3. Buat database baru bernama `paninjauan`

4. Import file `database/schema.sql`

5. Buka `http://localhost/keuangan-nagari-paninjauan/`

### Cara 2: PHP Built-in Server (Linux/Mac)

1. Clone repo

```bash
git clone https://github.com/Suwardi87/keuangan-nagari-paninjauan.git
cd keuangan-nagari-paninjauan
```

2. Buat database dan import schema

```bash
mysql -u root < database/schema.sql
```

3. Jalankan server

```bash
php -S 0.0.0.0:8080 router.php
```

4. Buka `http://localhost:8080`

## Login Default

| Username | Password |
|----------|----------|
| `admin`  | `admin123` |

## Struktur Folder

```
paninjauan/
├── admin/
│   ├── dashboard.php      # Halaman utama admin
│   ├── profil.php         # Edit profil nagari
│   ├── keuangan.php       # CRUD data keuangan
│   └── logout.php         # Logout
├── assets/
│   └── css/
│       └── style.css      # Custom CSS (green theme)
├── config/
│   └── database.php       # Koneksi database
├── database/
│   └── schema.sql         # Struktur database + seed data
├── includes/
│   ├── auth.php           # Cek session login
│   ├── functions.php      # Helper functions
│   ├── header.php         # Navbar admin
│   └── footer.php         # Footer
├── .htaccess              # Routing untuk Apache/XAMPP
├── login.php              # Halaman login
├── router.php             # Routing untuk PHP built-in server
└── README.md
```

## Tech Stack

- **Backend:** PHP 8
- **Frontend:** Bootstrap 5, Bootstrap Icons
- **Database:** MySQL / MariaDB
- **Server:** Apache (XAMPP) atau PHP Built-in Server

## License

MIT
