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

    $facebook = new Facebook($config);
    // See if there is a user from a cookie
    $user = $facebook->getUser();



    //////////////////////////////////////
    // If there's no logged in user...  //
    //////////////////////////////////////
    if ($user == 0 && !$session->logged_in) {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/register.css";
        $body = "register.php";
    }

    ////////////////////////////////
    // If there is a user and...  //
    ////////////////////////////////
    else {

        ///////////////////////////////////////////////////////////////
        // ...there is NO facebook user, but there is a custom user..  //
        ///////////////////////////////////////////////////////////////
        if ($user == 0 && $session->logged_in) {
            $user = $session->userinfo[0];
            $type = "custom";
            $logout = "href=\"process.php\"";
            echo "<!--".$user."-->";
        }

        ////////////////////////////////////
        // ..or there IS a facebook user  //
        ////////////////////////////////////
        else {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('/me');
                $name = $user_profile["name"];
                $first_name = $user_profile["first_name"];
                $last_name = $user_profile["last_name"];
                $email = $user_profile["email"];
                // echo "Facebook: ";
                // print_r($name);
                $type = "facebook";
                echo "<!--".$user."-->";
            } catch (FacebookApiException $e) {
                // echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
                // $user = null;
            }
            $logout = "onClick=\"logoutFb()\" href=\"#\"";
        }

        // From this point out, we need $user to definitively reflect our user,
        // whether or not that user is on facebook. At this point it should not matter.

        // If they were a custom user, it will find them. The whole $u thing is
        // for automatically generating facebook users in our DB, but our custom
        // users should have manually signed up (thus creating the ID).
        include("../db_campaign.php");
        $q = "SELECT * FROM `users` WHERE `id` LIKE '$user' LIMIT 0,1;";
        $r = mysql_query($q);
        $u=0;
        $c=0;
        $lnk = (int)$_GET['link'];

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

        // $qq = "SELECT * FROM `adminusers` WHERE "


        // If they're logged into facebook, and there's a link ID 

        if ($lnk > 0 && $user > 0 && $_GET['add'] == 1) {
            $q = "SELECT * FROM `users` WHERE `id` = '$lnk' LIMIT 0,1;";
            $r = mysql_query($q);
            $l = mysql_fetch_array($r);

            $q = "INSERT INTO users (id,name,first,last,email,categories,individuals,sidebar,mailimg,fromlink,showasnew) VALUES ('$user','$name','$first_name','$last_name','$email','".$l['categories']."','".$l['individuals']."','".$l['sidebar']."','".$l['mailimg']."','".$lnk."','1');";
            $r = mysql_query($q);
            $lastid = mysql_insert_id();
            if ($lastid > 0) $_GET['register'] = 0;
            else $regerror = 1;

            $q = "UPDATE `users` SET `todelete` = '1' WHERE `id` = '$lnk'";
            $r = mysql_query($q) or die("Failure to update temp user.");

            $q = "SELECT * FROM `adminusers` WHERE `uids` LIKE '%$lnk%' LIMIT 0,1;";
            $r = mysql_query($q) or die("Failure to select current admin user IDs.");
            $l = mysql_fetch_array($r);
            $aid = $l["id"];
            $uids = $l["uids"];

            $newuids = preg_replace("/(,?)".$lnk."/", "", $uids);
            $newuids = $user.",".$newuids;

            $q = "UPDATE `adminusers` SET `uids` = '$newuids' WHERE `id` = '$aid'";
            $r = mysql_query($q) or die("Couldn't update admin record.");

        }

        /////////////////////////////////////////////////////////////////
        // If they're logged into facebook, but we don't have their ID //
        /////////////////////////////////////////////////////////////////
        else if ($u == 0 && $user > 0 && $_GET["add"]==1 && $lnk == 0) {
            $q = "INSERT INTO users (id,name,first,last,email,categories,individuals,premium) VALUES ('$user','$name','$first_name','$last_name','$email','','','0');";
            $r = mysql_query($q);
            $lastid = mysql_insert_id();
            if ($lastid > 0) $_GET['register'] = 0;
            else $regerror = 1;
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

    $open = 0;

    if ($_GET["login"] == "1") {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/login.css";
        $body = "login.php";
        $open = 1;
    }

    if ($_GET["forgotpass"] == "1") {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/login.css";
        $body = "forgotpass.php";
    }

    if ($_GET["newpass"] == "1") {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/login.css";
        $body = "newpass.php";
    }

    if ($_GET["register"] == "1") {
        $normalize = "css/normalize.css";
        $main = "css/main.css";
        $campstyle = "css/register.css";
        $body = "register.php";
    }

    ///////////////////////////////////////////
    // Stuff to pull most recent message...  //
    ///////////////////////////////////////////
    $q = 'SELECT m.message, CONCAT(a.first, " ", a.last) as name, m.timestamp as ts FROM messages as m INNER JOIN adminusers as a ON SUBSTR(m.from, 2) = a.id WHERE SUBSTR(`to`, 2) LIKE '.$user.' ORDER BY ts DESC LIMIT 0,1';
    $r = mysql_query($q) OR DIE("Sorry, couldn't select recent message.");
    $l = mysql_fetch_array($r);
    $firstmsgfrom = $l["name"];
    $firstmsg = strip_tags(preg_replace('/\n/i', '', $l["message"]));
    $firstmsg = implode(' ', array_slice(explode(' ', $firstmsg), 0, 10));
    $firstmsg .= "...";

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

    <? include($body); ?>
    
    <script type="text/javascript">

    </script>
</html>
