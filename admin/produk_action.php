<?php
require_once '../config/db.php';
$action = $_GET['action'] ?? '';
if ($action === 'add') {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['description'];
    $kategori = $_POST['kategori_id'];
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "../assets/img/";
        $file_name = time() . "_" . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar = $file_name;
        }
    }
    $stmt = $pdo->prepare("INSERT INTO produk (nama_produk, description, harga, stok, kategori_id, gambar)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nama, $deskripsi, $harga, $stok, $kategori, $gambar]);
    header("Location: produk.php");
    exit;
} elseif ($action === 'edit') {
    $id = $_POST['id'];
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['description'];
    $kategori = $_POST['kategori_id'];
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "../assets/img/";
        $file_name = time() . "_" . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar = $file_name;
        }
    }
    if ($gambar) {
        $stmt = $pdo->prepare("UPDATE produk SET nama_produk=?, description=?, harga=?, stok=?, kategori_id=?, gambar=? WHERE id=?");
        $stmt->execute([$nama, $deskripsi, $harga, $stok, $kategori, $gambar, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE produk SET nama_produk=?, description=?, harga=?, stok=?, kategori_id=? WHERE id=?");
        $stmt->execute([$nama, $deskripsi, $harga, $stok, $kategori, $id]);
    }
    header("Location: produk.php");
    exit;
} elseif ($action === 'delete') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT gambar FROM produk WHERE id=?");
    $stmt->execute([$id]);
    $gambar = $stmt->fetchColumn();
    if ($gambar && file_exists("../assets/img/" . $gambar)) {
        unlink("../assets/img/" . $gambar);
    }
    $stmt = $pdo->prepare("DELETE FROM produk WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: produk.php");
    exit;
}
