<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $produk_id = intval($_POST['produk_id']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($rating >= 1 && $rating <= 5 && $comment !== '') {
        $stmt = $pdo->prepare("INSERT INTO testimoni (user_id, produk_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $produk_id, $rating, $comment]);
    }
    header("Location: index.php#products");
    exit();
}
