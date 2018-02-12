<div class="sql_query_div">
    <h4>Run SQL query on database <?php echo"$chosenDbName"; ?></h4>
    <form method="post" <?php echo"action=\"{$baseURL}sql.php/executeQuery/$chosenDbName/1\">";?>
        <textarea rows="6" cols="90" name="sql_query"></textarea>
        <div class="ok_in_form">
            <input type="submit" value="Go">
        </div>
    </form>
</div>

