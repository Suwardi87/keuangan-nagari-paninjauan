<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$db = getDB();
$profil = $db->query('SELECT * FROM profil LIMIT 1')->fetch();
$isEdit = (bool) $profil;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama_desa'  => trim($_POST['nama_desa'] ?? ''),
        'kecamatan'  => trim($_POST['kecamatan'] ?? ''),
        'kabupaten'  => trim($_POST['kabupaten'] ?? ''),
        'provinsi'   => trim($_POST['provinsi'] ?? ''),
        'kode_desa'  => trim($_POST['kode_desa'] ?? ''),
        'alamat'     => trim($_POST['alamat'] ?? ''),
        'telepon'    => trim($_POST['telepon'] ?? ''),
        'email'      => trim($_POST['email'] ?? ''),
        'website'    => trim($_POST['website'] ?? ''),
        'visi'       => trim($_POST['visi'] ?? ''),
        'misi'       => trim($_POST['misi'] ?? ''),
    ];

    if ($isEdit) {
        $stmt = $db->prepare('UPDATE profil SET nama_desa=?, kecamatan=?, kabupaten=?, provinsi=?, kode_desa=?, alamat=?, telepon=?, email=?, website=?, visi=?, misi=? WHERE id=?');
        $stmt->execute([...array_values($data), $profil['id']]);
    } else {
        $stmt = $db->prepare('INSERT INTO profil (nama_desa, kecamatan, kabupaten, provinsi, kode_desa, alamat, telepon, email, website, visi, misi) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array_values($data));
    }

    setFlash('success', 'Profil nagari berhasil disimpan.');
    redirect('/admin/profil.php');
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white fw-semibold">
                <i class="bi bi-person-lines-fill me-1"></i><?= $isEdit ? 'Edit' : 'Tambah' ?> Profil Nagari
            </div>
            <div class="card-body p-4">
                <?php $flash = getFlash(); if ($flash): ?>
                    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show py-2">
                        <?= e($flash['msg']) ?>
                        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/admin/profil.php">
                    <h6 class="text-success fw-bold mb-3"><i class="bi bi-geo-alt me-1"></i>Identitas Nagari</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Nagari</label>
                            <input type="text" name="nama_desa" class="form-control" value="<?= e($profil['nama_desa'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Desa</label>
                            <input type="text" name="kode_desa" class="form-control" value="<?= e($profil['kode_desa'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="<?= e($profil['kecamatan'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kabupaten</label>
                            <input type="text" name="kabupaten" class="form-control" value="<?= e($profil['kabupaten'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" value="<?= e($profil['provinsi'] ?? '') ?>">
                        </div>
                    </div>

                    <h6 class="text-success fw-bold mb-3"><i class="bi bi-telephone me-1"></i>Kontak</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"><?= e($profil['alamat'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="<?= e($profil['telepon'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= e($profil['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control" value="<?= e($profil['website'] ?? '') ?>">
                        </div>
                    </div>

                    <h6 class="text-success fw-bold mb-3"><i class="bi bi-bullseye me-1"></i>Visi & Misi</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Visi</label>
                            <textarea name="visi" class="form-control" rows="3"><?= e($profil['visi'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Misi (satu poin per baris)</label>
                            <textarea name="misi" class="form-control" rows="5"><?= e($profil['misi'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                        <a href="/admin/dashboard.php" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
