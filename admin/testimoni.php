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
$query = "
    SELECT t.*, u.username, p.nama_produk
    FROM testimoni t
    LEFT JOIN users u ON t.user_id = u.id
    LEFT JOIN produk p ON t.produk_id = p.id
    ORDER BY t.created_at DESC
";
$testimoni = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloomify | Testimoni</title>
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
                    <li><a href="produk.php"><i class="bi bi-bag-heart"></i> Produk</a></li>
                    <li><a href="testimoni.php" class="active"><i class="bi bi-chat-heart"></i> Testimoni</a></li>
                    <li class="logout-section"><a href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <div class="topbar">
                <h3><i class="bi bi-chat-heart-fill"></i> Testimoni Pelanggan</h3>
                <span class="text-muted">Bloomify Admin</span>
            </div>
            <div class="card p-4 shadow-sm mt-3">
                <h5 class="mb-3"><i class="bi bi-chat-left-heart"></i> Daftar Testimoni</h5>

                <table class="table table-hover align-middle">
                    <thead class="table-pink">
                        <tr>
                            <th>No</th>
                            <th>Pengguna</th>
                            <th>Produk</th>
                            <th>Komentar</th>
                            <th>Rating</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($testimoni as $i => $t): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($t['username'] ?? 'Tidak Diketahui') ?></td>
                                <td><?= htmlspecialchars($t['nama_produk'] ?? '-') ?></td>
                                <td><?= nl2br(htmlspecialchars($t['comment'])) ?></td>
                                <td>
                                    <?php for ($r = 1; $r <= 5; $r++): ?>
                                        <i class="bi <?= $r <= $t['rating'] ? 'bi-star-fill text-warning' : 'bi-star text-secondary' ?>"></i>
                                    <?php endfor; ?>
                                </td>
                                <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>
                                <td>
                                    <a href="testimoni_action.php?action=delete&id=<?= $t['id'] ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Yakin ingin menghapus testimoni ini?')">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>