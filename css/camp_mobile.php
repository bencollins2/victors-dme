.left.one-item {
    background: #FEC33A;
}

.left {
    display: block;
    position: absolute;
    z-index: 10;
    height: 100%;
    width: 270px;
    overflow: auto;
}

.left .leftlogo {
    position: relative;
    display: block;
    margin: 34px auto 13px;
    width: 180px;
}

.left .lefttext {
    width: 200px;
    text-align: center;
    display: block;
    margin: 0 auto;
    color: #032B5B;
    font-weight: 100;
    border-bottom: thin solid rgba(3, 43, 91, .3);
    border-top: thin solid rgba(3, 43, 91, .3);
    padding: 10px 0;
    font-size: 16px;
    line-height: 1.5;
}

.left h2 {
    font-family: "league-gothic", sans-serif;
    margin: 10px auto;
    color: #032B5A;
    text-transform: uppercase;
    font-size: 40px;
    line-height: 1em;
}

.left h3 {
    color: #032B5A;
    margin: 0 0 20px;
    font-weight: 100;
    font-style: italic;
}

.left p {
    font-size: 13px;
}

.left .readytogive {
    position: absolute;
    bottom: 10px;
    left: 50%;
    margin: 0 0 0 -95px;
}

.left .readytogive img {
    float: left;
}

.left .readytogive a {
    clear: none;
    display: block;
    margin: 9px 0;
    width: 180px;
    font-weight: 100;
    text-decoration: underline;
}

.items {
	position: absolute;
	width: 1080px;
	left: 0;
	top: 51px;
	height: 800px;
}

.itemcontainer {
    width: 1260px;
    height: 100%;
    padding: 0;
    display: block;
    position: relative;
}

ul.slider {
	list-style: none;
}

ul.slides {
    width: 100%;
    height: 100%;
    margin: 0;
    background: transparent;
    padding: 0;
    overflow: hidden;
}

li.current {
    transition: none !important;
    background: transparent;
}

.one-item {
	background: black;
	float: left;
	margin: 0;
	position: relative;
	display: block;
	overflow: hidden;
	-webkit-transition: all 500ms ease;
	-moz-transition: all 500ms ease;
	-o-transition: all 500ms ease;
	transition: width 500ms ease;
	height: 100%;
	opacity: 1;
}

.one-item:hover {
    opacity: 1;
}

.one-item .info {
    position: absolute;
    top: 10%;
    width: 156px;
    height: 180px;
    background: rgba(33,33,33,.8);
    font-size: 14px;
    color: white;
    padding: 0 12px 20px;
    border-top: 6px solid black;
    border-bottom: 6px solid black;
    -webkit-transition: border .3s ease, top .5s ease, width .5s ease;
    transition: border .3s ease, top .5s ease, width .5s ease;
    z-index: 200;
    cursor: pointer;
}

.one-item:hover .info {
    border-top: 6px solid #FEC33A;
    border-bottom: 6px solid #FEC33A;
}

.one-item .info h2 {
    font-family: "league-gothic",sans-serif;
    text-transform: uppercase;
    margin: 10px 0;
    font-size: 26px;
    font-weight: 100;
    line-height: 28px;
    cursor: text;
    -o-transition: all .5s ease;
    transition: all .5s ease;
}

.one-item .info div {
    font-weight: 100;
    cursor: text;
}

.img-cover {
    display: block;
    position: absolute;
    z-index: 100;
    top: 0;
}

.one-item .img-cover {
    opacity: .5;
    background-color: black;
    -webkit-transition: opacity .2s ease;
}

.one-item .img-cover img {
	width: 180px;
	min-height: 800px;
	-webkit-transition: opacity .2s ease;
}

.one-item .img-cover:hover {
	opacity: 1;
}

.img-cover:hover, #go-back:hover {
	cursor: pointer;
}

.img-cover .meta {
	display: none;
}

#go-back {
	color: #efefef;
	position: absolute;
	top: 13px;
	right: 12px;
	z-index: 1000;
	font-weight: 100;
	font-size: 13px;
	border-bottom: thin solid;
}

