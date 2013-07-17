<?php
	class tblmaestr
	{
		  
      public $cvemaestr;
  		public $nomaestr;
  		public $desmaestr;
  		public $actmaestr;
  		public $cveusua;
  		public $fecbaja;
  		public $fecalta;
      public $arr_col_con;
  		public $arr_cols_val;
      public $arr_cols_vals;
  		public $tblnombre='tblmaestr';
  		public $arr_cols = array('cvemaestr','nomaestr','desmaestr','actmaestr','cveusuar','fecbaja','fecalta');

  		public function _set($arr_javascript)
 		{
 			$this->arr_cols_val = explode("|",$arr_javascript);
 		}

	}
?>