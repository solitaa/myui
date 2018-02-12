<?php
    require_once(dirname(__FILE__) . "/selectFunc.php");
    require_once(dirname(__FILE__) . "/select.php");


function sql($chosenDbName)
{
    global $db;
    global $baseURL;
    require_once(dirname(__FILE__) . "/view/header.php");
    $db->exec("use $chosenDbName");
    require_once(dirname(__FILE__) . "/leftPart.php");
    require_once(dirname(__FILE__) . "/view/rightDiv/rightDivOpenTag.php");
    require_once(dirname(__FILE__) . "/view/rightDiv/menuBar.php");

    require_once(dirname(__FILE__) . "/view/rightDiv/sqlForm.php");

    require_once(dirname(__FILE__) . "/view/rightDiv/rightDivCloseTag.php");
    require_once(dirname(__FILE__) . "/view/footer.php");

}
function executeQuery($chosenDbName, $pageNumber){
    global $db;
    global $baseURL;
    global $rowsInPage;

    $db->exec("use $chosenDbName");

    if (isset($_POST['sql_query']) && $_POST['sql_query'] != '') {
        $newQuery = $_POST['sql_query'];
        $queryFirstWord = strtolower(substr(trim ($newQuery), 0, 6));
        $queryFirstWordShow = strtolower(substr(trim ($newQuery), 0, 4));

        if ($queryFirstWordShow == "show"){
            require_once(dirname(__FILE__) . "/showResults.php");
        } elseif ($queryFirstWord == "select"){
            $explainQuery = "EXPLAIN ".$newQuery;
            $statement = $db->prepare($explainQuery);
            $statement->execute();
            $fields = $statement->fetchAll(PDO::FETCH_ASSOC);
            if($fields){
                $chosenTableName = $fields[0]['table'];
                $allRowsCount = $fields[0]['rows'];
                $pageNumber = 1;
                require_once(dirname(__FILE__) . "/showResults.php");
            }

        } else {

            $statement = $db->prepare($newQuery);
            $statement->execute();
            $error = $statement->errorInfo();
            require_once(dirname(__FILE__) . "/view/header.php");
            require_once(dirname(__FILE__) . "/leftPart.php");
            require_once(dirname(__FILE__) . "/view/rightDiv/rightDivOpenTag.php");
            require_once(dirname(__FILE__) . "/view/rightDiv/menuBar.php");
            if($error[1] == '')
                require_once(dirname(__FILE__) . "/view/rightDiv/showSuccessResultUpdateInsert.php");
            else
                require_once(dirname(__FILE__) . "/view/rightDiv/showErrorResult.php");

            $queryString = $statement->queryString;
            require_once(dirname(__FILE__) . "/view/rightDiv/showSql.php");
            require_once(dirname(__FILE__) . "/view/rightDiv/rightDivCloseTag.php");
            require_once(dirname(__FILE__) . "/view/footer.php");
        }
    }
}