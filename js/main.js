var msnry, numLoads = 0;

function isFunction(functionToCheck) {
	var getType = {};
	return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}

function checkHash() {
	/////////////////////////////////////////////////////////////
	//  Open the corresponding page if there is a hash in url  //
	/////////////////////////////////////////////////////////////
	if (window.location.hash) {
		console.log("Has hash: ", window.location.hash + " .img-cover");
		// $(window.location.hash + " .img-cover").click();
		console.log("Item id: ", window.location.hash.substring(1));
		loadFeature($(window.location.hash + " .img-cover")[0], window.location.hash.substring(1), "500px");
	}
	else {
		console.log("Homepage");
		// $("#go-back").click();
		// leaveStory();
	}
}

function fader(elm) {
	/////////////////////////////////////////////
	// Fade elements in sequence, recursively  //
	/////////////////////////////////////////////
	setTimeout(function(){
		if (elm.next().length > 0) fader(elm.next());
		else {
			// $("#switch").on("click", switcher);
		};
	},100);

	elm.animate({"opacity":"1"}, 1000, function(){});
}

function loadFeature(that, item_id) {
	///////////////////////////////
	// Load an individual story  //
	///////////////////////////////
	var itemheight, $parent = $(that).parent(); 
	console.log("Be quiet, the children are testing: ", $(that).parent());
	numLoads++;
	$("#switch").hide();

	if (msnry !== undefined) msnry.destroy();
	window.location.hash = item_id;
	$parent.addClass("current");
	var title = $(that).children(".meta#title").html(), body = $(that).children(".meta#body").html(), customStyle = $(that).children(".meta#customStyle").html(), author = $(that).children(".meta#byline").html(), $itemcontent = $("#" + item_id + " .item-content");
	current = item_id;
	if ($("body").hasClass("explore")) $("body").removeClass().addClass("exploreOne");
	if ($("body").hasClass("slices")) $("body").removeClass().addClass("slicesOne");
	if ($("body").hasClass("favorite")) $("body").removeClass().addClass("favOne");

	$(".left, .info").hide();
	$("body").css({"left":"0","width":"100%", "overflow-x" : "hidden", "overflow-y" : "auto"});
	$(".items").css({"left":"0","width":"100%","overflow":"hidden", "height":"auto"});

	// Temp
	item_id_img = $("#" + current + " a.img-src").attr("href");

	// Clear all content in all items
	$("#" + item_id + " .img-cover").hide();

	///////////////////////////////
	// Body of the opened window //
	///////////////////////////////

	console.log("Be quiet, the adults are testing: ", $(that).parent()[0]);
	
	if (typeof($parent[0].dataset.video) == "string") {
		var vid = $parent[0].dataset.video;
		if ($(that).find("#title")[0].dataset.position == "top") {
			$itemcontent.html('<div class="content-image-div"><h2>'+title+'</h2><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><div class="left-stuff"></div><div class="body">'+body+'</div>');
		}
		else {
			$itemcontent.html('<div class="content-image-div"><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><div class="left-stuff"></div><h2>'+title+'</h2><div class="body"><iframe width="560" height="315" src="http://www.youtube.com/embed/'+vid+'" frameborder="0" allowfullscreen></iframe>'+body+'</div>');
		}

	}
	else if ($parent.hasClass("map")) {
		$itemcontent.html('<div class="content-image-div iframe"><iframe class="content-iframe" src="http://www.engin.umich.edu/college/admissions/visit/nc-live-map/"></iframe></div><!--div class="content-info"><h2>'+title+'</h2><div class="body">'+body+'</div-->');
	}

	else if ($parent.hasClass("mail")) {

		////////////////
		// Mail img  //
		//////////////
		console.log("Parent has class mail");
		if (mailimg == null) {
			mailimg = "mail.jpg";
		}
		var request = $.ajax({
			type: "GET",
			url: "dostuff.php",
			data: { type: "getmessages", id: userid, name: username }
		});
		request.done(function(msg) {
			console.log("MSG: ", msg);
			$itemcontent.html('<div class="content-image-div"><img class="content-image" src="img/big/' + mailimg + '" alt="item image" /></div><div class="content-info"><h2 class="fadewithme">Read your messages</h2><div class="body">' + msg.html + '</div></div>');
			
			console.log("About to init tinyMCE");
			//////////////////
			// Init tinyMCE //
			//////////////////
			
			
			tinymce.init({
			    selector: "textarea",
			    plugins: [
			    "lists link print anchor template",
			    "searchreplace visualblocks code fullscreen",
			    "insertdatetime media table contextmenu paste"
			  ],
			  toolbar: "undo redo | bold italic | bullist numlist | link",
			  menubar: false,
			  statusbar: false
			 });

			console.log("ID: ", msg.id);

			if (msg.id != null) {
				$.each(msg.id, function(k,v){
					$('#slides'+v).slidesjs({
						width: 940,
						navigation: false,
						start:1
					});	
				});			
			}

			$(".sendmessage input[type='submit']").on("click", function(e){
				e.preventDefault();
				console.log("Clicked the button");
				$this = $(this), $parent = $this.parent(), msg = tinymce.activeEditor.getContent(), ta = $($parent.find("textarea")[0]).val(), to = $this.data("to");
				///////////////////////
				// Send the message  //
				///////////////////////

				var request = $.ajax({
					type: "POST",
					url: "dostuff.php",
					data: { id: userid, type: "putmessage", msg: msg, to: to, name: username }
				});
				request.done(function(msg) {
					console.log(msg);
					$("div.message:last").before(msg.msg.replace("\n", ""));
					$($parent.find("textarea")[0]).val("");
					tinymce.activeEditor.setContent('');	
				});
				request.fail(function(jqXHR, textStatus) {
					console.log( " Failed: " + textStatus );
				});

				////////////////////////
				// / Send the message //
				////////////////////////
			});

		});
		request.fail(function(jqXHR, textStatus) {
			console.log( " Failed: " + textStatus );
		});	
	}

	////////////////////////////////////////////////////
	// For now, this is the only thing that matters.  //
	////////////////////////////////////////////////////
	else {
		$itemcontent.html('<style type="text/css">'+customStyle+'</style><div class="content-image-div"><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><div class="left-stuff">\
			<span id="fb" class=\'facebook st\' displayText=\'Facebook\'></span>\
			<span id="tw" class=\'twitter st\' displayText=\'Tweet\'></span>\
			<span id="gp" class=\'googleplus st\' displayText=\'Google +\'></span>\
			<span id="pn" class=\'pinterest st\' displayText=\'Pinterest\'></span>\
			<span id="rd" class=\'reddit st\' displayText=\'Reddit\'></span>\
			</div><h2 class="fadewithme">'+title+'</h2><h3>Subtitle</h3><span class="byline">'+author+'</span><div class="body"><a id="fav">'+body+'</div></div>');
	}

	stWidget.addEntry({
		"service":"facebook",
		"element":document.getElementById('fb'),
		"url":"http://facebook.com",
		"title":"facebook",
		"type":"large",
		"text":"Share on facebook",
		"summary":"Share on facebook"   
	});

	stWidget.addEntry({
		"service":"twitter",
		"element":document.getElementById('tw'),
		"url":"http://twitter.com",
		"title":"twitter",
		"type":"large",
		"text":"Share on twitter",
		"summary":"Share on twitter"   
	});

	stWidget.addEntry({
		"service":"googleplus",
		"element":document.getElementById('gp'),
		"url":"http://googleplus.com",
		"title":"googleplus",
		"type":"large",
		"text":"Share on googleplus",
		"summary":"Share on googleplus"   
	});

	stWidget.addEntry({
		"service":"pinterest",
		"element":document.getElementById('pn'),
		"url":"http://pinterest.com",
		"title":"pinterest",
		"type":"large",
		"text":"Share on pinterest",
		"summary":"Share on pinterest"   
	});

	stWidget.addEntry({
		"service":"reddit",
		"element":document.getElementById('rd'),
		"url":"http://reddit.com",
		"title":"reddit",
		"type":"large",
		"text":"Share on reddit",
		"summary":"Share on reddit"   
	});

	/////////////////////////////////
	// Scroll body back to the top //
	/////////////////////////////////
	$(".explore, body").scrollTop(0);
	$(".items").scrollLeft(0);

	///////////////////////////////
	// Check the favorite status of current feature //
	///////////////////////////////
	
	is_faved = false;
	
	for (var i=0; i<fav_array.length; i++){
		if (item_id.slice(5) == fav_array[i]){
			is_faved = true;
			break;
		}
	}
	
	if(is_faved == false){
		$('a#fav').html('Fav');
	}else{
		$('a#fav').html('Unfav');
	}
	
	$('a#fav').click(function(){
		if(is_faved ==false){
			is_faved=true;
			$('a#fav').html('Unfav');
			fav_array.push(item_id.slice(5));
			console.log(fav_array);
		}else{
			is_faved=false;
			$('a#fav').html('Fav');
			fav_array.splice(fav_array.indexOf(item_id.slice(5)),1);
			console.log(fav_array);
		}
		// send the new favorites to database
		console.log(fav_array.join());
		$.ajax({
			type: "POST",
			url: "dostuff.php",
			data: { id: userid, type: "newfav", favs: fav_array.join()}
		}).done(function( msg ) {
			console.log("Message: ", msg);
		});
	});
	

	///////////////////////////
	// Get height of img div //
	///////////////////////////
	$(".content-image").load(function(){
		itemheight = $(".content-image-div").height();
		bodymargin = $(".content-image-div").height()+"px";
		$(".content-info").css({"margin-top":bodymargin});
	});

	var margintop = $(".content-info .body").height()/-3;
	margintop = String(margintop) + "px";
	var itemwidth = $(window).width() - ($(window).width()/3);
	var marginleft = String(itemwidth/-2)+"px";
	//$(".content-info").css({"margin-top" : margintop,"margin-left":marginleft,"width":itemwidth});

	// Expand the selected item to full screen, and make all other cover images 0 so they will disappear
	$(".explore-item, .one-item").each(function(index, element) {
		if ($(this).attr("id") != item_id) {
			$(this).css({"width" : "0px"});
			var thatitem = $(this);
			setTimeout(function(){
				thatitem.css({"height" : "0px"});
			}, 500);
		} else {
			$(this).css({"width" : width+"px", "overflow" : "visible", "height": "auto"});
			$(window).scroll(function(e) {

				if (!($("body").hasClass("explore"))) {
					var opac = (itemheight - $("body").scrollTop())/itemheight;
					if (opac >= 0.01 && opac <= 1) {
						$("#" + current + " .fadewithme").css({"opacity" : opac});
					}
					else if (opac < .01) {
						$("#" + current + " .fadewithme").css({"opacity" : "0"});
					}
				}
			});
		}
	});
}


