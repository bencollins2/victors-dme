
var msnry, numLoads = 0;

function isFunction(functionToCheck) {
	var getType = {};
	return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}

function fader(elm) {
	/////////////////////////////////////////////
	// Fade elements in sequence, recursively  //
	/////////////////////////////////////////////
	setTimeout(function(){
		if (elm.next().length > 0) {
			fader(elm.next());
		}
		else {
			resizeWindow();
			// $("#switch").on("click", switcher);
		}
	},100);

	elm.animate({"opacity":"1"}, 700, function(){});
}

function checkHash() {
	/////////////////////////////////////////////////////////////
	//  Open the corresponding page if there is a hash in url  //
	/////////////////////////////////////////////////////////////
	if (window.location.hash) {

		if (window.location.hash == "#transportation") {
			$(".filter .transportation .seemore").slideDown();
			$(".filter .transportation .selectall").click()
		}
		if (window.location.hash == "#economics") {
			$(".filter .economics .seemore").slideDown();
			$(".filter .economics .selectall").click()
		}
		if (window.location.hash == "#wolverine") {
			$(".filter .wolverine .seemore").slideDown();
			$(".filter .wolverine .selectall").click()
		}
		if (window.location.hash == "#global") {
			$(".filter .global .seemore").slideDown();
			$(".filter .global .selectall").click()
		}
		if (window.location.hash == "#materials") {
			$(".filter .materials .seemore").slideDown();
			$(".filter .materials .selectall").click()
		}
		if (window.location.hash == "#healthcare") {
			$(".filter .healthcare .seemore").slideDown();
			$(".filter .healthcare .selectall").click()
		}
		if (window.location.hash == "#securingourfuture") {
			$(".filter .securingourfuture .seemore").slideDown();
			$(".filter .securingourfuture .selectall").click()
		}


	}
}


function doMasonry() {
	////////////////////////
	// jQuery masonry //
	////////////////////////

	if (!$("body").hasClass("lt-ie9")){
		var container = document.querySelector('ul.slides');
		msnry = new Masonry( container, {
		  // options
		  columnWidth: 260,
		  itemSelector: 'li.explore-item'
		});
		msnry.layout();
		$(window).resize();
	}
}


function refresh(cats) {
	$("#switch").off("click");
	$(".img-cover").off("click");
	$("ul.slides li").remove();
	loadExplore(cats);
	// console.log(cats);
}

function loadExplore(cats) {

	var url;
	
	if (cats) {
		url = 'exp.php?exp=1&cats='+cats;
	}
	else url = 'exp.php?exp=1';
	console.log(url);
	// console.log("URL: ", url);
	$("body").addClass("explore").removeClass("slices");	
	$.getJSON(url, function(data) {
		var len = 0;
		$.each(data,function(k,v){len++});
		$.each(data, function(key, val) {

			if (data[key] != false) {
				
				var fav_img = "img/storyFav.png";
				var faved = 0;
				for (var i=0; i<fav_array.length; i++){
					if (val["id"] == fav_array[i]){
						fav_img = "img/storyFaved.png";
						faved = 1;
						break;
					}
				}

				$newLi = $("<li />", {'class': 'explore-item hidestart', 'data-id' : val["id"], 'id': 'item-'+val["id"], 'html':'\
					<img class="fav-star" src="'+fav_img+'" faved = '+faved+'>\
					<div class="img-cover">\
						<img src="img/tiles/'+val["img_large"]+'.jpg" alt="mail cover" />\
					</div>\
					<div class="info">\
						<h2>'+val["title"]+'</h2>\
						<div class="description">'+val["description"]+'</div>\
					</div>\
					<a href="'+val["img_large"]+'.jpg" class="img-src"></a>\
					<div class="item-content"></div>\
					'}).appendTo("ul.slides");
			}


			if (key == len - 1) {
				doMasonry();
				fader($(".hidestart:first"));
				// console.log("Testing ", msnry);
				
				///////////////////////////////////
				// When you open the big image //
				///////////////////////////////////

				$(".img-cover").each(function(index, element) {
					var item_id = $(this).parent().attr("id"), itemheight;

					////////////////////////////////////////////////////////////////////////////////////////
					// Click event: when click on an image, load the image and append it into the HTML //
					////////////////////////////////////////////////////////////////////////////////////////
					$(this).colorbox({inline:true, width:"75%", height:"500px", href:"#signup"});
					$(this).click(function(){
						console.log($(this).parent().data("id"));
						$("#signup .nothanks").show();
						$("#signup .nothanks a").attr("href","article.php?id="+$(this).parent().data("id"))
					});
				});
			}
			resizeWindow();
		});

		$(".fav-star").colorbox({inline:true, width:"75%", height:"500px", href:"#signup"});
	});
}

function hideExplore() {
	if (msnry != undefined) msnry.destroy();
	$(".explore-cover").off("click");
	// leavestory scroll-x fix
	$(".items").css({"overflow-x" : "scroll", "overflow-y" : "hidden"});
	$("body").css({"overflow-y":"hidden"});
	$(".one-item").each(function(index, element) {
		$(this).css({"width":"","height" : "100%", "overflow" : "hidden"});
	});
	//
	$("ul.slides li").animate({"opacity":"0"}, 0, function(){		
		$(this).hide().remove();
		if (!$("ul.slides").children().length > 0) {
			loadSlices();
		};
	});
	$(window).resize();
}

