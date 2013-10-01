////////////////////
// Facebook stuff //
////////////////////

window.fbAsyncInit = function() {
      FB.init({
        appId      : '1381727122059014', // App ID
        // channelUrl : 'http://localhost:8888/htdocs/DME/campaign/channel.php', // Channel File
        // status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        oauth	   : true,
        xfbml      : true  // parse XFBML
      });

      // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
      // for any authentication related change, such as login, logout or session refresh. This means that
      // whenever someone who was previously logged out tries to log in again, the correct case below 
      // will be handled. 
      FB.Event.subscribe('auth.authResponseChange', function(response) {

      	// console.log("Something changed. ", response);

        // Here we specify what we do with the response anytime this event occurs. 
        if (response.status === 'connected') {
          // The response object is returned with a status field that lets the app know the current
          // login status of the person. In this case, we're handling the situation where they 
          // have logged in to the app.
          loggedIn();
        } else if (response.status === 'not_authorized') {
          // In this case, the person is logged into Facebook, but not into the app, so we call
          // FB.login() to prompt them to do so. 
          // In real-life usage, you wouldn't want to immediately prompt someone to login 
          // like this, for two reasons:
          // (1) JavaScript created popup windows are blocked by most browsers unless they 
          // result from direct interaction from people using the app (such as a mouse click)
          // (2) it is a bad experience to be continually prompted to login upon page load.
          // FB.login();
        } else {
          // In this case, the person is not logged into Facebook, so we call the login() 
          // function to prompt them to do so. Note that at this stage there is no indication
          // of whether they are logged into the app. If they aren't then they'll see the Login
          // dialog right after they log in to Facebook. 
          // The same caveats as above apply to the FB.login() call here.
          // FB.login();
        }
      });
      };

      // Load the SDK asynchronously
      (function(d){
       var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
       if (d.getElementById(id)) {return;}
       js = d.createElement('script'); js.id = id; js.async = true;
       js.src = "//connect.facebook.net/en_US/all.js";
       ref.parentNode.insertBefore(js, ref);
      }(document));

      function loginFb() {
      	FB.login();
      }
      function logoutFb() {
        FB.logout(function(){
          window.location.assign("index.php?login=1");
        });
      }

      // Here we run a very simple test of the Graph API after login is successful. 
      // This loggedIn() function is only called in those cases. 
      function loggedIn() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', function(response) {
          console.log('Good to see you, ' + response.name + '.');
          console.log("Response: ", response);
          // $(".lefttext").html("<h2>Welcome " + response.name + "</h2><span>Victor of Engineering</span><p>We've hand picked some stories and videos that we think you'll like. Let us know what you think. We'll be updating the site frequently, so please bookmark it and come back again soon.</p><a class='logout'>Log out</a>");
          // bindLogout();

          $.ajax({
            type: "POST",
            url: "dostuff.php",
            data: { id: response.id, type: "exists" }
          }).done(function( msg ) {
            if (msg == "no cats") window.location.assign("index.php?quiz=1");
          });


          // window.location
        });

    function bindLogout(){
        $(".logout").on("click", function(e){
            // $(e).preventDefault();
            FB.logout();
        });
    }
  }