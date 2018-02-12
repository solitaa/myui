<td>
    <select name="operator[]">
        <option value="LIKE">LIKE</option>
        <option value="NOT LIKE">NOT LIKE</option>
        <option value="=">=</option>
        <option value="!=">!=</option>
    </select>
</td>
<td><input type="text" value="<?php if (isset($val)) echo"$val"; ?>" size="<?php if (isset($length)) echo"$length"; ?>"
           name="value[]"></td>
