<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$db = getDB();

$totalProfil = $db->query('SELECT COUNT(*) FROM profil')->fetchColumn();
$totalKeuangan = $db->query('SELECT COUNT(*) FROM keuangan')->fetchColumn();
$totalAnggaran = (float) $db->query('SELECT COALESCE(SUM(anggaran),0) FROM keuangan')->fetchColumn();
$totalRealisasi = (float) $db->query('SELECT COALESCE(SUM(realisasi),0) FROM keuangan')->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
?>

<?php $flash = getFlash(); if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
        <?= e($flash['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                    <i class="bi bi-person-lines-fill fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0"><?= $totalProfil ?></h3>
                <small class="text-muted">Data Profil</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                    <i class="bi bi-wallet2 fs-4"></i>
                </div>
                <h3 class="fw-bold mb-0"><?= $totalKeuangan ?></h3>
                <small class="text-muted">Data Keuangan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                    <i class="bi bi-cash-stack fs-4"></i>
                </div>
                <h6 class="fw-bold mb-0"><?= rupiah($totalAnggaran) ?></h6>
                <small class="text-muted">Total Anggaran</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <h6 class="fw-bold mb-0"><?= rupiah($totalRealisasi) ?></h6>
                <small class="text-muted">Total Realisasi</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white fw-semibold">
                <i class="bi bi-person-lines-fill me-1"></i>Profil Nagari
            </div>
            <div class="card-body">
                <?php
                $profil = $db->query('SELECT * FROM profil LIMIT 1')->fetch();
                if ($profil):
                ?>
                    <table class="table table-borderless mb-3">
                        <tr><td class="text-muted" style="width:40%">Nama Nagari</td><td class="fw-semibold"><?= e($profil['nama_desa']) ?></td></tr>
                        <tr><td class="text-muted">Kecamatan</td><td><?= e($profil['kecamatan']) ?></td></tr>
                        <tr><td class="text-muted">Kabupaten</td><td><?= e($profil['kabupaten']) ?></td></tr>
                        <tr><td class="text-muted">Provinsi</td><td><?= e($profil['provinsi']) ?></td></tr>
                    </table>
                    <a href="<?= BASE_URL ?>/admin/profil.php" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-pencil-square me-1"></i>Edit Profil
                    </a>
                <?php else: ?>
                    <p class="text-muted">Belum ada data profil.</p>
                    <a href="<?= BASE_URL ?>/admin/profil.php" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Profil
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white fw-semibold">
                <i class="bi bi-wallet2 me-1"></i>Data Keuangan Terbaru
            </div>
            <div class="card-body">
                <?php
                $data = $db->query('SELECT * FROM keuangan ORDER BY tahun DESC, id DESC LIMIT 5')->fetchAll();
                if ($data):
                ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr><th>Tahun</th><th>Jenis</th><th>Nama</th><th class="text-end">Anggaran</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr>
                                        <td><?= e($row['tahun']) ?></td>
                                        <td><span class="badge bg-success"><?= e($row['jenis']) ?></span></td>
                                        <td><?= e($row['nama']) ?></td>
                                        <td class="text-end"><?= rupiah((float)$row['anggaran']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?= BASE_URL ?>/admin/keuangan.php" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                    </a>
                <?php else: ?>
                    <p class="text-muted">Belum ada data keuangan.</p>
                    <a href="<?= BASE_URL ?>/admin/keuangan.php" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Data
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