function switcher() {
	///////////////////////////////////////////////////
	// Switch from explore to slices and vice versa  //
	///////////////////////////////////////////////////

	$("#switch").off("click");
	if ($(this).find("span")[0].innerHTML == "Explore") {
		hideSlices();
		$(this).find("span")[0].innerHTML = "Home";
		$("#switch .square p").css({"height":"35px", "width":"7px"});
		$(".filter").fadeIn(500);
		$(".lefttext, .leftlogo").hide();
	}
	else {
		hideExplore();
		$(this).find("span")[0].innerHTML = "Explore";
		$("#switch .square p").css({"height":"", "width":""});
		$(".filter").hide();
		$(".lefttext, .leftlogo").fadeIn(500);
	}
}

function doMasonry() {
	////////////////////////
	// jQuery masonry //
	////////////////////////

	var container = document.querySelector('ul.slides');
	msnry = new Masonry( container, {
	  // options
	  columnWidth: 260,
	  itemSelector: 'li.explore-item'
	});
	msnry.layout();
	$(window).resize();
}

function loadSlices() {
	console.log("Load slices");
	$("ul.slides li").remove();
	
	/////////////////////////////
	// Load the slices. Duh.  //
	///////////////////////////
	//
	console.log("URL: ", 'explore.php?slices=1&cats='+usercats+"&inds="+userinds);
	$.getJSON('explore.php?slices=1&cats='+usercats+"&inds="+userinds, function(data) {
		console.log(data);
		$.each(data, function(key, val) {
			if (data[key] != false) {
				var html = val["html"];
				$newLi = $("<li />", {'class': 'one-item hidestart', 'id': 'item-'+val["id"], 'html':'<div class="info"><h2>'+val["title"]+'</h2><div class="description">'+val["description"]+'</div></div><div class="img-cover"><img class="cover" src="img/'+val["img_large"]+'_cover.jpg" alt="mail cover" /><div class="meta" id="customStyle">'+val["customStyle"]+'</div><div class="meta" id="title">'+val["title"]+'</div><div class="meta" id="byline">'+val["byline"]+'</div><div class="meta" id="body">'+html+'</div></div><a href="'+val["img_large"]+'.jpg" class="img-src"></a><div class="item-content"></div>'}).appendTo("ul.slides");
			}
			if (key == 2) {
				////////////////////
				// Message area  //
				//////////////////

				var mimg = mailimg.substring(0, mailimg.length - 4) + "_cover.jpg";
				$newLi = $("<li />", {'class': 'one-item hidestart mail', 'id': 'item-mail', 'html':'<div class="info"><h2>From: '+firstmsgfrom+'</h2><div class="description">'+firstmsg+'</div></div><div class="img-cover"><img class="cover" src="img/'+mimg+'" alt="mail cover" /><div class="meta" id="title">'+val["title"]+'</div><div class="meta" id="body">Message</div></div><a href="'+val["img_large"]+'.jpg" class="img-src"></a><div class="item-content"></div>'}).appendTo("ul.slides");
			}
			if (key == 5) {
				$newLi = $("<li />", {'class': 'one-item hidestart map', 'id': 'item-map', 'html':'<div class="info"><h2>Campus Map</h2><div class="description">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div></div><div class="img-cover"><img class="cover" src="img/map_cover.jpg" alt="map cover" /></div><div class="meta" id="title">New Messages</div><div class="meta" id="body">stuff</div></div><a href="mail.jpg" class="img-src"></a><div class="item-content"></div>'}).appendTo("ul.slides");
				fader($(".hidestart:first"));

				///////////////////////////////////
				// When you open the big image //
				///////////////////////////////////
				$(".img-cover").each(function(index, element) {
					var item_id = $(this).parent().attr("id"), itemheight;

					////////////////////////////////////////////////////////////////////////////////////////
					// Click event: when click on an image, load the image and append it into the HTML //
					////////////////////////////////////////////////////////////////////////////////////////
					$(this).click(function(e) {
						console.log("This looks like ", this);
						// loadFeature(this, item_id);
						window.location.hash = item_id;

					});
				});
				$(".info").each(function(index, element) {
					var item_id = $(this).parent().attr("id"), itemheight, that = $(this).parent().find(".img-cover")[0];

					////////////////////////////////////////////////////////////////////////////////////////
					// Click event: when click on an image, load the image and append it into the HTML //
					////////////////////////////////////////////////////////////////////////////////////////
					$(this).click(function(e) {
						console.log("This looks like ", this);
						loadFeature(that, item_id);
					});
				});
				checkHash();
			}
			$(window).resize();
			// $(".items").css({"overflow-x" : "scroll", "overflow-y" : "hidden"});
		});
		if (sidebar != "") {
			$("div.lefttext p.msg").html(sidebar);
		}
		else $("div.lefttext p.msg").html("We've hand picked some stories and videos that we think you'll like. Let us know what you think. We'll be updating the site frequently, so please bookmark it and come back again soon.");
	});
}

