//cursor coordinates
var cursorX = 0;
var cursorY = 0;
var mouseDown = false;

//background offset
var xPos = 0;
var yPos = 0;

//floating boxes offset
var floatX = 0;
var floatY = 0;
var floatRange = 5;

//focus
var openedImage = false;
var focusReady = true;
var imageContainer = document.getElementById("focus");
var imageFocus = document.getElementById("focus-image");
var darken = document.getElementById("darken");
darken.style.opacity = 1;
darken.style.zIndex = 5;

//tips
var tip = document.getElementById("cursor");
var shownTip = false;
var tipShowTime = false;
var removeTipTime = false;
var tipRemoved = false;
tip.style.opacity = 0;

//loading
var loader = document.getElementById("loader");
var loaded = false;

window.scrollTo(0, 0);

window.onbeforeunload = function () {
	window.scrollTo(0, 0);
}

window.addEventListener("load", function () {
	setup();
	setTimeout(function(){loaded = true;}, 500);
	setTimeout(function(){tipShowTime = true;}, 5000);
	window.scrollTo(0, 0);
});

//on mouse move
window.addEventListener('mousemove', function(e) {
	e.preventDefault();

	//get cursor coordinates
	cursorX = e.clientX;
	cursorY = e.clientY;

	//drag tip
	if (removeTipTime) {
		tip.style.opacity = 0;
		setTimeout(function(){
			tip.style.zIndex = -100;
			tipRemoved = true;
		}, 500);
	}

	if (!/Mobi/.test(navigator.userAgent) && !shownTip && tipShowTime) {
		shownTip = true;
		tip.style.zIndex = 100;
		tip.style.opacity = 1;
		setTimeout(function(){removeTipTime = true;}, 5000);
	}

	if (!tipRemoved) {
		tip.style.left = cursorX + 20 + "px";
		tip.style.top =  cursorY + "px";
	}

	//don't show drag tip if user has already showcased knowledge
	if (document.body.scrollLeft > 20 || document.documentElement.scrollLeft) {
		shownTip = true;
		removeTipTime = true;
	}

	//if mouse is being dragged, offset background and scroll page
	if (mouseDown && loaded) {
		window.scrollTo(document.body.scrollLeft + (xPos - e.clientX), document.body.scrollTop + (yPos - e.clientY));
		document.body.style.backgroundPosition = (window.pageXOffset / 2) + "px " + (window.pageYOffset / 2) + "px";
		darken.style.backgroundPosition = (window.pageXOffset / 2) + "px " + (window.pageYOffset / 2) + "px";
	}
});

//on mouse click, get page scroll
window.addEventListener('mousedown', function(e) {
	e.preventDefault();
	if (e.button === 0) {
		xPos = e.pageX;
		yPos = e.pageY;
		
		//if image is opened and mouse is clicked, leave focus mode
		if (openedImage && focusReady) {
			unfocus();
		}

		mouseDown = true;
	}
});

window.addEventListener('mouseup', function(e) {
	e.preventDefault();
	mouseDown = false;
	if (!focusReady) {
		focusReady = true;
	}
});

//on click images, make background and image container appear
function setup() {
	loader.style.opacity = 0;
	darken.style.opacity = 0;
	setTimeout(function(){loader.style.zIndex = -15;}, 500);
	setTimeout(function(){darken.style.zIndex = -10;}, 500);

	document.body.style.backgroundPosition = (window.pageXOffset / 2) + "px " + (window.pageYOffset / 2) + "px";
	darken.style.backgroundPosition = (window.pageXOffset / 2) + "px " + (window.pageYOffset / 2) + "px";

	//if (!/Mobi/.test(navigator.userAgent)) {

		var images = document.getElementsByClassName("head-image");

		for (let i = 0; i < images.length; i++) {
			images[i].addEventListener("mousedown", function(e) {
				if (!openedImage && e.button === 0) {
					var u = images[i].src;
					focus(u);
				}
			});
		}

		var smallImages = document.getElementsByClassName("small-image");

		for (let i = 0; i < smallImages.length; i++) {
			smallImages[i].addEventListener("mousedown", function(e) {
				if (!openedImage && e.button === 0) {
					var u = smallImages[i].style.backgroundImage;
					focus(u.substring(4, u.length - 1));
				}
			});
		}
	}
//}

function focus(imagepath) {
	imagepath = imagepath.replace(/["']/g, "");

	imageContainer.style.display = "block";
	imageFocus.src = imagepath;

	darken.style.zIndex = 5;
	darken.style.opacity = 1;
	
	openedImage = true;
	focusReady = false;
}

function unfocus() {
	imageContainer.style.display = "none";

	darken.style.opacity = 0;
	setTimeout(function(){darken.style.zIndex = -10;}, 500);

	openedImage = false;
}