<?
/**
 * Register.php
 * 
 * Displays the registration form if the user needs to sign-up,
 * or lets the user know, if he's already logged in, that he
 * can't register another name.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 19, 2004
 */
require_once("include/session.php");
$userInfo = $session->linkInfo($_GET["link"]);
$linked = false;
if ($userInfo["first"] != "") {
  $linked = true;
  $firstName = $userInfo["first"];



}
?>

<body>
<header class="sticky" id="nav">
    <ul>
        <li class="home"><a href="http://engin.umich.edu">Michigan Engineering</a></li>
        <li class="login"><a href="index.php?login=1">Login</a></li>
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
<div class="upper">

<?

/**
 * The user is already logged in, not allowed to register.
 */
if($session->logged_in){

   echo "<h1>Registered</h1>";
   echo "<p>We're sorry <b>$session->username</b>, but you've already registered. "
       ."<a href=\"main.php\">Main</a>.</p>";
}
/**
 * The user has submitted the registration form and the
 * results have been processed.
 */
else if(isset($_SESSION['regsuccess'])){
   /* Registration was successful */
   if($_SESSION['regsuccess']){
      echo "<h1>Registered!</h1>";
      echo "<p>Thank you <b>".$_SESSION['reguname']."</b>, your information has been added to the database, "
          ."you may now <a href=\"index.php?login=1\">log in</a>.</p>";
   }
   /* Registration failed */
   else{
      echo "<h1>Registration Failed</h1>";
      echo "<p>We're sorry, but an error has occurred and your registration for the username <b>".$_SESSION['reguname']."</b>, "
          ."could not be completed.<br>Please try again at a later time.</p>";
   }
   unset($_SESSION['regsuccess']);
   unset($_SESSION['reguname']);
}
/**
 * The user has not filled out the registration form yet.
 * Below is the page with the sign-up form, the names
 * of the input fields are important and should not
 * be changed.
 */
else{
?>

<? if ($linked) { ?> 

  <h1>Welcome, <?= $firstName?>.</h1>

<? } else { ?> 

  <h1>Register</h1>

<? } ?>

<? if ($regerror ==1 ) { ?>

  <h3 class="error">This user is already in the system.</h3>

<? } ?>

  <?
  if($form->num_errors > 0){
     echo "<span><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></span>";
  }
  ?>
</div>
<div class="bg two"></div>
<div class="bg one"/></div>
<div class="semitrans"></div>
<div id='register'>
    <div class="left">
      <div class="vfm">Victors for <span>Michigan</span></div>
      <a class="fbl" href="#">
        <?= ($linked) ? "<h2>We've selected some content we think you'll like.</h2>" : ""?>
        <img src="img/signupwithfb_220.png" />
      </a>
      <div class="or">
        <span>..or if you'd prefer, you can <a href="#" class="create">create an account directly</a>.</span>
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
              $lnk = (int) $_GET["link"];
            ?> 
            <input type="hidden" name="link" value="<?= $lnk?>">
            <? }?>
            <input type="submit" value="Join!"></span></div>
          </div>
        </form>
      </div>
      <div class="info">
        <h2>Explore the site</h2>
        <p>This customizable "Victors Experience" website will allow you to filter through all the amazing work being done by Michigan Engineers on campus and around the world. Use the navigation above (or to the left, depending on where you put it) to get started and create your own custom experience.</p>
      </div>

    </div>
    <div class="right">
      <p>Discover why you should be a victor for engineering</p>
      <a href="http://www.youtube.com/embed/g5J9tcRuX9U?rel=0&amp;wmode=transparent" class="play youtube cboxElement" alt="Watch the video">Watch the video.</a>
      <a href="http://engcomm.engin.umich.edu/campaign/article.php?id=115" class="learnmore" alt="Why be a victor">Learn more about the campaign</a>
    </div>
  <?
  }
  ?>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
<script src="js/masonry.js"></script>
<script src="./js/colorbox-master/jquery.colorbox-min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/fb.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$(".fbl").on("click",function(e){
  e.preventDefault();
  FB.login(function(response) {
      window.location.assign("index.php?register=1&link=<?= $_GET['link']?>&add=1");
    }, {scope: 'read_stream'});;
  });

        if (<?= $open?> == 1) {
            $(".custom").show();
        }

        $(".or a").on("click", function(e){
            e.preventDefault();
            $(".custom").slideToggle();
        })

        $(".create").on("click", function(e){
          e.preventDefault();
          $(this).parent().parent().parent().find(".createacct").fadeToggle(500);
        });

        $("#register a").on("mouseover", function(e){
          e.preventDefault();
          $(".bg.one").stop().fadeOut(400);
        });
        $("#register a").on("mouseleave", function(e){
          e.preventDefault();
          $(".bg.one").stop().fadeIn(400);
        });
        $(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390, opacity:.85});
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
