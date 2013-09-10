function loadSlices() {
	$.getJSON('slices.php', function(data) {
		$.each(data, function(key, val) {
			if (data[key] != false) {
				$newLi = $("<li />", {'class': 'one-item', 'id': 'item-'+val["id"], 'html':'<div class="info"><h2>'+val["title"]+'</h2><div class="description">'+val["description"]+'</div></div><div class="img-cover"><img src="img/'+val["img_tall"]+'" alt="mail cover" /><div class="meta" id="title">'+val["title"]+'</div><div class="meta" id="body">'+val["HTML"]+'</div></div><a href="'+val["img_large"]+'" class="img-src"></a><div class="item-content"></div>'}).appendTo("ul.slides");
			}

			console.log(key);
			if (key == 2) {
				$newLi = $("<li />", {'class': 'one-item', 'id': 'item-mail', 'html':'<div class="info"><h2>Ken, Good seeing you at the CFE Board meeting...</h2><div class="description">Read your messages.</div></div><div class="img-cover"><img src="img/mail_cover.jpg" alt="mail cover" /></div><div class="meta" id="title">New Messages</div><div class="meta" id="body">stuff</div></div><a href="mail.jpg" class="img-src"></a><div class="item-content"></div>'}).appendTo("ul.slides");
			}
			if (key == 6) {
				$newLi = $("<li />", {'class': 'one-item', 'id': 'item-mail', 'html':'<div class="info"><h2>Campus Map</h2><div class="description">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div></div><div class="img-cover"><img src="img/map_cover.jpg" alt="map cover" /></div><div class="meta" id="title">New Messages</div><div class="meta" id="body">stuff</div></div><a href="mail.jpg" class="img-src"></a><div class="item-content"></div>'}).appendTo("ul.slides");

				///////////////////////////////////
				// When you open the big image //
				///////////////////////////////////

				$(".img-cover").each(function(index, element) {
					var item_id = $(this).parent().attr("id"), itemheight;

					////////////////////////////////////////////////////////////////////////////////////////
					// Click event: when click on an image, load the image and append it into the HTML //
					////////////////////////////////////////////////////////////////////////////////////////
					$(this).click(function(e) {
						window.location.hash = item_id;
						$(this).parent().addClass("current");
						var title = $(this).children(".meta#title").html(), body = $(this).children(".meta#body").html();
						current = item_id;
						$("body").removeClass("slices");
						$(".left, .info").hide();
						$("body").css({"left":"0","width":"100%", "overflow-x" : "hidden", "overflow-y" : "auto"});
						$(".items").css({"left":"0","width":"100%","overflow":"hidden"});
						
						// Temp
						item_id_img = $("#" + current + " a.img-src").attr("href");
						
						// Clear all content in all items
						$("#" + item_id + " .img-cover").hide();

						///////////////////////////////////
						// Body of the opened window //
						///////////////////////////////////
						if (typeof($(this).parent()[0].dataset.video) == "string") {
							var vid = $(this).parent()[0].dataset.video;
							if ($(this).find("#title")[0].dataset.position == "top") {
								$("#" + item_id + " .item-content").html('<div class="content-image-div"><h2>'+title+'</h2><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><div class="body">'+body+'</div>');
							}
							else {
								$("#" + item_id + " .item-content").html('<div class="content-image-div"><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><h2>'+title+'</h2><div class="body"><iframe width="560" height="315" src="http://www.youtube.com/embed/'+vid+'" frameborder="0" allowfullscreen></iframe>'+body+'</div>');
							}

						}
						else if ($(this).parent().hasClass("map")) {
							$("#" + item_id + " .item-content").html('<div class="content-image-div iframe"><iframe class="content-iframe" src="http://www.engin.umich.edu/college/admissions/visit/nc-live-map/"></iframe></div><!--div class="content-info"><h2>'+title+'</h2><div class="body">'+body+'</div-->');
						}
						else if ($(this).find("#title")[0].dataset.position == "top") {
							console.log("Hello");
							$("#" + item_id + " .item-content").html('<div class="content-image-div"><h2>'+title+'</h2><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><div class="body">'+body+'</div>');
						}
						else {
							$("#" + item_id + " .item-content").html('<div class="content-image-div"><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><h2>'+title+'</h2><div class="body">'+body+'</div>');
						}

						/////////////////////////////////////
						// Scroll body back to the top //
						/////////////////////////////////////
						$(".slides, body").scrollTop(0);
						$(".items").scrollLeft(0);
						$(window).resize();


						///////////////////////////////
						// Get height of img div //
						///////////////////////////////
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
						$("#go-back").show();

						// Expand the selected item to full screen, and make all other cover images 0 so they will disappear
						$(".one-item").each(function(index, element) {
							if ($(this).attr("id") != item_id) {
								$(this).css({"width" : "0px"});
								var thisitem = $(this);
								setTimeout(function(){
									thisitem.css({"height" : "0px"});
								}, 500);
							} else {
								$(this).css({"width" : width+"px", "overflow" : "visible"});
								$(window).scroll(function(e) {

									if (!($("body").hasClass("slices"))) {
										var opac = (itemheight - $("body").scrollTop())/itemheight;
										if (opac >= 0.01 && opac <= 1) {
											console.log("TEST");
											console.log(opac);
											$("#" + current + " .content-image-div").css({"opacity" : opac});
										}
										else if (opac < .01) {
											$("#" + current + " .content-image-div").css({"opacity" : "0"});
										}
									}
								});
							}
						});
					});
				});
			}
		});
	});
}

