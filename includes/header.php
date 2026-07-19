<?php require_once __DIR__ . '/auth.php'; ?>
<?php require_once __DIR__ . '/functions.php'; ?>
<?php require_once __DIR__ . '/../config/database.php'; ?>
<?php $currentPage = basename($_SERVER['SCRIPT_NAME'], '.php'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Nagari Paninjauan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/admin/dashboard.php">
                <i class="bi bi-house-door me-2"></i>Nagari Paninjauan
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>" href="/admin/dashboard.php">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'profil' ? 'active' : '' ?>" href="/admin/profil.php">
                            <i class="bi bi-person-lines-fill me-1"></i>Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'keuangan' ? 'active' : '' ?>" href="/admin/keuangan.php">
                            <i class="bi bi-wallet2 me-1"></i>Keuangan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="/admin/logout.php">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container-fluid py-4">
