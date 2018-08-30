<?php
//get artifact to load
if (isset($_GET['v'])) {
	if ($_GET['v']) {
		$v = strtolower($_GET['v']);
	} else $v = 'gallery';
} else {
	$_GET['v'] = 'gallery';
	$v = $_GET['v'];
}

include 'assets/private/parser.php';
include 'assets/private/artifact.php';

//name of directory for artifact declarations
$pageDirectory = 'pages';

//single parser for all artifacts
$parser = new Parser();

//array holding artifacts
$artifacts = array();

//creates and formats artifacts
createArtifacts();
formatArtifacts();

//load page
if ($v == 'gallery') {
	include 'assets/private/gallery.php';
}
?>