function resizeWindow() {
	// console.log("resizing");
// debugger;
	height = $(window).height(), width = $(window).width();
	var leftwidth = $(".left").width(), itemsWidth = width - leftwidth, itemsHeight = height-51;
	$("body").css({"height" : height+"px"});
	if (height > 800) {
		$(".one-item .img-cover img.cover").css({"width" : "auto"});
		$(".one-item .img-cover img.cover").css({"height" : itemsHeight + "px"});
	}
	else {
		$(".one-item .img-cover img.cover").css({"width" : "auto"});
	}
	//Resize large image
	$(".left").css("height",itemsHeight);
	$(".one li.current").css({"width":width+"px"});
	// debugger;
	$(".slices .items").css({"width":itemsWidth, "height":itemsHeight+"px"});
	$(".explore .items").css({"width":itemsWidth + "px", "height":itemsHeight});
	$(".favorite .items").css({"width":itemsWidth + "px", "height":itemsHeight});

	if ($("body").hasClass("slices")) {

	}
	else {
		$(".one #" + current).css({"width":width + "px"});
	}
	// $(".items").css({"height" : height + "px"});
	bodymargin = $(".content-image-div").height()+"px";
	$(".content-info").css({"margin-top":bodymargin});
	$(".slices .items").css({"left":leftwidth+"px"});
	$(".explore .items").css({"left":""});
	$(".favorite .items").css({"left":""});


	var ifwidth = $(".content-info div.body iframe").width(), ifheight = ifwidth * (2/3);

	$(".content-info div.body iframe").css({"height":ifheight+"px"});
}

var current, bodymargin, imagemargin;



$(document).ready(function(e) {
	
	if (window.location.hash == ""){
		loadExplore();
	}

	var width = $(window).width(), height = $(window).height();
	var leftwidth = $(".left").width(), itemsWidth = width - leftwidth, itemsHeight = height-51;
	// $(".mobile-left").hide();
	// $(".one-item img").css({"height" : height + "px"});

	/////////////////////
	// Window resize //
	/////////////////////
	$(window).resize(function(e) {
		resizeWindow();
	});

	/////////////////////////////////////////////////
	//			When you close the big image       //
	/////////////////////////////////////////////////
	
	// $("#go-back").on("click", leaveStory);
	// $("#nav_home").on("click", switch_view);
	// $("#nav_exp").on("click", switch_view);
	// $("#nav_fav").on("click", switch_view);
	
	// $("#nav_msg").click(function(){
	// 	$("#item-mail .img-cover").click();
	// 	$('#item-mail').show();
	// });

	/////////////////////////////
	// When you click explore //
	/////////////////////////////

	$(window).resize();
	// $(window).hashchange();
	////////////////////
	//  Hover states  //
	////////////////////
	$(".one-item .info").mouseenter(function(e){
		$(this).parent().find(".img-cover").addClass("opaque");
	});
	$(".one-item .info").mouseleave(function(e){
		$(this).parent().find(".img-cover").removeClass("opaque");
	});

	/////////////////
	// Drop-downs //
	/////////////////
	$(".dropdown").each(function(){
		$(this).find("h3").on("click", function(){
			// console.log($(this).parent());
			if ($(this).parent().hasClass("expanded")) {
				$(this).parent().removeClass("expanded");
				$(this).parent().find(".seemore").slideUp(500);
			}
			else {
				$(this).parent().addClass("expanded");
				$(this).parent().find(".seemore").slideDown(500);
			}
		});

	});
	
	cats = '';

	$("form input").on("click", function(){
		var checked = {};
		if($(this).parent().find('input:checked').length == $(this).parent().find('input').length){
			// console.log("yes");
			$(this).parent().find('a.selectall').attr('status','false');
			$(this).parent().find('a.selectall').html('Deselect all');
		}
		
		$(this).parent().parent().parent().find("input:checked").each(function(){
			var index = $(this).attr("value");
			checked[index] = "1";
		});
		cats = "";
		$.each(checked, function(k,v){
			if (v === "1") {
				cats += k + ",";
			}
		});
		cats = cats.substring(0, cats.length - 1);
		refresh(cats);
	});
	
	$("a.selectall").on("click", function(){
		if($(this).attr('status') == 'true'){
			$(this).parent().find("input").each(function(){
				$(this).prop('checked', true);
			});
			$(this).attr('status','false');
			$(this).html('Deselect all');
		}else{
			$(this).parent().find("input").each(function(){
				$(this).prop('checked', false);
			});
			$(this).attr('status','true');
			$(this).html('Select all');
		}
		
		var checked ={};
		$(this).parent().parent().parent().find("input:checked").each(function(){
			var index = $(this).attr("value");
			checked[index] = "1";

		});
		cats = "";
		$.each(checked, function(k,v){
			if (v === "1") {
				cats += k + ",";
			}
		});
		cats = cats.substring(0, cats.length - 1);
		refresh(cats);
	});
});

$("*").on("scroll",function(e){
	console.log(e);
});

$(window).load(function(){
	if (window.location.hash != "")	checkHash();
	$(window).hashchange(function(){
		checkHash();
	});
});