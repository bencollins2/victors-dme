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
?>


<header class="sticky" id="nav">
    <ul>
        <li class="home"><a href="http://engin.umich.edu">Michigan Engineering</a></li>
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


<?
/**
 * The user is already logged in, not allowed to register.
 */
echo "<div id='register'>";
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


  <h1>Register</h1>
  <?
  if($form->num_errors > 0){
     echo "<span><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></span>";
  }
  ?>
  <form action="process.php" method="POST">
    <div class="table">
      <div><span>Email:</span><span><input type="text" name="email" maxlength="50" value="<? echo $form->value("email"); ?>"></span><span><? echo $form->error("email"); ?></span></div>
      <div><span>Password:</span><span><input type="password" name="pass" maxlength="30" value="<? echo $form->value("pass"); ?>"></span><span><? echo $form->error("pass"); ?></span></div>
      <div><span>First Name:</span><span><input type="text" name="first" maxlength="30" value="<? echo $form->value("first"); ?>"></span><span><? echo $form->error("first"); ?></span></div>
      <div><span>Last Name:</span><span><input type="text" name="last" maxlength="30" value="<? echo $form->value("last"); ?>"></span><span><? echo $form->error("last"); ?></span></div>
      <div><span colspan="2" align="right">
      <input type="hidden" name="subjoin" value="1">
      <input type="submit" value="Join!"></span></div>
      <div><span colspan="2" align="left"><a href="main.php">Back to Main</a></span></div>
    </div>
  </form>
<?
}
?>
</div>
