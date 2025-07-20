<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite::memory:', // Base de datos en memoria para evitar errores
    'username' => '',
    'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
