<?php
echo "<h1>PHP está funcionando correctamente!</h1>";
echo "<p>Puerto: " . ($_ENV['PORT'] ?? $_SERVER['SERVER_PORT'] ?? 'No definido') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Directorio actual: " . __DIR__ . "</p>";
echo "<p>Servidor: " . $_SERVER['SERVER_SOFTWARE'] ?? 'No definido' . "</p>";

// Verificar que Yii2 se puede cargar
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "<p style='color: green;'>✅ Composer autoload encontrado</p>";
    require_once __DIR__ . '/../vendor/autoload.php';
    
    if (file_exists(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php')) {
        echo "<p style='color: green;'>✅ Yii2 encontrado</p>";
        require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
        echo "<p style='color: green;'>✅ Yii2 cargado correctamente</p>";
    } else {
        echo "<p style='color: red;'>❌ Yii2 NO encontrado</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Composer autoload NO encontrado</p>";
}

echo "<hr>";
echo "<a href='/'>🏠 Ir a la página principal</a> | ";
echo "<a href='/examen'>📝 Ir al examen</a>";
?>
