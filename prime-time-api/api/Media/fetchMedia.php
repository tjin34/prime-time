<?php


require_once dirname(__FILE__).'/'.'../Class/Media.php';

$Media = new Media();
$uid = $_GET['uid'];
//$token = $_GET['token'];
$offset = $_GET['offset'];
$pageSize = $_GET['pageSize'];
$Media->fetchMedia($uid,$offset,$pageSize);