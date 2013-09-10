<?php
	////////////////////////////////////////////////////////////////
	// Function to convert MySQL record to a standard PHP array  //
	////////////////////////////////////////////////////////////////
	function recordToArray($mysql_result) {
	 $rs = array();
	 while($rs[] = mysql_fetch_assoc($mysql_result)) {
	    // you don´t really need to do anything here.
	  }
	 // return json_encode($rs);
	 return $rs;
	}
?>