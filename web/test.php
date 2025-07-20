<?php
echo "PHP está funcionando correctamente!";
echo "\nPuerto: " . ($_ENV['PORT'] ?? 'No definido');
echo "\nPHP Version: " . phpversion();
echo "\nDirectorio actual: " . __DIR__;
echo "\nArchivos en directorio: ";
print_r(scandir(__DIR__));
?>
