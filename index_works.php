<?php
    // session_start();
    // ini_set('display_errors','On');
    // error_reporting(E_ALL);

    include("Mobile_Detect.php");
    require_once("include/session.php");
    ///////////////////
    // Device stuff  //
    ///////////////////
    $detect = new Mobile_Detect();
    $device = "";
    if ( $detect->isMobile() ) {
        if($detect->isiOS()){
            // iPhone!
            $device = "iPhone";
        }
        if($detect->isAndroidOS()){
            // Android Phone!
            $device = "androidPhone";
        }
    }
    if( $detect->isTablet() ){
        if($detect->isiOS()){
            // iPad!            
            $device = "iPad";
        }
        if($detect->isAndroidOS()){
            // Android tablet!
            $device = "androidTablet";
        }
    }
    if ($device == "") {
            // Desktop device
            // echo "<script type='text/javascript'>alert('Not mobile');</script>";
            $device = "desktop";
    }

    if ($device == "desktop") {
            $normalize = "css/normalize.css";
            $main = "css/main.css";
            $campstyle = "css/campaign.css";
            $body = "desktop.php";
    }

    if ($device == "iPhone" || $device == "androidPhone" || $_GET["t"] == "mobile") {
            $normalize = "css/normalize_mobile.css";
            $main = "css/main_mobile.css";
            $campstyle = "css/camp_mobile.css";
            $body = "mobile.php";
    }
    else if ($device == "iPad" || $device == "androidTablet" || $_GET["t"] == "tablet") {
            $normalize = "css/normalize.css";
            $main = "css/main_mobile.css";
            $campstyle = "css/campaign.css";
            $body = "desktop.php";
    }
    /////////////////////
    // Facebook stuff  //
    /////////////////////
    require("facebook.php");
    $config = array();
    $config['appId'] = '345879355542024';
    $config['secret'] = '584301cc6805d51704746e45d0459d19';

    $facebook = new Facebook($config);
    // See if there is a user from a cookie
    $user = $facebook->getUser();

    echo "<!--".$user."-->";


    ///////////////////////////////////////////////////////////
    // If there's no FB user AND no custom user logged in..  //
    ///////////////////////////////////////////////////////////
    if ($user == 0 && !$session->logged_in) {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/login.css";
        $body = "login.php";
    }

    ////////////////////////////////
    // If there is a user and...  //
    ////////////////////////////////
    else {

        ///////////////////////////////////////////////////////////////
        // If there is NO facebook user, but there is a custom user  //
        ///////////////////////////////////////////////////////////////
        if ($user == 0 && $session->logged_in) {
            $user = $session->userinfo[0];
            $type = "custom";
        }

        //////////////////////////////////
        // If there IS a facebook user  //
        //////////////////////////////////
        else {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('/me');
                $name = $facebook->api('/me')["name"];
                $first_name = $facebook->api('/me')["first_name"];
                $email = $facebook->api('/me')["email"];
                $type = "facebook";
            } catch (FacebookApiException $e) {
                // echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
                // $user = null;
            }
        }

        // From this point out, we need $user to definitively reflect our user,
        // whether or not that user is on facebook. At this point it should not matter.
        // 
        // If they were a custom user, it will find them. The whole $u thing is
        // for automatically generating facebook users in our DB, but our custom
        // users should have manually signed up (thus creating the ID).
        include("../db_campaign.php");
        $q = "SELECT * FROM `users` WHERE `id` LIKE '$user' LIMIT 0,1;";
        $r = mysql_query($q);
        $u=0;
        $c=0;

        while($line = mysql_fetch_array($r)){
            $u++;
            if ($line["categories"] != "") {
                $c++;
            }
            $cats = $line["categories"];
            $inds = $line["individuals"];
            $sidebar = $line["sidebar"];
        }

        /////////////////////////////////////////////////////////////////
        // If they're logged into facebook, but we don't have their ID //
        /////////////////////////////////////////////////////////////////
        if ($u == 0 && $user > 0) {
            $q = "INSERT INTO users (id,name,email,categories,individuals,premium) VALUES ('$user','$name','$email','','','0');";
            $r = mysql_query($q) or die("Failure: ". $q);
        }
        // They have a user account but no categories
        if ($u > 0 && $c == 0) {
            $normalize = "css/normalize.css";
            $main = "css/main.css";
            $campstyle = "css/quiz.css";
            $body = "quiz.php";
        }

    } // End "if there's a user"

    if ($_GET["t"] == "desktop") {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/campaign.css";
        $body = "desktop.php";
    }

    if ($_GET["quiz"] == "1") {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/quiz.css";
        $body = "quiz.php";
    }

    if ($_GET["login"] == "1") {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/login.css";
        $body = "main.php";
    }

    if ($_GET["forgotpass"] == "1") {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/login.css";
        $body = "forgotpass.php";
    }

    if ($_GET["register"] == "1") {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/login.css";
        $body = "register.php";
    }



    echo "<!--$body-->";


?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Campaign</title>

        <meta property="og:title" content="Campaign"/>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="http://engcomm.engin.umich.edu/campaign"/>
        <meta property="og:image" content="http://www.engin.umich.edu/college/about/news/dme/fracktopia/img/large/one.jpg"/>
        <meta property="og:site_name" content="Campaign"/>
        <meta property="fb:admins" content="2206222" />
        <meta property="og:description"
          content=""/>
        <meta name="description" content="Campaign">
        <meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" id="vp" name="viewport">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="<?= $normalize?>">
        <link rel="stylesheet" href="<?= $main?>">
        <link rel="stylesheet" href="css/dme.css">
        <link rel="stylesheet" href="<?= $campstyle?>">
        
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
		<script>document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';</script>
        <script type="text/javascript" src="//use.typekit.net/zsu8wmg.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
    </head>
    <? include($body); ?>
    <script type="text/javascript">

    </script>
</html>
