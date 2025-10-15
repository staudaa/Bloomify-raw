<?php
session_start();
require_once '../config/db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header('Location: ../admin/dashboard.php');
            exit;
        } else {
            header('Location: ../user/index.php');
            exit;
        }
    } else {
        $message = "Email atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bloomify | Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="auth-page">
    <div class="form-container">
        <h1 class="logo">Bloomify</h1>
        <p class="subtitle">Masuk untuk menjelajah keindahan asli dari bunga</p>
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email kamu" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>
        <p class="register-text">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </p>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>
    <div class="background-bloom"></div>
</body>

</html>