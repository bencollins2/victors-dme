<?php
	require("../db_campaign.php");

	if (!extension_loaded('json')) {
            dl('json.so');  
    }

	$type = $_REQUEST["type"];
	$userid = (int)$_REQUEST["id"];
	$username = mysql_real_escape_string($_REQUEST["name"]);
	$cats = mysql_real_escape_string($_REQUEST["cats"]);
	$favs = mysql_real_escape_string($_REQUEST["favs"]);

	///////////////////////////////
	// Checking if user exists  //
	///////////////////////////////
	// if ($type == "exists") {
	// 	$query = "SELECT * FROM users WHERE `id` LIKE '".$id."' LIMIT 0,1";
	// 	$result = mysql_query($query);
	// 	$answer = "does not exist";
	// 	while($line = mysql_fetch_array($result)){
	// 		$answer = "exists";
	// 	}
	// 	///////////////////////////////////////////////////////////////////
	// 	// If they do not exist, let's create their ID automatically.  //
	// 	///////////////////////////////////////////////////////////////////
	// 	if ($answer == "does not exist") {
	// 		$query = "INSERT INTO users (ID) VALUES ('$id');";
	// 		$result = mysql_query($query) or die("Sorry: " . $query);
	// 	}
	// 	$query = "SELECT * FROM users WHERE `id` LIKE '".$id."' LIMIT 0,1";
	// 	$result = mysql_query($query);
	// 	while($line = mysql_fetch_array($result)){
	// 		$cats = $line["categories"];
	// 		$ind = $line["individuals"];
			
	// 		if ($cats == "" && $ind == "") {
	// 			$answer = "no cats";
	// 		}
	// 	}
	// 	/////////////////////////////////////////////////////////////
	// 	// Logically should only respond 'exists' or 'no cats.'  //
	// 	/////////////////////////////////////////////////////////////
	// 	echo $answer;
	// }

	if ($type == "newcats") {
		if ($cats != "") {
			$query = "UPDATE users SET `categories` = '$cats' WHERE `id` LIKE '$userid'";
			$result = mysql_query($query) or die("Sorry: " . $query);
			echo "added cats";
			// echo $query;
		}
	}

	if ($type == "setUserTutorial") {
		if ($_REQUEST["val"] != "") {
			$val = (int)$_REQUEST["val"];
			$query = "UPDATE users SET `tutorial` = '$val' WHERE `id` LIKE '$userid'";
			$result = mysql_query($query) or die("Sorry: " . $query);
			echo "Changed tutorial val to $val.";
			// echo $query;
		}
	}
	
	if($type == "newfav"){
		$query = "UPDATE users SET `favorites` = '$favs' WHERE `id` LIKE '$userid'";
		$result = mysql_query($query) or die("Sorry:" . $query);
		echo "added favs";
	}
	
	if($type == "tutorial"){
		$query = "UPDATE users SET `tutorial` = 1 WHERE `id` LIKE '$userid'";
		$result = mysql_query($query) or die("Sorry:" . $query);
		echo "tutorial checked";
	}

	if ($type == "putmessage") {
		header('Content-type: application/json');
		$msg = nl2br($_REQUEST["msg"]);
		$msg = preg_replace('/\n/i', '', $msg);
		$msg = mysql_real_escape_string($msg);
		$to = (int)$_REQUEST["to"];

		if ($msg != "" && $to != "") {
			// $msg = "<p>" . implode( "</p>\n\n<p>", preg_split( '/\n(?:\s*\n)+/', $msg ) ) . "</p>";
			$query = "INSERT INTO `messages` (`id`, `from`, `to`, `message`, `timestamp`, `img1`, `img2`, `img3`, `img4`, `img5`, `img6`, `img7`, `img8`, `img9`, `img10`, `img1_para`, `img2_para`, `img3_para`, `img4_para`, `img5_para`, `img6_para`, `img7_para`, `img8_para`, `img9_para`, `img10_para`, `published`, `viewed`) VALUES (NULL, 'u$userid', 'a$to', '$msg', CURRENT_TIMESTAMP, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1')";
			$result = mysql_query($query) or die("Sorry: " . $query);
			$json["message"] = "Message added.";
			$insertid = mysql_insert_id();
			$query = "SELECT * FROM messages WHERE id LIKE $insertid LIMIT 0,1";
			$result = mysql_query($query);
			$line = mysql_fetch_array($result);
			$ts = date("F j, Y", strtotime($line["timestamp"]));
			$json["msg"] = "<div class=\"message\"><h3 class=\"from\">$username</h3>
						<span class=\"timestamp\">$ts</span>
						$msg
					</div>";

			$query2 = "UPDATE `users` SET `showreplied` = '1' WHERE `id` = '$userid'";
			$result2 = mysql_query($query2) or die("Couldn't change view value");

			$query2 = "SELECT * FROM `users` WHERE `id` = '$userid'";
			$result2 = mysql_query($query2);
			$line2 = mysql_fetch_array($result2);
			$un = $line2["first"] . " " . $line2["last"];

			$query3 = "SELECT * FROM `adminusers` WHERE `id` LIKE $to LIMIT 0,1;";
			$result3 = mysql_query($query3);
			while($line3 = mysql_fetch_array($result3)) {
			    $email = $line3["email"];
			    $name = $line3["first"] . " " . $line3["last"];
			    $message = "Hello $name. \n\n$un has sent you a new message on the Michigan Engineering Campaign platform. Please log in to view it.\n\nThanks!";
				$subject = "New message for Michigan Engineering Campaign";
				$from = "engcom@umich.edu";
				$headers = "From:" . $from;
				mail($email,$subject,$message,$headers);
			}

			echo json_encode($json);
		}
	}

	if ($type == "getmessages") {
		header('Content-type: application/json');
		$query = "SELECT m.id AS 'mid', SUBSTR(m.from, 2) AS 'from', SUBSTR(m.to, 2) AS 'to', m.message, m.timestamp, u.id, CONCAT(a.first, ' ', a.last) AS fromName, CONCAT(u.first, ' ', u.last) AS toName, m.img1, m.img2, m.img3, m.img4, m.img5, m.img6, m.img7, m.img8, m.img9, m.img10, a.avatar_sm FROM `messages` AS m INNER JOIN `users` AS u ON SUBSTR(m.to, 2) = u.id INNER JOIN `adminusers` AS a ON SUBSTR(m.from, 2) = a.id WHERE SUBSTR(m.to, 2) LIKE '$userid' UNION SELECT m.id, SUBSTR(m.from, 2) AS 'from', SUBSTR(m.to, 2), m.message, m.timestamp, u.id, CONCAT(u.first, ' ', u.last), CONCAT(a.first, ' ', a.last), m.img1, m.img2, m.img3, m.img4, m.img5, m.img6, m.img7, m.img8, m.img9, m.img10, u.avatar_sm FROM `messages` AS m INNER JOIN `users` AS u ON SUBSTR(m.from, 2) = u.id INNER JOIN `adminusers` AS a ON SUBSTR(m.to, 2) = a.id WHERE SUBSTR(m.from, 2) LIKE '$userid' ORDER BY `timestamp` ASC";
		$result = mysql_query($query);
		$html = "<div class=\"messages\">";
		if (mysql_num_rows($result) == 0) {
            $html .= "<div class=\"message\">No messages yet.</div>";
        }

		while ($line = mysql_fetch_array($result)){
			$id = $line["mid"];
			$hasimages = false;
			$numimages = 0;
			$imagetext = "";
			$images = array(
				1 => $line["img1"], 
				2 => $line["img2"],
				3 => $line["img3"],
				4 => $line["img4"],
				5 => $line["img5"],
				6 => $line["img6"],
				7 => $line["img7"],
				8 => $line["img8"],
				9 => $line["img9"],
				10 => $line["img10"]
			);

			if ($line["to"] == $userid) {
				$to = $line["from"];
			} 

			$imgurls = array();
			foreach($images as $k => $v) {
				if ($v != null) {
					$hasimages = true;
					$numimages ++;
					$imgurls[] = $v;
				}
			}

			if ($numimages == 1) {
				$v = substr($imgurls[0], 0, -4);
				$imagetext = "<div class=\"singleimg msgimg\"><img src=\"imagehelper.php?i=$v\" alt=\"Photo 1\"></div>";
			}
			else if ($hasimages) {
				$imgids[] = $id;
				$imagetext = "<div id=\"slides$id\" class=\"slides msgimg\">";
				foreach($imgurls as $k => $v) {
					$v = substr($v, 0, -4);
					$kk = $k+1;
					// $imagetext .= "<img src=\"http://localhost:8888/htdocs/campaign/img/uploads/$v\" alt=\"Photo $kk\">";
					$imagetext .= "<img src=\"imagehelper.php?i=$v\" alt=\"Photo $kk\">";
				}
				$imagetext .= "<a href=\"#\" class=\"slidesjs-previous slidesjs-navigation\"><i class=\"icon-chevron-left icon-large\"></i></a>
			<a href=\"#\" class=\"slidesjs-next slidesjs-navigation\"><i class=\"icon-chevron-right icon-large\"></i></a></div>";

			}

			$ts = date("F j, Y", strtotime($line["timestamp"]));
			$html .= "<div class=\"message\">";
			if ($line["avatar_sm"] != "") $html .= "<p class='avatar'><img class=\"avatar\" src=\"./img/avatars/".$line["avatar_sm"]."\" /></p>";
			$html .= "		<h3 class=\"from\">".$line["fromName"]."</h3>
						<span class=\"timestamp\">$ts</span>
						".$line["message"]."
						$imagetext
					</div>";
		}
		$html .= "
		<div class=\"message\">
		<div class=\"sendmessage\">
		  		<textarea></textarea>
				<input class=\"sendmsg\" type=\"submit\" data-from=\"$userid\" data-to=\"$to\" data-fromname=\"$username\" data-publish=\"1\" value=\"Send Message\"></div></div></div>";
		$json["query"] = $query;
		$json["html"] = $html;
		$json["id"] = $imgids;


		$query2 = "SELECT * FROM `messages` WHERE `to` LIKE 'u".$userid."' LIMIT 0,1000";
		$result2 = mysql_query($query2);

		if (mysql_num_rows($result2) > 0) {
			$query3 = "SELECT * FROM `adminusers` WHERE `uids` LIKE '%$userid%' LIMIT 0,1000";
			$result3 = mysql_query($query3);
			while($line3 = mysql_fetch_array($result3)) {
			    $email = $line3["email"];
			    $name = $line3["first"] . " " . $line3["last"];
			    $message = "Hello $name. \n\n$username has viewed a message on the Michigan Engineering Campaign platform.\n\nThanks!";
				$subject = "New message for Michigan Engineering Campaign";
				$from = "engcom@umich.edu";
				$headers = "From:" . $from;
				mail($email,$subject,$message,$headers);
			}
		}

	


		$query2 = "UPDATE `users` SET `showviewed` = '1' WHERE `id` = '$userid'";
		$result2 = mysql_query($query2) or die("Couldn't change view value");

		echo json_encode($json);

	}
	

?>