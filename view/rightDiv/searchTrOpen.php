<tr class="<?php if ($rowNumber % 8 == 1) echo"active"; else if ($rowNumber % 8 == 3) echo"success"; else if ($rowNumber % 8 == 5) echo"info"; else if ($rowNumber % 8 == 7) echo"danger"; else echo"no_color"; ?>">
    <td><?php echo"$fieldName"; ?></td>
    <td><?php echo"$fieldType"; ?></td>