<?php
if (isset($_GET["id"]) && $_GET["id"]!=""){
	$item_id = $_GET["id"];
		
	require("../db_campaign.php");
	$getfeature = "SELECT * FROM features WHERE id = $item_id";
	$result = mysql_query($getfeature) or die ("Get Feature Error: " . $getfeature);
	$row = mysql_fetch_assoc($result);
	if($row !== false){
		if($row["story_images"] == ""){
			//doing nothing
		}else{
			$img_json = $row['story_images'];
			$img_arr = json_decode($img_json);

			// Remove parent <p> tags, split result by paragraph
			$split0 = preg_split("/^<p>/", $row['html']);
			$split1 = preg_split("/<\/p>\Z/", $split0[1]);
			$split = preg_split("/(<\/p>*.<p>)|(<br*\s\/>)/", $split1[0]);

			// Loop each paragraph in the array
			foreach($split as $kk => $vv) {
				$newHTML .= "<p>";
				// Loop images
				foreach ($img_arr as $kkk => $vvv) {
					// If we're currently in the paragraph for this image..
					if ($vvv->para == $kk) {
						$style = " style='";
						foreach ($vvv->style as $kkkk => $vvvv) {
							$style .= $kkkk . ": ". $vvvv . "; ";
						}
						$style .= "'";
						$newHTML .= "<img".$style." src = '".$vvv->src."' alt='alt' />";
					}
				}
				$newHTML .= $vv . "</p>\n\n";
			}
			$row['html'] = $newHTML;
		}
		
		$feature_content = <<<html
			<style type="text/css">{$row["customStyle"]}</style><div class="content-image-div"><!--<img class="content-image" src="img/big/{$row["img_large"]}.jpg" alt="item image" />--></div>
			<div class="content-info"><div class="left-stuff">
			<span id="fb" class='facebook st' displayText='Facebook'></span>
			<span id="tw" class='twitter st' displayText='Tweet'></span>
			<span id="gp" class='googleplus st' displayText='Google +'></span>
			<span id="pn" class='pinterest st' displayText='Pinterest'></span>
			<span id="rd" class='reddit st' displayText='Reddit'></span>
			</div><h2 class="fadewithme">{$row["title"]}</h2><span class="byline">{$row["byline"]}</span><div class="body">{$row["html"]}</div></div>
html;
		
	}else{
		header("Location: index.php");
	}
	
}else{
	header("Location: index.php");
}
?>

<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?= $row["title"]?></title>
	
	<meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" id="vp" name="viewport">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/dme.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/article.css">
	
</head>

<body>
    <div id="fb-root"></div>

        <header class="sticky" id="nav">
            <ul>
                <li class="home"><a href="http://engin.umich.edu"><img src="img/mighigan_engineering_25.png" alt="Michigan Engineering" /></a></li>
            </ul>
           <div id="nav_home" class="navi_item" onclick="location.href='index.php'">
                <span>Home</span>
                <div class="square">
                    <p class="one p_long"></p>
                    <p class="two p_long"></p>
                    <p class="three p_long"></p>
                    <p class="four p_long"></p>`
                </div>
            </div>
             <!-- <div id="nav_exp" class="navi_item">
                <span>Explore</span>
                <div class="square">
                    <p class="one p_short"></p>
                    <p class="two p_short"></p>
                    <p class="three p_short"></p>
                    <p class="four p_short"></p>
                </div>
            </div>
            <div id="nav_fav" class="navi_item">
                <span>Favorite</span>
                <div class="square">
                    <p class="one p_long"></p>
                    <p class="two p_long"></p>
                    <p class="three p_short"></p>
                    <p class="four p_short"></p>
                </div>
            </div>
			<div id="nav_msg">
				<div class="msgicon"></div>
			</div> -->
        </header>

        <div class="items bgimg" style="background-image:url(<?php echo "img/big/".$row["img_large"].".jpg"?>)">
        	<div class="itemcontainer flexslider">
            	<ul class="slides">
					<li><div class="item-content"><?php echo($feature_content)?></div></li>
                </ul>
            </div>
        </div>
		
        <script type="text/javascript" src="//use.typekit.net/zsu8wmg.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "ur-56eaa73e-f333-782-e2af-e4f274ea562f"});</script>
		<script type="text/javascript" charset="utf-8">
		stWidget.addEntry({
			"service":"facebook",
			"element":document.getElementById('fb'),
			"url":"article.php?item=<?= $item_id ?>",
			"title":"facebook",
			"type":"large",
			"text":"Share on facebook",
			"summary":"Share on facebook"   
		});

		stWidget.addEntry({
			"service":"twitter",
			"element":document.getElementById('tw'),
			"url":"http://twitter.com",
			"title":"twitter",
			"type":"large",
			"text":"Share on twitter",
			"summary":"Share on twitter"   
		});

		stWidget.addEntry({
			"service":"googleplus",
			"element":document.getElementById('gp'),
			"url":"http://googleplus.com",
			"title":"googleplus",
			"type":"large",
			"text":"Share on googleplus",
			"summary":"Share on googleplus"   
		});

		stWidget.addEntry({
			"service":"pinterest",
			"element":document.getElementById('pn'),
			"url":"http://pinterest.com",
			"title":"pinterest",
			"type":"large",
			"text":"Share on pinterest",
			"summary":"Share on pinterest"   
		});

		stWidget.addEntry({
			"service":"reddit",
			"element":document.getElementById('rd'),
			"url":"http://reddit.com",
			"title":"reddit",
			"type":"large",
			"text":"Share on reddit",
			"summary":"Share on reddit"   
		});
		</script>
</body>