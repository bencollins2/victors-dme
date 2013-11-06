<body class="slices">

    <div id="fb-root"></div>

        <header class="sticky" id="nav">
            <ul>
                <li class="home"><a href="http://engin.umich.edu"><img src="img/CoE-horiz-rev.png" alt="logo"/></a></li>
            </ul>
            <!-- <div id="go-back">Go back to full screen</div>
            <div id="switch">
                <span>Explore</span>
                <div class="square">
                    <p class="one"></p>
                    <p class="two"></p>
                    <p class="three"></p>
                    <p class="four"></p>
                </div>
            </div> -->
        </header>

    	
        <div class="login">
        	<div class="vfm">Victors for <span>Michigan</span></div>
        	<a class="fbl" href="#"><img src="img/fblogin.png" /></a>
        </div>

        <div class="or"><a href="#">Or log into our system directly</a></div>
            <div class="custom" style="display:none">
                <?
                    if($form->num_errors > 0){
                       echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
                    }
                ?>
                <form action="process.php" method="POST">
                <table align="left" border="0" cellspacing="0" cellpadding="3">
                <tr><td>Email Address:</td><td><input type="text" name="user" maxlength="30" value="<? echo $form->value("user"); ?>"></td><td><? echo $form->error("user"); ?></td></tr>
                <tr><td>Password:</td><td><input type="password" name="pass" maxlength="30" value="<? echo $form->value("pass"); ?>"></td><td><? echo $form->error("pass"); ?></td></tr>
                <tr><td colspan="2" align="left"><input type="checkbox" name="remember" <? if($form->value("remember") != ""){ echo "checked"; } ?>>
                <font size="2">Remember me next time &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="hidden" name="sublogin" value="1">
                <input type="submit" value="Login"></td></tr>
                <tr><td colspan="2" align="left"><font size="2"><a class="forgot" href="index.php?forgotpass=1">Forgot Password?</a></font></td><td align="right"></td></tr>
                <tr><td colspan="2" align="left">Not registered? <a href="index.php?register=1">Sign-Up!</a></td></tr>
                </table>
                </form>
            </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
        <script src="js/masonry.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/fb.js"></script>
        <script type="text/javascript">
			$(document).ready(function(){
				$(".fbl").on("click",function(e){
					e.preventDefault();
					FB.login(function(response) {
						window.location.assign("index.php");
					}, {scope: 'user_status'});;
				});

                if (<?= $open?> == 1) {
                    $(".custom").show();
                }

                $(".or a").on("click", function(e){
                    e.preventDefault();
                    $(".custom").slideToggle();
                })
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