<?php
	include("../db_campaign.php");
	
	$query = "SELECT id, title, description, img_tall FROM `features`";//" WHERE id='1'";
	$result = mysql_query($query) or die("Fail: " . $query);

	while ($r = mysql_fetch_array($result)) {
		
		echo $r[0] . ": ";
		print_r($r);
		echo "<br /><br />";



	}

	// echo $itemjson;

	// print_r(json_decode($itemjson));
	// foreach(json_decode($itemjson) as $k => $v) {
	// 	echo $k;
	// 	echo $v;	
	// }
	// $html .= "</tbody>";
	// // echo $html;

	// $output = array('html' => $html);
	// header('Content-type: application/json');
	// echo json_encode($output);
?>