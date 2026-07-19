<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $stmt = $db->prepare('INSERT INTO keuangan (tahun, jenis, nama, anggaran, realisasi) VALUES (?,?,?,?,?)');
        $stmt->execute([
            $_POST['tahun'],
            $_POST['jenis'],
            trim($_POST['nama']),
            (float) $_POST['anggaran'],
            (float) $_POST['realisasi'],
        ]);
        setFlash('success', 'Data keuangan berhasil ditambahkan.');
    } elseif ($action === 'edit') {
        $stmt = $db->prepare('UPDATE keuangan SET tahun=?, jenis=?, nama=?, anggaran=?, realisasi=? WHERE id=?');
        $stmt->execute([
            $_POST['tahun'],
            $_POST['jenis'],
            trim($_POST['nama']),
            (float) $_POST['anggaran'],
            (float) $_POST['realisasi'],
            (int) $_POST['id'],
        ]);
        setFlash('success', 'Data keuangan berhasil diperbarui.');
    } elseif ($action === 'delete') {
        $stmt = $db->prepare('DELETE FROM keuangan WHERE id=?');
        $stmt->execute([(int) $_POST['id']]);
        setFlash('success', 'Data keuangan berhasil dihapus.');
    }

    redirect('/admin/keuangan.php');
}

$data = $db->query('SELECT * FROM keuangan ORDER BY tahun DESC, jenis, nama')->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<?php $flash = getFlash(); if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show py-2">
        <?= e($flash['msg']) ?>
        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-wallet2 me-2 text-success"></i>Data Keuangan Nagari</h5>
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i>Tambah
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-success">
                    <tr>
                        <th class="text-center" style="width:40px">No</th>
                        <th>Tahun</th>
                        <th>Jenis</th>
                        <th>Nama</th>
                        <th class="text-end">Anggaran</th>
                        <th class="text-end">Realisasi</th>
                        <th class="text-center" style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data keuangan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($data as $i => $row): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><?= e($row['tahun']) ?></td>
                                <td><span class="badge bg-<?= $row['jenis'] === 'pendapatan' ? 'success' : ($row['jenis'] === 'belanja' ? 'danger' : 'warning') ?>"><?= e(ucfirst($row['jenis'])) ?></span></td>
                                <td><?= e($row['nama']) ?></td>
                                <td class="text-end"><?= rupiah((float)$row['anggaran']) ?></td>
                                <td class="text-end"><?= rupiah((float)$row['realisasi']) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h6 class="modal-title">Edit Data Keuangan</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Tahun</label>
                                                <input type="number" name="tahun" class="form-control" value="<?= e($row['tahun']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jenis</label>
                                                <select name="jenis" class="form-select" required>
                                                    <option value="pendapatan" <?= $row['jenis']==='pendapatan'?'selected':'' ?>>Pendapatan</option>
                                                    <option value="belanja" <?= $row['jenis']==='belanja'?'selected':'' ?>>Belanja</option>
                                                    <option value="pembiayaan" <?= $row['jenis']==='pembiayaan'?'selected':'' ?>>Pembiayaan</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" name="nama" class="form-control" value="<?= e($row['nama']) ?>" required>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">Anggaran (Rp)</label>
                                                    <input type="number" name="anggaran" class="form-control" step="0.01" value="<?= e($row['anggaran']) ?>" required>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">Realisasi (Rp)</label>
                                                    <input type="number" name="realisasi" class="form-control" step="0.01" value="<?= e($row['realisasi']) ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title">Tambah Data Keuangan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="<?= date('Y') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <option value="pendapatan">Pendapatan</option>
                        <option value="belanja">Belanja</option>
                        <option value="pembiayaan">Pembiayaan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Hasil Usaha Desa" required>
                </div>
                <div class="row g-2">
                    <div class="col-6 mb-3">
                        <label class="form-label">Anggaran (Rp)</label>
                        <input type="number" name="anggaran" class="form-control" step="0.01" value="0" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Realisasi (Rp)</label>
                        <input type="number" name="realisasi" class="form-control" step="0.01" value="0" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success btn-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
