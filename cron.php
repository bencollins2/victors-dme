<?php //phpinfo();
	include("php.php");
	
	// insert id, current date and curremt user_nums into nums_users table
	$query = "INSERT INTO `nums_users` (`id`, `date`, `nums_users`) SELECT NULL, CURDATE(), COUNT(*) FROM `users`";
	$result = mysql_query($query) or die("Sorry: " . $query);

?>