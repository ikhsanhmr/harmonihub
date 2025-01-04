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
            $highlight = $this->db->prepare("SELECT * FROM info_sirus WHERE type = ? ORDER BY createdAt DESC LIMIT 1");
            $highlight->execute(["video"]);
            $highlights = $highlight->fetchAll();

            // flyer
            $flyer = $this->db->prepare("SELECT * FROM info_sirus WHERE type = ? ORDER BY createdAt DESC LIMIT 2");
            $flyer->execute(["flyer"]);
            $flyers = $flyer->fetchAll();

            
            // video
            $video = $this->db->prepare("SELECT * FROM info_sirus WHERE type = ? ORDER BY createdAt DESC LIMIT 2");
            $video->execute(["video"]);
            $videos = $video->fetchAll();

            include "view/frontend/welcome.php";
        }
 

        public function flyers()  {
            $pageF = isset($_GET["flyer_page"]) ? (int)$_GET["flyer_page"] : 1;
            $limit =10;
            $pageFlyer = max($pageF, 1);  
            $offset = ($pageF - 1) * $limit;

            $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM info_sirus WHERE type = ?");
            $stmt->execute(["flyer"]);
            $totalFlyer = $stmt->fetch()["total"];

            if ($pageFlyer > 1 && $totalFlyer < $limit) {
                $limit = $totalFlyer;  
            }

            $flyer = $this->db->prepare("SELECT * FROM info_sirus WHERE type = ? ORDER BY createdAt DESC LIMIT $limit OFFSET $offset");
            $flyer->execute(["flyer"]);
            $flyers = $flyer->fetchAll();

           
            $totalFlyerPages = ceil($totalFlyer / $limit);
            include "view/frontend/flyers.php";
        }
        public function videos()  {
            $pageV = isset($_GET["video_page"]) ? (int)$_GET["video_page"] : 1;
            $limit =10  ;
            $pageVideo = max($pageV, 1);  
            $offset = ($pageV- 1) * $limit;

            $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM info_sirus WHERE type = ?");
            $stmt->execute(["video"]);
            $totalVideo = $stmt->fetch()["total"];

            if ($pageVideo > 1 && $totalVideo < $limit) {
                $limit = $totalVideo;  
            }

            $video = $this->db->prepare("SELECT * FROM info_sirus WHERE type = ? ORDER BY createdAt DESC LIMIT $limit OFFSET $offset");
            $video->execute(["video"]);
            $videos = $video->fetchAll();

           
            $totalVideoPages = ceil($totalVideo / $limit);
            include "view/frontend/videos.php";
        }
        
    }
    


?>