function loadFavorites(){
	console.log(fav_array);
	
	var url;
	if (fav_array.length > 0){
		favs = fav_array.join();
		url = 'explore.php?favs=' + favs;
		console.log(url);
		$("body").removeClass().addClass("favorite");
		$("ul.slides li").remove();
		setTimeout(function(){
			$.getJSON(url, function(data){
				var len = data.length;
				$.each(data, function(key, val) {
					console.log("Data: ", data);
					if (data[key] != false) {
						$newLi = $("<li />", {'class': 'explore-item hidestart', 'id': 'item-'+val["id"], 'html':'<div class="img-cover"><img src="img/tiles/'+val["img_large"]+'.jpg" alt="mail cover" /><div class="meta" id="title">'+val["title"]+'</div><div class="meta" id="body">'+val["html"]+'</div></div><div class="info"><h2>'+val["title"]+'</h2><div class="description">'+val["description"]+'</div></div><a href="'+val["img_large"]+'.jpg" class="img-src"></a><div class="item-content"></div>'}).appendTo("ul.slides").delay(200);
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
							$(this).click(function(){
								loadFeature(this, item_id);
							});
						});
					}
			
					resizeWindow();
				});
			});
		}, 500);

	}else{
		alert("You haven't fav anything");
		$("#nav_home").click();
	}
}

