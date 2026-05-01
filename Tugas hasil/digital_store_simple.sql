CREATE DATABASE IF NOT EXISTS digital_store_simple;
USE digital_store_simple;

DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS kategori;

CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NOT NULL,
    nama_produk VARCHAR(150) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    deskripsi TEXT NOT NULL,
    foto VARCHAR(255) NOT NULL DEFAULT '',
    link_foto VARCHAR(255) NOT NULL DEFAULT '',
    CONSTRAINT fk_produk_kategori
        FOREIGN KEY (kategori_id) REFERENCES kategori(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pembeli VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    produk_id INT NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Menunggu Pembayaran',
    CONSTRAINT fk_pesanan_produk
        FOREIGN KEY (produk_id) REFERENCES produk(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

INSERT INTO kategori (nama_kategori) VALUES
('E-book'),
('Template'),
('Preset'),
('Mini Course');

INSERT INTO produk (kategori_id, nama_produk, harga, deskripsi, foto, link_foto) VALUES
(1, 'E-book Digital Marketing', 55000, 'Panduan strategi pemasaran digital untuk pemula.', '', ''),
(2, 'Template Landing Page', 85000, 'Template website landing page sederhana dan responsif.', '', ''),
(3, 'Preset Lightroom Clean Tone', 35000, 'Preset editing foto untuk tampilan clean dan modern.', '', ''),
(4, 'Mini Course PHP Native', 120000, 'Materi belajar PHP Native, CRUD, OOP, dan MVC sederhana.', '', '');

INSERT INTO pesanan (nama_pembeli, email, produk_id, total, status) VALUES
('Alya Putri', 'alya@example.com', 1, 55000, 'Menunggu Pembayaran'),
('Raka Digital', 'raka@example.com', 2, 85000, 'Selesai');
