<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Produk Digital</a>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="history.back()">Back</button>
            <a class="btn btn-success btn-sm" href="index.php?page=produk&action=tambah">Tambah Produk</a>
        </div>
    </div>
</nav>

<main class="container py-5">
    <h2 class="fw-bold mb-3">CRUD Data Produk</h2>
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
                <?php $no = 1; while ($row = $produk->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= e($row['nama_produk']); ?></td>
                        <td><?= e($row['nama_kategori']); ?></td>
                        <td><?= rupiah($row['harga']); ?></td>
                        <td><?= e($row['deskripsi']); ?></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="index.php?page=produk&action=edit&id=<?= $row['id']; ?>">Edit</a>
                            <a class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')" href="index.php?page=produk&action=hapus&id=<?= $row['id']; ?>">Hapus</a>
                            <a class="btn btn-primary btn-sm" href="index.php?page=pesanan&action=checkout&produk_id=<?= $row['id']; ?>">Checkout</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</main>
</body>
</html>
