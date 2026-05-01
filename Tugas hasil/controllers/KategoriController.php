<?php
require_once __DIR__ . "/../config/Database.php";

class Kategori
{
    private mysqli $conn;

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    public function getAll(): mysqli_result
    {
        return $this->conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
    }
}

class KategoriController
{
    private Kategori $kategori;

    public function __construct()
    {
        $db = (new Database())->connect();
        $this->kategori = new Kategori($db);
    }

    public function getModel(): Kategori
    {
        return $this->kategori;
    }
}
