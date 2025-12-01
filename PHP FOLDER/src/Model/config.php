<?php
return  [
    'db' => [
        'user' => 'root',
        'dsn' => 'mysql:host=localhost;dbname=taskapp;charset=utf8mb4',
        'pass' => '', 
        'options' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    ],
    'storage' => [
        'file' => __DIR__ . '/../storage/tasks.json'
    ], 
    'repository' => 'mysql'
];