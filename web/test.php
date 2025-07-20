<?php
echo "<h1>🚀 Test de Deployment</h1>";
echo "<h2>✅ PHP está funcionando!</h2>";
echo "<p><strong>Versión PHP:</strong> " . phpversion() . "</p>";
echo "<p><strong>Directorio actual:</strong> " . __DIR__ . "</p>";
echo "<p><strong>Archivos en web/:</strong></p>";
echo "<ul>";
foreach (scandir(__DIR__) as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "<li>$file</li>";
    }
}
echo "</ul>";

echo "<h3>🔍 Test de Yii2:</h3>";
if (file_exists(__DIR__ . '/index.php')) {
    echo "✅ index.php existe<br>";
} else {
    echo "❌ index.php NO existe<br>";
}

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "✅ Composer autoload existe<br>";
} else {
    echo "❌ Composer autoload NO existe<br>";
}

echo "<h3>📋 Enlaces de prueba:</h3>";
echo '<p><a href="/">🏠 Página Principal</a></p>';
echo '<p><a href="/examen">📝 Sistema de Exámenes</a></p>';
echo '<p><a href="/pokemon">🔴 API Pokemon</a></p>';
