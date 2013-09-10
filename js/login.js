$(document).ready(function(){
	$(".fbl").on("click",function(e){
		e.preventDefault();
		FB.login(function(response) {
			// handle the response
		}, {scope: 'email,user_likes'});;
	});
});