function hidetest(){
	if (msnry !== undefined) msnry.destroy();
	loadFavorites();
}

function hideSlices() {
	$(".img-cover").off("click");
	$("ul.slides li").remove();

	setTimeout(function(){
			loadExplore();
		}, 400);
	
	$("body").removeClass().addClass("explore");
	$(window).resize();
}

function refresh(cats) {
	$("#switch").off("click");
	$(".img-cover").off("click");
	$("ul.slides li").remove();
	loadExplore(cats);
	console.log(cats);
}

function loadExplore(cats) {

	var url;
	
	if (cats) {
		url = 'explore.php?cats='+cats;
	}
	else url = 'explore.php';

	console.log("URL: ", url);
	$("body").addClass("explore").removeClass("slices");	
	$.getJSON(url, function(data) {
		var len = data.length; 
		console.log("LENGTH: ", data.length);
		$.each(data, function(key, val) {
			console.log("Data: ", data);
			if (data[key] != false) {
				
				var fav_img = "img/storyFav.png";
				for (var i=0; i<fav_array.length; i++){
					if (val["id"] == fav_array[i]){
						fav_img = "img/storyFaved.png"
						break;
					}
				}
				
				$newLi = $("<li />", {'class': 'explore-item hidestart', 'id': 'item-'+val["id"], 'html':'<img class="fav-star" src="'+fav_img+'"><div class="img-cover"><img src="img/tiles/'+val["img_large"]+'.jpg" alt="mail cover" /><div class="meta" id="title">'+val["title"]+'</div><div class="meta" id="body">'+val["html"]+'</div></div><div class="info"><h2>'+val["title"]+'</h2><div class="description">'+val["description"]+'</div></div><a href="'+val["img_large"]+'.jpg" class="img-src"></a><div class="item-content"></div>'}).appendTo("ul.slides").delay(200);
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
					$(this).click(function(){
						loadFeature(this, item_id);
					});
				});
			}
			
			resizeWindow();
		});
	});
}

