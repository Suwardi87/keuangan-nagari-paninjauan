<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (isset($_SESSION['user_id'])) {
    redirect('/admin/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            redirect('/admin/dashboard.php');
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Masukkan username dan password.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Keuangan Nagari Paninjauan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <style>
        body { background: #0f172a; min-height: 100vh; overflow: hidden; }

        .login-split {
            display: flex;
            min-height: 100vh;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(160deg, #064e3b 0%, #065f46 30%, #047857 60%, #059669 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }

        .login-left::after {
            content: '';
            position: absolute;
            bottom: -15%;
            left: -10%;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .login-left .content { position: relative; z-index: 2; }

        .login-left .brand-icon {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: #fff;
            margin-bottom: 1.5rem;
        }

        .login-left h1 {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 0.75rem;
        }

        .login-left p {
            color: rgba(255,255,255,0.7);
            font-size: 1rem;
            line-height: 1.6;
        }

        .finance-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2.5rem;
            position: relative;
            z-index: 2;
        }

        .finance-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 1.25rem;
            color: #fff;
        }

        .finance-card .icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }

        .finance-card .icon.green { background: rgba(34,197,94,0.3); color: #86efac; }
        .finance-card .icon.blue { background: rgba(59,130,246,0.3); color: #93c5fd; }
        .finance-card .icon.amber { background: rgba(245,158,11,0.3); color: #fcd34d; }
        .finance-card .icon.rose { background: rgba(244,63,94,0.3); color: #fda4af; }

        .finance-card .label {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.6);
            margin-bottom: 0.15rem;
        }

        .finance-card .value {
            font-size: 1rem;
            font-weight: 600;
        }

        .login-right {
            width: 480px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
        }

        .login-right .form-header {
            margin-bottom: 2rem;
        }

        .login-right .form-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.35rem;
        }

        .login-right .form-header p {
            color: #64748b;
            font-size: 0.9rem;
        }

        .form-floating-custom .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.85rem 1rem 0.85rem 2.75rem;
            height: auto;
            background-color: #f8fafc;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-floating-custom .form-control:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5,150,105,0.15);
            background-color: #fff;
        }

        .form-floating-custom .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            z-index: 5;
            transition: color 0.2s;
        }

        .form-floating-custom .form-control:focus ~ .input-icon,
        .form-floating-custom .form-control:focus + .input-icon {
            color: #059669;
        }

        .form-floating-custom label {
            position: absolute;
            left: 2.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
            transition: all 0.2s;
            pointer-events: none;
            background: transparent;
            padding: 0;
        }

        .form-floating-custom .form-control:focus ~ label,
        .form-floating-custom .form-control:not(:placeholder-shown) ~ label {
            top: -0.5rem;
            left: 0.75rem;
            font-size: 0.75rem;
            color: #059669;
            background: #fff;
            padding: 0 0.25rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #059669, #047857);
            border: none;
            border-radius: 10px;
            padding: 0.85rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #fff;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #047857, #065f46);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(5,150,105,0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i { transition: transform 0.3s; }
        .btn-login:hover i { transform: translateX(3px); }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
        }

        .login-footer p {
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .alert {
            border-radius: 10px;
            font-size: 0.875rem;
            border: none;
        }

        .alert-danger {
            background: #fef2f2;
            color: #b91c1c;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            color: #94a3b8;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        @media (max-width: 992px) {
            .login-split { flex-direction: column; }
            .login-left { min-height: auto; padding: 2rem; }
            .finance-cards { display: none; }
            .login-right { width: 100%; padding: 2rem; }
            body { overflow: auto; }
        }
    </style>
</head>
<body>
    <div class="login-split">
        <div class="login-left">
            <div class="content">
                <div class="brand-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <h1>Sistem Pengelolaan<br>Keuangan Nagari</h1>
                <p>Kelola data keuangan Nagari Paninjauan secara transparan, akuntabel, dan terintegrasi.</p>

                <div class="finance-cards">
                    <div class="finance-card">
                        <div class="icon green"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="label">Anggaran</div>
                        <div class="value">Terencana</div>
                    </div>
                    <div class="finance-card">
                        <div class="icon blue"><i class="bi bi-receipt"></i></div>
                        <div class="label">Realisasi</div>
                        <div class="value">Tercatat</div>
                    </div>
                    <div class="finance-card">
                        <div class="icon amber"><i class="bi bi-pie-chart"></i></div>
                        <div class="label">Pelaporan</div>
                        <div class="value">Akuntabel</div>
                    </div>
                    <div class="finance-card">
                        <div class="icon rose"><i class="bi bi-shield-check"></i></div>
                        <div class="label">Keamanan</div>
                        <div class="value">Terkunci</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="form-header">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                        <i class="bi bi-lock-fill text-success"></i>
                    </div>
                    <span class="text-muted small fw-semibold">ADMIN PANEL</span>
                </div>
                <h2>Masuk ke Sistem</h2>
                <p>Silakan masukkan akun Anda untuk mengakses panel keuangan.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 py-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login.php">
                <div class="mb-3 position-relative form-floating-custom">
                    <input type="text" name="username" class="form-control" id="username"
                           placeholder="Username" required autofocus
                           value="<?= e($_POST['username'] ?? '') ?>">
                    <i class="bi bi-person input-icon"></i>
                    <label for="username">Username</label>
                </div>
                <div class="mb-4 position-relative form-floating-custom">
                    <input type="password" name="password" class="form-control" id="password"
                           placeholder="Password" required>
                    <i class="bi bi-lock input-icon"></i>
                    <label for="password">Password</label>
                </div>
                <button type="submit" class="btn btn-login w-100">
                    Masuk <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="login-footer">
                <p class="mb-1"><i class="bi bi-building me-1"></i>Nagari Paninjauan &middot; Kec. X Koto</p>
                <p class="mb-0">Kab. Tanah Datar, Sumatera Barat &copy; <?= date('Y') ?></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
