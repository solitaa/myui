<tr class="<?php if ($rowNumber % 8 == 1) echo"active"; else if ($rowNumber % 8 == 3) echo"success"; else if ($rowNumber % 8 == 5) echo"info"; else if ($rowNumber % 8 == 7) echo"danger"; else echo"no_color"; ?>">
    <td>
        <a <?php echo"href=\"{$baseURL}select.php/browse/$chosenDbName/$tableName/1\""; ?>><?php echo"$tableName"; ?></a>
    </td>
    <td>
        <a <?php echo"href=\"{$baseURL}select.php/browse/$chosenDbName/$tableName/1\""; ?>><span><img title="Browse"
                                                                                                     alt="Browse"
                                                                                                     class="browse"> Browse</span></a>
    </td>
    <td>
        <a <?php echo"href=\"{$baseURL}structure.php/structure/$chosenDbName/$tableName\""; ?>><span><img title="Structure"
                                                                                                      alt="Structure"
                                                                                                      class="structure"> Structure</span></a>
    </td>
    <td>
        <a <?php echo"href=\"{$baseURL}search.php/searchForm/$chosenDbName/$tableName\""; ?>><span><img title="Search"
                                                                                                          alt="Search"
                                                                                                          class="search"> Search</span></a>
    </td>
    <td>
        <a <?php echo"href=\"{$baseURL}insert.php/insertForm/$chosenDbName/$tableName\""; ?>><span><img title="Insert"
                                                                                                   alt="Insert"
                                                                                                   class="insert"> Insert</span></a>
    </td>
    <td class="delete_td">
        <a <?php echo"href=\"{$baseURL}delete.php/dropTable/$chosenDbName/$tableName\""; ?>><span><img title="Drop"
                                                                                                      alt="Drop"
                                                                                                      class="delete"> Drop</span></a>
    </td>
    <td><?php echo"$tableRows"; ?></td>
    <td><?php echo"$type"; ?></td>
    <td><?php echo"$tableCollation"; ?></td>
    <td><?php echo"$size Kib"; ?></td>
</tr>