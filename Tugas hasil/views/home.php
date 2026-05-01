<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Penjualan Produk Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .product-img {
            height: 190px;
            object-fit: cover;
        }

        .no-photo-box {
            height: 190px;
            background: #f1f3f5;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Produk Digital</a>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="if(document.referrer !== '') { history.back(); } else { window.location.href='index.php'; }">Back</button>
            <a class="btn btn-primary btn-sm" href="index.php?page=produk">Data Produk</a>
        </div>
    </div>
</nav>

<section class="bg-primary text-white py-5">
    <div class="container">
        <h1 class="fw-bold">Sistem Penjualan Produk Digital</h1>
        <p class="lead mb-0">Project PHP Native berbasis PBO/OOP dan MVC sederhana by all</p>
    </div>
</section>

<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Produk Tersedia</h2>
        <a class="btn btn-success" href="index.php?page=produk&action=tambah">Tambah Produk</a>
    </div>

    <div class="row g-4">
        <?php while ($row = $produk->fetch_assoc()): ?>
            <?php
            $imgSrc = '';
            if (!empty($row['foto']) && file_exists(__DIR__ . '/../Asset/uploads/' . $row['foto'])) {
                $imgSrc = 'Asset/uploads/' . $row['foto'];
            } elseif (!empty($row['link_foto'])) {
                $imgSrc = $row['link_foto'];
            }
            $hasFoto = $imgSrc !== '';
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <?php if ($hasFoto): ?>
                        <img src="<?= e($imgSrc); ?>" class="card-img-top product-img" alt="<?= e($row['nama_produk']); ?>">
                    <?php else: ?>
                        <div class="card-img-top no-photo-box">Tidak ada foto</div>
                    <?php endif; ?>
                    <div class="card-body">
                        <span class="badge text-bg-info mb-2"><?= e($row['nama_kategori']); ?></span>
                        <h5 class="fw-bold"><?= e($row['nama_produk']); ?></h5>
                        <p class="text-secondary"><?= e($row['deskripsi']); ?></p>
                        <h5><?= rupiah($row['harga']); ?></h5>
                        <a class="btn btn-primary btn-sm" href="index.php?page=pesanan&action=checkout&produk_id=<?= $row['id']; ?>">Checkout</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    <div class="container">
        &copy; 2026 Produk Digital | Built with PHP Native MVC
    </div>
</footer>
</body>
</html>
