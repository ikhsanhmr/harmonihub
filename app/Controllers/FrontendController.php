<?php
    namespace Controllers;

use Libraries\Database;

    final class FrontendController
    {
        private $db;
        public function __construct() {
            $this->db= Database::getInstance();
        }
        public function index()  {
            include "view/frontend/welcome.php";
        }
        public function serikat()  {

            $stmt = $this->db->prepare("select * from serikat");
            $stmt->execute();
            $serikats = $stmt->fetchAll();
            $stmt2 = $this->db->prepare("select * from units");
            $stmt2->execute();
            $units = $stmt2->fetchAll();
            include "view/frontend/serikat.php";
        }
        public function infoSiru()  {
            $flyer = $this->db->prepare("select * from info_sirus where type= ? limit 2");
            $flyer->execute(["flyer"]);
            $flyers = $flyer->fetchAll();
            $video = $this->db->prepare("select * from info_sirus where type= ? limit 2");
            $video->execute(["video"]);
            $videos = $video->fetchAll();
            $highlight = $this->db->prepare("SELECT * FROM info_sirus WHERE type = ? ORDER BY createdAt DESC LIMIT 1");

            $highlight->execute(["video"]);
            $highlights = $highlight->fetchAll();
            include "view/frontend/infoSiru.php";
        }
    }
    


?>