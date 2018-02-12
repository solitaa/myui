<?php
    require_once(dirname(__FILE__) . "/selectFunc.php");
    require_once(dirname(__FILE__) . "/select.php");


function searchForm($chosenDbName, $chosenTableName)
{
    global $db;
    global $baseURL;
    require_once(dirname(__FILE__) . "/view/header.php");
    $db->exec("use $chosenDbName");
    require_once(dirname(__FILE__) . "/leftPart.php");
    require_once(dirname(__FILE__) . "/view/rightDiv/rightDivOpenTag.php");
    require_once(dirname(__FILE__) . "/view/rightDiv/menuBar.php");

    require_once(dirname(__FILE__) . "/view/rightDiv/searchTableOpenTag.php");

    $selectFieldType = "SHOW FIELDS FROM $chosenTableName ";
    $stat = $db->prepare($selectFieldType);
    $stat->execute();
    $fields = $stat->fetchAll(PDO::FETCH_ASSOC);
    $rowNumber = 1;

    foreach ($fields as $field) {
        $fieldType = $field['Type'];
        $fieldName = $field['Field'];
        require(dirname(__FILE__) . "/view/rightDiv/searchTrOpen.php");

        $length = 30;
        if (strpos($fieldType, "(") !== false) {
            $fieldTypeArray = explode("(", $fieldType);
            $type = $fieldTypeArray[0];
        } else {
            $type = $fieldType;
        }
        if (strpos($type, "int") !== false) {
            require(dirname(__FILE__) . "/view/rightDiv/searchInputForNumber.php");
        } elseif ( $type == "datetime" || $type == "time" || $type == "date") {
            require(dirname(__FILE__) . "/view/rightDiv/searchInputForDates.php");
        }else
            require(dirname(__FILE__) . "/view/rightDiv/searchInputForText.php");

        require(dirname(__FILE__) . "/view/rightDiv/searchTrClose.php");
        $rowNumber++;
    }
    require_once(dirname(__FILE__) . "/view/rightDiv/searchTableCloseTag.php");
    require_once(dirname(__FILE__) . "/view/rightDiv/rightDivCloseTag.php");
    require_once(dirname(__FILE__) . "/view/footer.php");

}
function search($chosenDbName, $chosenTableName){
    global $db;
    global $baseURL;
    global $rowsInPage;

    $db->exec("use $chosenDbName");

    if (isset($_POST['operator']) && $_POST['value'] != '') {
        $operators = $_POST['operator'];
        $values = $_POST['value'];

        $selectFieldName = "SHOW FIELDS FROM $chosenTableName ";
        $stat = $db->prepare($selectFieldName);
        $stat->execute();
        $fields = $stat->fetchAll(PDO::FETCH_ASSOC);

        $fieldNames = array();
        foreach ($fields as $field) {
            $fieldName = $field['Field'];
            $fieldNames[] = $fieldName;
        }
        $newQuery = "SELECT * FROM $chosenTableName WHERE 1";
        for ($i = 0; $i < count($values); $i++) {
            if($values[$i] != '') {
                $newQuery .= " AND ";
                $newQuery .= "$fieldNames[$i] $operators[$i] '$values[$i]'";
            }
        }
        require_once(dirname(__FILE__) . "/showResults.php");

    }
}