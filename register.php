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
if ($_REQUEST["link"]>0) $linked = true;

?>

<body>
<header class="sticky" id="nav">
    <ul>
        <li class="home"><a href="http://engin.umich.edu"><img src="img/CoE-horiz-rev.png" alt="logo"/></a></li>

        <? if (!$_REQUEST["link"]) {?>

        <li class="login"><a href="index.php?login=1">Login</a></li>
         <? }?>
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
<div id='register'>
  <div class="ve">Victors Experience</div>
  <a class="cta" href="#signup">Sign Up Now</a>
  <p class="reg">
    Customize this website to filter through the amazing work being done by Michigan Engineers, who are stepping up to tackle the world's greatest problems.
  </p>
  <p class="explore"><a href="#explore" class="explore">Preview the site &#8594;</a></p>
  <img class="comp" src="./img/computer.png" />
  <p class="what"><a href="javascript:document.getElementById('moreinfo').scrollIntoView();">What is a Victor for Michigan?&nbsp;&nbsp;&nbsp;&#8595;</a></p>


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

</div>
<div id="moreinfo">

  <div class="vfm">Victors<br/>for Michigan</div>
  <p class="reg">
    The world is facing challenges, the likes of which it has never faced before. Problems that only engineers can solve. Because we are Michigan Engineering, <strong>we are stepping up</strong> to tackle these great challenges and make breakthrough solutions.
  </p>
  <p class="play"><a href="http://www.youtube.com/embed/g5J9tcRuX9U?rel=0&amp;wmode=transparent" class="youtube cboxElement" alt="Watch the video"><!--img src="./img/play_40.png" /-->&raquo; Watch the video</a></p>
      <p class="learnmore"><a href="http://victors.engin.umich.edu/article.php?id=115" class="" alt="Why be a victor"><!--img src="./img/qmark_40.png" /-->&raquo; Learn more about the campaign</a></p>
  <p><a class="cta" href="#signup">Sign up to get started</a></p>
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
<script src="js/fb.js"></script>
<script type="text/javascript">
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

$(document).ready(function(){


var position;
// $(document).on('cbox_open', function() {
//     $('body').css({ overflow: 'hidden' });
// });
// $(document).on('cbox_close', function() {
//     $('body').css({ overflow: '' });
// });

$(".fbl").on("click",function(e){
  e.preventDefault();
  FB.login(function(response) {
    
    <? if ($linked) { ?>
      window.location.assign("index.php?register=1&link=<?= $_GET['link']?>&add=1");
    <? } else { ?>
      window.location.assign("index.php");
      <? } ?>

    }, {scope: 'user_status,email'});;
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
          $("div.info").fadeToggle(500);
        });

        $(".learnmore a, .play a").on("mouseover", function(e){
          e.preventDefault();
          $(".bg.one").stop().fadeOut(400);
        });
        $(".learnmore a, .play a").on("mouseleave", function(e){
          e.preventDefault();
          $(".bg.one").stop().fadeIn(400);
        });
        $(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390, opacity:.85});

        $("a.cta").colorbox({inline:true, width:"75%"});

        $("a.explore").colorbox({inline:true, width:"75%", height:"500px"});


        if (getUrlVars()["err"] == 1) $(".create").trigger("click");
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
