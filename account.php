<?php
	require_once("include/session.php");
	
	$user = $session->userinfo[0];
	
	// define('PATH', 'img/avatars/');
	// define('UPLOADPATH', '/afs/umich.edu/group/e/engcomm/Private/uploads/img/avatars/');
	
	define('UPLOADPATH', 'img/avatars/');	
	
    include("../db_campaign.php");
	



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
			
			if($line["avatar_sm"]){
				$avatar = UPLOADPATH . $line["avatar_sm"];
			}else{
				$avatar = UPLOADPATH . "default.gif";
			}
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
	
	if ($_FILES['file']['size'] > 0) {
		$tmpname = $_FILES['file']['tmp_name'];
		$filename = $_FILES['file']['name'];
		$target = UPLOADPATH . $filename;
		$moved = move_uploaded_file(($tmpname), $target);
		if( $moved ) {
		  echo $target;
		} else {
		  echo "Not uploaded";
		}
		return;
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['avatarurl']))
	{
		$targ_w = $targ_h = 150;
		$jpeg_quality = 90;

		$src = $_POST['avatarurl'];
		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
		$targ_w,$targ_h,$_POST['w'],$_POST['h']);

		$newavatar = uniqid() . '.jpg';
		$success = imagejpeg($dst_r, UPLOADPATH . $newavatar, $jpeg_quality);
		
		if($success){
			unlink($src);
			$query = "UPDATE users SET `avatar_sm` = '$newavatar' WHERE `id` LIKE '$user'";
			$result = mysql_query($query) or die("Sorry: " . $query);
			echo "added avatar";
			header('Location: account.php');
		}
	}
	

	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Account Setting</title>

        <meta property="og:title" content="Victors for Michigan"/>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="http://victors.engin.umich.edu/"/>
        <meta property="og:image" content="http://victors.engin.umich.edu/img/big/victors.jpg"/>
        <meta property="og:site_name" content="Victors for Michigan"/>
        <meta property="fb:admins" content="2206222" />
        <meta property="og:description"
          content=""/>
        <meta name="description" content="Campaign">
        <meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" id="vp" name="viewport">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/dme.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/account.css">
		<link rel="stylesheet" href="css/jquery.Jcrop.min.css">
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.css" rel="stylesheet">
        
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
		<script>document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';</script>
        <script type="text/javascript" src="//use.typekit.net/zsu8wmg.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>


    </head>

    <body>
		
        <header class="sticky" id="nav">
            <ul>
                <li class="home"><a href="http://engin.umich.edu"><img src="img/CoE-horiz-rev.png" alt="Michigan Engineering" /></a></li>
            </ul>
           	<a href="index.php#favorites"><div id="nav_fav" class="navi_item">
                <span>Favorite</span>
                <div class="square">
                    <p class="one p_long"></p>
                    <p class="two p_long"></p>
                    <p class="three p_short"></p>
                    <p class="four p_short"></p>
                </div>
            </div></a>
            <a href="index.php#explore"><div id="nav_exp" class="navi_item">
                <span>Explore</span>
                <div class="square">
                    <p class="one p_short"></p>
                    <p class="two p_short"></p>
                    <p class="three p_short"></p>
                    <p class="four p_short"></p>
                </div>
            </div></a>
            <a href="index.php"><div id="nav_home" class="navi_item">
                <span>Home</span>
                <div class="square">
                    <p class="one p_long"></p>
                    <p class="two p_long"></p>
                    <p class="three p_long"></p>
                    <p class="four p_long"></p>
                </div>
            </div></a>
            
            

        </header>
		
        <div class="left desktop-left">
            <!-- <img class="leftlogo" src="img/leftlogo.png" alt="logo" /> -->
            <div class="lefttext">
                <h2>Welcome <?= $first_name?></h2>
				<div class="preview">
					 <img src="<?= $avatar?>" />
				</div>
				<br/>
				<a href="#" id="avatarbtn">Upload Avatar</a>
            </div>
		</div>
		
		<div class="right">
	        <div class="quiz" style="display:block">
	        	<h2>Select some things that you like.</h2>
	        	<ul>
	        		<li class="transportation">
	                    <div class="imgdiv">
	                        <img src="img/cats/transportation.jpg" />
	                    </div>
	                    <div class="overlay">
	                        <h3>Transportation Innovations</h3>
	                    </div>
	                    <div class="checkboxes">
	                        <div data-shortname="vautonomous" class="item"><img src="img/box.png" alt="checkbox" /><span>Autonomous Vehicles</span></div>
	                        <div data-shortname="invehicle" class="item"><img src="img/box.png" alt="checkbox" /><span>In-Vehicle Technology</span></div>
	                        <div data-shortname="vefficiency" class="item"><img src="img/box.png" alt="checkbox" /><span>Vehicle Efficiency</span></div>
	                        <div data-shortname="vsafety" class="item"><img src="img/box.png" alt="checkbox" /><span>Vehicle Safety</span></div>
	                        <div data-shortname="vpowersources" class="item"><img src="img/box.png" alt="checkbox" /><span>Power Sources</span></div>
	                    </div>
	                </li>
	                <li class="economics">
	                    <div class="imgdiv">
	                        <img src="img/cats/economics.jpg" />
	                    </div>
	                    <div class="overlay">
	                        <h3>Economics & Entrepreneurship</h3>
	                    </div>
	                    <div class="checkboxes">
	                        <div data-shortname="startups" class="item"><img src="img/box.png" alt="checkbox" /><span>Startups</span></div>
	                        <div data-shortname="highrisk" class="item"><img src="img/box.png" alt="checkbox" /><span>High-Risk Research</span></div>
	                        <div data-shortname="entreco" class="item"><img src="img/box.png" alt="checkbox" /><span>Entrepreneurial Ecosystem</span></div>
	                        <div data-shortname="ecoimpact" class="item"><img src="img/box.png" alt="checkbox" /><span>Economic Impact</span></div>
	                    </div>
	                </li>
	                <li class="wolverineexperience">
	                    <div class="imgdiv">
	                        <img src="img/cats/wolverineexperience.jpg" />
	                    </div>
	                    <div class="overlay">
	                        <h3>Wolverine Experience</h3>
	                    </div>
	                    <div class="checkboxes">
	                        <div data-shortname="handson" class="item"><img src="img/box.png" alt="checkbox" /><span>Hands-On Engineering</span></div>
	                        <div data-shortname="leadersbest" class="item"><img src="img/box.png" alt="checkbox" /><span>Academic Leaders & Best</span></div>
	                        <div data-shortname="world" class="item"><img src="img/box.png" alt="checkbox" /><span>Wolverines Around the World</span></div>
	                        <div data-shortname="innovations" class="item"><img src="img/box.png" alt="checkbox" /><span>Innovations at Michigan</span></div>
	                        <div data-shortname="stories" class="item"><img src="img/box.png" alt="checkbox" /><span>Victor Stories</span></div>
	                        <div data-shortname="life" class="item"><img src="img/box.png" alt="checkbox" /><span>Life in Ann Arbor</span></div>
	                    </div>
	                </li>
	                <li class="globalresources">
	                    <div class="imgdiv">
	                        <img src="img/cats/globalresources.jpg" />
	                    </div>
	                    <div class="overlay">
	                        <h3>Global Resources</h3>
	                    </div>
	                    <div class="checkboxes">
	                        <div data-shortname="water" class="item"><img src="img/box.png" alt="checkbox" /><span>Water</span></div>
	                        <div data-shortname="sustain" class="item"><img src="img/box.png" alt="checkbox" /><span>Sustainability</span></div>
	                        <div data-shortname="eefficiency" class="item"><img src="img/box.png" alt="checkbox" /><span>Energy Efficiency</span></div>
	                        <div data-shortname="alternativee" class="item"><img src="img/box.png" alt="checkbox" /><span>Alternative Energy</span></div>
	                        <div data-shortname="environment" class="item"><img src="img/box.png" alt="checkbox" /><span>Environment</span></div>
	                    </div>
	                </li>
	                <li class="materials">
	                    <div class="imgdiv">
	                        <img src="img/cats/materials.jpg" />
	                    </div>
	                    <div class="overlay">
	                        <h3>Revolutionary Materials</h3>
	                    </div>
	                    <div class="checkboxes">
	                        <div data-shortname="nanotech" class="item"><img src="img/box.png" alt="checkbox" /><span>Nanotechnology</span></div>
	                        <div data-shortname="structures" class="item"><img src="img/box.png" alt="checkbox" /><span>Structures</span></div>
	                        <div data-shortname="healthsafety" class="item"><img src="img/box.png" alt="checkbox" /><span>Health & Safety</span></div>
	                        <div data-shortname="eenvironment" class="item"><img src="img/box.png" alt="checkbox" /><span>Energy & Environment</span></div>
	                        <div data-shortname="electronics" class="item"><img src="img/box.png" alt="checkbox" /><span>Electronics</span></div>
	                    </div>
	                </li>
	        		<li class="healthcare">
	                    <div class="imgdiv">
	                        <img src="img/cats/healthcare.jpg" />
	                    </div>
	                    <div class="overlay">
	                        <h3>Healthcare</h3>
	                    </div>
	                    <div class="checkboxes">
	                        <div data-shortname="disease" class="item"><img src="img/box.png" alt="checkbox" /><span>Disease Research</span></div>
	                        <div data-shortname="diagnostics" class="item"><img src="img/box.png" alt="checkbox" /><span>Diagnostics</span></div>
	                        <div data-shortname="meddev" class="item"><img src="img/box.png" alt="checkbox" /><span>Medical Devices</span></div>
	                        <div data-shortname="treatments" class="item"><img src="img/box.png" alt="checkbox" /><span>Treatments</span></div>
	                        <div data-shortname="globalhealth" class="item"><img src="img/box.png" alt="checkbox" /><span>Global Health</span></div>
	                    </div>
	                </li>
	                <li class="securing">
	                    <div class="imgdiv">
	                        <img src="img/cats/securing.jpg" />
	                    </div>
	                    <div class="overlay">
	                        <h3>Securing our Future</h3>
	                    </div>
	                    <div class="checkboxes">
	                        <div data-shortname="weapons" class="item"><img src="img/box.png" alt="checkbox" /><span>Weapons Detection</span></div>
	                        <div data-shortname="cybersecurity" class="item"><img src="img/box.png" alt="checkbox" /><span>CyberSecurity</span></div>
	                        <div data-shortname="nuclear" class="item"><img src="img/box.png" alt="checkbox" /><span>Nuclear Non-Proliferation</span></div>
	                        <div data-shortname="natsec" class="item"><img src="img/box.png" alt="checkbox" /><span>National Security</span></div>
	                        <div data-shortname="infrastructure" class="item"><img src="img/box.png" alt="checkbox" /><span>Infrastructure</span></div>
	                        <div data-shortname="natdis" class="item"><img src="img/box.png" alt="checkbox" /><span>Natural Disasters</span></div>
	                    </div>
	                </li>
        		
	        	</ul>
	        	<div class="next"><a class="next" href="#">Submit</a><img src="img/whiteblue.gif" class="wheel" /></div>

	        </div>
			
			
			<div class="avatar" style="display:none">
				<div class="frame">
					<img id="avatar" src=""/>
					<form id="cropform" action="account.php" method="post" onsubmit="return checkCoords();">
						<input type="hidden" id="x" name="x" />
						<input type="hidden" id="y" name="y" />
						<input type="hidden" id="w" name="w" />
						<input type="hidden" id="h" name="h" />
						<input type="hidden" id="avatarurl" name="avatarurl" value=""/>
			        	<div class="next"  style="margin-top:20px"><a class="next" href="#" onclick="document.getElementById('cropform').submit()">Submit Avatar</a></div>
					</form>
				</div>
			</div>
			
		</div>
		
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
        <script src="js/masonry.js"></script>
        <script src="js/plugins.js"></script>
		
        <script type="text/javascript" src="js/plupload.js"></script>
        <script type="text/javascript" src="js/plupload.html4.js"></script>
        <script type="text/javascript" src="js/plupload.html5.js"></script>
		<script type="text/javascript" src="js/jquery.Jcrop.min.js"></script>
		
        <!--script src="js/fb.js"></script-->
        <script type="text/javascript">
			$(document).ready(function(){
				$(".item").on("click",function(e){
					e.preventDefault();
					$this = $(this);
                    $this.toggleClass("active");
					$img = $($this.find("img")[0]);
                    if ($img.attr("src") == "img/box.png") $img.attr("src", "img/checked.png");
                    else $img.attr("src", "img/box.png");
                    console.log($this.data("shortname"));
				});
				$(".imgdiv").on("click",function(){
					if($(this).attr("all") != "all"){
						$(this).attr("all", "all");
						$(this).parent().find(".item").each(function(){
							$(this).addClass("active");
							$($(this).find("img")[0]).attr("src", "img/checked.png");
						});
					}else{
						$(this).attr("all", "none");
						$(this).parent().find(".item").each(function(){
							$(this).removeClass("active");
							$($(this).find("img")[0]).attr("src", "img/box.png");
						});
					}
					
				});
				$("a.next").on("click", function (e){
					e.preventDefault();
					// console.log($(".wheel")[0])
					$(".wheel").toggle();
					$this = $(this);
					query = "";
					$("div.active").each(function(){
						$that = $(this);
						$cls = $that.data("shortname");
						query += $cls + ",";
					});
					query = query.substring(0, query.length - 1);
					$.ajax({
						type: "POST",
						url: "dostuff.php",
						data: { id: <?= $user?>, type: "newcats", cats: query }
					}).done(function( msg ) {
						console.log("Message: ", msg);
						if (msg == "added cats") window.location.assign("index.php");
					});
					console.log(query);
				});
				
				//upload avatar images
				
				var uploader = new plupload.Uploader({
					runtimes : 'html5,html4',
					browse_button : 'avatarbtn',
					max_file_size : '10mb',
					url : 'account.php',
					filters : [
						{title : "Image files", extensions : "jpg,png,gif"},
					]
				});
				
				uploader.init();
				
				uploader.bind('FilesAdded', function(up, files) {
					console.log('file added');
					uploader.start();
				});
				
				uploader.bind("FileUploaded", function(uploader, file, response){
					console.log(response);
					var url = response.response;
					$('.quiz').hide();
					$('#avatar').attr('src',url);
					$('.preview img').attr('src', url);
					$('#avatarurl').attr('value', url);
					
					$('.preview img').load(function(){
						$('.avatar').show();
						cropAvatar();
					})	
				});
				
				
				// to crop and preview avatar
				cropAvatar = function(){
			        $pcnt = $('.preview'),
			        $pimg = $('.preview img'),

			        xsize = $pcnt.width(),
			        ysize = $pcnt.height();
				
					var sizeration = $pimg.width()/$('#avatar').width();
				
				    $('#avatar').Jcrop({
				      aspectRatio: 1,
				      onSelect: update
				    },function(){
				      // Use the API to get the real image size
				      var bounds = this.getBounds();
				      boundx = bounds[0];
				      boundy = bounds[1];
				    });

					function update(c){
					    $('#x').val(c.x*sizeration);
					    $('#y').val(c.y*sizeration);
					    $('#w').val(c.w*sizeration);
					    $('#h').val(c.h*sizeration);
					
						console.log(c.x,c.y,c.w,c.h);
						console.log(sizeration);
						console.log($pimg.width(),$pimg.height());
					
				        if (parseInt(c.w) > 0)
				        {
				          var rx = xsize / c.w;
				          var ry = ysize / c.h;

				          $pimg.css({
				            width: Math.round(rx * boundx) + 'px',
				            height: Math.round(ry * boundy) + 'px',
				            marginLeft: '-' + Math.round(rx * c.x) + 'px',
				            marginTop: '-' + Math.round(ry * c.y) + 'px'
				          });
					  	}
					};
				
				    function checkCoords()
				    {
				      if (parseInt($('#w').val())) return true;
				      alert('Please select a crop region then press submit.');
				      return false;
				    };
				}

			});
        </script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script type="text/javascript">

          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-39713895-1']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

        </script>
		
	</body>
</html>
