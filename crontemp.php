<?
	if (!extension_loaded('json')) {
            dl('json.so');  
    }

    $url = "http://www.engin.umich.edu/college/about/news/stories/2013/december/making-cars-lighter/dmejson";
    $json = file_get_contents($url);
    $data = json_decode($json, TRUE);

	if ($data["Include in Campaign DME"] == "True") {

		$title = ($data["Short Title"] != "") ? $data["Short Title"] : $data["Title"];
		$byline = $data["Author"];
		$description = $data["Subtitle"];
		$longdesc = $data["Description"];
		$img_large = $data["Campaign DME Image"];
		$img_slice = $data["Campaign DME Image Slice"];
		$html = $data["Body content"];

		$query = "INSERT INTO `features` (`id`, `title`, `byline`, `description`, `longdesc`, `img_large`, `tags`, `html`, `options`, `customStyle`, `story_images`, `pullquotes`, `weight`, `public`, `link`, `titletop`, `pubdate`) 
		                          VALUES (NULL, '$title', '$byline', '$description', '$longdesc', '$img_large', NULL, '$html', NULL, NULL, NULL, NULL, NULL, '0', NULL, '1', NULL)";


	}

?>