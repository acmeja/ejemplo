<?php
	class tblcolumn
	{
    public $cvecolumn; 
    public $nomcolumn;
    public $cvetpodts;
    public $loncolumn;
    public $nulcolumn;
    public $leycolumn;
    public $comcolumn;
    public $valcolumn;
    public $cvemaestr;
    public $arr_col_con;
  	public $arr_cols_val;
    public $arr_cols_vals;
  	public $tblnombre='tblcolumn';
  	public $arr_cols = array('cvecolumn','nomcolumn','cvetpodts','loncolumn','nulcolumn','leycolumn','comcolumn','valcolumn','cvemaestr');
    
  	public function _set($arr_javascript)
 		{
 			$this->arr_cols_val = explode("|",$arr_javascript);
 		}



	}
?>