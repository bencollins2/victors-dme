<?php
	require_once("include/session.php");
	
	$user = $session->userinfo[0];
	
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
            <div id="nav_fav" class="navi_item">
                <span>Favorite</span>
                <div class="square">
                    <p class="one p_long"></p>
                    <p class="two p_long"></p>
                    <p class="three p_short"></p>
                    <p class="four p_short"></p>
                </div>
            </div>
            <div id="nav_exp" class="navi_item">
                <span>Explore</span>
                <div class="square">
                    <p class="one p_short"></p>
                    <p class="two p_short"></p>
                    <p class="three p_short"></p>
                    <p class="four p_short"></p>
                </div>
            </div>
            <div id="nav_home" class="navi_item">
                <span>Home</span>
                <div class="square">
                    <p class="one p_long"></p>
                    <p class="two p_long"></p>
                    <p class="three p_long"></p>
                    <p class="four p_long"></p>
                </div>
            </div>
            
            

        </header>
		
        <div class="left desktop-left">
            <!-- <img class="leftlogo" src="img/leftlogo.png" alt="logo" /> -->
            <div class="lefttext">
                <h2>Welcome <?= $first_name?></h2>
                <span>Victor of engineering</span>
                <p class="msg"><!-- We've hand picked some stories and videos that we think you'll like. Let us know what you think. We'll be updating the site frequently, so please bookmark it and come back again soon. --></p>
                <p><a <?= $logout?>>(Log Out)</a></p>
            </div>
		</div>
		
		
        <div class="quiz">
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
        	<div class="next"><a class="next" href="#">Submit</a><img src="img/whiteblue.gif" class="wheel" />
            </div>

        </div>
		
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
        <script src="js/masonry.js"></script>
        <script src="js/plugins.js"></script>
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
				$("a.next").on("click", function(e){
					e.preventDefault();
					// console.log($(".wheel")[0])
					$(".wheel").toggle();
					$this = $(this);
					$query = "";
					$("div.active").each(function(){
						$that = $(this);
						$cls = $that.data("shortname");
						$query += $cls + ",";
					});
					$query = $query.substring(0, $query.length - 1);
					$.ajax({
						type: "POST",
						url: "dostuff.php",
						data: { id: <?= $user?>, type: "newcats", cats: $query }
					}).done(function( msg ) {
						console.log("Message: ", msg);
						if (msg = "added cats") window.location.assign("index.php");

					});
					console.log($query);
				});
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
