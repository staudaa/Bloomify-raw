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
$accounts = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloomify | Kelola Akun</title>
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
                    <li><a href="akun.php" class="active"><i class="bi bi-person-gear"></i> Kelola Akun</a></li>
                    <li><a href="produk.php"><i class="bi bi-bag-heart"></i> Produk</a></li>
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
                    <h5><i class="bi bi-people"></i> Daftar Akun</h5>
                    <button class="btn btn-pink" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-person-plus"></i> Tambah Akun
                    </button>
                </div>
                <table class="table table-hover align-middle">
                    <thead class="table-pink">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accounts as $i => $acc): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($acc['username']) ?></td>
                                <td><?= htmlspecialchars($acc['email']) ?></td>
                                <td><?= htmlspecialchars($acc['role']) ?></td>
                                <td><?= date('d M Y', strtotime($acc['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary editBtn"
                                        data-id="<?= $acc['id'] ?>"
                                        data-username="<?= htmlspecialchars($acc['username']) ?>"
                                        data-email="<?= htmlspecialchars($acc['email']) ?>"
                                        data-role="<?= htmlspecialchars($acc['role']) ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="akun_action.php?action=delete&id=<?= $acc['id'] ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Yakin ingin menghapus akun ini?')">
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

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="akun_action.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus"></i> Tambah Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_user" class="btn btn-pink">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="akun_action.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" id="edit-username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="edit-email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" id="edit-role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_user" class="btn btn-pink">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>

</html>