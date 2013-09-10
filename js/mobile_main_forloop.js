function size(obj) {
    var sz = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) sz++;
    }
    return sz-1;
}

function refresh() {
	setTimeout(function () {
		myScroll.refresh();
		console.log("test");
	}, 0);
}

function walkmydog() {
	window.onresize = resizeme;
	function resizeme() {
		var width = window.innerWidth, height = window.innerHeight, lilength = size($(".items li")), itemswidth = width*lilength, marginleft = width/-2;
		$("#wrapper, .one-item, .img-cover img").style({"width":width+"px"});
		$(".img-cover img").style({"margin-left":marginleft + "px"});
		$("ul.slides").style({"width":itemswidth+"px"});


		// for ($k in $(".img-cover img")) {
		// var that = $(".img-cover img")[$k];
		// if (typeof(that) == "object") { 
		//	var marginleft = that.width/-2;
		//	$($(".img-cover img")[$k]).style({"margin-left":marginleft+"px"});
		// }
		// }

		// $(".img-cover img").

		myScroll = new iScroll('wrapper', {
			snap: "li",
			momentum: false,
			hScrollbar: false,
			vScrollbar: false,
			vScroll: false
		});
		refresh();		
	}
	resizeme();
}