.collapsed #go-back {
    display: none;
}
.img-src {
	display: none;
}

.item-content {
    position: absolute;
    height: 100%;
    width: 100%;
    margin: 0;
    padding: 0;
}

.item-content img {
    height: auto;
    min-width: 600px;
    width: 100%;
    z-index: -1;
    transition: opacity 2s ease-out;
}

.content-image-div {
    display: block;
    opacity: 1;
    top: 0;
    left: 0;
    right: 0;
    z-index: -1;
    position: fixed;
    width: 100%;
    max-height: 610px;
    overflow: hidden;
    background-color: #FEC33A;
    box-shadow: inset 0 -10px 80px rgba(0,0,0,.4);
}

.collapsed .content-info {
	
}

.content-info {
    /*position: absolute;
    top: 50%;
    left: 50%;*/
    display: block;
    position: relative;
    background: #fefefe;
    max-width: 960px;
    width: 85%;
    margin: 0 auto;
    padding: 20px 20px 30px;
    /*z-index: 1000 !important;*/
    z-index: 100 !important;
}

.content-info h2 {
    font-family: "league-gothic",sans-serif;
    margin: 0 0 10px;
    font-weight: 100;
    text-transform: uppercase;
    font-size: 58px;
}

.content-info div.body {
    width: auto;
}

.sticky {
	background: none repeat scroll 0 0 #171100;
    /*box-shadow: 0 2px 4px #333333;
    -webkit-box-shadow: 0 2px 4px #333333;*/
    display: block;
    height: 51px;
	position: fixed !important;
    top: 0;
	left: 0;	
    width: 100%;
	overflow: visible;
	padding: 0;
	z-index: 1000;
}

.ie7 .sticky {
}

a#circle {
	display: block;
	position: absolute;
	right: -60px;
	height: 48px;
	width: 48px;
	top: -12px;	
}

#holder {
    height: 260px;
}

#top-header-nav a {
	text-decoration: none;
	display: inline-block;
	border: thin solid black;
	padding: 1px 6px;
	background: black;
}

.sticky ul {
	display: block;
	margin: 0 auto;
	overflow: visible;
	height: 50px;
	background: url(../img/dmebadge.jpg) scroll no-repeat right 3px;
}

.sticky ul li {
	list-style: none;
	padding: 0;
	display: inline-block;
	margin: 4px 8px 0 0;
	color: white;
}

.sticky ul .simplify a {
	text-align: right;
	right: 0;
	position: absolute;
	width: 270px;	
}

.dmegallery {
	right: 57px;
	display: block;
	position: absolute;
	top: 9px;
}

.sticky ul li a {
	color: #ddd;
	text-decoration: none;
	font-size: 13px;
	font-weight: 100;
    -o-transition: all 1s ease;
    transition: all 1s ease;
}

.sticky ul .home {
	position: absolute;
	/*width: 43px;*/
	overflow: hidden;
	left: 7px; top: 8px;
	margin: 0;
}

.sticky ul .counts {
	float: none;	
}

.opaque {
    opacity: 1 !important;
}

@media only screen and (max-height: 800px) {
    .img-cover {
        top: auto;
        bottom: 0;
    }
    .one-item .info {
        top: 7%;
    }
}

@media only screen and (max-width: 1200px) {
    .left {
        width: 220px;
    }

    .left .leftlogo {
        width: 140px;
    }

    .itemcontainer {
        width: 1050px;
    }

    .left .lefttext {
        width: 170px;
    }

    .items {
    }

    .one-item {
        width: 150px;
    }

    .one-item .info {
        width: 130px;
    }

    .one-item .info h2 {
        font-size: 22px;
        line-height: 23px;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }
    .one-item .info .description {
        font-size: .9em;
    }

}

@media only screen and (max-width: 400px) {
	ul.slider {
		margin: 0;
		padding: 0;
	}
	
	.mobile-left {
		display: none;
		position: relative;
		top: 0;
		height: 100%;
		width: 270px;
		overflow: auto;
		background: #FEC33A;
		min-height: 810px;
		box-shadow: 0 0 10px black;
	}
	
	.items, .itemcontainer {
	}
}