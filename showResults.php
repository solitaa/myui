<?php


    require_once(dirname(__FILE__) . "/view/header.php");
    require_once(dirname(__FILE__) . "/leftPart.php");
    require_once(dirname(__FILE__) . "/view/rightDiv/rightDivOpenTag.php");
    require_once(dirname(__FILE__) . "/view/rightDiv/menuBar.php");

    if(isset($allRowsCount)){
        if (is_numeric($pageNumber)) {
            $pageNum = $pageNumber - 1;
        } else
            $pageNum = 0;
        $start = $pageNum * $rowsInPage;
    }
    if(isset($start))
        $newQuery .= " LIMIT $start, $rowsInPage";

    $statement = $db->prepare($newQuery);
    $statement->execute();
    $error = $statement->errorInfo();
    $queryString = $statement->queryString;


    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    $rowsCount = count($rows);

    if($error[1] == '')
        require_once(dirname(__FILE__) . "/view/rightDiv/showSuccessResult.php");
    else
        require_once(dirname(__FILE__) . "/view/rightDiv/showErrorResult.php");
    require_once(dirname(__FILE__) . "/view/rightDiv/showSql.php");

    if ($rows) {
        require_once(dirname(__FILE__) . "/view/rightDiv/sqlRowsListTheadOpenTag.php");
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
        require_once(dirname(__FILE__) . "/view/rightDiv/rowsListTbodyCloseTag.php");
    }
    if(isset($allRowsCount)){
        $links = ceil($allRowsCount / $rowsInPage);
        for ($i = 1; $i <= $links; $i++) {
            require_once(dirname(__FILE__) . "/view/rightDiv/pagesTitle.php");
            require(dirname(__FILE__) . "/view/rightDiv/showResultsPages.php");
        }
        require_once(dirname(__FILE__) . "/view/rightDiv/pagesCloseDiv.php");
    }
    require_once(dirname(__FILE__) . "/view/rightDiv/rightDivCloseTag.php");
    require_once(dirname(__FILE__) . "/view/footer.php");



