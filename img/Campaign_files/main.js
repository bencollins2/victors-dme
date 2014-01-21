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

function imageLoadedSame(id) {
	// debugger;

	$(".current .content-info, .current .content-image-div").fadeIn(1000);
	$(this).parent().fadeIn(1000);

	// var imgcontainerheight = $(".content-image-div").height();
	var imgcontainerheight = 450;
	var bodymargin = imgcontainerheight+"px";
	$(".content-info").css({"margin-top":bodymargin});


	// Expand the selected item to full screen, and make all other cover images 0 so they will disappear
	$(".explore-item, .one-item").each(function(index, element) {
		if (!$(this).hasClass("current")) {
			$(this).css({"width" : "0px"});
			var thatitem = $(this);
			thatitem.css({"height" : "0px"});
		} else {
			$(this).css({"width" : width+"px", "overflow" : "visible", "height": "auto"});
			$(window).off("scroll").on("scroll", function(e) {

				if (!($("body").hasClass("explore"))) {
					var opac = (imgcontainerheight - $("body").scrollTop())/imgcontainerheight;
					console.log(opac);
					if (opac >= 0.01 && opac <= 1) {
						$("#" + current + " .fadewithme").css({"opacity" : opac});
					}
					else if (opac < 0.01) {
						$("#" + current + " .fadewithme").css({"opacity" : "0"});
					}
				}
			});
		}
	});
	// debugger;
	window.location.hash="item-"+id;
}	

function imageLoaded(id) {
	// debugger;
	var item_id = "item-"+id;

	$("#"+item_id+" .content-info, #"+item_id+" .content-image-div").fadeIn(1000);
	$(this).parent().fadeIn(1000);

	// var imgcontainerheight = $(".content-image-div").height();

	var imgcontainerheight = 450;

	var bodymargin = imgcontainerheight+"px";
	$(".content-info").css({"margin-top":bodymargin});


	// Expand the selected item to full screen, and make all other cover images 0 so they will disappear
	$(".explore-item, .one-item").each(function(index, element) {
		if ($(this).attr("id") != item_id) {
			$(this).css({"width" : "0px"});
			var thatitem = $(this);
			thatitem.css({"height" : "0px"});
		} else {
			$(this).css({"width" : width+"px", "overflow" : "visible", "height": "auto"});
			$(window).off("scroll").on("scroll", function(e) {

				if (!($("body").hasClass("explore"))) {
					var opac = (imgcontainerheight - $("body").scrollTop())/imgcontainerheight;
					// console.log(opac);
					if (opac >= 0.01 && opac <= 1) {
						$("#" + current + " .fadewithme").css({"opacity" : opac});
					}
					else if (opac < 0.01) {
						$("#" + current + " .fadewithme").css({"opacity" : "0"});
					}
				}
			});
		}
	});
	$("#"+item_id).scrollTop();
}	

