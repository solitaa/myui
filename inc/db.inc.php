<?php
require_once(dirname(__FILE__) . "/config.inc.php");
$db = new PDO("mysql:host=$dbhost;charset=utf8", $dbuser, $dbpass);
$db->exec("SET NAMES UTF8");
?>