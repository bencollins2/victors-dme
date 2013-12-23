<?php

require_once("include/session.php");


if (isset($_GET["id"]) && $_GET["id"]!=""){

	 /////////////////////
    // Facebook stuff  //
    /////////////////////

    require("facebook.php");

    $facebook = new Facebook($config);

    // See if there is a user from a cookie
    $user = $facebook->getUser();

    if ($_GET["link"] > 0) {
        $session->logout();
        
    }


    //////////////////////////////////////
    // If there's no logged in user...  //
    //////////////////////////////////////
    if ($user == 0 && !$session->logged_in) {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/register.css";
        $body = "register.php";
    }

    ////////////////////////////////
    // If there is a user and...  //
    ////////////////////////////////
    else {

        ///////////////////////////////////////////////////////////////
        // ...there is NO facebook user, but there is a custom user..  //
        ///////////////////////////////////////////////////////////////
        if ($session->logged_in) {
            $user = $session->userinfo[0];
            $type = "custom";
            $logout = "href=\"process.php\"";
        
        }

        ////////////////////////////////////
        // ..or there IS a facebook user  //
        ////////////////////////////////////
        else {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('/me');
                $name = $user_profile["name"];
                $first_name = $user_profile["first_name"];
                $last_name = $user_profile["last_name"];
                $email = $user_profile["email"];
                // echo "Facebook: ";
                // print_r($name);
                $type = "facebook";
            } catch (FacebookApiException $e) {
                // echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
                // $user = null;
            }
            $logout = "onClick=\"logoutFb()\" href=\"#\"";
        }

        // From this point out, we need $user to definitively reflect our user,
        // whether or not that user is on facebook. At this point it should not matter.

        // If they were a custom user, it will find them. The whole $u thing is
        // for automatically generating facebook users in our DB, but our custom
        // users should have manually signed up (thus creating the ID).
        include("../db_campaign.php");


         // If they're logged into facebook, and there's a link ID 
        

        $q = "SELECT * FROM `users` WHERE `id` LIKE '$user' LIMIT 0,1;";
        $r = mysql_query($q);
        $u=0;
        $c=0;

        while($line = mysql_fetch_array($r)){
            $u++;
            if ($line["categories"] != "") {
                $c++;
            }
            $first_name = $line["first"];
            $name = $line["first"] . " " . $line["last"];
            $cats = $line["categories"];
            $inds = $line["individuals"];
            $sidebar = $line["sidebar"];
            $mailimg = $line["mailimg"];
			$favorites = $line["favorites"];
			$msgslice = $line["show_message_slice"];
			$firsttime = $line["firsttime"];
        }

        // $qq = "SELECT * FROM `adminusers` WHERE "

        $lnk = mysql_real_escape_string($_REQUEST['link']);
// echo "<!--Info: user $user, u $u, lnk $lnk-->";        
        if ($lnk > 0 && $user > 0 && $_GET['add'] == 1) {
            $q = "SELECT * FROM `users` WHERE `id` = '$lnk' AND `todelete` IS NULL LIMIT 0,1;";
            $r = mysql_query($q);
            $l = mysql_fetch_array($r);
 // echo $q."<br /><br/>";
            if (!mysql_error()){
                 $q = "INSERT INTO users (id,name,first,last,email,categories,individuals,sidebar,mailimg,fromlink,showasnew,show_message_slice) VALUES ('$user','$name','$first_name','$last_name','$email','".$l['categories']."','".$l['individuals']."','".$l['sidebar']."','".$l['mailimg']."','".$lnk."','1','1');";
                $r = mysql_query($q);
                $lastid = mysql_insert_id();
                if ($lastid > 0) $_GET['register'] = 0;
                else $regerror = 1;

                $q = "UPDATE `users` SET `todelete` = '1' WHERE `id` = '$lnk'";
                $r = mysql_query($q) or die("Failure to update temp user.");
 // echo $q."<br /><br/>";
                $q = "SELECT * FROM `adminusers` WHERE `uids` LIKE '%$lnk%' LIMIT 0,1;";
                $r = mysql_query($q) or die("Failure to select current admin user IDs.");
                $l = mysql_fetch_array($r);
                $aid = $l["id"];
                $uids = $l["uids"];
 // echo $q."<br /><br/>";
                $newuids = preg_replace("/(,?)".$lnk."/", "", $uids);
                $newuids = $user.",".$newuids;

                $q = "UPDATE `adminusers` SET `uids` = '$newuids' WHERE `id` = '$aid'";
                $r = mysql_query($q) or die("Couldn't update admin record.");
 // echo $q."<br /><br/>";
                $q = "UPDATE `messages` SET `to` = 'u".$user."' WHERE `to` = 'u".$lnk."'";
 // echo $q."<br /><br/>";
                $r = mysql_query($q) or die("Couldn't get messages.");
                $c++;
                // die;
            }

           

        }
       

        /////////////////////////////////////////////////////////////////
        // If they're logged into facebook, but we don't have their ID //
        /////////////////////////////////////////////////////////////////
        
        else if ($u == 0 && $user > 0 && $email != "" && $lnk == 0) {
            $q = "INSERT INTO users (id,name,first,last,email,categories,individuals,premium) VALUES ('$user','$name','$first_name','$last_name','$email','','','0');";
            $r = mysql_query($q);
            $lastid = mysql_insert_id();
            if ($lastid > 0) $_GET['register'] = 0;
            else $regerror = 1;
            if (mysql_error()) {
                // echo "<!--$q-->";
            }
            $u++;
        }
        // They have a user account but no categories
        if ($u > 0 && $c == 0 && !$lnk) {
            $normalize = "css/normalize.css";
            $main = "css/main.css";
            $campstyle = "css/quiz.css";
            $body = "quiz.php";
        }

    } // End "if there's a user"




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
                <li class="home"><a href="http://engin.umich.edu"><img src="img/CoE-horiz-rev.png" alt="Michigan Engineering" /></a></li>
            </ul>

            <ul>
                <li class="home"><a href="http://engin.umich.edu"><img src="img/CoE-horiz-rev.png" alt="Michigan Engineering" /></a></li>
            </ul>

