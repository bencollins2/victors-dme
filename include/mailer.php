<? 
/**
 * Mailer.php
 *
 * The Mailer class is meant to simplify the task of sending
 * emails to users. Note: this email system will not work
 * if your server is not setup to send mail.
 *
 * If you are running Windows and want a mail server, check
 * out this website to see a list of freeware programs:
 * <http://www.snapfiles.com/freeware/server/fwmailserver.html>
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 19, 2004
 */
 
class Mailer
{
   /**
    * sendWelcome - Sends a welcome message to the newly
    * registered user, also supplying the username and
    * password.
    */
   function sendWelcome($user, $email){
      $subject = "Victors for Engineering - Welcome!";
      $body = $user.",<br /><br />"
             ."Welcome! You've just registered at Victors for Engineering "
             ."with the following information:<br /><br />"
             ."Username: ".$user."<br /><br />"
			 ."Please <a href='http://engcomm.engin.umich.edu/campaign'>log in</a> to view more details.<br /><br />"
             ."If you ever lose or forget your password, a new "
             ."password will be generated for you and sent to this "
             ."email address, if you would like to change your "
             ."email address you can do so by going to the "
             ."My Account page after signing in.<br /><br />"
             ."- Victors for Engineering";
	  
	  $headers  = 'MIME-Version: 1.0' . "\r\n";
	  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	  $headers .= "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR."> \n";
	  
      return mail($email,$subject,$body,$headers);
   }
   
   /**
    * sendNewPass - Sends the newly generated password
    * to the user's email address that was specified at
    * sign-up.
    */
   function sendNewPass($user, $email, $pass){
      $from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
      $subject = "Victors for Engineering - Your new password";
      $body = "We've generated a new password for you at your "
             ."request, you can use this new password with your "
             ."username to log in to Victors for Engineering.\n\n"
             ."Email: ".$email."\n"
             ."New Password: ".$pass."\n\n"
             ."It is recommended that you change your password "
             ."to something that is easier to remember, which "
             ."can be done by going to the My Account page "
             ."after signing in.\n\n"
             ."- Victors for Engineering";
             
      return mail($email,$subject,$body,$from);
   }
};

/* Initialize mailer object */
$mailer = new Mailer;