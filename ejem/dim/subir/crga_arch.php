<?php
require_once("Classes/PHPExcel.php");
require_once("Classes/PHPExcel/Reader/Excel2007.php");

$ltxt_archiv = $_FILES['file']['name'];
$ltxt_nomarc = explode(".", $ltxt_archiv);

if ($ltxt_nomarc[1] == 'xls')
{
   $ltxt_verecxel = "Excel5";    
} 
if ($ltxt_nomarc[1] == 'xlsx')
{
   $ltxt_verecxel = "Excel2007";    
}

$objReader = PHPExcel_IOFactory::createReader($ltxt_verecxel);
$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);
 //$dim = $objPHPExcel->getActiveSheet()->calculateWorksheetDimension();
$lint_numfils = $_REQUEST['txt_numfils'];
$lint_numcolu = $_REQUEST['txt_numcol'];

$larr_columnas = gra_columns($_REQUEST['txt_numcol']);
$i=1;
//echo "<table border='1'>";

while ($i < 35)
{
 // echo "<tr>";
 // echo "<td>".$i."</td>";
  for ($j=0; $j < count($larr_columnas) ; $j++)
  { 
   //echo "<td>".$objPHPExcel->getActiveSheet()->getCell($larr_columnas[$j].$i)->getValue()."</td>";
    $larr_tabla[$i-1][$j] = $objPHPExcel->getActiveSheet()->getCell($larr_columnas[$j].$i)->getValue();
  } 
  //echo "</tr>";
  $i++;
}
//echo "</table>";

gra_tabla($larr_tabla, $ltxt_nomarc[0],count($larr_columnas),$lint_numfils);


 function gra_columns($lint_numcols)
 {
  $larr_abcd = explode("|","A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z");
  $ltxt_letra = "";
  $a = 0;
  $x = 0;
    while( $x < $lint_numcols ) 
    { 
      for ($j=0; $j < count($larr_abcd); $j++) 
      { 
        if ($x < $lint_numcols)
        {
          $larr_abcde[$x] = $ltxt_letra.$larr_abcd[$j]; 
        }     
        $x++;
      }

     if($a >= 26)
     {
        $a = 0;
     }      
     $ltxt_letra = $larr_abcd[$a]; 
   $a++;
  }

  return $larr_abcde;

 }

function gra_tabla($larr_cols , $ltxt_nomtbl, $numcols,$numfilas)
{

$ltxt_query="CREATE TABLE ".$ltxt_nomtbl." (";
$ltxt_valore= 'INSERT INTO '.$ltxt_nomtbl." VALUES ";

for ($i=0; $i < $numcols ; $i++) 
{ 
    $ltxt_query = $ltxt_query." ".$larr_cols[0][$i]." ".$larr_cols[1][$i].",";
}
for ($i=2; $i < $numfilas ; $i++) 
{ 
  $ltxtvals = "(";
  for ($j=0; $j < $numcols ; $j++) 
  { 
    $ltxtvals =  $ltxtvals." '".$larr_cols[$i][$j]."',";
  }
  $ltxtvals = substr($ltxtvals,0,-1);
  $ltxtvals =  $ltxtvals."),";
  $ltxt_valore = $ltxt_valore.$ltxtvals;

}

$ltxt_query = substr($ltxt_query, 0,-1);
$ltxt_query = $ltxt_query.");";
echo $ltxt_query."<br>";
$ltxt_valore = substr($ltxt_valore,0,-1);
echo  $ltxt_valore.";";
}


?>