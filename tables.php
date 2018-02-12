<?php
    require_once(dirname(__FILE__) . "/selectFunc.php");


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

