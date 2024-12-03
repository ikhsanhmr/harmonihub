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
        public function infoSiru()  {
            $flyer = $this->db->prepare("select * from info_sirus where type= ? order by createdAt desc limit 2 ");
            $flyer->execute(["flyer"]);
            $flyers = $flyer->fetchAll();
            $video = $this->db->prepare("select * from info_sirus where type= ?  order by createdAt desc limit 2 ");
            $video->execute(["video"]);
            $videos = $video->fetchAll();
            $highlight = $this->db->prepare("SELECT * FROM info_sirus WHERE type = ? ORDER BY createdAt DESC LIMIT 1");

            $highlight->execute(["video"]);
            $highlights = $highlight->fetchAll();
            include "view/frontend/infoSiru.php";
        }
    }
    


?>