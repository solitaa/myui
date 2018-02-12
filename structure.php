<?php
    require_once(dirname(__FILE__) . "/selectFunc.php");


function structure($chosenDbName, $chosenTableName)
{
    global $db;
    $db->exec("use $chosenDbName");
    require_once(dirname(__FILE__) . "/view/header.php");

    // check table and db existence
    $tableExist = "SELECT table_name FROM information_schema.tables WHERE table_schema = '$chosenDbName' AND table_name = '$chosenTableName'";
    $s = $db->prepare($tableExist);
    $s->execute();
    $table = $s->fetchAll(PDO::FETCH_ASSOC);
    if ($table) {

        require_once(dirname(__FILE__) . "/leftPart.php");
        require_once(dirname(__FILE__) . "/view/rightDiv/rightDivOpenTag.php");
        require_once(dirname(__FILE__) . "/view/rightDiv/menuBar.php");


        $query = "DESCRIBE $chosenTableName";
        $statement = $db->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $queryString = $statement->queryString;
        $rowsCount = count($rows);
        require_once(dirname(__FILE__) . "/view/rightDiv/showSuccessResult.php");
        require_once(dirname(__FILE__) . "/view/rightDiv/showSql.php");
        if ($rows) {
            require_once(dirname(__FILE__) . "/view/rightDiv/rowsListTheadOpenTagInStructure.php");

            foreach ($rows[0] as $key => $val) {
                $fieldTitle = $key;
                require(dirname(__FILE__) . "/view/rightDiv/rowsListTh.php");
            }
            require_once(dirname(__FILE__) . "/view/rightDiv/rowsListTheadCloseTag.php");
            require_once(dirname(__FILE__) . "/view/rightDiv/rowsListTbodyOpenTag.php");
            $rowNumber = 1;

            foreach ($rows as $row) {
                require(dirname(__FILE__) . "/view/rightDiv/rowsListTrOpen.php");
                foreach ($row as $key => $val) {
                    require(dirname(__FILE__) . "/view/rightDiv/rowsListTd.php");
                }
                require(dirname(__FILE__) . "/view/rightDiv/rowsListTrClose.php");
                $rowNumber++;
            }
            require_once(dirname(__FILE__) . "/view/rightDiv/rowsListTheadCloseTag.php");
        }

        require_once(dirname(__FILE__) . "/view/rightDiv/rightDivCloseTag.php");
    } else
        require_once(dirname(__FILE__) . "/view/tableDoesntExist.php");
    require_once(dirname(__FILE__) . "/view/footer.php");
}

