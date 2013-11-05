<?
/**
 * Process.php
 * 
 * The Process class is meant to simplify the task of processing
 * user submitted forms, redirecting the user to the correct
 * pages if errors are found, or if form is successful, either
 * way. Also handles the logout procedure.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 19, 2004
 */

// print_r($_REQUEST);
// exit;

error_reporting(E_ALL);

include("include/session.php");

class Process
{

  // echo "YES";

  // exit;
   /* Class constructor */
   function Process(){
      global $session;

      /* User submitted login form */
      if(isset($_POST['sublogin'])){
         $this->procLogin();
      }
      /* User submitted registration form */
      else if(isset($_POST['subjoin'])){
         $this->procRegister();
      }
      /* User submitted forgot password form */
      else if(isset($_POST['subforgot'])){
         $this->procForgotPass();
      }
      /* User submitted edit account form */
      else if(isset($_POST['subedit'])){
         $this->procEditAccount();
      }
      /**
       * The only other reason user should be directed here
       * is if he wants to logout, which means user is
       * logged in currently.
       */
      else if($session->logged_in){
         $this->procLogout();
      }
      /**
       * Should not get here, which means user is viewing this page
       * by mistake and therefore is redirected.
       */
       else{
          header("Location: index.php");
       }
   }

   /**
    * procLogin - Processes the user submitted login form, if errors
    * are found, the user is redirected to correct the information,
    * if not, the user is effectively logged in to the system.
    */
   function procLogin(){
      global $session, $form;
      /* Login attempt */
      $retval = $session->login($_POST['user'], $_POST['pass'], isset($_POST['remember']));
      
      /* Login successful */
      if($retval){
         //header("Location: ".$session->referrer);
		 header("location: index.php");
         // exit;
      }
      /* Login failed */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procLogout - Simply attempts to log the user out of the system
    * given that there is no logout form to process.
    */
   function procLogout(){
      global $session;
      $retval = $session->logout();
      header("Location: index.php");
   }
   
   /**
    * procRegister - Processes the user submitted registration form,
    * if errors are found, the user is redirected to correct the
    * information, if not, the user is effectively registered with
    * the system and an email is (optionally) sent to the newly
    * created user.
    */
   function procRegister(){
      // echo "TEST";
      global $session, $form;
      /* Convert username to all lowercase (by option) */
      if(ALL_LOWERCASE){
         $_POST['first'] = strtolower($_POST['first']);
         $_POST['last'] = strtolower($_POST['last']);
      }
      /* Registration attempt */

      if ($_POST["link"] > 0) $retval = $session->register($_POST['first'], $_POST['last'], $_POST['pass'], $_POST['email'], $_POST['link']);
      else $retval = $session->register($_POST['first'], $_POST['last'], $_POST['pass'], $_POST['email']);
      
      /* Registration Successful */
      if($retval == 0){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = true;
         // echo "Successful!";
		 
		 //send confirmation email
		
		 $message = "Dear ".$_POST['first']." ".$_POST['last'].". <br /><br />Thank you for the registration on the Michigan Engineering Campaign platform. Please <a href='http://engcomm.engin.umich.edu/campaign'>log in</a> to view more details.<br /><br />Thanks!";

		 $subject = "Welcome to Michigan Engineering Campaign";
		 
		 $headers = "From: Michigan Engineering <engcom@umich.edu> \n";
		 $headers .= "Reply-To: <engcom@umich.edu> \n";
		 $headers = "MIME-Version: 1.0" . "\n";
		 $headers .= "Content-type:text/html;charset=iso-8859-1" . "\n";
		 
		 mail($_POST['email'],$subject,$message,$headers);
		 
         header("Location: ".$session->referrer);
      }
      /* Error found with form */
      else if($retval == 1){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         // echo "Error: ";
         // print_r($form->getErrorArray());
         header("Location: ".$session->referrer);
      }
      /* Registration attempt failed */
      else if($retval == 2){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = false;
         // echo "Registration failed.";
         header("Location: ".$session->referrer);
      }
      // print_r($session);
      // exit;
   }
   
   /**
    * procForgotPass - Validates the given username then if
    * everything is fine, a new password is generated and
    * emailed to the address the user gave on sign up.
    */
   function procForgotPass(){
      global $database, $session, $mailer, $form;
      /* Username error checking */
      $subemail = $_POST['email'];
      $field = "email";  //Use field name for username
      if(!$subemail || strlen($subemail = trim($subemail)) == 0){
         $form->setError($field, "* Email not entered<br>");
      }
      else{
         /* Make sure username is in database */
         $subemail = stripslashes($subemail);
         // if(strlen($subemail) < 5 || !eregi("^([0-9a-z])+$", $subemail) || (!$database->emailTaken($subemail))){
         //    $form->setError($field, "* Email address does not exist<br>");
         // }
         
         if(strlen($subemail) < 5 || (!$database->emailTaken($subemail))){
            $form->setError($field, "* Email address does not exist<br>");
         }
      }
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
      }
      /* Generate new password and email it to user */
      else{
         /* Generate new password */
         $newpass = $session->generateRandStr(8);
         
         /* Get email of user */
         $usrinf = $database->getUserInfo($subemail);
         $email  = $usrinf['email'];
         
         /* Attempt to send the email with new password */
         if($mailer->sendNewPass($subemail,$email,$newpass)){
            /* Email sent, update database */
            $database->updateUserField($subemail, "password", md5($newpass));
            $_SESSION['forgotpass'] = true;
         }
         /* Email failure, do not change password */
         else{
            $_SESSION['forgotpass'] = false;
         }
      }
      // echo $session->referrer; exit;
      header("Location: ".$session->referrer);
   }
   
   /**
    * procEditAccount - Attempts to edit the user's account
    * information, including the password, which must be verified
    * before a change is made.
    */
   function procEditAccount(){
      global $session, $form;
      /* Account edit attempt */
      $retval = $session->editAccount($_POST['curpass'], $_POST['newpass'], $_POST['email']);

      /* Account edit successful */
      if($retval){
         $_SESSION['useredit'] = true;
         header("Location: ".$session->referrer);
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }
};

/* Initialize process */
$process = new Process;