function hideExplore() {
	if (msnry !== undefined) msnry.destroy();
	$(".explore-cover").off("click");
	$("ul.slides li").animate({"opacity":"0"}, 0, function(){		
		$(this).hide().remove();
		if (!$("ul.slides").children().length > 0) {
			loadSlices();
		};
	});

	$("body").removeClass().addClass("slices");
	$(window).resize();
}


function leaveStory() {
	/////////////////////////////////////////////////////////////////
	// Close the current story and go back to slices or explore.  //
	/////////////////////////////////////////////////////////////////
	$("#switch").show();
		if ($("body").hasClass("slices")) {

			var leftwidth = $(".left").width()+ "px";
			$("li").removeClass("current");
			window.location.hash = "";
			$(".items").scrollTop(0);
			$(".content-info, .content-image").hide();
			$(".left, .info").show();
			$(".items").css({"left":leftwidth,"overflow-x" : "scroll", "overflow-y" : "hidden"});
			$("body").css({"overflow-y":"hidden"});
			$(".one-item").each(function(index, element) {
				$(this).css({"width":"","height" : "100%", "overflow" : "hidden"});
			});

			$(".one-item").remove();
			loadSlices();
		}

		else if ($("body").hasClass("explore")) {
			var leftwidth = $(".left").width()+ "px";
			$("li").removeClass("current");
			window.location.hash = "";
			$(".items").scrollTop(0);
			$(".content-info, .content-image").hide();
			$(".left, .info").show();
			$(".items").css({"left":"", "width":itemsWidth+"px", "overflow":"", "height":itemsHeight+"px"});
			$("body").css({"overflow-y":"hidden"});
			$(".explore-item").each(function(index, element) {
				$(this).css({"width":"240px", "overflow" : "hidden", "height": ""});
			});
			doMasonry();
			setTimeout(function(){
				$("#" + current + " .img-cover").fadeIn(500, function(){
					$(".item-content").html("");
					$("#go-back").hide();
					// $(window).resize();
				});
			}, 400);
		}
}

