<?php
require_once __DIR__ . "/ProdukController.php";

class Pesanan
{
    private mysqli $conn;

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    public function create(string $nama, string $email, int $produkId, float $total): int
    {
        $nama = $this->conn->real_escape_string($nama);
        $email = $this->conn->real_escape_string($email);

        $this->conn->query("INSERT INTO pesanan (nama_pembeli, email, produk_id, total, status)
            VALUES ('$nama', '$email', $produkId, $total, 'Menunggu Pembayaran')");

        return $this->conn->insert_id;
    }

    public function getById(int $id): ?array
    {
        return $this->conn->query("SELECT pesanan.*, produk.nama_produk
            FROM pesanan
            JOIN produk ON pesanan.produk_id = produk.id
            WHERE pesanan.id = $id")->fetch_assoc();
    }
}

class PesananController
{
    private mysqli $db;
    private Pesanan $pesanan;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->pesanan = new Pesanan($this->db);
    }

    public function checkout(): void
    {
        $produkModel = new Produk($this->db);
        $produk = $produkModel->getById((int) $_GET['produk_id']);
        include_once __DIR__ . "/../views/checkout.php";
    }

    public function proses(): void
    {
        $produkModel = new Produk($this->db);
        $produk = $produkModel->getById((int) $_POST['produk_id']);

        if (!$produk) {
            header("Location: index.php?page=produk");
            exit;
        }

        $idPesanan = $this->pesanan->create(
            $_POST['nama_pembeli'],
            $_POST['email'],
            (int) $_POST['produk_id'],
            (float) $produk['harga']
        );

        header("Location: index.php?page=pesanan&action=invoice&id=" . $idPesanan);
    }

    public function invoice(): void
    {
        $invoice = $this->pesanan->getById((int) $_GET['id']);
        include_once __DIR__ . "/../views/invoice.php";
    }
}
