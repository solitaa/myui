<?php
    require_once(dirname(__FILE__) . "/selectFunc.php");

function browse($chosenDbName, $chosenTableName, $pageNumber)
{
    global $db;
    global $baseURL;
    global $rowsInPage;
    $db->exec("use $chosenDbName");
    require_once(dirname(__FILE__) . "/view/header.php");

    //check table and db existence
    $tableExist = "SELECT table_name FROM information_schema.tables WHERE table_schema = '$chosenDbName' AND table_name = '$chosenTableName'";
    $s = $db->prepare($tableExist);
    $s->execute();
    $table = $s->fetchAll(PDO::FETCH_ASSOC);

    if ($table) {
        require_once(dirname(__FILE__) . "/leftPart.php");
        require_once(dirname(__FILE__) . "/view/rightDiv/rightDivOpenTag.php");
        require_once(dirname(__FILE__) . "/view/rightDiv/menuBar.php");

        $queryForRowsCount = "SELECT * FROM $chosenTableName";
        $st = $db->prepare($queryForRowsCount);
        $st->execute();

        $allRowsCount = count($st->fetchAll(PDO::FETCH_ASSOC));

        if (is_numeric($pageNumber)) {
            $pageNum = $pageNumber - 1;
        } else {
            $pageNum = 0;
        }

        $start = $pageNum * $rowsInPage;
        $query = "SELECT * FROM $chosenTableName WHERE 1 LIMIT $start, $rowsInPage";

        $statement = $db->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $rowsCount = count($rows);

        require_once(dirname(__FILE__) . "/view/rightDiv/showSuccessResult.php");
        $queryString = $statement->queryString;
        require_once(dirname(__FILE__) . "/view/rightDiv/showSql.php");

        $links = ceil($allRowsCount / $rowsInPage);

        if ($rows) {
            require_once(dirname(__FILE__) . "/view/rightDiv/rowsListTheadOpenTag.php");

            //get fields titles and primary key field and it's value
            $queryForPriKey = "DESCRIBE $chosenTableName";
            $stat = $db->prepare($queryForPriKey);
            $stat->execute();
            $fields = $stat->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fields as $field) {
                if ($field['Key'] == "PRI")
                    $primaryKey = $field['Field'];
                $fieldTitle = $field['Field'];
                require(dirname(__FILE__) . "/view/rightDiv/rowsListTh.php");
            }

            require_once(dirname(__FILE__) . "/view/rightDiv/rowsListTheadCloseTag.php");
            require_once(dirname(__FILE__) . "/view/rightDiv/rowsListTbodyOpenTag.php");
            $rowNumber = 1;
            foreach ($rows as $row) {
                $primaryKeyValue = $row["$primaryKey"];

                require(dirname(__FILE__) . "/view/rightDiv/rowsListTrOpen.php");
                require(dirname(__FILE__) . "/view/rightDiv/rowsListEditDelete.php");
                foreach ($row as $key => $val) {
                    require(dirname(__FILE__) . "/view/rightDiv/rowsListTd.php");
                }
                require(dirname(__FILE__) . "/view/rightDiv/rowsListTrClose.php");
                $rowNumber++;
            }
            require_once(dirname(__FILE__) . "/view/rightDiv/rowsListTbodyCloseTag.php");
        }
        for ($i = 1; $i <= $links; $i++) {
            require_once(dirname(__FILE__) . "/view/rightDiv/pagesTitle.php");
            require(dirname(__FILE__) . "/view/rightDiv/pages.php");
        }
        require_once(dirname(__FILE__) . "/view/rightDiv/pagesCloseDiv.php");

        require_once(dirname(__FILE__) . "/view/rightDiv/rightDivCloseTag.php");
    } else
        require_once(dirname(__FILE__) . "/view/tableDoesntExist.php");

    require_once(dirname(__FILE__) . "/view/footer.php");

}

