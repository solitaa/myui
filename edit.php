<?php
    require_once(dirname(__FILE__) . "/selectFunc.php");

function edit($chosenDbName, $chosenTableName, $priKey)
{
    session_start();

    global $db;
    global $baseURL;
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

        // get primary key field's name and value
        $arr = explode('=', $priKey);
        if (count($arr) == 2) {
            $primaryKey = $arr[0];
            $primaryKeyValue = $arr[1];
        }
        $rowNumber = 1; //for row colors

        $fieldNames = array();
        $fieldTypes = array();
        $query = "SELECT * FROM $chosenTableName WHERE $primaryKey = $primaryKeyValue";
        $statement = $db->prepare($query);
        $statement->execute();
        $resultArray = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (isset($_SESSION['error_messages'])) {
            $errorMessages = $_SESSION['error_messages'];
            $emptyFields = $errorMessages['empty_fields'];
            $incorrectDatetime = $errorMessages['incorrect_datetime'];
            $incorrectDate = $errorMessages['incorrect_date'];
            $incorrectTime = $errorMessages['incorrect_time'];

            if ($incorrectDatetime)
                require_once(dirname(__FILE__) . "/view/rightDiv/messageIncorrectDatetime.php");
            if ($incorrectDate)
                require_once(dirname(__FILE__) . "/view/rightDiv/messageIncorrectDate.php");
            if ($incorrectTime)
                require_once(dirname(__FILE__) . "/view/rightDiv/messageIncorrectTime.php");
            if ($emptyFields)
                require_once(dirname(__FILE__) . "/view/rightDiv/messageEmptyFields.php");
        }
        if ($resultArray) {
            require_once(dirname(__FILE__) . "/view/rightDiv/editDataTableOpenTag.php");
            foreach ($resultArray as $result) {
                foreach ($result as $key => $val) {
                    $fieldName = $key;
                    $fieldNames[] = $fieldName;
                    $selectFieldType = "SHOW FIELDS FROM $chosenTableName WHERE Field = '$key'";
                    $stat = $db->prepare($selectFieldType);
                    $stat->execute();
                    $fields = $stat->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($fields as $field) {
                        $fieldType = $field['Type'];
                        $fieldTypes[] = $fieldType;
                    }

                    require(dirname(__FILE__) . "/view/rightDiv/editDataTrOpen.php");
                    // get input type and size or textarea size type using fieldType
                    if (strpos($fieldType, "(") !== false) {
                        $fieldTypeArray = explode("(", $fieldType);
                        $type = $fieldTypeArray[0];
                        $length = substr($fieldTypeArray[1], 0, -1);
                    } else {
                        $type = $fieldType;
                        $length = strlen($val);
                    }
                    if (strpos($type, "int") !== false) {
                        require(dirname(__FILE__) . "/view/rightDiv/editDataInputForNumber.php");
                    } elseif (strpos($type, "text") !== false || $type == "varchar") {
                        $textareaRows = $length / 70;
                        if ($textareaRows > 9)
                            $textareaRows = 9;
                        elseif ($textareaRows < 3)
                            $textareaRows = 3;

                        require(dirname(__FILE__) . "/view/rightDiv/editDataTextarea.php");
                    } else {
                        require(dirname(__FILE__) . "/view/rightDiv/editDataInputForText.php");
                    }
                    require(dirname(__FILE__) . "/view/rightDiv/editDataTrClose.php");
                    $rowNumber++;
                }
            }

        }
        if (isset($_SESSION['error_messages'])) {
            session_destroy();
        }
        require_once(dirname(__FILE__) . "/view/rightDiv/editDataTableCloseTag.php");
        require_once(dirname(__FILE__) . "/view/rightDiv/rightDivCloseTag.php");
    } else
        require_once(dirname(__FILE__) . "/view/tableDoesntExist.php");
    require_once(dirname(__FILE__) . "/view/footer.php");
}


function saveChanges($chosenDbName, $chosenTableName, $priKey)
{
    global $db;
    global $baseURL;
    $db->exec("use $chosenDbName");

    // get primary key field's name and value
    $arr = explode('=', $priKey);
    if (count($arr) == 2) {
        $primaryKey = $arr[0];
        $primaryKeyValue = $arr[1];
    }
    $selectFieldType = "SHOW FIELDS FROM $chosenTableName ";
    $stat = $db->prepare($selectFieldType);
    $stat->execute();
    $fields = $stat->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fields as $field) {
        $fieldType = $field['Type'];
        $fieldName = $field['Field'];
        $fieldNull = $field['Null'];
        $fieldExtra = $field['Extra'];
        $fieldTypes[] = $fieldType;
        $fieldNames[] = $fieldName;
        $fieldNulls[] = $fieldNull;
        $fieldExtras[] = $fieldExtra;
    }
    if (isset($_POST['value'])) {
        $newData = $_POST['value'];

        //check for regexp
        $emptyFields = 0;
        $incorrectDatetime = 0;
        $incorrectDate = 0;
        $incorrectTime = 0;
        for ($i = 0; $i < count($newData); $i++) {

            $regExpDate = '^(19|20)\d\d([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])$';
            $regExpTime = '/^(([0-1][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$/';
            $regExpDatetime = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';

            if ($newData[$i] == '' && $fieldNulls[$i] != 'YES' && $fieldExtras[$i] != 'auto_increment') {
                $emptyFields = 1;
            }

            if ($fieldTypes[$i] == 'datetime' || $fieldTypes[$i] == 'timestamp') {
                if (!preg_match($regExpDatetime, $newData[$i]))
                    $incorrectDatetime = 1;

            } elseif ($fieldTypes[$i] == 'date') {
                if (!preg_match($regExpDate, $newData[$i]))
                    $incorrectDate = 1;

            } elseif ($fieldTypes[$i] == 'time') {
                if (!preg_match($regExpTime, $newData[$i]))
                    $incorrectTime = 1;
            }
        }
        $newData[] = $primaryKeyValue;

        // update data
        if ($incorrectDatetime == 0 && $incorrectDate == 0 && $incorrectTime == 0 && $emptyFields == 0) {
            $query = "UPDATE " . $chosenTableName . " SET ";
            $query .= implode(" = ?, ", $fieldNames);
            $query .= " = ? WHERE  $primaryKey = ?";
            $statement = $db->prepare($query);
            $statement->execute($newData);
            header("location: {$baseURL}select.php/browse/$chosenDbName/$chosenTableName/1");
            exit();
        } else {
            session_start();
            $errorMessages['empty_fields'] = $emptyFields;
            $errorMessages['incorrect_datetime'] = $incorrectDatetime;
            $errorMessages['incorrect_date'] = $incorrectDate;
            $errorMessages['incorrect_time'] = $incorrectTime;

            $_SESSION['error_messages'] = $errorMessages;
            header("location: {$baseURL}edit.php/edit/$chosenDbName/$chosenTableName/$priKey");
            exit();
        }

    }
}