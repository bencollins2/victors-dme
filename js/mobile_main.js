function size(obj) {
    var sz = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) sz++;
    }
    return sz-1;
}

function ref() {
	setTimeout(function () {
		myScroll.refresh();
		itemZero.refresh();
		itemOne.refresh();
		itemTwo.refresh();
		itemThree.refresh();
		itemFour.refresh();
		itemFive.refresh();
		itemSix.refresh();
		itemSeven.refresh();
	}, 0);
	return "ok";

}

console.log("Hello");

//////////////
// On ready //
//////////////

function walkmydog() {
	window.onresize = resizeme;
	function resizeme() {

		console.log(window.location.hash);

		var width = window.innerWidth, height = window.innerHeight-35, lilength = size($(".items li")), itemswidth = width*lilength;//, marginleft = width/-2;
		$("#wrapper, .one-item, .img-cover").style({"width":width+"px"});
		$(".one-item").style({"height":height+"px"});
		// $(".img-cover img").style({"margin-left":marginleft + "px"});
		$("ul.slides").style({"width":itemswidth+"px"});

		for (var $k in $(".img-cover img")) {
			var that = $(".img-cover img")[$k];
			if (typeof(that) == "object") {
				var marginleft = that.width/-2;
				$($(".img-cover img")[$k]).style({"margin-left":marginleft+"px"});
			}
		}

		for (var $l in $(".one-item")) {
			var that = $(".one-item")[$l];
			if (typeof(that) == "object") {
				var pageheight = 0;
				if (that.getElementsByClassName("meta")[0]) pageheight += that.getElementsByClassName("meta")[0].clientHeight;
				else { pageheight += 0; }
				pageheight += 800;
				that.getElementsByClassName("scroll")[0].style.height = pageheight + "px";
				ref();
			}
		}

		myScroll = new iScroll('wrapper', {
			snap: "li",
			momentum: false,
			hScrollbar: false,
			vScrollbar: false,
			vScroll: false,
			snapThreshold: 80
		});
		itemZero = new iScroll('item-0', {
			hScrollbar: false,
			vScrollbar: false,
			lockDirection: true
		});
		itemOne = new iScroll('item-1', {
			hScrollbar: false,
			vScrollbar: false,
			lockDirection: true
		});
		itemTwo = new iScroll('item-2', {
			hScrollbar: false,
			vScrollbar: false,
			lockDirection: true
		});
		itemThree = new iScroll('item-3', {
			hScrollbar: false,
			vScrollbar: false,
			lockDirection: true
		});
		itemFour = new iScroll('item-4', {
			hScrollbar: false,
			vScrollbar: false,
			lockDirection: true
		});
		itemFive = new iScroll('item-5', {
			hScrollbar: false,
			vScrollbar: false,
			lockDirection: true
		});
		itemSix = new iScroll('item-6', {
			hScrollbar: false,
			vScrollbar: false,
			lockDirection: true
		});
		itemSeven = new iScroll('item-7', {
			hScrollbar: false,
			vScrollbar: false,
			lockDirection: true
		});

		ref();	
	}
	resizeme();
}