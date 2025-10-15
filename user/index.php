<?php
session_start();
include '../config/db.php';
$stmtProduk = $pdo->prepare("
    SELECT p.*, k.nama_kategori 
    FROM produk p 
    LEFT JOIN kategori k ON p.kategori_id = k.id 
    ORDER BY p.created_at DESC
");
$stmtProduk->execute();
$produk = $stmtProduk->fetchAll(PDO::FETCH_ASSOC);
$stmtTestimoni = $pdo->prepare("
    SELECT t.*, u.username, p.nama_produk, p.gambar
    FROM testimoni t
    JOIN users u ON t.user_id = u.id
    JOIN produk p ON t.produk_id = p.id
    ORDER BY t.created_at DESC
");
$stmtTestimoni->execute();
$testimoni = $stmtTestimoni->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Bloomify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/landing.css">
</head>

<body>
    <nav id="mainNav" class="navbar navbar-expand-lg fixed-top navbar-transparent shadow-sm">
        <div class="container">
            <a class="navbar-brand ms-1" href="#">Bloomify</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link active" href="#hero">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimoni">Testimoni</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php
                        if (!isset($username)) {
                            $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
                            $stmt->execute([$_SESSION['user_id']]);
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $username = $row ? htmlspecialchars($row['username']) : 'User';
                        }
                        ?>

                        <li>
                            <a class="dropdown-item text-danger" href="../auth/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-2">
                            <a class="nav-link" href="../auth/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <header id="hero" class="hero d-flex align-items-center">
        <div class="hero-overlay"></div>
        <img src="../assets/img/pink_1.png" alt="flower" class="flower flower-1" />
        <img src="../assets/img/pink_2.png" alt="flower" class="flower flower-2" />
        <img src="../assets/img/pink_3.png" alt="flower" class="flower flower-3" />

        <div class="container hero-inner text-center">
            <h1 class="hero-title">Hadirkan <span class="accent">Keindahan</span> setiap hari</h1>
            <p class="hero-sub">Bloomify hadir dengan koleksi yang dirancang untuk membuat harimu lebih bermakna dalam setiap momen.</p>
            <a href="#products" class="btn btn-cta">Lihat Koleksi</a>
        </div>
    </header>

    <section id="about" class="about-section py-5 reveal">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-md-6 text-center text-md-start">
                    <h2 class="mb-3">Tentang Bloomify</h2>
                    <p class="lead about-text">
                        Bloomify merangkai bunga dengan teliti — kami memilih bunga segar, menyusun setiap buket dengan detail, dan memberikan layanan pengiriman yang ramah.
                    </p>
                    <div class="d-flex gap-3 mt-4">
                        <div class="about-card p-3 shadow-sm">
                            <i class="bi bi-clock-history fs-3 text-pink"></i>
                            <p class="mb-0 mt-2"><strong>Pengiriman Cepat</strong><br><small>Same day delivery</small></p>
                        </div>
                        <div class="about-card p-3 shadow-sm">
                            <i class="bi bi-award fs-3 text-pink"></i>
                            <p class="mb-0 mt-2"><strong>Kualitas Terbaik</strong><br><small>Dipilih manual</small></p>
                        </div>
                        <div class="about-card p-3 shadow-sm">
                            <i class="bi bi-person-check fs-3 text-pink"></i>
                            <p class="mb-0 mt-2"><strong>Layanan Ramah</strong><br><small>Customer support</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <img src="../assets/img/fresh_1.png" alt="about" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4 reveal">Produk Unggulan</h2>
            <div class="row g-4 product-grid">
                <?php if ($produk): ?>
                    <?php foreach ($produk as $p): ?>
                        <div class="col-md-4 reveal product-item" data-category="<?= htmlspecialchars(strtolower($p['nama_kategori'] ?? 'lainnya')) ?>">
                            <div class="card product-card h-100 shadow-sm">
                                <img src="../assets/img/<?= htmlspecialchars($p['gambar'] ?: 'noimage.png') ?>"
                                    alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                                    class="card-img-top" style="object-fit: cover; height: 200px;">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?= htmlspecialchars($p['nama_produk']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars(substr($p['description'], 0, 80)) ?>...</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-muted">Belum ada produk tersedia</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="testimoni" class="py-5 bg-light reveal">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-center flex-grow-1">Apa Kata Mereka</h2>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="btn btn-outline-pink" data-bs-toggle="modal" data-bs-target="#modalAddTestimoni">
                        <i class="bi bi-chat-right-quote"></i> Tambah Testimoni
                    </button>
                <?php else: ?>
                    <a href="../auth/login.php" class="btn btn-outline-pink"><i class="bi bi-box-arrow-in-right"></i> Login untuk Menulis</a>
                <?php endif; ?>
            </div>

            <div id="carouselTestimoni" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php if (count($testimoni) > 0): ?>
                        <?php foreach ($testimoni as $i => $t): ?>
                            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                                <div class="d-flex flex-column align-items-center text-center p-4">
                                    <img src="../assets/img/<?= htmlspecialchars($t['gambar'] ?: 'noimage.png') ?>"
                                        alt="<?= htmlspecialchars($t['nama_produk']) ?>"
                                        width="80" height="80" style="object-fit: cover; border-radius: 8px;">
                                    <h5 class="fw-bold mt-3"><?= htmlspecialchars($t['username']) ?></h5>
                                    <p class="text-muted mb-1"><i><?= htmlspecialchars($t['nama_produk']) ?></i></p>
                                    <div class="mb-2 text-warning">
                                        <?= str_repeat('<i class="bi bi-star-fill"></i>', $t['rating']) ?>
                                        <?= str_repeat('<i class="bi bi-star"></i>', 5 - $t['rating']) ?>
                                    </div>
                                    <p class="fst-italic">"<?= htmlspecialchars($t['comment']) ?>"</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="carousel-item active">
                            <div class="text-center p-4">
                                <p class="text-muted">Belum ada testimoni untuk saat ini 🌸</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselTestimoni" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselTestimoni" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalAddTestimoni" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="testimoni_action.php" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Testimoni</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Produk</label>
                        <select name="produk_id" class="form-select" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($produk as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama_produk']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select" required>
                            <option value="5">⭐⭐⭐⭐⭐ (5 - Excellent)</option>
                            <option value="4">⭐⭐⭐⭐ (4 - Sangat Baik)</option>
                            <option value="3">⭐⭐⭐ (3 - Baik)</option>
                            <option value="2">⭐⭐ (2 - Cukup)</option>
                            <option value="1">⭐ (1 - Kurang)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Komentar</label>
                        <textarea name="comment" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-pink">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center mb-3">Contact</h2>
            <div class="contact-wrap mx-auto p-4 shadow-sm reveal">
                <form id="contactForm">
                    <div class="row g-3">
                        <div class="col-md-6"><input id="name" class="form-control form-control-lg" placeholder="Nama" required></div>
                        <div class="col-md-6"><input id="phone" class="form-control form-control-lg" placeholder="No. Telepon (opsional)"></div>
                        <div class="col-12"><textarea id="message" class="form-control" rows="3" placeholder="Pesan untuk kami" required></textarea></div>
                        <div class="col-12 d-grid"><button class="btn btn-cta" type="submit">Kirim via WhatsApp</button></div>
                    </div>
                </form>
                <div class="social-row mt-3 text-center">
                    <a class="social" href="#"><i class="bi bi-github"></i></a>
                    <a class="social" href="#"><i class="bi bi-linkedin"></i></a>
                    <a class="social" href="#"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer py-4 text-center">
        <div class="container">
            <p class="mb-1">&copy; <span id="year"></span> Bloomify <small class="text-muted">Handcrafted bouquets • Fresh everyday</small></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
</body>

</html>