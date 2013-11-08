<?php

if (isset($_GET["id"]) && $_GET["id"]!=""){
	$item_id = (int)$_GET["id"];
	require("../db_campaign.php");
	if (!extension_loaded('json')) {
			dl('json.so');  
	}
	$getfeature = "SELECT * FROM features WHERE id = $item_id";
	$result = mysql_query($getfeature) or die ("Get Feature Error: " . $getfeature);
	$row = mysql_fetch_assoc($result);

	if($row !== false){
		if($row["story_images"] == ""){

		}else{
			$img_json = $row['story_images'];
			$img_arr = json_decode($img_json);
			// Remove parent <p> tags, split result by paragraph
			$row['html'] = str_replace('</p>', '', trim($row['html']));
			$split0 = explode('<p>', $row['html']);

			foreach($split0 as $kk => $vv) {
				if($vv == "") unset($split0[$kk]);
			}
			$split = array_values($split0);
			$newHTML="";
			// Loop each paragraph in the array
			// foreach($split as $kk => $vv) {
			// 	$newHTML .= "<p>";
			// 	// Loop images
			// 	foreach ($img_arr as $kkk => $vvv) {
			// 		// If we're currently in the paragraph for this image..
			// 		if ($vvv->para == $kk) {
			// 			$style = " style='";
			// 			foreach ($vvv->style as $kkkk => $vvvv) {
			// 				$style .= $kkkk . ": ". $vvvv . "; ";
			// 			}
			// 			$style .= "'";
			// 			$newHTML .= "<img".$style." src = '".$vvv->src."' alt='alt' />";
			// 		}
			// 	}
			// 	$newHTML .= $vv . "</p>\n\n";
			// }
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
			$row['html'] = $newHTML;
		}
		if($row["pullquotes"] == ""){

		}else{
			$img_json = $row['pullquotes'];
			$img_arr = json_decode($img_json);
			// Remove parent <p> tags, split result by paragraph
			$row['html'] = str_replace('</p>', '', trim($row['html']));
			$split0 = explode('<p>', $row['html']);

			foreach($split0 as $kk => $vv) {
				if($vv == "") unset($split0[$kk]);
			}
			$split = array_values($split0);
			$newHTML = "";
			// Loop each paragraph in the array
			foreach($split as $kk => $vv) {
				$newHTML .= "<p>";
				// Loop pullquotes
				foreach ($img_arr as $kkk => $vvv) {
					// If we're currently in the paragraph for this pullquote..
					if ($vvv->para == $kk) {
						$newHTML .= "<span class='pullquote'>".$vvv->quote."<span class='attr'>".$vvv->attr."</span></span>";
					}
				}
				$newHTML .= $vv . "</p>\n\n";
			}
			$row['html'] = $newHTML;
		}

		if ($row["titletop"] == 1) {
			$feature_content = "
			<style type=\"text/css\">".$row["customStyle"]."</style><div class=\"content-image-div\"><h2 class=\"fadewithme\">".$row["title"]."</h2><img class=\"content-image\" src=\"img/big/".$row["img_large"].".jpg\" alt=\"item image\" /></div>
			<div class=\"content-info\"><div class=\"left-stuff\">
			<span id=\"fb\" class='facebook st' displayText='Facebook'></span>
			<span id=\"tw\" class='twitter st' displayText='Tweet'></span>
			<span id=\"gp\" class='googleplus st' displayText='Google +'></span>
			<span id=\"pn\" class='pinterest st' displayText='Pinterest'></span>
			<span id=\"rd\" class='reddit st' displayText='Reddit'></span>
			</div><h3 class='subtitle'>".$row["description"]."</h3><span class=\"byline\">".$row["byline"]."</span><div class=\"body\">".$row["html"]."</div></div>";		}
		else {
			$feature_content = "
			<style type=\"text/css\">".$row["customStyle"]."</style><div class=\"content-image-div\"><img class=\"content-image\" src=\"img/big/".$row["img_large"].".jpg\" alt=\"item image\" /></div>
			<div class=\"content-info\"><div class=\"left-stuff\">
			<span id=\"fb\" class='facebook st' displayText='Facebook'></span>
			<span id=\"tw\" class='twitter st' displayText='Tweet'></span>
			<span id=\"gp\" class='googleplus st' displayText='Google +'></span>
			<span id=\"pn\" class='pinterest st' displayText='Pinterest'></span>
			<span id=\"rd\" class='reddit st' displayText='Reddit'></span>
			</div><h2 class=\"fadewithme\">".$row["title"]."</h2><h3 class='subtitle'>".$row["description"]."</h3><span class=\"byline\">".$row["byline"]."</span><div class=\"body\">".$row["html"]."</div></div>";
		}

		$title = $row["title"];

		
		
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
    <link rel="stylesheet" href="css/campaign.css">
	
</head>

<body class="article">
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

        <div class="items" style="">
        	<div class="itemcontainer flexslider">
            	<ul class="slides">
					<li><div class="item-content"><?php echo($feature_content)?></div></li>
                </ul>
            </div>
        </div>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
        <script type="text/javascript" src="//use.typekit.net/zsu8wmg.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "ur-56eaa73e-f333-782-e2af-e4f274ea562f"});</script>
		<script type="text/javascript" charset="utf-8">
		stWidget.addEntry({
			"service":"facebook",
			"element":document.getElementById('fb'),
			"url":"http://victors.engin.umich.edu/article.php?id=<?= $item_id ?>",
			"title":"<?= $title?>",
			"type":"large",
			"text":"Share on facebook",
			"summary":"Share on facebook"   
		});

		stWidget.addEntry({
			"service":"twitter",
			"element":document.getElementById('tw'),
			"url":"http://victors.engin.umich.edu/article.php?id=<?= $item_id ?>",
			"title":"<?= $title?>",
			"type":"large",
			"text":"Share on twitter",
			"summary":"Share on twitter"   
		});

		stWidget.addEntry({
			"service":"googleplus",
			"element":document.getElementById('gp'),
			"url":"http://victors.engin.umich.edu/article.php?id=<?= $item_id ?>",
			"title":"<?= $title?>",
			"type":"large",
			"text":"Share on googleplus",
			"summary":"Share on googleplus"   
		});

		stWidget.addEntry({
			"service":"pinterest",
			"element":document.getElementById('pn'),
			"url":"http://victors.engin.umich.edu/article.php?id=<?= $item_id ?>",
			"title":"<?= $title?>",
			"type":"large",
			"text":"Share on pinterest",
			"summary":"Share on pinterest"   
		});

		stWidget.addEntry({
			"service":"reddit",
			"element":document.getElementById('rd'),
			"url":"http://victors.engin.umich.edu/article.php?id=<?= $item_id ?>",
			"title":"<?= $title?>",
			"type":"large",
			"text":"Share on reddit",
			"summary":"Share on reddit"   
		});

		$(window).on("scroll", function(e){
			var opacity = 1-($("html").scrollTop()/450);
			console.log(opacity);
			if (opacity < 0.01) opacity = 0;
			if (opacity > 0.99) opacity = 1;
			$(".fadewithme").css({"opacity":opacity});
		});
		
		$(document).ready(function(){
			//var bodymargin = $(".content-image-div").height()+"px";
			var bodymargin = "450px";
			$(".content-info").css({"margin-top":bodymargin});
			
			$(window).off("scroll").on("scroll", function(e) {
				var imgcontainerheight = 450;
				var opac = (imgcontainerheight - $("body").scrollTop())/imgcontainerheight;
				// console.log(opac);
				if (opac >= 0.01 && opac <= 1) {
					$(".fadewithme").css({"opacity" : opac});
				}
				else if (opac < 0.01) {
					$(".fadewithme").css({"opacity" : "0"});
				}
				
			});	
		});

		</script>
		<script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-45546416-1', 'umich.edu');
          ga('send', 'pageview');

        </script>
</body>