function loadInSame(id) {
	$("ul.slides li .item-content").html("");
	var itemheight, $itemcontent = $(".current").find(".item-content");

	var request = $.ajax({
			type: "POST",
			url: "dostuff.php",
			data: { id: id, type: "getstory" }
		});
		request.done(function(msg) {

			var json = $.parseJSON(msg);
			// console.log(json);
			if (json.titletop == 1) {
				$itemcontent.html('<style type="text/css">'+json.customStyle+'</style><div class="content-image-div"><h2 class="fadewithme">'+json['title']+'</h2><img class="content-image big'+json.id+'" src="img/big/' + json.img_large + '.jpg" alt="item image" /></div><div class="content-info" style="display:none;"><div class="left-stuff">\
					<span id="fb" class=\'facebook st\' displayText=\'Facebook\'></span>\
					<span id="tw" class=\'twitter st\' displayText=\'Tweet\'></span>\
					<span id="gp" class=\'googleplus st\' displayText=\'Google +\'></span>\
					<span id="pn" class=\'pinterest st\' displayText=\'Pinterest\'></span>\
					<span id="rd" class=\'reddit st\' displayText=\'Reddit\'></span>\
					</div><h3 class="subtitle">'+json.description+'</h3><span class="byline">'+json.byline+'</span><div class="body"><a href="#" id="fav"></a>'+json.html+'</div></div>');
			}
			else {
					$itemcontent.html('<style type="text/css">'+json.customStyle+'</style><div class="content-image-div"><img class="content-image big'+json.id+'" src="img/big/' + json.img_large + '.jpg" alt="item image" /></div><div class="content-info" style="display:none;"><div class="left-stuff">\
					<span id="fb" class=\'facebook st\' displayText=\'Facebook\'></span>\
					<span id="tw" class=\'twitter st\' displayText=\'Tweet\'></span>\
					<span id="gp" class=\'googleplus st\' displayText=\'Google +\'></span>\
					<span id="pn" class=\'pinterest st\' displayText=\'Pinterest\'></span>\
					<span id="rd" class=\'reddit st\' displayText=\'Reddit\'></span>\
					</div><h2>'+json['title']+'</h2><h3 class="subtitle">'+json.description+'</h3><span class="byline">'+json.byline+'</span><div class="body"><a href="#" id="fav"></a>'+json.html+'</div></div>');
			}
			
			$(".big"+json.id).imagesLoaded(imageLoadedSame(json.id));

			stWidget.addEntry({
				"service":"facebook",
				"element":document.getElementById('fb'),
				"url":"http://victors.engin.umich.edu/article.php?id="+id,
				"title":"facebook",
				"type":"large",
				"text":"Share on facebook",
				"summary":"Share on facebook"
			});

			stWidget.addEntry({
				"service":"twitter",
				"element":document.getElementById('tw'),
				"url":"http://victors.engin.umich.edu/article.php?id="+id,
				"title":"twitter",
				"type":"large",
				"text":"Share on twitter",
				"summary":"Share on twitter"   
			});

			stWidget.addEntry({
				"service":"googleplus",
				"element":document.getElementById('gp'),
				"url":"http://victors.engin.umich.edu/article.php?id="+id,
				"title":"googleplus",
				"type":"large",
				"text":"Share on googleplus",
				"summary":"Share on googleplus"   
			});

			stWidget.addEntry({
				"service":"pinterest",
				"element":document.getElementById('pn'),
				"url":"http://victors.engin.umich.edu/article.php?id="+id,
				"title":"pinterest",
				"type":"large",
				"text":"Share on pinterest",
				"summary":"Share on pinterest"   
			});

			stWidget.addEntry({
				"service":"reddit",
				"element":document.getElementById('rd'),
				"url":"http://victors.engin.umich.edu/article.php?id="+id,
				"title":"reddit",
				"type":"large",
				"text":"Share on reddit",
				"summary":"Share on reddit"   
			});
			/////////////////////////////////
			// Scroll body back to the top //
			/////////////////////////////////
			$(".explore, body, html").scrollTop(0);
			$(".items").scrollLeft(0);

			///////////////////////////////
			// Check the favorite status of current feature //
			///////////////////////////////
			
			is_faved = false;
			
			for (var i=0; i<fav_array.length; i++){
				if (id){
					is_faved = true;
					break;
				}
			}
			
			if(is_faved == false){
				$('a#fav').html('Favorite');
				$('a#fav').removeClass("unfav").addClass("fav");
			}else{
				$('a#fav').html('Unfavorite');
				$('a#fav').removeClass("fav").addClass("unfav");
			}
			
			$('a#fav').click(function(){
				if(is_faved ==false){
					is_faved=true;
					$('a#fav').html('Unfavorite');
					$('a#fav').removeClass("fav").addClass("unfav");
					fav_array.push(item_id.slice(5));
					// console.log(fav_array);
				}else{
					is_faved=false;
					$('a#fav').html('Favorite');
					$('a#fav').removeClass("unfav").addClass("fav");
					fav_array.splice(fav_array.indexOf(item_id.slice(5)),1);
					// console.log(fav_array);
				}
				// send the new favorites to database
				// console.log(fav_array.join());
				$.ajax({
					type: "POST",
					url: "dostuff.php",
					data: { id: userid, type: "newfav", favs: fav_array.join()}
				}).done(function( msg ) {
					// console.log("Message: ", msg);
				});
			});
			$("#loading").fadeOut(500);

		});
		request.fail(function(jqXHR, textStatus) {
			// console.log( " Failed: " + textStatus );
		});	
}

