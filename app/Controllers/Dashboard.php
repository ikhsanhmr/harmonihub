<?php

namespace Controllers;

use Libraries\Database;
use PDO;

class Dashboard
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function login()
    {
        require_once 'view/login.php';
    }

    public function home()
    {
        $queries = [
            'total_pengguna' => "SELECT COUNT(*) AS total FROM users",
            'total_serikat'  => "SELECT COUNT(*) AS total FROM serikat",
            'total_unit'     => "SELECT COUNT(*) AS total FROM units",
            'total_laporan'  => "SELECT COUNT(*) AS total FROM laporan_lks_bipartit"
        ];

        $data = [];

        foreach ($queries as $key => $query) {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $data[$key] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        }

        require_once 'view/home.php';
    }
}
