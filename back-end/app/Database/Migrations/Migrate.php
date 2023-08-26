<?php

namespace App\Database\Migrations;

use PDO;

class Migrate
{
    private PDO $db;

    public function __construct(string $db_name, string $db_host, string $user, string $password)
    {
        $this->db = new PDO("mysql:host=$db_host;dbname=$db_name", $user, $password);
    }

    public function users()
    {
        $table = "users";
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            email VARCHAR(255),
            password VARCHAR(255),
            remember_token VARCHAR(100),
            role ENUM('user','admin'),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";

        ($this->db->prepare($sql))->execute();
    }

    public function galleries()
    {
        $table = "galleries";
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255),
            description TEXT,
            image TEXT,
            favorite INT,
            category VARCHAR(255),
            user INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        ($this->db->prepare($sql))->execute();
    }
}
