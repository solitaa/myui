<div class="menu_bar">
    <?php
    echo "<a href=\"{$baseURL}index.php\">Localhost</a>";
    if (isset($chosenDbName)) {
        echo"<span class='item'> » </span><a href=\"{$baseURL}tables.php/tables/$chosenDbName\">$chosenDbName</a>";
    }
    if (isset($chosenTableName)) {
        echo"<span class='item'> » </span><a href=\"{$baseURL}select.php/browse/$chosenDbName/$chosenTableName/1\">$chosenTableName</a>";
    }
    ?>
</div>
<?php
if (isset($chosenDbName)) {
    echo"<div class = 'sql_div'>
            <a href=\"{$baseURL}sql.php/sql/$chosenDbName\"><img class = 'sql_image'></a>
        </div>";
}
?>