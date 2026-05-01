<?php
require_once __DIR__ . "/../config/Database.php";

if (!function_exists('rupiah')) {
    function rupiah($angka): string
    {
        return "Rp " . number_format((float) $angka, 0, ',', '.');
    }
}

if (!function_exists('e')) {
    function e($text): string
    {
        return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
    }
}

class Produk
{
    private mysqli $conn;

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    public function getAll(): mysqli_result
    {
        return $this->conn->query("SELECT produk.*, kategori.nama_kategori
            FROM produk
            JOIN kategori ON produk.kategori_id = kategori.id
            ORDER BY produk.id DESC");
    }

    public function getById(int $id): ?array
    {
        return $this->conn->query("SELECT produk.*, kategori.nama_kategori
            FROM produk
            JOIN kategori ON produk.kategori_id = kategori.id
            WHERE produk.id = $id")->fetch_assoc();
    }

    public function create(int $kategoriId, string $nama, float $harga, string $deskripsi): bool
    {
        $nama = $this->conn->real_escape_string($nama);
        $deskripsi = $this->conn->real_escape_string($deskripsi);

        return $this->conn->query("INSERT INTO produk (kategori_id, nama_produk, harga, deskripsi)
            VALUES ($kategoriId, '$nama', $harga, '$deskripsi')");
    }

    public function update(int $id, int $kategoriId, string $nama, float $harga, string $deskripsi): bool
    {
        $nama = $this->conn->real_escape_string($nama);
        $deskripsi = $this->conn->real_escape_string($deskripsi);

        return $this->conn->query("UPDATE produk SET
            kategori_id = $kategoriId,
            nama_produk = '$nama',
            harga = $harga,
            deskripsi = '$deskripsi'
            WHERE id = $id");
    }

    public function delete(int $id): bool
    {
        return $this->conn->query("DELETE FROM produk WHERE id = $id");
    }
}

class ProdukController
{
    private mysqli $db;
    private Produk $produk;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->produk = new Produk($this->db);
    }

    public function home(): void
    {
        $produk = $this->produk->getAll();
        include_once __DIR__ . "/../views/home.php";
    }

    public function index(): void
    {
        $produk = $this->produk->getAll();
        include_once __DIR__ . "/../views/list.php";
    }

    public function tambah(): void
    {
        $kategori = (new KategoriController())->getModel()->getAll();
        include_once __DIR__ . "/../views/tambah.php";
    }

    public function simpan(): void
    {
        $this->produk->create(
            (int) $_POST['kategori_id'],
            $_POST['nama_produk'],
            (float) $_POST['harga'],
            $_POST['deskripsi']
        );

        header("Location: index.php?page=produk");
    }

    public function edit(): void
    {
        $data = $this->produk->getById((int) $_GET['id']);
        $kategori = (new KategoriController())->getModel()->getAll();
        include_once __DIR__ . "/../views/edit.php";
    }

    public function update(): void
    {
        $this->produk->update(
            (int) $_POST['id'],
            (int) $_POST['kategori_id'],
            $_POST['nama_produk'],
            (float) $_POST['harga'],
            $_POST['deskripsi']
        );

        header("Location: index.php?page=produk");
    }

    public function hapus(): void
    {
        $this->produk->delete((int) $_GET['id']);
        header("Location: index.php?page=produk");
    }
}
