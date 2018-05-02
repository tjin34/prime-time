<?php

require_once dirname(__FILE__).'/'.'Database.php';
require_once dirname(__FILE__).'/'.'JSON.php';
require_once dirname(__FILE__).'/'.'Utils.php';
require_once dirname(__FILE__).'/'.'../Scraper/base.php';



class Medias {

    var $time;
    var $db;
    var $json;
    var $utils;
    var $base;

    function __construct() {
        $this->time = time();
        $this->db = new DB();
        $this->json = new JSON();
        $this->utils = new Utils();
        $this->base = new Base();
    }

    /**
     * Fetch media info for user
     * @param $username
     * @param $password
     * @param $start
     * @param $pageSize
     */
    function fetchMedia($username, $password, $start, $pageSize) {
        /* Check Caching in Database */
        $cacheCheck = $this->db->fetch_all("SELECT * FROM ".DB_PRE."member_profile WHERE ins_username = '$username'");

        /* If User Caching Found */
        if ($cacheCheck) {

            /* Check Last Cached date */
            $cachedTime = $cacheCheck[0]['ins_cached_time'];

            /* Grab UserId */
            $uid = $cacheCheck[0]['uid'];

            /* Check if Last Cached Time is TODAY */
            if ($this->utils->checkIfToday($cachedTime)) {

                /* Get first 20 cached MediaId Order by Created DESC */
                $medias = $this->db->fetch_all("SELECT * FROM ".DB_PRE."user_media WHERE uid = '$uid' ORDER BY created DESC LIMIT $start, $pageSize");
                $data = array();

                /* Based on 20 MediaIds and fetch from total cache table */
                foreach ($medias as $key => $media) {
                    $tableName = $this->utils->formTableName($media['mid']);
                    $insMedia = $this->db->fetch_all("SELECT * FROM $tableName WHERE id = '".$media['mid']."'");
                    $data[] = $insMedia[0];
                }

                /* Return JSON output */
                echo json_encode(array(
                    'error' => '',
                    'success' => true,
                    'data' => $data
                ),JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            } else {  /* If time is not TODAY, then fetch from Instagram WebScraper */

                /* Open Curl an d setup start.php to fetch from Instagram WebScraper */
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.shinji.io/api/Scraper/start.php?username=".$username."&password=".$password."&depth=1");
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                /* Start the script and record output boolean */
                $output = curl_exec($ch);
                curl_close($ch);

                /* If output is true, recursively call fetchMedia, and run the first layer */
                if ($output) {
                    $this->fetchMedia($username, $password, $start, $pageSize);
                }
            }
        } else {  /* If User was not cached at first */

            /* Insert User into Cache user table */
            $query = $this->db->query("INSERT INTO ".DB_PRE."member_profile (ins_username, ins_password) VALUES ('$username', '$password')");

            /* If query passed, recursively call fetchMedia, and run the second layer */
            if ($query) {
                $this->fetchMedia($username, $password, $start, $pageSize);
            }


        }
    }


}