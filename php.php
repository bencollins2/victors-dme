<?php
	require("../db_campaign.php");


	/////////////////////////////
	// Load the JSON Library  //
	/////////////////////////////
	if (!extension_loaded('json')) {
			dl('json.so');  
	}

	////////////////////////////////////////////////////////////////
	// Function to convert MySQL record to a standard PHP array  //
	////////////////////////////////////////////////////////////////
	function recordToArray($mysql_result) {
	 $rs = array();
	 while($rs[] = mysql_fetch_assoc($mysql_result)) {
	    // you don´t really need to do anything here.
	  }
	 
	  foreach($rs as $k => $v) {
	  	if ($v==false) unset($rs[$k]);
	  }
	 return $rs;
	}
?>