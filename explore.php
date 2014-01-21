<?php
    $preview = 0;
    require_once("include/session.php");
    $normalize = "css/normalize.css";
    $campstyle = "css/campaign.css";
    $body = "desktop.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Campaign</title>

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

        <link rel="stylesheet" href="<?= $normalize?>">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/dme.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="<?= $campstyle?>">
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.css" rel="stylesheet">
        
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
		<script>document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';</script>
        <script type="text/javascript" src="//use.typekit.net/zsu8wmg.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>

        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "ur-56eaa73e-f333-782-e2af-e4f274ea562f"});</script>
    </head>

    <? 
    function removeBreaks($txt) {
        $txt = str_replace(array("\r\n", "\r"), "\n", $txt);
        $lines = explode("\n", $txt);
        $new_lines = array();

        foreach ($lines as $i => $line) {
            if(!empty($line))
                $new_lines[] = trim($line);
        }
        return implode($new_lines);
    }
    echo "<!--Preview: $preview-->";
?>
<body class="explore">

    <div id="loading">
        <img src="img/loader.gif" />

    </div>

    <div id="fb-root"></div>

        <header class="sticky" id="nav">
            <ul>
                <li class="home"><a href="http://engin.umich.edu"><img src="img/CoE-horiz-rev.png" alt="Michigan Engineering" /></a></li>
            </ul>
          <!--   <div id="go-back" class="navi_item">
                <span>Home</span>
                <div class="square">
                    <p class="one p_long"></p>
                    <p class="two p_long"></p>
                    <p class="three p_long"></p>
                    <p class="four p_long"></p>
                </div>
            </div> -->
            <div class="navi_item" id="nav_msg">
                <div class="msgicon"></div>
            </div>
            <a href="#signup" id="nav_fav" class="navi_item su">
                <span>Favorite</span>
                <div class="square">
                    <p class="one p_long"></p>
                    <p class="two p_long"></p>
                    <p class="three p_short"></p>
                    <p class="four p_short"></p>
                </div>
            </a>
            <a href="#signup" id="nav_exp" class="navi_item su">
                <span>Explore</span>
                <div class="square">
                    <p class="one p_short"></p>
                    <p class="two p_short"></p>
                    <p class="three p_short"></p>
                    <p class="four p_short"></p>
                </div>
            </a>
            <a href="#signup" id="nav_home" class="navi_item su">
                <span>Home</span>
                <div class="square">
                    <p class="one p_long"></p>
                    <p class="two p_long"></p>
                    <p class="three p_long"></p>
                    <p class="four p_long"></p>
                </div>
            </a>
            
            

        </header>

    	
        <div class="left desktop-left">
            <!-- <img class="leftlogo" src="img/leftlogo.png" alt="logo" /> -->
            <div class="filter">
                <h2>Filter by category</h2>
                <span>Select multiple categories below</span>
                <form>

                    <div class="dropdown transportation">
                        <h3>Transportation Innovations</h3>
                        <div class="seemore">
                            <a href="javascript:void(0)" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="vautonomous,preventingaccidents,ivcommunication,disdriving"> Autonomous Vehicles<br />
                            <input type="checkbox" value="invehicletech,apps,disdriving"> In-Vehicle Technology<br />
                            <input type="checkbox" value="fuelefficiency,iwvhicles,aerodynamics"> Vehicle Efficiency<br />
                            <input type="checkbox" value="vautonomous,disdriving,invehicletech,vehiclesafety,preventingaccidents,ivcommunication"> Vehicle Safety<br />
                            <input type="checkbox" value="batteries"> Power Sources<br />
                        </div>
                    </div>

                    <div class="dropdown economics">
                        <h3>Economics & Entrepreneurship</h3>
                        <div class="seemore">
							<a href="javascript:void(0)" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="studentstart,techtransfer,facultystart"> Startups<br />
                            <input type="checkbox" value="mcubed,innovation,indcollab"> High-Risk Research<br />
                            <input type="checkbox" value="cfe,me,innovation"> Entrepreneurial Ecosystem<br />
                            <input type="checkbox" value="economy,indcollab"> Economic Impact<br />
                        </div>
                    </div>

                    <div class="dropdown wolverine">
                        <h3>Wolverine Experience</h3>
                        <div class="seemore">
							<a href="javascript:void(0)" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="hoexperience,studentteams,multidisc,studentresearch"> Hands-On Engineering<br />
                            <input type="checkbox" value="highlevstudentprojects,scholarships,classfuture,honors,onlinelearning,gradexperience,studentresearch"> Academic Leaders & Best<br />
                            <input type="checkbox" value="commoutreach,globalexp"> Wolverines Around the World<br />
                            <input type="checkbox" value="classfuture,honors,onlinelearning"> Innovations at Michigan<br />
                            <input type="checkbox" value="scholarships,studentteams,honors,globalexp,gradexperience,extracurr,studentstories"> Victor Stories<br />
                            <input type="checkbox" value="studentstories,nostalgia,lifeinaa,extracurr"> Life in Ann Arbor<br />
                        </div>
                    </div>

                    <div class="dropdown global">
                        <h3>Global Resources</h3>
                        <div class="seemore">
							<a href="javascript:void(0)" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="cleanwater,watershortage,waterpurification"> Water<br />
                            <input type="checkbox" value="sustainability,resourcemanagement,cleanair,cleanwater,globalenergy"> Sustainability<br />
                            <input type="checkbox" value="eefficiency,otheresources,resourcemanagement,globalenergy"> Energy Efficiency<br />
                            <input type="checkbox" value="biofuel,batteries,solarpower,hydropower,windpower,geothermalpower,nuclearpower"> Alternative Energy<br />
                            <input type="checkbox" value="cleanwater,cleanair,sustainability,climatechange"> Environment<br />
                        </div>
                    </div>

                    <div class="dropdown materials">
                        <h3>Revolutionary Materials</h3>
                        <div class="seemore">
							<a href="javascript:void(0)" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="nanotech"> Nanotechnology<br />
                            <input type="checkbox" value="lightweightmat,othermaterials,memorymetals,composites,gels"> Structures<br />
                            <input type="checkbox" value="peptides,drugdelivery,gels,safety"> Health & Safety<br />
                            <input type="checkbox" value="solarcells,electronics,environment,nuclearmaterials"> Energy & Environment<br />
                            <input type="checkbox" value="polymers,solarcells,electronics,computersfuture"> Electronics<br />
                        </div>
                    </div>

                    <div class="dropdown healthcare">
                        <h3>Healthcare</h3>
                        <div class="seemore">
							<a href="javascript:void(0)" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="disresearch,distreatment,drugs,drugdelivery"> Disease Research<br />
                            <input type="checkbox" value="imaging,diagnostics,affordability"> Diagnostics<br />
                            <input type="checkbox" value="3dprinting,imaging,meddevices,affordability"> Medical Devices<br />
                            <input type="checkbox" value="drugs,drugdelivery,3dprinting,distreatment,tissengineering,therapies,patientcare,quality,safety,affordability"> Treatments<br />
                            <input type="checkbox" value="globalhealth,affordability,safety,quality,patientcare,apptechnologies"> Global Health<br />
                        </div>
                    </div>

                    <div class="dropdown securingourfuture">
                        <h3>Securing our Future</h3>
                        <div class="seemore">
							<a href="javascript:void(0)" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="weaponsdetection,nuclearnon,drones,autonomous"> Weapons Detection<br />
                            <input type="checkbox" value="cybersec,surveillance"> CyberSecurity<br />
                            <input type="checkbox" value="nuclear,nuclearnon,weaponsdetection"> Nuclear Non-Proliferation<br />
                            <input type="checkbox" value="nuclearnon,weaponsdetection,cybersec,drones,natsec,millitary,surveillance"> National Security<br />
                            <input type="checkbox" value="cybersec,infrastructure,disaster,weather"> Infrastructure<br />
                            <input type="checkbox" value="infrastructure,disaster,weather"> Natural Disasters<br />
                        </div>
                    </div>

                </form>
            </div>
            <div class="readytogive">
                <ul>
                    <li>
                        <a href="article.php?id=115">Why be a victor?</a>
                    </li>
                    <li>
                        <a href="http://engin.umich.edu/giving">Ready to Give</a>
                    </li>
                 <!--    <li>
                        <a href="http://engin.umich.edu/giving">Tutorial</a>
                    </li> -->
                </ul>
                <!-- <img src="img/gift_box_50.png" /> -->
            </div>
			
			<div id="tutoicon">
				<i class="fa fa-question"></i>
			</div>
        </div>

        <div class="items">
        	<div class="itemcontainer flexslider">
            	<ul class="slides">

                    
                </ul>
            </div>
        </div>


        <div style="display:none">
          <div id="signup" style="">
                      <h2>Sign up and get started</h2>

              <a class="fbl" href="#">
                <img src="img/sign_in_with_facebook.png" />
              </a>
              <div class="or">
                <span>..or.. <a href="#" class="create">create an account directly</a>.</span>
              </div>
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
            <p class="exp"><a class="su" href="#explore">Explore some features of the site</a></p>
            <p class="nothanks"><a href="#">No thanks, take me to the story!</a></p>
          </div>
        </div>

        <div style="display:none">
          <div id="explore">
            <h2>Features</h2>
            <p><img class='e1' src="img/home.png">Use the Victors Experience website as a powerful information pipeline to stay informed about work the College is doing to address the world's grand challenges.</p>
            <p><img class='e2' src="img/quiz.png">Customize the content by telling the site what topics you care most about and it will remember to show you related stories and videos.</p>
            <p><img class='e3' src="img/favorites.png">Create a favorites list and share stories you like on Facebook, Twitter, Pinterest and other social media sites.</p>
            <p><img class='e4' src="img/explore.png">Use Explore to see all the stories and videos about work being done across the College by students, faculty and alumni.</p>
            <p><a class="cta" href="#signup">Sign up to get started</a></p>
          </div>
        </div>
		
		<!-- load data from php into js -->
        <script type="text/javascript">
            var usercats = "<?= $cats?>", userinds = "<?= $inds?>", sidebar = "<?= removeBreaks(nl2br($sidebar)) ?>", mailimg = <? if (!$mailimg) echo "\"mail.jpg\""; else echo "\"" . $mailimg . "\"";?>, firstmsg = "<?= $firstmsg?>", firstmsgfrom = "<?= $firstmsgfrom?>", userid = "<?= $user?>", username="<?= $name?>", favorites="<?= $favorites?>", msgslice="<?= $msgslice?>", firsttime="<?= $firsttime?>", preview="<?= $preview?>";
	        
			if(favorites != ''){
				fav_array = favorites.split(',');
			}else{
				fav_array = Array();
			}
		
        </script>
		
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
        <script src="js/jquery.slides.min.js"></script>
        <script src="./js/colorbox-master/jquery.colorbox-min.js"></script>
        <script src="js/hashchange.js"></script>
        <script src="js/masonry.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/imagesloaded.js"></script>
        <script src="js/mainexplore.js?v=131126"></script>

        <script>
            $("a.su").on("click", function(){
                $("#signup .nothanks").hide();
                $.colorbox({inline:true, width:"75%", height:"500px", href:"#signup"});
            });
        </script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-45546416-1', 'umich.edu');
          ga('send', 'pageview');

        </script>
    </body>

    
    <script type="text/javascript">
    </script>
</html>
