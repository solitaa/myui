<?php
    require_once(dirname(__FILE__) . "/selectFunc.php");
echo 'exav';

function dbNames()
{
    global $db;
    require_once(dirname(__FILE__) . "/view/header.php");
    require_once(dirname(__FILE__) . "/view/leftDiv/leftDivTitle.php");
    require_once(dirname(__FILE__) . "/view/leftDiv/leftDivOpenTag.php");

    $query = "SHOW DATABASES";
    $statement = $db->prepare($query);
    $statement->execute();
    $resultArray = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($resultArray as $result) {
        foreach ($result as $param => $value) {
            $dbName = $result[$param];
            require(dirname(__FILE__) . "/view/leftDiv/dbNames.php");
        }
    }
    require_once(dirname(__FILE__) . "/view/leftDiv/leftDivCloseTag.php");
    require_once(dirname(__FILE__) . "/view/rightDiv/rightDivOpenTag.php");
    require_once(dirname(__FILE__) . "/view/rightDiv/menuBar.php");

    $version = apache_get_version();
    require_once(dirname(__FILE__) . "/view/rightDiv/apacheVersion.php");

    require_once(dirname(__FILE__) . "/view/rightDiv/rightDivCloseTag.php");
    require_once(dirname(__FILE__) . "/view/footer.php");
}


function tables($chosenDbName)
{
    global $db;
    global $baseURL;
    require_once(dirname(__FILE__) . "/view/header.php");

    //tables names and data in right part
    $q = "SELECT * FROM information_schema.TABLES where TABLE_SCHEMA = ?";
    $result = $db->prepare($q);
    $result->execute(array($chosenDbName));
    $r = $result->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        require_once(dirname(__FILE__) . "/leftPart.php");
        require_once(dirname(__FILE__) . "/view/rightDiv/rightDivOpenTag.php");
        require_once(dirname(__FILE__) . "/view/rightDiv/menuBar.php");

        require_once(dirname(__FILE__) . "/view/rightDiv/tableDataOpenTag.php");
        $rowNumber = 1;

        foreach ($r as $row) {
            $tableName = $row['TABLE_NAME'];
            $tableRows = $row['TABLE_ROWS'];
            $type = $row['ENGINE'];
            $tableCollation = $row['TABLE_COLLATION'];
            $dataLength = $row['DATA_LENGTH'];
            $indexLength = $row['INDEX_LENGTH'];
            $dataFree = $row['DATA_FREE'];
            $size = ($dataLength + $indexLength - $dataFree) / 1024;
            require(dirname(__FILE__) . "/view/rightDiv/tableData.php");
            $rowNumber++;
        }
        require_once(dirname(__FILE__) . "/view/rightDiv/tableDataCloseTag.php");

        require_once(dirname(__FILE__) . "/view/rightDiv/rightDivCloseTag.php");
    } else
        require_once(dirname(__FILE__) . "/view/dbDoesntExist.php");
    require_once(dirname(__FILE__) . "/view/footer.php");
}



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

            header("location: {$baseURL}index.php/browse/$chosenDbName/$chosenTableName/1");
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
