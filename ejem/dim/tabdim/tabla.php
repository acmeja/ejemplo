<?php

include("../lib/lib_fun.php");
include("../obj/obj_tblmaestr.php");

$objeto = new tblmaestr();

$objeto->arr_cols_val = explode("|","|'fsfs'|'gdgd'||||");
$objeto->arr_col_con  = explode("|","1|||||jj|");
actualiza_registro($objeto);
//agregar_registro($objeto)
elimina_registro($objeto);

?>