//lloadfeature
function loadFeature(id) {

	///////////////////////////////
	// Load an individual story  //
	///////////////////////////////
	// debugger;
	// reset..


	var itemheight, item_id = "item-"+id, $that = $("#"+item_id), $itemcontent = $("#" + item_id + " .item-content");
	numLoads++;
	$("#switch").hide();

	if (msnry != undefined) msnry.destroy();
	window.location.hash = item_id;
	$that.addClass("current");
	//var title = $that.children(".meta#title").html(), subtitle = $that.children(".meta#subtitle").html(), titletop = $that.children(".meta#title").data().titletop, body = $that.children(".meta#body").html(), customStyle = $that.children(".meta#customStyle").html(), author = $that.children(".meta#byline").html(), $itemcontent = $("#" + item_id + " .item-content");

	current = item_id;
	$("body").removeClass().addClass("one");

	$(".left, .info").hide();
	$("body").css({"left":"0","width":"100%", "overflow-x" : "hidden", "overflow-y" : "auto"});
	$(".items").css({"left":"0","width":"100%","overflow":"hidden", "height":"auto"});

	// Temp
	item_id_img = $("#" + current + " a.img-src").attr("href");

	// Clear all content in all items
	$("#" + item_id + " .img-cover").hide();

	$("#loading").fadeIn(500);

	///////////////////////////////
	// Body of the opened window //
	///////////////////////////////

	// console.log("Be quiet, the adults are testing: ", $that.parent()[0]);

	if ($that.hasClass("map")) {
		$itemcontent.html('<div class="content-image-div iframe"><iframe class="content-iframe" src="http://www.engin.umich.edu/college/admissions/visit/nc-live-map/"></iframe></div><!--div class="content-info"><h2>'+title+'</h2><div class="body">'+body+'</div-->');
	}

	else if ($that.hasClass("mail")) {

		////////////////
		// Mail img  //
		//////////////
		// console.log("Parent has class mail");
		if (mailimg == null) {
			mailimg = "mail.jpg";
		}
		var request = $.ajax({
			type: "GET",
			url: "dostuff.php",
			data: { type: "getmessages", id: userid, name: username }
		});
		request.done(function(msg) {

			// console.log("MSG: ", msg);
			$itemcontent.html('<div class="content-image-div"><h2 class="fadewithme">Read your messages</h2><img class="content-image bigmail" src="img/big/' + mailimg + '" alt="item image" /></div><div class="content-info"><div class="body">' + msg.html + '</div></div>');
			$(".bigmail").imagesLoaded(imageLoaded("mail"));
			// console.log("About to init tinyMCE");
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

			// console.log("ID: ", msg.id);
			$("#loading").fadeOut(1000);

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
				
				// console.log("Clicked the button");
				$this = $(this), $that = $this.parent(), msg = tinymce.activeEditor.getContent(), ta = $($that.find("textarea")[0]).val(), to = $this.data("to");
				///////////////////////
				// Send the message  //
				///////////////////////

				var request = $.ajax({
					type: "POST",
					url: "dostuff.php",
					data: { id: userid, type: "putmessage", msg: msg, to: to, name: username }
				});
				request.done(function(msg) {
					// console.log(msg);
					$("div.message:last").before(msg.msg.replace("\n", ""));
					$($that.find("textarea")[0]).val("");
					tinymce.activeEditor.setContent('');	
				});
				request.fail(function(jqXHR, textStatus) {
					// console.log( " Failed: " + textStatus );
				});


				////////////////////////
				// / Send the message //
				////////////////////////
			});
		});
		request.fail(function(jqXHR, textStatus) {
			// console.log( " Failed: " + textStatus );
		});	
	}

	////////////////////////////////////////////////////
	// For now, this is the only thing that matters.  //
	////////////////////////////////////////////////////
	else {

		var request = $.ajax({
			type: "POST",
			url: "dostuff.php",
			data: { id: id, type: "getstory" }
		});
		request.done(function(msg) {
			var json = $.parseJSON(msg);
			// console.log(json);
			if (json.titletop == 1) {
				$itemcontent.html('<style type="text/css">'+json.customStyle+'</style><div class="content-image-div"><h2 class="fadewithme">'+json['title']+'</h2><img class="content-image big'+json.id+'" src="img/big/' + json.img_large + '.jpg" alt="item image" /></div><div class="content-info" style="display:none;"><div class="left-stuff">\
					<span id="fb" class=\'facebook st\' displayText=\'Facebook\'></span>\
					<span id="tw" class=\'twitter st\' displayText=\'Tweet\'></span>\
					<span id="gp" class=\'googleplus st\' displayText=\'Google +\'></span>\
					<span id="pn" class=\'pinterest st\' displayText=\'Pinterest\'></span>\
					<span id="rd" class=\'reddit st\' displayText=\'Reddit\'></span>\
					</div><h3 class="subtitle">'+json.description+'</h3><span class="byline">'+json.byline+'</span><div class="body"><a href="#" id="fav"></a>'+json.html+'</div></div>');
			}
			else {
					$itemcontent.html('<style type="text/css">'+json.customStyle+'</style><div class="content-image-div"><img class="content-image big'+json.id+'" src="img/big/' + json.img_large + '.jpg" alt="item image" /></div><div class="content-info" style="display:none;"><div class="left-stuff">\
					<span id="fb" class=\'facebook st\' displayText=\'Facebook\'></span>\
					<span id="tw" class=\'twitter st\' displayText=\'Tweet\'></span>\
					<span id="gp" class=\'googleplus st\' displayText=\'Google +\'></span>\
					<span id="pn" class=\'pinterest st\' displayText=\'Pinterest\'></span>\
					<span id="rd" class=\'reddit st\' displayText=\'Reddit\'></span>\
					</div><h2>'+json['title']+'</h2><h3 class="subtitle">'+json.description+'</h3><span class="byline">'+json.byline+'</span><div class="body"><a href="#" id="fav"></a>'+json.html+'</div></div>');
			}
			
			$(".big"+json.id).imagesLoaded(imageLoaded(json.id));

			stWidget.addEntry({
				"service":"facebook",
				"element":document.getElementById('fb'),
				"url":"http://victors.engin.umich.edu/article.php?id="+item_id.split("-")[1],
				"title":"facebook",
				"type":"large",
				"text":"Share on facebook",
				"summary":"Share on facebook",
			});

			stWidget.addEntry({
				"service":"twitter",
				"element":document.getElementById('tw'),
				"url":"http://victors.engin.umich.edu/article.php?id="+item_id.split("-")[1],
				"title":"twitter",
				"type":"large",
				"text":"Share on twitter",
				"summary":"Share on twitter"   
			});

			stWidget.addEntry({
				"service":"googleplus",
				"element":document.getElementById('gp'),
				"url":"http://victors.engin.umich.edu/article.php?id="+item_id.split("-")[1],
				"title":"googleplus",
				"type":"large",
				"text":"Share on googleplus",
				"summary":"Share on googleplus"   
			});

			stWidget.addEntry({
				"service":"pinterest",
				"element":document.getElementById('pn'),
				"url":"http://victors.engin.umich.edu/article.php?id="+item_id.split("-")[1],
				"title":"pinterest",
				"type":"large",
				"text":"Share on pinterest",
				"summary":"Share on pinterest"   
			});

			stWidget.addEntry({
				"service":"reddit",
				"element":document.getElementById('rd'),
				"url":"http://victors.engin.umich.edu/article.php?id="+item_id.split("-")[1],
				"title":"reddit",
				"type":"large",
				"text":"Share on reddit",
				"summary":"Share on reddit"   
			});
			/////////////////////////////////
			// Scroll body back to the top //
			/////////////////////////////////
			$(".explore, body, html").scrollTop(0);
			$(".items").scrollLeft(0);
			$("#item-"+id).scrollTop();

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
				$('a#fav').html('Favorite');
				$('a#fav').removeClass("unfav").addClass("fav");
			}else{
				$('a#fav').html('Unfavorite');
				$('a#fav').removeClass("fav").addClass("unfav");
			}
			
			$('a#fav').click(function(){
				if(is_faved ==false){
					is_faved=true;
					$('a#fav').html('Unfavorite');
					$('a#fav').removeClass("fav").addClass("unfav");
					fav_array.push(item_id.slice(5));
					// console.log(fav_array);
				}else{
					is_faved=false;
					$('a#fav').html('Favorite');
					$('a#fav').removeClass("unfav").addClass("fav");
					fav_array.splice(fav_array.indexOf(item_id.slice(5)),1);
					// console.log(fav_array);
				}
				// send the new favorites to database
				// console.log(fav_array.join());
				$.ajax({
					type: "POST",
					url: "dostuff.php",
					data: { id: userid, type: "newfav", favs: fav_array.join()}
				}).done(function( msg ) {
					// console.log("Message: ", msg);
				});
			});
			$("#loading").fadeOut(500);

		});
		request.fail(function(jqXHR, textStatus) {
			// console.log( " Failed: " + textStatus );
		});	

	}
	
}

