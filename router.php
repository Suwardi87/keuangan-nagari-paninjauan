<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri === '/') {
    require __DIR__ . '/login.php';
    return true;
}

$file = __DIR__ . $uri;
if ($uri !== '/' && is_file($file)) {
    return false;
}

$routes = [
    '/login.php'            => __DIR__ . '/login.php',
    '/admin/dashboard.php'  => __DIR__ . '/admin/dashboard.php',
    '/admin/profil.php'     => __DIR__ . '/admin/profil.php',
    '/admin/keuangan.php'   => __DIR__ . '/admin/keuangan.php',
    '/admin/logout.php'     => __DIR__ . '/admin/logout.php',
];

if (isset($routes[$uri])) {
    require $routes[$uri];
    return true;
}

http_response_code(404);
echo '404 - Halaman tidak ditemukan';
return true;
