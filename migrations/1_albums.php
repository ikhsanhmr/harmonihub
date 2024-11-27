<?php
return [
    "up"=>function ($db) {
        $sql = "CREATE TABLE albums (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            type int not null,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $db->exec($sql);
    }
];