function resizeWindow() {
	console.log("resizing");
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
	$(".exploreOne li.current, .slicesOne li.current").css({"width":width+"px"});
	$(".slices .items").css({"width":itemsWidth, "height":itemsHeight+"px"});
	$(".explore .items").css({"width":itemsWidth + "px", "height":itemsHeight});
	$(".favorite .items").css({"width":itemsWidth + "px", "height":itemsHeight});

	if ($("body").hasClass("slices")) {

	}
	else {
		$(".exploreOne #" + current + ", .slicesOne #" + current).css({"width":width + "px"});
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

function switch_view(){
	window.location.hash="";
	switch ($(this).attr('id')) {
	case 'nav_home':
		$(".filter").hide();
		$(".favtext").hide();
		$(".left").show();
		$(".lefttext").fadeIn(500);
		hideExplore();
		break;
	case 'nav_exp':
		$(".left").show();
		$(".favtext").hide();
		$(".lefttext").hide();
		$(".filter").fadeIn(500);
		hideSlices();
		break;
	case 'nav_fav':
		$(".filter").hide();
		$(".lefttext").hide();
		$(".left").show();
		$(".favtext").fadeIn(500);
		hidetest();
		break;
	default:
		break;
	}
}

$(document).ready(function(e) {
	loadSlices();

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
	
	$("#go-back").on("click", leaveStory);
	$("#nav_home").on("click", switch_view);
	$("#nav_exp").on("click", switch_view);
	$("#nav_fav").on("click", switch_view);
	
	$("#nav_msg").click(function(){
		$("#item-mail .img-cover").click();
	});
	
	$(".fav-star").click(function(){
	});

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
			console.log($(this).parent());
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

	$("form input").on("click", function(){
		var checked = {};
		
		if($(this).parent().find('input:checked').length == $(this).parent().find('input').length){
			console.log("yes");
			$(this).parent().find('a.selectall').attr('status','false');
			$(this).parent().find('a.selectall').html('Deselect all');
		}
		
		$(this).parent().parent().parent().find("input:checked").each(function(){
			var index = $(this).attr("value");
			checked[index] = "1";
		});
		var cats = "";
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
		var cats = "";
		$.each(checked, function(k,v){
			if (v === "1") {
				cats += k + ",";
			}
		});
		cats = cats.substring(0, cats.length - 1);
		refresh(cats);
	});
});

$(window).load(function(){
	$(window).hashchange(function(){
		checkHash();
	});
});