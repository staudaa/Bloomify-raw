<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
$action = $_GET['action'] ?? '';
if ($action === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM testimoni WHERE id = ?");
    $stmt->execute([$id]);
    $testimoni = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($testimoni) {
        $delete = $pdo->prepare("DELETE FROM testimoni WHERE id = ?");
        $delete->execute([$id]);
    }
    header("Location: testimoni.php");
    exit();
}
header("Location: testimoni.php");
exit();
