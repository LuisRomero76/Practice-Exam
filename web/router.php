<?php
// Router para Railway - Maneja todas las peticiones
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Servir archivos estáticos
if ($path !== '/' && file_exists(__DIR__ . $path)) {
    return false;
}

// Todas las demás peticiones van a index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/index.php';
?>
