<?
	include("php.php");
	if (!extension_loaded('json')) {
            dl('json.so');  
    }

    $query = "SELECT `link` FROM `features`";
    $result = mysql_query($query);
    $existing_urls = recordToArray($result);

    
    $url = "http://www.engin.umich.edu/college/about/news/news/jsonlist";

    $json = file_get_contents($url);
    $data = json_decode($json, TRUE);

    foreach($data["articles"] as $k => $v) {
    	$json_url = $v["URL"] . "/dmejson";

    	$story_json = file_get_contents($json_url);
    	$story_data = json_decode($story_json, TRUE);
		
		$count = 0;

    	if($story_data["Include in Campaign DME"] == "True") {
				$count = $count + 1;
    			$title = ($story_data["Short Title"] != "") ? $story_data["Short Title"] : $story_data["Title"];
				$byline = $story_data["Author"];
				$description = $story_data["Subtitle"];
				$longdesc = $story_data["Description"];
				$img_large = $story_data["Campaign DME Image"];
				$img_slice = $story_data["Campaign DME Image Slice"];
				$html = $story_data["Body content"];
				
				$query = "INSERT INTO `features` (`id`, `title`, `byline`, `description`, `longdesc`, `img_large`, `tags`, `html`, `options`, `customStyle`, `story_images`, `pullquotes`, `weight`, `public`, `link`, `titletop`, `pubdate`) VALUES (NULL, '$title', '$byline', '$description', '$longdesc', '$img_large', NULL, '$html', NULL, NULL, NULL, NULL, NULL, '0', NULL, '1', NULL)";
				
    	}
    }
	
	echo "Databased Updated! $count stories have been added.";



    // $url = "http://www.engin.umich.edu/college/about/news/stories/2013/december/making-cars-lighter/dmejson";

	// if ($data["Include in Campaign DME"] == "True") {

	// 	$title = ($data["Short Title"] != "") ? $data["Short Title"] : $data["Title"];
	// 	$byline = $data["Author"];
	// 	$description = $data["Subtitle"];
	// 	$longdesc = $data["Description"];
	// 	$img_large = $data["Campaign DME Image"];
	// 	$img_slice = $data["Campaign DME Image Slice"];
	// 	$html = $data["Body content"];

	// 	$query = "INSERT INTO `features` (`id`, `title`, `byline`, `description`, `longdesc`, `img_large`, `tags`, `html`, `options`, `customStyle`, `story_images`, `pullquotes`, `weight`, `public`, `link`, `titletop`, `pubdate`) 
	// 	                          VALUES (NULL, '$title', '$byline', '$description', '$longdesc', '$img_large', NULL, '$html', NULL, NULL, NULL, NULL, NULL, '0', NULL, '1', NULL)";


	// }

?>