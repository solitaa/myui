<?php
require_once(dirname(__FILE__) . "/view/leftDiv/leftDivTitle.php");
require_once(dirname(__FILE__) . "/view/leftDiv/leftDivOpenTag.php");
require_once(dirname(__FILE__) . "/view/leftDiv/dropdownDivOpenTag.php");
//get db names in dropdown
$query = "SHOW DATABASES";
$stat = $db->prepare($query);
$stat->execute();
$resultArray = $stat->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultArray as $result) {
    foreach ($result as $param => $value) {
        $dbName = $result[$param];
        require(dirname(__FILE__) . "/view/leftDiv/dropdownLi.php");
    }
}
require_once(dirname(__FILE__) . "/view/leftDiv/dropdownDivCloseTag.php");

$tablesNamesArray = array();
//get Tables names in left part
$query = "SHOW TABLES FROM $chosenDbName";
$stat = $db->prepare($query);
$stat->execute();
$tables = $stat->fetchAll(PDO::FETCH_ASSOC);

foreach ($tables as $table) {
    foreach ($table as $param => $value) {
        $tableName = $table[$param];
        $tablesNamesArray[] = $tableName;
        require(dirname(__FILE__) . "/view/leftDiv/tableNames.php");
    }
}
require_once(dirname(__FILE__) . "/view/leftDiv/tableNamesCloseTag.php");
require_once(dirname(__FILE__) . "/view/leftDiv/leftDivCloseTag.php");
?>