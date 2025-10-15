<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$username = htmlspecialchars($user['username']);
$produk = $pdo->query("
    SELECT p.*, k.nama_kategori 
    FROM produk p 
    LEFT JOIN kategori k ON p.kategori_id = k.id 
    ORDER BY p.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
$kategori = $pdo->query("SELECT * FROM kategori ORDER BY nama_kategori ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloomify | Kelola Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="bi bi-flower1"></i>
                    <span>Bloomify</span>
                </div>
                <p class="sidebar-sub">Admin Panel</p>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li><a href="dashboard.php"><i class="bi bi-house-heart"></i> Dashboard</a></li>
                    <li><a href="akun.php"><i class="bi bi-person-gear"></i> Kelola Akun</a></li>
                    <li><a href="produk.php" class="active"><i class="bi bi-bag-heart"></i> Produk</a></li>
                    <li><a href="testimoni.php"><i class="bi bi-chat-heart"></i> Testimoni</a></li>
                    <li class="logout-section"><a href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <div class="topbar">
                <h3>Kelola Akun Pengguna</h3>
                <span class="text-muted">Bloomify Admin</span>
            </div>
            <div class="card p-4 shadow-sm mt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="bi bi-bag-heart"></i> Daftar Produk</h5>
                    <button class="btn btn-pink" data-bs-toggle="modal" data-bs-target="#addProdukModal">
                        <i class="bi bi-bag-plus"></i> Tambah Produk
                    </button>
                </div>
                <table class="table table-hover align-middle">
                    <thead class="table-pink">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produk as $i => $p): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <img src="../assets/img/<?= htmlspecialchars($p['gambar'] ?: 'noimage.png') ?>"
                                        alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                                        width="60" height="60" style="object-fit: cover; border-radius: 8px;">
                                </td>
                                <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                                <td><?= htmlspecialchars($p['nama_kategori'] ?? '-') ?></td>
                                <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($p['stok']) ?></td>
                                <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary editBtn" data-bs-toggle="modal" data-bs-target="#editProdukModal"
                                        data-id="<?= $p['id'] ?>"
                                        data-nama="<?= htmlspecialchars($p['nama_produk']) ?>"
                                        data-deskripsi="<?= htmlspecialchars($p['description']) ?>"
                                        data-harga="<?= $p['harga'] ?>"
                                        data-stok="<?= $p['stok'] ?>"
                                        data-kategori="<?= $p['kategori_id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="produk_action.php?action=delete&id=<?= $p['id'] ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div class="modal fade" id="addProdukModal" tabindex="-1" aria-labelledby="addProdukModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="produk_action.php?action=add" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-bag-plus"></i> Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Kategori</label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-2">
                        <label>Gambar Produk</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-pink">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editProdukModal" tabindex="-1" aria-labelledby="editProdukModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="produk_action.php?action=edit" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-2">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" id="edit-nama" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Kategori</label>
                        <select name="kategori_id" id="edit-kategori" class="form-select" required>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Harga</label>
                        <input type="number" name="harga" id="edit-harga" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Stok</label>
                        <input type="number" name="stok" id="edit-stok" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <textarea name="description" id="edit-deskripsi" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-2">
                        <label>Gambar Baru (opsional)</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-pink">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>

</html>