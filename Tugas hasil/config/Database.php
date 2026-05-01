<?php
class Database
{
    private string $host = "localhost";
    private string $user = "root";
    private string $pass = "";
    private string $db = "digital_store_simple";

    public function connect(): mysqli
    {
        $conn = new mysqli($this->host, $this->user, $this->pass);

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        $conn->query("CREATE DATABASE IF NOT EXISTS {$this->db}");
        $conn->select_db($this->db);
        $conn->set_charset("utf8mb4");
        $this->setupDatabase($conn);

        return $conn;
    }

    private function setupDatabase(mysqli $conn): void
    {
        $conn->query("CREATE TABLE IF NOT EXISTS kategori (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama_kategori VARCHAR(100) NOT NULL
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS produk (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kategori_id INT NOT NULL,
            nama_produk VARCHAR(150) NOT NULL,
            harga DECIMAL(12,2) NOT NULL,
            deskripsi TEXT NOT NULL,
            FOREIGN KEY (kategori_id) REFERENCES kategori(id)
            ON UPDATE CASCADE ON DELETE RESTRICT
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS pesanan (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama_pembeli VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            produk_id INT NOT NULL,
            total DECIMAL(12,2) NOT NULL,
            status VARCHAR(30) NOT NULL DEFAULT 'Menunggu Pembayaran',
            FOREIGN KEY (produk_id) REFERENCES produk(id)
            ON UPDATE CASCADE ON DELETE RESTRICT
        )");

        $jumlahKategori = $conn->query("SELECT COUNT(*) AS total FROM kategori")->fetch_assoc()['total'];
        if ((int) $jumlahKategori === 0) {
            $conn->query("INSERT INTO kategori (nama_kategori) VALUES
                ('E-book'),
                ('Template'),
                ('Preset'),
                ('Mini Course')
            ");
        }

        $jumlahProduk = $conn->query("SELECT COUNT(*) AS total FROM produk")->fetch_assoc()['total'];
        if ((int) $jumlahProduk === 0) {
            $conn->query("INSERT INTO produk (kategori_id, nama_produk, harga, deskripsi) VALUES
                (1, 'E-book Digital Marketing', 55000, 'Panduan strategi pemasaran digital untuk pemula.'),
                (2, 'Template Landing Page', 85000, 'Template website landing page sederhana dan responsif.'),
                (3, 'Preset Lightroom Clean Tone', 35000, 'Preset editing foto untuk tampilan clean dan modern.'),
                (4, 'Mini Course PHP Native', 120000, 'Materi belajar PHP Native, CRUD, OOP, dan MVC sederhana.')
            ");
        }
    }
}
