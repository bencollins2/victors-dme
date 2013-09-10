<?php

	if (!extension_loaded('json')) {
			dl('json.so');  
	}

	function recordToArray($mysql_result) {
	 $rs = array();
	 while($rs[] = mysql_fetch_assoc($mysql_result)) {
	    // you don´t really need to do anything here.
	  }
	 // return json_encode($rs);
	 return $rs;
	}

	include("../db_campaign.php");

	$where = "";
	$limit = "";

	if ($_GET['cats'] != "") {
		$where .= " WHERE `tags` LIKE ";
		$urlstr = htmlentities($_GET['cats'], ENT_QUOTES);
		$cats = explode(",", $urlstr);

		foreach($cats as $v) {
			$where .= "\"%$v%\" OR `tags` LIKE ";
		}
		$where = substr($where, 0, -16);
		// echo $where;
	}

	if ($_GET['slices'] == 1) {
		$limit = " LIMIT 0,6";
	}


	$query = "SELECT * FROM `features`$where$limit";


	// $query = "SELECT * FROM `features`";

	$result = mysql_query($query) or die("Fail: " . $query);
	$arr = recordToArray($result);
	foreach($arr as $k=>$v) {
		if ( $v[story_images] == "") {
			// Do nothing
		}
		else {
			// $arr[$k][html] = "TEST HTML";

			// echo $arr[$k]['html'];

			$json = $arr[$k]['story_images'];
			$ar = json_decode($json);
			$split0 = preg_split("/^<p>/", $arr[$k]['html']);
			$split1 = preg_split("/<\/p>\Z/", $split0[1]);
			$split = preg_split("/(<\/p>*.<p>)|(<br*\s\/>)/", $split1[0]);

			foreach($split as $kk => $vv) {

				$newHTML .= "<p>";

				foreach ($ar as $kkk => $vvv) {
					if ($vvv->para == $kk) {

						$style = " style='";

						foreach ($vvv->style as $kkkk => $vvvv) {
							$style .= $kkkk . ": ". $vvvv . "; ";
						}
						$style .= "'";
						$newHTML .= "<img".$style." src = '".$vvv->url."' alt='alt' />";
					}
				}

				$newHTML .= $vv . "</p>\n\n";
			}
			$arr[$k]['html'] = $newHTML;
			// echo $newHTML;
		}
	}

	$arr = json_encode($arr);

	header('Content-type: application/json');
	echo($arr);

?>