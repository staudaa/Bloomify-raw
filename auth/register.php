<?php
require_once '../config/db.php';
session_start();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        $message = "Email sudah digunakan! silakan gunakan email lain";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        if ($stmt->execute([$username, $email, $password])) {
            header("Refresh:2; url=login.php");
            $message = "Registrasi berhasil! Silahkan login";
        } else {
            $message = "Terjadi kesalahan saat registrasi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bloomify | Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="auth-page">
    <div class="form-container">
        <h1 class="logo">Bloomify</h1>
        <p class="subtitle">Mulai menjelajah keindahan bunga bersama kami</p>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-login">Daftar Sekarang</button>
        </form>
        <p class="register-text">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </p>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>
    <div class="background-bloom"></div>
</body>

</html>