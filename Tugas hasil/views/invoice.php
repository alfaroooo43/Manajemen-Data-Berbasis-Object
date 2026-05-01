<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5">
    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" onclick="history.back()">Back</button>
    <div class="card border-0 shadow-sm mx-auto" style="max-width:720px">
        <div class="card-body p-4">
            <?php if (!$invoice): ?>
                <div class="alert alert-warning">Invoice tidak ditemukan.</div>
            <?php else: ?>
                <h2 class="fw-bold">Invoice #<?= $invoice['id']; ?></h2>
                <hr>
                <p><strong>Nama Pembeli:</strong> <?= e($invoice['nama_pembeli']); ?></p>
                <p><strong>Email:</strong> <?= e($invoice['email']); ?></p>
                <p><strong>Produk:</strong> <?= e($invoice['nama_produk']); ?></p>
                <p><strong>Total:</strong> <?= rupiah($invoice['total']); ?></p>
                <p><strong>Status:</strong> <span class="badge text-bg-warning"><?= e($invoice['status']); ?></span></p>
                <a class="btn btn-primary" href="index.php">Kembali ke Home</a>
                <a class="btn btn-secondary" href="index.php?page=produk">Data Produk</a>
            <?php endif; ?>
        </div>
    </div>
</main>
</body>
</html>