<? 
	if ($user == 0) {
?>
			
           <a id="nav_home" class="navi_item signup cta" href="#signup">
                Sign up
            </a>
<?
	} else {
?>
           <div id="nav_home" class="navi_item" onclick="location.href='index.php'">
                <span>Home</span>
                <div class="square">
                    <p class="one p_long"></p>
                    <p class="two p_long"></p>
                    <p class="three p_long"></p>
                    <p class="four p_long"></p>`
                </div>
            </div>
<?
	} 
?>

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



<div style="display:none;">
  <div id="signup" style="">
              <h2>Sign up and get started</h2>

      <a class="fbl" href="#">
        <img src="img/sign_in_with_facebook.png" />
      </a>
      <div class="or">
        <span>..or.. <a href="#" class="create">create an account directly</a>.</span>
      </div>

      <a href="#explore" class="prev">Preview the site</a>

      <div class="createacct">
        <form action="process.php" method="POST">

          <div class="table">
            <div><span>Email:</span><span><input type="text" name="email" maxlength="50" value="<? echo $form->value("email"); ?>"></span><span><? echo $form->error("email"); ?></span></div>
            <div><span>Password:</span><span><input type="password" name="pass" maxlength="30" value="<? echo $form->value("pass"); ?>"></span><span><? echo $form->error("pass"); ?></span></div>
            <div><span>First Name:</span><span><input type="text" name="first" maxlength="30" value="<? echo $form->value("first"); ?>"></span><span><? echo $form->error("first"); ?></span></div>
            <div><span>Last Name:</span><span><input type="text" name="last" maxlength="30" value="<? echo $form->value("last"); ?>"></span><span><? echo $form->error("last"); ?></span></div>
            <div class="submit"><span colspan="2" align="right">
            <input type="hidden" name="subjoin" value="1">
            <?php if ($_GET["link"] > 0) { 
              $lnk = mysql_real_escape_string($_GET["link"]);
            ?> 
            <input type="hidden" name="link" value="<?= $lnk?>">
            <? }?>
            <input type="submit" value="Join!"></span></div>
          </div>
        </form>
      </div>

  </div>
</div>

<div style="display:none">

  <div id="explore">
    <h2>Features</h2>
    <p><a class="cta" href="#signup">Back to signup</a></p>

    <p><img class='e1' src="img/home.png">Use the Victors Experience website as a powerful information pipeline to stay informed about work the College is doing to address the world's grand challenges.</p>
    <p><img class='e2' src="img/quiz.png">Customize the content by telling the site what topics you care most about and it will remember to show you related stories and videos.</p>
    <p><img class='e3' src="img/favorites.png">Create a favorites list and share stories you like on Facebook, Twitter, Pinterest and other social media sites.</p>
    <p><img class='e4' src="img/explore.png">Use Explore to see all the stories and videos about work being done across the College by students, faculty and alumni.</p>
    <p><a class="cta" href="#signup">Back to signup</a></p>
  </div>

</div>


		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
        <script type="text/javascript" src="//use.typekit.net/zsu8wmg.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "ur-56eaa73e-f333-782-e2af-e4f274ea562f"});</script>
		<script src="./js/colorbox-master/jquery.colorbox-min.js"></script>
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


			var position;
			$(document).on('cbox_open', function() {
			    $('body').css({ overflow: 'hidden' });
			});
			$(document).on('cbox_closed', function() {
			    $('body').css({ overflow: '' });
			});

			$(".create").on("click", function(e){
	          e.preventDefault();
	          $(this).parent().parent().parent().find(".createacct").fadeToggle(500);
	          $("div.info").fadeToggle(500);
	        });

	        $("a.cta").colorbox({inline:true, width:"75%"});
	        $("a.prev").colorbox({inline:true, width:"75%", height:"500px"});
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