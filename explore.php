<?php
	include("php.php");

	////////////////////////////////////
	// No where, no limit, no order  //
	////////////////////////////////////
	$where = "";
	$limit = "";
	$order = "";

	/////////////////////////
	// Set up categories  //
	/////////////////////////
	if ($_GET['cats'] != "") {

		if ($where == "") $where = " WHERE ";
		$where .= "`tags` LIKE ";
		$urlstr = htmlentities($_GET['cats'], ENT_QUOTES);
		$cats = explode(",", $urlstr);
		foreach($cats as $v) {
			/////////////////////////
			// Is it a global?  //
			/////////////////////////
			// if ($v == "") {
			// }

			$where .= "\"%$v%\" OR `tags` LIKE ";
		}
		$where = substr($where, 0, -16);
	}

	$query = "SELECT * FROM `features`$where";
	// echo $query;
	$result = mysql_query($query) or die("Fail: " . $query);
	$arr = recordToArray($result);

	////////////////////////////////////////////////////////
	// Check if there are individual entries to prepend  //
	////////////////////////////////////////////////////////

	if ($_GET['inds'] != "") {
		$indarray = array();
		$where2 = " WHERE ";
		$where2 .= "`id` LIKE ";
		$urlstr = htmlentities($_GET['inds'], ENT_QUOTES);
		$inds = explode(",", $urlstr);
		foreach($inds as $k => $v) {
			
			if ($v != 0) {
				$query2 = "SELECT * FROM `features` WHERE `id` LIKE $v LIMIT 0,1;";
				$result = mysql_query($query2) or die("Fail: " . $query2);
				$temp = recordToArray($result);
				array_pop($temp);
				$tempid = $temp[0]['id'];

				foreach($arr as $kk => $vv) {
					if ($vv['id'] == $tempid) {
						unset($arr[$kk]);
					}
				}
				
				array_splice($arr, $k, 0, $temp);
			}

			// array_unshift($arr, $temp);
			// print_r($temp[0]);
		}
	}

	
	// $arr = recordToArray($result);

	//////////////////////////////////////////////////
	// Let's make sure there are enough stories..  //
	//////////////////////////////////////////////////

	$count = count($arr);

	if ($count < 6) {
		$limit = 7 - $count;
		$query3 = "SELECT * FROM `features` ORDER BY `id` DESC LIMIT 0,$limit;";
		$result3 = mysql_query($query3);
		$temp = recordToArray($result3);
		array_pop($temp);
		foreach($temp as $k => $v) {
			$inarr = false;
			foreach($arr as $kk => $vv) {
				if ($vv['id'] == $v['id']) {
					$inarr = true;
				}
			}
			if (!$inarr) {
				array_push($arr, $v);
			}
		}
	}



	//////////////////////
	// Loop the array  //
	//////////////////////
	foreach($arr as $k=>$v) {

		//////////////////////////////////////
		// Ignore records without images  //
		//////////////////////////////////////
		if ( $v[story_images] == "") {
			// Do nothing
		}
		else {
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

	///////////////////////////////////////////////
	// Split first six off array if slices = 1  //
	///////////////////////////////////////////////
	if ($_GET['slices'] == 1) {
		$arr = array_slice($arr, 0, 6);
	}
	// print_r($arr);

	$arr = json_encode($arr);
	header('Content-type: application/json');
	echo($arr);
?>