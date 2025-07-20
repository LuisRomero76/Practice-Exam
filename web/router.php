<?php
// Router mejorado para Railway
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Log para debugging
error_log("Router: Processing request for path: " . $path);

// Remover query string
$path = strtok($path, '?');

// Archivos estáticos (css, js, images)
if (preg_match('/\.(css|js|png|jpg|gif|ico)$/', $path)) {
    $file = __DIR__ . $path;
    if (file_exists($file)) {
        return false; // Servir archivo estático
    }
}

// Archivos PHP específicos
if ($path === '/test.php' && file_exists(__DIR__ . '/test.php')) {
    require_once __DIR__ . '/test.php';
    return true;
}

// Todas las demás peticiones van al index.php de Yii2
chdir(__DIR__);
require_once __DIR__ . '/index.php';
?>
