<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5">
    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" onclick="history.back()">Back</button>
    <div class="card border-0 shadow-sm mx-auto" style="max-width:720px">
        <div class="card-body p-4">
            <h2 class="fw-bold mb-3">Tambah Produk</h2>
            <form method="post" action="index.php?page=produk&action=simpan">
                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input class="form-control" name="nama_produk" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="kategori_id" required>
                        <?php while ($row = $kategori->fetch_assoc()): ?>
                            <option value="<?= $row['id']; ?>"><?= e($row['nama_kategori']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input class="form-control" type="number" name="harga" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="4" required></textarea>
                </div>
                <button class="btn btn-primary">Simpan</button>
                <a class="btn btn-secondary" href="index.php?page=produk">Batal</a>
            </form>
        </div>
    </div>
</main>
</body>
</html>
