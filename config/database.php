<?php
$docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
$appDir = dirname(__DIR__);
if ($docRoot && strpos($appDir, $docRoot) === 0) {
    $baseDir = substr($appDir, strlen($docRoot));
} else {
    $baseDir = '';
}
$baseDir = str_replace('\\', '/', $baseDir);
define('BASE_URL', $baseDir);
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'paninjauan');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;max-width:500px;margin:80px auto;padding:30px;border:1px solid #e2e8f0;border-radius:10px;">
                <h3 style="color:#dc2626;">Koneksi Database Gagal</h3>
                <p>Pastikan:</p>
                <ol>
                    <li>MySQL/MariaDB sudah berjalan</li>
                    <li>Database <b>paninjauan</b> sudah dibuat</li>
                    <li>File <code>database/schema.sql</code> sudah di-import</li>
                </ol>
                <p style="color:#64748b;font-size:0.85rem;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>
            </div>');
        }
    }
    return $pdo;
}
