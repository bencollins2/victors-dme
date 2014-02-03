<?php
	include("php.php");
	
	//check the user number today
	$query = "SELECT COUNT(*) FROM `users`";
	$result = mysql_query($query) or die("Sorry: " . $query);
	if($result){
		$row = mysql_fetch_assoc($result);
		$count = $row['COUNT(*)'];
	}
	
	echo "Hey, today we have $count users!!";
?>