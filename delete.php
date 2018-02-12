<?php
    require_once(dirname(__FILE__) . "/selectFunc.php");

function del($chosenDbName, $chosenTableName, $priKey)
{
    global $db;
    global $baseURL;
    $db->exec("use $chosenDbName");

    // check table and db existence
    $tableExist = "SELECT table_name FROM information_schema.tables WHERE table_schema = '$chosenDbName' AND table_name = '$chosenTableName'";
    $s = $db->prepare($tableExist);
    $s->execute();
    $table = $s->fetchAll(PDO::FETCH_ASSOC);
    if ($table) {

        // get primary key field's name and value
        $arr = explode('=', $priKey);
        if (count($arr) == 2) {
            $primaryKey = $arr[0];
            $primaryKeyValue = $arr[1];

            $rowNumber = 1;
            $fieldNames = array();
            $query = "DELETE FROM $chosenTableName WHERE $primaryKey = $primaryKeyValue";
            $statement = $db->prepare($query);
            $statement->execute();

            header("location: {$baseURL}select.php/browse/$chosenDbName/$chosenTableName/1");
            exit();
        }
    } else
        require_once(dirname(__FILE__) . "/view/tableDoesntExist.php");
}


function dropTable($chosenDbName, $chosenTableName)
{
    global $db;
    global $baseURL;
    $db->exec("use $chosenDbName");

    // check table and db existence
    $tableExist = "SELECT table_name FROM information_schema.tables WHERE table_schema = '$chosenDbName' AND table_name = '$chosenTableName'";
    $s = $db->prepare($tableExist);
    $s->execute();
    $table = $s->fetchAll(PDO::FETCH_ASSOC);
    if ($table) {
        //drop table
        $query = "DROP TABLE IF EXISTS $chosenTableName";
        $statement = $db->prepare($query);
        $statement->execute();

        header("location: {$baseURL}index.php/tables/$chosenDbName");
        exit();
    } else
        require_once(dirname(__FILE__) . "/view/tableDoesntExist.php");
}
