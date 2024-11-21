<?php
return [
    "up" => function ($db) {
        $sql = "CREATE TABLE users (
             id INT AUTO_INCREMENT PRIMARY KEY,
             role_id INT NOT NULL,
             serikat_id INT NOT NULL,
             name VARCHAR(255) NOT NULL,
             username VARCHAR(255) NOT NULL,
             password VARCHAR(255) NOT NULL,
             email VARCHAR(255) NOT NULL,
             profile_picture VARCHAR(255) NOT NULL,
             created_at datetime DEFAULT CURRENT_TIMESTAMP,
             updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
             FOREIGN KEY (role_id) REFERENCES roles(id),
             FOREIGN KEY (serikat_id) REFERENCES serikats(id)
        )";
        $db->exec($sql);
    }
];