function checkHash() {
	/////////////////////////////////////////////////////////////
	//  Open the corresponding page if there is a hash in url  //
	/////////////////////////////////////////////////////////////
	if (window.location.hash) {
		// console.log("Has hash: ", window.location.hash + " .img-cover");
		// $(window.location.hash + " .img-cover").click();
		// console.log("Item id: ", window.location.hash.substring(1));
		//loadFeature($(window.location.hash + " .img-cover")[0], window.location.hash.substring(1), "500px");
		// debugger;
		if (window.location.hash != "#explore" && window.location.hash!= "#favorites") {
			if ($("body").hasClass("one")) {
				loadInSame(window.location.hash.split("-")[1]);
			}
			else {
				loadFeature(window.location.hash.split("-")[1]);
			}
		}
		else if (window.location.hash == "#explore") {
			// $("#nav_exp").trigger("click");
		}
		


		// var nextid = window.location.hash.split("-")[1];
	}
	else {
		if (window.location.hash != "#explore" && window.location.hash!= "#favorites") {

		}
		else {
			$("#nav_home").trigger("click");
		}
		// $("#go-back").click();
		// leaveStory();
	}
}


// function switcher() {
// 	///////////////////////////////////////////////////
// 	// Switch from explore to slices and vice versa  //
// 	///////////////////////////////////////////////////

