<?php
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function rupiah(float $angka): string {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function url(string $path): string {
    return BASE_URL . $path;
}

function redirect(string $path): void {
    header('Location: ' . url($path));
    exit;
}

function setFlash(string $type, string $msg): void {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
