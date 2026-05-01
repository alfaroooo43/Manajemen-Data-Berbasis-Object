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

    public function create(int $kategoriId, string $nama, float $harga, string $deskripsi, string $foto = '', string $linkFoto = ''): bool
    {
        $nama = $this->conn->real_escape_string($nama);
        $deskripsi = $this->conn->real_escape_string($deskripsi);
        $foto = $this->conn->real_escape_string($foto);
        $linkFoto = $this->conn->real_escape_string($linkFoto);

        return $this->conn->query("INSERT INTO produk (kategori_id, nama_produk, harga, deskripsi, foto, link_foto)
            VALUES ($kategoriId, '$nama', $harga, '$deskripsi', '$foto', '$linkFoto')");
    }

    public function update(int $id, int $kategoriId, string $nama, float $harga, string $deskripsi, string $foto = '', string $linkFoto = ''): bool
    {
        $nama = $this->conn->real_escape_string($nama);
        $deskripsi = $this->conn->real_escape_string($deskripsi);
        $foto = $this->conn->real_escape_string($foto);
        $linkFoto = $this->conn->real_escape_string($linkFoto);

        return $this->conn->query("UPDATE produk SET
            kategori_id = $kategoriId,
            nama_produk = '$nama',
            harga = $harga,
            deskripsi = '$deskripsi',
            foto = '$foto',
            link_foto = '$linkFoto'
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
    private string $uploadDir;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->produk = new Produk($this->db);
        $this->uploadDir = __DIR__ . '/../Asset/uploads/';

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    private function handleUpload(array $file, string $existingFoto = ''): string
    {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return $existingFoto;
        }

        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] === 0) {
            return $existingFoto;
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            return $existingFoto;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            return $existingFoto;
        }

        $imageInfo = @getimagesize($file['tmp_name']);
        $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
        if (!$imageInfo || !in_array($imageInfo[2], $allowedTypes, true)) {
            return $existingFoto;
        }

        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file['name']));
        $targetPath = $this->uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            if ($existingFoto && file_exists($this->uploadDir . $existingFoto)) {
                @unlink($this->uploadDir . $existingFoto);
            }
            return $filename;
        }

        return $existingFoto;
    }

    private function cleanPhotoUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : '';
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
        $foto = $this->handleUpload($_FILES['foto'] ?? []);
        $linkFoto = $this->cleanPhotoUrl($_POST['link_foto'] ?? '');

        $this->produk->create(
            (int) $_POST['kategori_id'],
            $_POST['nama_produk'],
            (float) $_POST['harga'],
            $_POST['deskripsi'],
            $foto,
            $linkFoto
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
        $data = $this->produk->getById((int) $_POST['id']);
        $foto = $this->handleUpload($_FILES['foto'] ?? [], $data['foto'] ?? '');
        $linkFoto = $this->cleanPhotoUrl($_POST['link_foto'] ?? '');

        $this->produk->update(
            (int) $_POST['id'],
            (int) $_POST['kategori_id'],
            $_POST['nama_produk'],
            (float) $_POST['harga'],
            $_POST['deskripsi'],
            $foto,
            $linkFoto
        );

        header("Location: index.php?page=produk");
    }

    public function hapus(): void
    {
        $data = $this->produk->getById((int) $_GET['id']);
        if ($data && $data['foto'] && file_exists($this->uploadDir . $data['foto'])) {
            @unlink($this->uploadDir . $data['foto']);
        }

        $this->produk->delete((int) $_GET['id']);
        header("Location: index.php?page=produk");
    }
}
