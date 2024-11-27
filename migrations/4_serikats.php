<?php
return [
    "up" => function ($db) {
        $sql = "CREATE TABLE serikats (
             id INT AUTO_INCREMENT PRIMARY KEY,
             unit_id INT NOT NULL,
             name VARCHAR(255) NOT NULL,
             nip INT NOT NULL,
             membership VARCHAR(255) NOT NULL,
             noKta INT NOT NULL,
             position VARCHAR(120) NOT NULL,
             logoPath VARCHAR(255) NOT NULL,
             created_at datetime DEFAULT CURRENT_TIMESTAMP,
             updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
             FOREIGN KEY (unit_id) REFERENCES units(id)
        )";
        $db->exec($sql);
    }
];