// 	$("#switch").off("click");
// 	if ($(this).find("span")[0].innerHTML == "Explore") {
// 		hideSlices();
// 		$(this).find("span")[0].innerHTML = "Home";
// 		$("#switch .square p").css({"height":"35px", "width":"7px"});
// 		$(".filter").fadeIn(500);
// 		$(".lefttext, .leftlogo").hide();
// 	}
// 	else {
// 		hideExplore();
// 		$(this).find("span")[0].innerHTML = "Explore";
// 		$("#switch .square p").css({"height":"", "width":""});
// 		$(".filter").hide();
// 		$(".lefttext, .leftlogo").fadeIn(500);
// 	}
// }

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

function loadSlices() {
	// console.log("Load slices");
	$("ul.slides li").remove();
	
	/////////////////////////////
	// Load the slices. Duh.  //
	///////////////////////////
	var url = 'exp.php?slices=8&cats='+usercats+"&inds="+userinds;
	console.log(url);
	if (msgslice == '0' || firstmsgfrom == "") url = 'exp.php?slices=8&cats='+usercats+"&inds="+userinds;
	if (firsttime != 1){
		url = 'exp.php?slices=8&cats='+usercats+"&inds="+userinds+"&intro=1";
	}
	
	// console.log("URL: ", url);
	$.getJSON(url, function(data) {
		// console.log(data);
		$.each(data, function(key, val) {
			if (data[key] != false) {
				var html = val["html"];
				$newLi = $("<li />", {'class': 'one-item hidestart', 'data-id' : val["id"], 'id': 'item-'+val["id"], 'html':'\
					<div class="info">\
						<h2>'+val["title"]+'</h2>\
						<div class="description">\
							'+val["description"]+'\
						</div>\
					</div>\
					<div class="img-cover">\
						<img class="cover" src="img/'+val["img_large"]+'_cover.jpg" alt="mail cover" />\
					</div>\
					<a href="'+val["img_large"]+'.jpg" class="img-src"></a>\
					<div class="item-content"></div>'}).appendTo("ul.slides");
				
			}
			if (key == 2) {
				////////////////////
				// Message area  //
				//////////////////

				var mimg = mailimg.substring(0, mailimg.length - 4) + "_cover.jpg";

				if (firstmsgfrom != "") {
					$newLi = $("<li />", {'class': 'one-item hidestart mail','data-id' : "mail", 'id': 'item-mail', 'html':'\
					<div class="info">\
						<h2>From: '+firstmsgfrom+'</h2>\
						<div class="description">'+firstmsg+'</div>\
					</div>\
					<div class="img-cover">\
						<img class="cover" src="img/'+mimg+'" alt="mail cover" />\
					</div>\
					<a href="'+val["img_large"]+'.jpg" class="img-src"></a>\
					<div class="item-content"></div>\
					'}).appendTo("ul.slides");
				}
				else {
					$('#item-mail').hide();
				}

				
				if(msgslice == '0'){
				}
			}
			if (key == 7) {
				// $newLi = $("<li />", {'class': 'one-item hidestart map','data-id' : "map", 'id': 'item-map', 'html':'\
				// 	<div class="info">\
				// 		<h2>Campus Map</h2>\
				// 		<div class="description">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>\
				// 	</div>\
				// 	<div class="img-cover">\
				// 		<img class="cover" src="img/map_cover.jpg" alt="map cover" />\
				// 	</div>\
				// 	<div class="meta" id="title">New Messages</div>\
				// 	<div class="meta" id="body">stuff</div>\
				// 	<a href="mail.jpg" class="img-src"></a>\
				// 	<div class="item-content"></div>'}).appendTo("ul.slides");
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
						// console.log("This looks like ", this);
						loadFeature($(this).parent().data("id"));
						window.location.hash = item_id;

					});
				});
				$(".info").each(function(index, element) {
					var item_id = $(this).parent().attr("id"), itemheight, that = $(this).parent().find(".img-cover")[0];

					////////////////////////////////////////////////////////////////////////////////////////
					// Click event: when click on an image, load the image and append it into the HTML //
					////////////////////////////////////////////////////////////////////////////////////////
					$(this).click(function(e) {
						// console.log("This looks like ", this);

						loadFeature($(this).parent().data("id"));
						window.location.hash = item_id;
					});
				});
				// checkHash();
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
	// console.log(fav_array);
	var url;
	if (fav_array.length > 0){
		favs = fav_array.join();
		url = 'exp.php?favs=' + favs;
		// console.log(url);
		$("body").removeClass().addClass("favorite");
		$("ul.slides li").remove();
		setTimeout(function(){
			$.getJSON(url, function(data){

				var len = 0;
				$.each(data,function(k,v){len++});
				$.each(data, function(key, val) {

					// console.log("Data: ", data);
					if (data[key] != false) {
						$newLi = $("<li />", {'class': 'explore-item hidestart', 'data-id' : val["id"], 'id': 'item-'+val["id"], 'html':'\
							<div class="img-cover">\
								<img src="img/tiles/'+val["img_large"]+'.jpg" alt="mail cover" />\
							</div>\
							<div class="info">\
								<h2>'+val["title"]+'</h2>\
								<div class="description">'+val["description"]+'</div>\
							</div>\
							<a href="'+val["img_large"]+'.jpg" class="img-src"></a>\
							<div class="item-content"></div>\
							'}).appendTo("ul.slides").delay(200);
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
								// debugger;
								loadFeature($(this).parent().data("id"));
							});
						});
					}
			
					resizeWindow();
				});
			});
		}, 500);

	}else{
		alert("You haven't favorited anything yet.");
		$("#nav_home").click();
	}
}