function hideSlices() {
	$(".img-cover").off("click");
	$("ul.slides li").animate({"opacity":"0"}, 2000, function(){
		$(this).remove();
	});
	$("body").removeClass("slices");
	$(window).resize();
}

function loadExplore () {

	$("body").addClass("explore");

	$.getJSON('explore.php', function(data) {
		$.each(data, function(key, val) {
			if (data[key] != false) {
				$newLi = $("<li />", {'class': 'explore-item', 'id': 'item-'+val["id"], 'html':'<div class="img-cover"><img src="img/tiles/'+val["img_sm"]+'" alt="mail cover" /></div><div class="info"><h2>'+val["title"]+'</h2><div class="description">'+val["description"]+'</div></div><div class="meta" id="title">'+val["title"]+'</div><div class="meta" id="body">'+val["HTML"]+'</div></div><a href="'+val["img_large"]+'" class="img-src"></a><div class="item-content"></div>'}).appendTo("ul.slides");
			}
			if (key == 6) {
				
				///////////////////////////////////
				// When you open the big image //
				///////////////////////////////////

				$(".img-cover").each(function(index, element) {
					var item_id = $(this).parent().attr("id"), itemheight;

					////////////////////////////////////////////////////////////////////////////////////////
					// Click event: when click on an image, load the image and append it into the HTML //
					////////////////////////////////////////////////////////////////////////////////////////
					$(this).click(function(e) {
						window.location.hash = item_id;
						$(this).parent().addClass("current");
						var title = $(this).children(".meta#title").html(), body = $(this).children(".meta#body").html();
						current = item_id;
						$("body").removeClass("explore");
						$(".left, .info").hide();
						$("body").css({"left":"0","width":"100%", "overflow-x" : "hidden", "overflow-y" : "auto"});
						$(".items").css({"left":"0","width":"100%","overflow":"hidden"});
						
						// Temp
						item_id_img = $("#" + current + " a.img-src").attr("href");
						
						// Clear all content in all items
						$("#" + item_id + " .img-cover").hide();

						///////////////////////////////////
						// Body of the opened window //
						///////////////////////////////////
						if (typeof($(this).parent()[0].dataset.video) == "string") {
							var vid = $(this).parent()[0].dataset.video;
							if ($(this).find("#title")[0].dataset.position == "top") {
								$("#" + item_id + " .item-content").html('<div class="content-image-div"><h2>'+title+'</h2><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><div class="body">'+body+'</div>');
							}
							else {
								$("#" + item_id + " .item-content").html('<div class="content-image-div"><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><h2>'+title+'</h2><div class="body"><iframe width="560" height="315" src="http://www.youtube.com/embed/'+vid+'" frameborder="0" allowfullscreen></iframe>'+body+'</div>');
							}

						}
						else if ($(this).parent().hasClass("map")) {
							$("#" + item_id + " .item-content").html('<div class="content-image-div iframe"><iframe class="content-iframe" src="http://www.engin.umich.edu/college/admissions/visit/nc-live-map/"></iframe></div><!--div class="content-info"><h2>'+title+'</h2><div class="body">'+body+'</div-->');
						}
						else if ($(this).find("#title")[0].dataset.position == "top") {
							console.log("Hello");
							$("#" + item_id + " .item-content").html('<div class="content-image-div"><h2>'+title+'</h2><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><div class="body">'+body+'</div>');
						}
						else {
							$("#" + item_id + " .item-content").html('<div class="content-image-div"><img class="content-image" src="img/big/' + item_id_img + '" alt="item image" /></div><div class="content-info"><h2>'+title+'</h2><div class="body">'+body+'</div>');
						}

						/////////////////////////////////////
						// Scroll body back to the top //
						/////////////////////////////////////
						$(".slides, body").scrollTop(0);
						$(".items").scrollLeft(0);
						$(window).resize();


						///////////////////////////////
						// Get height of img div //
						///////////////////////////////
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
						$("#go-back").show();

						// Expand the selected item to full screen, and make all other cover images 0 so they will disappear
						$(".one-item").each(function(index, element) {
							if ($(this).attr("id") != item_id) {
								$(this).css({"width" : "0px"});
								var thisitem = $(this);
								setTimeout(function(){
									thisitem.css({"height" : "0px"});
								}, 500);
							} else {
								$(this).css({"width" : width+"px", "overflow" : "visible"});
								$(window).scroll(function(e) {

									if (!($("body").hasClass("slices"))) {
										var opac = (itemheight - $("body").scrollTop())/itemheight;
										if (opac >= 0.01 && opac <= 1) {
											console.log("TEST");
											console.log(opac);
											$("#" + current + " .content-image-div").css({"opacity" : opac});
										}
										else if (opac < .01) {
											$("#" + current + " .content-image-div").css({"opacity" : "0"});
										}
									}
								});
							}
						});
					});
				});
			}
		});
	});
	$(window).resize();


}

