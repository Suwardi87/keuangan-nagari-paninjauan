<?php
require_once __DIR__ . '/../config/database.php';
session_start();

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

function getNamaAdmin(): string {
    return $_SESSION['nama_lengkap'] ?? 'Admin';
}
