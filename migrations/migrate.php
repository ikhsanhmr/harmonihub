<?php

namespace Migrations;

require_once __DIR__ . '/../vendor/autoload.php';

use Libraries\Database;

$migrationPath =  __DIR__ . '/../migrations';
$db = Database::getInstance(); 

$db->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

function logging($db, $migration) {
    $insert = $db->prepare("INSERT INTO migrations (migration) VALUES (:migration)");
    $insert->execute(['migration' => $migration]);
}

function migrate($db, $migration) {
    $data = $db->prepare("SELECT * FROM migrations WHERE migration = :migration");
    $data->execute(['migration' => $migration]);
    return $data->fetch() !== false;
}

$files = scandir($migrationPath);
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $migration = pathinfo($file, PATHINFO_FILENAME);
        if (!migrate($db, $migration)) {
            echo "Running migration: $migration\n";

            $migrationScript = include $migrationPath . '/' . $file; 
            $migrationScript['up']($db); 
            logging($db, $migration);
            
            echo "Migration completed: $migration\n";
        } else {
            echo "Migration already run: $migration\n";
        }
    }
}
