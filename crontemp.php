<?
	include("php.php");
	if (!extension_loaded('json')) {
            dl('json.so');  
    }
	include("include/simple_html_dom.php");

	    $query = "SELECT `link` FROM `features`";
	    $result = mysql_query($query);
	    $existing_urls = recordToArray($result);
	
	    
	    $url = "http://www.engin.umich.edu/college/about/news/news/jsonlist";
	
	    $json = file_get_contents($url);
	    $data = json_decode($json, TRUE);
		
		$count = 0;
		
	    foreach($data["articles"] as $k => $v) {
	    	$json_url = $v["URL"] . "/dmejson";
	
	    	$story_json = file_get_contents($json_url);
	    	$story_data = json_decode($story_json, TRUE);
	
	    	if($story_json && $story_data["Include in Campaign DME"] == "True") {
				$count = $count + 1;
				$title = ($story_data["Short Title"] != "") ? addslashes($story_data["Short Title"]) : addslashes($story_data["Title"]);
				$byline = addslashes($story_data["Author"]);
				$description = addslashes($story_data["Subtitle"]);
				$longdesc = addslashes($story_data["Description"]);
				$img_large = saveImage($story_data["Campaign DME Image"], $story_data["Campaign DME Image Slice"]);
				$content = parseHTML($story_data["Body content"]);
	
				$query = "INSERT INTO `features` (`id`, `title`, `byline`, `description`, `longdesc`, `img_large`, `tags`, `html`, `options`, `customStyle`, `story_images`, `pullquotes`, `weight`, `public`, `link`, `titletop`, `pubdate`) 
				                          VALUES (NULL, '$title', '$byline', '$description', '$longdesc', '$img_large', NULL, '$content[0]', NULL, NULL, '$content[1]', NULL, NULL, '0', NULL, '1', NULL)";
				$result = mysql_query($query) or die("Sorry: " . $query);
				
	    	}
	    }
	
	echo "Databased Updated! $count stories have been added.";



	//     $url = "http://www.engin.umich.edu/college/about/news/stories/2014/january/understanding-concussions-testing-head-impact-sensors/dmejson";
	// 	$url = "http://www.engin.umich.edu/college/about/news/stories/2014/january/michigan-engineerings-partnership-in-china-wins-award-for-excellence-innovation/dmejson";
	//     $json = file_get_contents($url);
	//    	$data = json_decode($json, TRUE);
	// 
	// 
	// if ($data["Include in Campaign DME"] == "True") {
	// 
	// 	$title = ($data["Short Title"] != "") ? addslashes($data["Short Title"]) : addslashes($data["Title"]);
	// 	$byline = addslashes($data["Author"]);
	// 	$description = addslashes($data["Subtitle"]);
	// 	$longdesc = addslashes($data["Description"]);
	// 	$img_large = saveImage($data["Campaign DME Image"], $data["Campaign DME Image Slice"]);
	// 	$content = parseHTML($data["Body content"]);
	// 
	// 	$query = "INSERT INTO `features` (`id`, `title`, `byline`, `description`, `longdesc`, `img_large`, `tags`, `html`, `options`, `customStyle`, `story_images`, `pullquotes`, `weight`, `public`, `link`, `titletop`, `pubdate`) 
	// 	                          VALUES (NULL, '$title', '$byline', '$description', '$longdesc', '$img_large', NULL, '$content[0]', NULL, NULL, '$content[1]', NULL, NULL, '0', NULL, '1', NULL)";
	// 	$result = mysql_query($query) or die("Sorry: " . $query);
	// 
	// }
	
	
	function parseHTML($html){
		
		//echo $html;
		
		$html = "<html>" . $html . "</html>";
		$html = str_get_html($html);
		$content_arr = $html->find('html')[0]->children();
	
		$output_html = '';
		$image_arr = array();
	
		for($i=0; $i<count($content_arr); $i++){
			//echo $i;
			//echo $output_html;
			// if the tag is a video, put it into a p tag
			if($content_arr[$i]->tag == 'iframe'){
				$output_html .= '<p>' . $content_arr[$i]->outertext . '</p>';
				continue;
			}
		
			// if the tag contains videos, put them into p tags
			if($content_arr[$i]->find('iframe')){
				foreach($content_arr[$i]->find('iframe') as $iframe){
					$output_html .= '<p>' . $iframe->outertext . '</p>';
				}
				continue;
			}
		
			// if the tag is an image, push its information into the image_arr and add an empty p tag as the placeholder
			if($content_arr[$i]->tag == 'img'){
				// check the image position
				$position = '';
				if(strpos($content_arr[$i]->class, 'left') !== false){
					$position = 'left';
				}else if(strpos($content_arr[$i]->class, 'right') !== false){
					$position = 'right';
				}
				// push img into image_arr
				$image_arr[]=array('src' => 'http://www.engin.umich.edu'.$content_arr[$i]->src, 'para' => $i+1, 'alt' => $content_arr[$i]->alt, 'position' => $position);
				$output_html .= '<p></p>';
				continue;
			}
		
			// if the tag contains images, push image information into the image_arr, remove the img tag and add the contents left into p tag
			if($content_arr[$i]->find('img')){
				foreach($content_arr[$i]->find('img') as $img){
					// check the image position
					$position = '';
					if(strpos($img->class, 'left') !== false){
						$position = 'left';
					}else if(strpos($img->class, 'right') !== false){
						$position = 'right';
					}
					// push img into image_arr
					$image_arr[]=array('src' => 'http://www.engin.umich.edu'.$img->src, 'para' => $i+1, 'alt' => $img->alt, 'position' => $position);
					$img->outertext = '';
				}
				$output_html .= '<p>' . $content_arr[$i]->innertext . '</p>';
				continue;
			}
		
			// if nothing above happened, put the innertext into a new p tag
			$output_html .= '<p>' . $content_arr[$i]->innertext . '</p>';
		
		}
	
		//echo $output_html;

		//echo json_encode($image_arr, JSON_FORCE_OBJECT); 
		
		$image_json = json_encode($image_arr, JSON_FORCE_OBJECT);
		
		return array(addslashes($output_html), $image_json);
		
	}
	
	function saveImage($large_url, $slice_url){
		//get the image name based on the url of the large image
		$img_name = substr(strrchr($large_url, '/'),1);
		
		$large_image = file_get_contents($large_url);
		file_put_contents('img/big/'.$img_name, $large_image);
		
		$slice_image = file_get_contents($slice_url);
		file_put_contents('img/'.explode('.',$img_name)[0].'_cover.'.explode('.',$img_name)[1], $slice_image);
		
		return explode('.',$img_name)[0];
	}
?>