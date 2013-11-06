<?php
	include("php.php");

	////////////////////////////////////
	// No where, no limit, no order  //
	////////////////////////////////////
	$where = " where `public` = 1 and (";
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
			$where .= "\"%$v%\" OR `tags` LIKE ";
		}
		$where = substr($where, 0, -16);
		$where .= ")";
	}

	if ($where == " where `public` = 1 and (") $where = " where `public` = 1";


	if ($_GET["pvt"] == 1 && $where == " where `public` = 1") $where = "";

	$query = "SELECT * FROM `features`$where ORDER BY id DESC";
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
				$query2 = "SELECT * FROM `features` WHERE `id` LIKE $v AND `public` = 1 LIMIT 0,1;";
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
		}
	}
	
	/////////////////////////
	// Set up intro slices  //
	/////////////////////////
	if($_GET['intro'] == 1) {
		$getintro = "SELECT * FROM features WHERE id = 115";
		$result = mysql_query($getintro) or die ("Get Feature Error: " . $getintro);
		$intro = recordToArray($result);
		array_splice($arr, 0, 0, $intro);
	}

	//////////////////////////////////////////////////
	// Let's make sure there are enough stories..  //
	//////////////////////////////////////////////////

	$count = count($arr);

	if ($count < 6 && $_REQUEST["exp"] != 1) {
		$limit = 7 - $count;
		$query3 = "SELECT * FROM `features` WHERE `public` = 1 ORDER BY `id` DESC LIMIT 0,$limit;";
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
	
	
	
	
	//////////////////////////////////////////////////
	// Fetch favorites features  //
	//////////////////////////////////////////////////
	
	
	if ($_GET['favs'] != "") {

		$urlstr = htmlentities($_GET['favs'], ENT_QUOTES);
		// $favs = explode(",", $urlstr);
	// 	$test = $favs[0];
		
		$query = "SELECT * FROM `features` WHERE `id` in ($urlstr) AND `public` = 1";
		// echo $query;
		$result = mysql_query($query) or die("Fail: " . $query);
		$arr = recordToArray($result);

	}


	//////////////////////
	// Loop the array  //
	//////////////////////
	foreach($arr as $k=>$v) {

		//////////////////////////////////////
		// Ignore records without images  //
		//////////////////////////////////////
		if ( $v[story_images] == "" || $v[story_images] == "{}") {
			// Do nothing
		}
		else {
			$newHTML = "";
			
			$img_json = $arr[$k]['story_images'];
			$img_arr = json_decode($img_json);

			// Remove parent <p> tags, split result by paragraph
			$arr[$k]['html'] = str_replace('</p>', '', trim($arr[$k]['html']));
			$split0 = explode('<p>', $arr[$k]['html']);

			foreach($split0 as $kk => $vv) {
				if($vv == "") unset($split0[$kk]);
			}
			$split = array_values($split0);

			// Loop each paragraph in the array
			foreach($split as $kk => $vv) {
				$currentpara = $kk + 1;
				$newHTML .= "<p>";
				// Loop images
				foreach ($img_arr as $kkk => $vvv) {
					// If we're currently in the paragraph for this image..
					if ($vvv->para == $currentpara) {
						$style = " style='";
						foreach ($vvv->style as $kkkk => $vvvv) {
							$style .= $kkkk . ": ". $vvvv . "; ";
						}
						//set image position
						if($vvv->position !=null && $vvv->position == 'center'){
							$style .= "display:block; margin:0 auto 10px;";
						}elseif($vvv->position !=null && $vvv->position == 'right'){
							$style .= "float:right; margin:0 0 10px 10px;";
						}else{
							$style .= "float:left; margin:0 10px 10px 0;"; 
						}
						//set image size
						if($vvv->width !=null){
							$width = " width='".$vvv->width."'";
						}else{
							$width = "";
						}
						$style .= "'";
						$newHTML .= "<img".$style." src = '".$vvv->src."' alt='alt' ".$width."/>";
					}
				}
				$newHTML .= $vv . "</p>\n\n";
			}
			$arr[$k]['html'] = $newHTML;
			// echo $newHTML;
		}

		/////////////////////////////////////////
		// Ignore records without pullquotes //
		/////////////////////////////////////////
		if ( $v[pullquotes] == "" || $v[pullquotes] == "{}") {
			// Do nothing
		}
		else {
			$newHTML = "";
			
			$pq_json = $arr[$k]['pullquotes'];
			$pq_array = json_decode($pq_json);

			// Remove parent <p> tags, split result by paragraph
			$arr[$k]['html'] = str_replace('</p>', '', trim($arr[$k]['html']));
			$split0 = explode('<p>', $arr[$k]['html']);


			foreach($split0 as $kk => $vv) {
				if($vv == "") unset($split0[$kk]);
			}
			$split = array_values($split0);

			// Loop each paragraph in the array
			foreach($split as $kk => $vv) {
				$currentpara = $kk + 1;
				$newHTML .= "<p>";
				// Loop images
				foreach ($pq_array as $kkk => $vvv) {
					$thi = $vvv;
					// If we're currently in the paragraph for this image..
					if ($vvv->para == $currentpara) {
						$newHTML .= "<span class='pullquote'>".$vvv->quote."</span>";
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