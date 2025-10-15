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
if (!$user) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit();
}
$username = htmlspecialchars($user['username']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloomify | Dashboard</title>
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
                    <li><a href="dashboard.php" class="active"><i class="bi bi-house-heart"></i> Dashboard</a></li>
                    <li><a href="akun.php"><i class="bi bi-person-gear"></i> Kelola Akun</a></li>
                    <li><a href="produk.php"><i class="bi bi-bag-heart"></i> Produk</a></li>
                    <li><a href="testimoni.php"><i class="bi bi-chat-heart"></i> Testimoni</a></li>
                    <li class="logout-section"><a href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <div class="topbar">
                <h3>Selamat datang, <span class="username"><?= $username ?></span>!</h3>
                <span class="text-muted">Bloomify Admin</span>
            </div>
            <div class="card-container-left">
                <div class="dashboard-card">
                    <i class="bi bi-person-heart"></i>
                    <h4>Kelola Akun</h4>
                    <p>Tambah, ubah, atau hapus akun pengguna</p>
                    <a href="akun.php" class="btn btn-light mt-2">Lihat Akun</a>
                </div>
                <div class="dashboard-card">
                    <i class="bi bi-bag-heart"></i>
                    <h4>Kelola Produk</h4>
                    <p>Atur koleksi buket bunga dengan kategori menarik</p>
                    <a href="produk.php" class="btn btn-light mt-2">Lihat Produk</a>
                </div>
                <div class="dashboard-card">
                    <i class="bi bi-chat-heart"></i>
                    <h4>Testimoni</h4>
                    <p>Lihat pendapat pelanggan tentang produkmu</p>
                    <a href="testimoni.php" class="btn btn-light mt-2">Lihat Testimoni</a>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/script.js"></script>
</body>

</html>