function hidetest(){
	//if (msnry != undefined) msnry.destroy();
	loadFavorites();
}

function hideSlices() {
	$(".img-cover").off("click");
	$("ul.slides li").remove();

	// setTimeout(function(){
			loadExplore();
		// }, 400);
	
	$("body").removeClass().addClass("explore");
	$(window).resize();
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
					$(this).click(function(){
						loadFeature($(this).parent().data("id"));
					});
				});
			}
			
			resizeWindow();
		});
		
		$(".fav-star").click(function(){
			var item_id = $(this).parent().attr('id').slice(5);
			var faved = $(this).attr('faved');
			if (faved == 0){
				this.src = "img/storyFaved.png";
				$(this).attr('faved',1);
				fav_array.push(item_id);
			}else{
				this.src = "img/storyFav.png";
				$(this).attr('faved',0);
				fav_array.splice(fav_array.indexOf(item_id),1);
			}
			$.ajax({
				type: "POST",
				url: "dostuff.php",
				data: { id: userid, type: "newfav", favs: fav_array.join()}
			}).done(function( msg ) {
				// console.log("Message: ", msg);
			});
		});
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
			// debugger;
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

function switch_view(){
	// debugger;
	window.location.hash="";
	switch ($(this).attr('id')) {
	case 'nav_home':
		$(".filter").hide();
		$(".favtext").hide();
		$(".left").show();
		$(".lefttext").fadeIn(500);
		$("body").removeClass().addClass("slices");
		hideExplore();
		break;
	case 'nav_exp':
		$(".left").show();
		$(".favtext").hide();
		$(".lefttext").hide();
		$(".filter").fadeIn(500);
		$("body").removeClass().addClass("explore");
		window.location.hash="explore";
		hideSlices();
		break;
	case 'nav_fav':
		$(".filter").hide();
		$(".lefttext").hide();
		$(".left").show();
		$(".favtext").fadeIn(500);
		window.location.hash="favorites";
		hidetest();
		break;
	default:
		break;
	}
}

