var pieceDimension = 900;
var columns = 3;
var rows = 1;
var margins = 120;
var padding = 0;

var html = document.documentElement;
var body = document.getElementsByTagName("BODY")[0];
var board = document.getElementById('board');
var darken = document.getElementById('darken');

//set dimensions of containers
var width = ((pieceDimension + (margins * 2)) * columns) + (margins * 2) + 'px';
var height = ((pieceDimension + (margins * 2)) * rows) + (margins * 2) + 'px';

html.style.width = width;
html.style.height = height;
body.style.width = width;
body.style.height = height;
board.style.width = width - (margins * 2);
board.style.height = height - (margins * 2);
darken.style.width = width;
darken.style.height = height;
board.style.margin = margins + 'px';

//set dimensions of pieces
var pieces = document.getElementsByClassName("piece");

for (var i = 0; i < pieces.length; i++) {
	pieces[i].style.margin = margins + 'px';
	pieces[i].style.padding = padding + 'px';
	pieces[i].style.width = pieceDimension + 'px';
	pieces[i].style.height = pieceDimension + 'px';
}

//set dimensions of picture frames
var limited = document.getElementsByClassName("small-image-limited");
var partial = document.getElementsByClassName("small-image-partial-limited");
var full = document.getElementsByClassName("small-image-full");

for (var i = 0; i < limited.length; i++) {
	limited[i].style.height = limited[i].offsetWidth + 'px';
}

for (var i = 0; i < partial.length; i++) {
	partial[i].style.height = partial[i].offsetWidth + 'px';
}

for (var i = 0; i < full.length; i++) {
	full[i].style.height = full[i].offsetWidth + 'px';
}