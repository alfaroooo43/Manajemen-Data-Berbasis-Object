<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5">
    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" onclick="history.back()">Back</button>
    <?php if (!$produk): ?>
        <div class="alert alert-warning">Produk tidak ditemukan.</div>
    <?php else: ?>
        <div class="card border-0 shadow-sm mx-auto" style="max-width:720px">
            <div class="card-body p-4">
                <h2 class="fw-bold">Checkout Produk</h2>
                <?php
                $imgSrc = '';
                if (!empty($produk['foto']) && file_exists(__DIR__ . '/../Asset/uploads/' . $produk['foto'])) {
                    $imgSrc = 'Asset/uploads/' . $produk['foto'];
                } elseif (!empty($produk['link_foto'])) {
                    $imgSrc = $produk['link_foto'];
                }
                $hasFoto = $imgSrc !== '';
                ?>
                <?php if ($hasFoto): ?>
                    <img src="<?= e($imgSrc); ?>" alt="<?= e($produk['nama_produk']); ?>" class="img-fluid rounded mb-3" style="max-height:240px; object-fit:cover; width:100%;">
                <?php else: ?>
                    <div class="bg-light border rounded text-secondary fw-semibold d-flex align-items-center justify-content-center mb-3" style="height:240px;">
                        Tidak ada foto
                    </div>
                <?php endif; ?>
                <p class="mb-1"><strong>Produk:</strong> <?= e($produk['nama_produk']); ?></p>
                <p><strong>Harga:</strong> <?= rupiah($produk['harga']); ?></p>
                <form method="post" action="index.php?page=pesanan&action=proses">
                    <input type="hidden" name="produk_id" value="<?= $produk['id']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Pembeli</label>
                        <input class="form-control" name="nama_pembeli" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" required>
                    </div>
                    <button class="btn btn-primary">Buat Pesanan</button>
                    <a class="btn btn-secondary" href="index.php?page=produk">Batal</a>
                </form>
            </div>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