function setTutorial(slide){
	$('#tutorial img').hide('slow');
	$('#tutorial li').removeClass();

	$('#tutorial .slide'+slide).show('slow');
	$('#tutorial ul').children().eq(slide-1).addClass('selected');
}

function setUserTutorial(val) {
	var request = $.ajax({
		type: "POST",
		url: "dostuff.php",
		data: { id: userid, type: "setUserTutorial", val: val }
	});
	request.done(function(msg) {
		console.log(msg);
	});
	request.fail(function(jqXHR, textStatus) {
		// console.log( " Failed: " + textStatus );
	});
}

$(document).ready(function(e) {
	
	// set the tutorial
	setTutorial(1);
	tutorial_slide = 1;
	// $('#tutorial').on('click',function(){
	// 	if(tutorial_slide == 4){
	// 		$('#tutorial').hide();
	// 		setTutorial(1);
	// 		tutorial_slide = 1;
	// 		$.ajax({
	// 			type: "POST",
	// 			url: "dostuff.php",
	// 			data: { id: userid, type: "tutorial"}
	// 		}).done(function( msg ) {
	// 			console.log("Message: ", msg);
	// 		});
	// 	}else{
	// 		++tutorial_slide;
	// 		setTutorial(tutorial_slide);
	// 	}
	// });

	$('#tutorial li').on('click', function(){
		// cancel the click event on #tutorial
		// var evnt = window.event?window.event:arg;
		// if(evnt.stopPropagation){
		// 	evnt.stopPropagation();
		// }else{
		// 	evnt.cancelBubble=true;
		// }
		e.preventDefault();
		tutorial_slide = $(this).index() + 1;
		setTutorial(tutorial_slide);
	});

	$("#tutorial .nav a.next").on('click',function(e){
		// cancel the click event on #tutorial
		// var evnt = window.event?window.event:arg;
		// if(evnt.stopPropagation){
		// 	evnt.stopPropagation();
		// }else{
		// 	evnt.cancelBubble=true;
		// }
		// 
		e.preventDefault();
		var next = $(this).parent().parent().find("li.selected").index()+2;
		if (next < 5) setTutorial(next);
	});

	$("#tutorial .nav a.prev").on('click',function(e){
		// cancel the click event on #tutorial
		// var evnt = window.event?window.event:arg;
		// if(evnt.stopPropagation){
		// 	evnt.stopPropagation();
		// }else{
		// 	evnt.cancelBubble=true;
		// }
		e.preventDefault();
		var next = $(this).parent().parent().find("li.selected").index();
		if (next > -1) setTutorial(next);
	});
	
	$('#tutorial .close').on('click', function(e){
		// cancel the click event on #tutorial
		// var evnt = window.event?window.event:arg;
		// if(evnt.stopPropagation){
		// 	evnt.stopPropagation();
		// }else{
		// 	evnt.cancelBubble=true;
		// }
		e.preventDefault();
		$('#tutorial').hide();
		setTutorial(1);
		setUserTutorial(1);
		tutorial_slide = 1;
	});
	
	$(document).keyup(function(e){
		// console.log(e.keyCode);
		if (e.keyCode == 27){
			$('#tutorial').hide();
			setTutorial(1);
			setUserTutorial(1);
			tutorial_slide = 1;
		}
		if (e.keyCode == 37){
			//Left
			var prev = $("#tutorial ul li.selected").index();
			if (prev > 0) setTutorial(prev);
		}
		if (e.keyCode == 39){
			//Right
			var next = $("#tutorial ul li.selected").index()+2;
			if (next < 5) setTutorial(next);
		}
	});
	
	$('#tutoicon').on('click',function(){
		$('#tutorial').show();
	})
	
	//check tutotial status
	
	if(firsttime!=1){

		$('#tutorial').show();
	}	
	
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
		$('#item-mail').show();
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

$("*").on("scroll",function(e){
	console.log(e);
});

$(window).load(function(){
	if (window.location.hash != "")	checkHash();
	$(window).hashchange(function(){
		checkHash();
	});
});