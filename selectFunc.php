<?php
global $baseURL;
require_once(dirname(__FILE__) . "/inc/db.inc.php");
$params = explode('/', $_SERVER['REQUEST_URI']);

if ($params[count($params) - 1] == "index.php" || ($params[count($params) - 2] == "myui" && $params[count($params) - 1] == '')) {
    dbNames();
    exit();
} else {
    foreach ($params as $key => $val) {
        if (strpos($val, ".php")) {
            $index = $key;
        }
    }
    if ($index) {
            $funcName = $params[$index + 1];
            for ($i = $index + 2; $i <= count($params) - 1; $i++) {
                $array[] = $params[$i];
            }
            call_user_func_array("$funcName", $array);
        }
}
