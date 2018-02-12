<td>
    <select name="operator[]">
        <option value="=">=</option>
        <option value=">">&gt;</option>
        <option value=">=">&gt;=</option>
        <option value="<">&lt;</option>
        <option value="<=">&lt;=</option>
        <option value="!=">!=</option>
    </select>
</td>
<td><input type="text" value="<?php if (isset($val)) echo"$val"; ?>" size="<?php if (isset($length)) echo"$length"; ?>"
           name="value[]"></td>
