CREATE DATABASE IF NOT EXISTS paninjauan;
USE paninjauan;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE profil (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_desa VARCHAR(100) NOT NULL,
    kecamatan VARCHAR(100),
    kabupaten VARCHAR(100),
    provinsi VARCHAR(100),
    kode_desa VARCHAR(20),
    alamat TEXT,
    telepon VARCHAR(30),
    email VARCHAR(100),
    website VARCHAR(150),
    visi TEXT,
    misi TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE keuangan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tahun YEAR NOT NULL,
    jenis ENUM('pendapatan','belanja','pembiayaan') NOT NULL,
    nama VARCHAR(200) NOT NULL,
    anggaran DECIMAL(15,2) DEFAULT 0,
    realisasi DECIMAL(15,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2y$12$Kx03Kwt2.peundm/h37.vu7.JifvdGYEsbDMqJ1bPdbmlnzv9hLQi', 'Administrator');

INSERT INTO profil (nama_desa, kecamatan, kabupaten, provinsi, kode_desa, alamat, telepon, email, visi, misi) VALUES
('Nagari Paninjauan', 'X Koto', 'Tanah Datar', 'Sumatera Barat', '1304012004', 'Jorong Balai Satu, Nagari Paninjauan, Kecamatan X Koto, Kabupaten Tanah Datar', '-', 'balaisatupaninjauan@gmail.com',
 'Mewujudkan Nagari Paninjauan yang maju, mandiri, dan sejahtera berlandaskan adat istiadat dan kearifan lokal.',
 '1. Menningkatkan pelayanan publik yang transparan dan akuntabel.\n2. Mengembangkan potensi ekonomi masyarakat.\n3. Memperkuat kelembagaan nagari.\n4. Melestarikan budaya dan adat istiadat Minangkabau.\n5. Meningkatkan kualitas sumber daya manusia.');
