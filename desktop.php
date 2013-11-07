<body class="slices">

    <div id="loading">
        <img src="img/loader.gif" />

    </div>

	<div id="tutorial">
		<div class="close"><i class="fa fa-times"></i></div>
        <div class="nav"><a href="#" class="prev">Previous</a><a href="#" class="next">Next</a></div>
		<img class="slide1" src="img/tutorial/slide1.png">
		<img class="slide2" src="img/tutorial/slide2.png">
		<img class="slide3" src="img/tutorial/slide3.png">
		<img class="slide4" src="img/tutorial/slide4.png">
		<div class="bullet"><ul><li></li><li></li><li></li><li></li></ul></div>
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
			<div class="favtext">
                <h2>Welcome <?= $first_name?></h2>
                <span>Victor of engineering</span>
                <p class="msg">Hey, here presents your favorite features.</p>
                <p><a <?= $logout?>>(Log Out)</a></p>
			</div>
            <div class="filter">
                <h2>Filter by category</h2>
                <span>Select multiple categories below</span>
                <form>

                    <div class="dropdown">
                        <h3>Transportation Innovations</h3>
                        <div class="seemore">
                            <a href="#" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="vautonomous"> Autonomous Vehicles<br />
                            <input type="checkbox" value="invehicletech"> In-Vehicle Technology<br />
                            <input type="checkbox" value="fuelefficiency"> Vehicle Efficiency<br />
                            <input type="checkbox" value="vehiclesafety"> Vehicle Safety<br />
                            <input type="checkbox" value="batteries"> Power Sources<br />
                        </div>
                    </div>

                    <div class="dropdown">
                        <h3>Economics & Entrepreneurship</h3>
                        <div class="seemore">
							<a href="#" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="studentstart,facultystart"> Startups<br />
                            <input type="checkbox" value="mcubed"> High-Risk Research<br />
                            <input type="checkbox" value="cfe,me"> Entrepreneurial Ecosystem<br />
                            <input type="checkbox" value="economy"> Economic Impact<br />
                        </div>
                    </div>

                    <div class="dropdown">
                        <h3>Wolverine Experience</h3>
                        <div class="seemore">
							<a href="#" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="hoexperience"> Hands-On Engineering<br />
                            <input type="checkbox" value="extracurr,highlevstudentprojects"> Academic Leaders & Best<br />
                            <input type="checkbox" value="commoutreach"> Wolverines Around the World<br />
                            <input type="checkbox" value="classfuture"> Innovations at Michigan<br />
                            <input type="checkbox" value="nostalgia,lifeinaa"> Victor Stories<br />
                            <input type="checkbox" value="lifeinaa"> Life in Ann Arbor<br />
                        </div>
                    </div>

                    <div class="dropdown">
                        <h3>Global Resources</h3>
                        <div class="seemore">
							<a href="#" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="cleanwater,watershortage,waterpurification,hydropower"> Water<br />
                            <input type="checkbox" value="sustain"> Sustainability<br />
                            <input type="checkbox" value="eefficiency"> Energy Efficiency<br />
                            <input type="checkbox" value="alternativee"> Alternative Energy<br />
                            <input type="checkbox" value="environment"> Environment<br />
                        </div>
                    </div>

                    <div class="dropdown">
                        <h3>Revolutionary Materials</h3>
                        <div class="seemore">
							<a href="#" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="nanotech"> Nanotechnology<br />
                            <input type="checkbox" value="structures"> Structures<br />
                            <input type="checkbox" value="healthsafety"> Health & Safety<br />
                            <input type="checkbox" value="eenvironment"> Energy & Environment<br />
                            <input type="checkbox" value="electronics"> Electronics<br />
                        </div>
                    </div>

                    <div class="dropdown">
                        <h3>Healthcare</h3>
                        <div class="seemore">
							<a href="#" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="disease"> Disease Research<br />
                            <input type="checkbox" value="diagnostics"> Diagnostics<br />
                            <input type="checkbox" value="meddev"> Medical Devices<br />
                            <input type="checkbox" value="treatments"> Treatments<br />
                            <input type="checkbox" value="globalhealth"> Global Health<br />
                        </div>
                    </div>

                    <div class="dropdown">
                        <h3>Securing our Future</h3>
                        <div class="seemore">
							<a href="#" class="selectall" status="true">Select all</a>
                            <input type="checkbox" value="weapons"> Weapons Detection<br />
                            <input type="checkbox" value="cybersec"> CyberSecurity<br />
                            <input type="checkbox" value="nuclear"> Nuclear Non-Proliferation<br />
                            <input type="checkbox" value="natsec"> National Security<br />
                            <input type="checkbox" value="infrastructure"> Infrastructure<br />
                            <input type="checkbox" value="natdis"> Natural Disasters<br />
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
		
		<!-- load data from php into js -->
        <script type="text/javascript">
            var usercats = "<?= $cats?>", userinds = "<?= $inds?>", sidebar = "<?= $sidebar ?>", mailimg = <? if (!$mailimg) echo "\"mail.jpg\""; else echo "\"" . $mailimg . "\"";?>, firstmsg = "<?= $firstmsg?>", firstmsgfrom = "<?= $firstmsgfrom?>", userid = "<?= $user?>", username="<?= $name?>", favorites="<?= $favorites?>", msgslice="<?= $msgslice?>", firsttime="<?= $firsttime?>";
	        // var usercats = "<?= $cats?>", userinds = "<?= $inds?>", sidebar = "<?= $sidebar ?>", mailimg = <? if (!$mailimg) echo "\"mail.jpg\""; else echo "\"" . $mailimg . "\"";?>, firstmsg = "<?= $firstmsg?>", userid = "<?= $user?>", username="<?= $name?>", favorites="<?= $favorites?>";
			if(favorites != ''){
				fav_array = favorites.split(',');
			}else{
				fav_array = Array();
			}
		
        </script>
		
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
        <script src="js/jquery.slides.min.js"></script>
        <script src="js/hashchange.js"></script>
        <script src="js/masonry.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/tinymce/tinymce.min.js"></script>
        <script src="js/imagesloaded.js"></script>
        <script src="js/main.js"></script>
        <script src="js/fb.js"></script>

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
