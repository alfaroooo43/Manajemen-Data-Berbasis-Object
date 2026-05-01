<?php
require_once __DIR__ . "/controllers/ProdukController.php";
require_once __DIR__ . "/controllers/KategoriController.php";
require_once __DIR__ . "/controllers/PesananController.php";

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

if ($page === 'produk') {
    $controller = new ProdukController();
} elseif ($page === 'kategori') {
    $controller = new KategoriController();
} elseif ($page === 'pesanan') {
    $controller = new PesananController();
} else {
    $controller = new ProdukController();
    $action = 'home';
}

if (!method_exists($controller, $action)) {
    $action = 'index';
}

$controller->$action();