function resizeWindow() {
	console.log("resizing");
	height = $(window).height(), width = $(window).width();
	console.log(width);
	var leftwidth = $(".left").width(), itemsWidth = width - leftwidth, itemsHeight = height-51;
	$("body").css({"height" : itemsHeight+"px"});
	if (height > 800) {
		$(".one-item .img-cover img").css({"width" : "auto"});
		$(".one-item .img-cover img").css({"height" : itemsHeight + "px"});
	}
	else {
		$(".one-item .img-cover img").css({"width" : "auto"});
	}
	//Resize large image
	$(".left").css("height",itemsHeight);
	$("li.current").css({"width":width+"px"});
	$(".slices .items").css({"width":itemsWidth, "height":"auto"});
	$(".explore .items").css({"width":"", "height":""});

	if ($("body").hasClass("slices")) {

	}
	else {
		$("#" + current).css({"width":width + "px"});
	}
	// $(".items").css({"height" : height + "px"});
	bodymargin = $(".content-image-div").height()+"px";
	$(".content-info").css({"margin-top":bodymargin});
	$(".slices .items").css({"left":leftwidth+"px"});
	$(".explore .items").css({"left":""});


	var ifwidth = $(".content-info div.body iframe").width(), ifheight = ifwidth * (2/3);

	$(".content-info div.body iframe").css({"height":ifheight+"px"});
}

var current, bodymargin, imagemargin;
$(document).ready(function(e) {

	loadSlices();

	var width = $(window).width(), height = $(window).height();
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
	$("#go-back").click(function(e) {
		var leftwidth = $(".left").width()+ "px";
		$("li").removeClass("current");
		window.location.hash = "";
		$(".items").scrollTop(0);
		$("body").addClass("slices");
		$(".content-info, .content-image").hide();
		$(".left, .info").show();
		$(".mobile-left").hide();
		$(".items").css({"left":leftwidth,"overflow-x" : "scroll", "overflow-y" : "hidden"});
		$("body").css({"overflow-y":"hidden"});
		$(".one-item").each(function(index, element) {
			$(this).css({"width":"","height" : "100%", "overflow" : "hidden"});
		});
		setTimeout(function(){
			$("#" + current + " .img-cover").fadeIn(500, function(){
				$(".item-content").html("");
				$("#go-back").hide();
				$(window).resize();
			});
		}, 500);
	});

	$(window).resize();

	/////////////////////////////////////////////////////////////
	//  Open the corresponding page if there is a hash in url  //
	/////////////////////////////////////////////////////////////
	if (window.location.hash) {
		$(window.location.hash + " .img-cover").click();
	}

	////////////////////
	//  Hover states  //
	////////////////////
	$(".one-item .info").mouseenter(function(e){
		$(this).parent().find(".img-cover").addClass("opaque");
	});
	$(".one-item .info").mouseleave(function(e){
		$(this).parent().find(".img-cover").removeClass("opaque");
	});

});