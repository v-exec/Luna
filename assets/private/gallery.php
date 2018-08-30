<?php global $artifacts; ?>

<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>luna · <?php echo $v?></title>

	<meta name="viewport" content="width=1200, initial-scale=0.8, minimum-scale=0.5">

	<meta property="og:url" content="https://luna.v-os.ca/">
	<meta property="og:title" content="THE LUNA GALLERY">
	<meta property="og:type" content="website">
	<meta property="og:description" content="The Luna Gallery">
	<meta property="og:image" content="https://luna.v-os.ca/assets/ui/logo.svg">

	<meta name="twitter:url" content="https://luna.v-os.ca/">
	<meta name="twitter:title" content="THE LUNA GALLERY">
	<meta name="twitter:card" content="summary">
	<meta name="twitter:description" content="The Luna Gallery">
	<meta name="twitter:image" content="https://luna.v-os.ca/assets/ui/logo.svg">

	<meta name="description" content="The Luna Gallery">
	<meta name="keywords" content="Digital, Art, Design, Videogames, Games, Music, Montreal">
	<meta name="author" content="Lucency">

	<link rel='icon' href='https://luna.v-os.ca/assets/ui/icon.png' type='image/ico'>

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i,900,900i|Roboto+Mono">
	<link rel="stylesheet" type="text/css" href="assets/styles/style.css?ver=<?php echo filemtime('assets/styles/style.css');?>">
</head>

<body>
	<div id="cursor">
		<span id="cursor-text">Use the mouse to drag<br>horizontally and vertically.</span>
	</div>

	<div id="loader">
		<img id="load-logo" src="../assets/ui/logo.svg"></img>
		<span id="load-text"><i>Loading Gallery - High Resolution Media</i></span>
	</div>
	
	<div id="darken"></div>
	<div id="focus">
		<img id="focus-image" src=""></img>
	</div>

	<ul id="board">
	<div id="top">
		<img id="logo" src="../assets/ui/logo.svg"></img>
		<span>Luna · A monthly exhibit showcasing up to 9 pieces,<br>hosted by <i><a href="https://v-os.ca/lucency">Lucency</a></i>.<br><br>This month's theme: <i><b>Progress</b></i>.</span>
	</div>

		<?php
			for ($i = 0; $i < sizeof($artifacts); $i++) {
				$art = $artifacts[$i];
				if ($art->hasTag('september 2018')) {
					echo '<li class="piece">';
					if ($art->hasTag('2 columns')) twoColumns($art);	
					else if ($art->hasTag('2 columns double')) twoColumnsDouble($art);
					else if ($art->hasTag('2 columns triple')) twoColumnsTriple($art);
					else if ($art->hasTag('text heavy')) textHeavy($art);
					else if ($art->hasTag('text heavy header')) textHeavyHeader($art);						
					else if ($art->hasTag('wide')) wide($art);
					else if ($art->hasTag('wide double')) wideDouble($art);
					else if ($art->hasTag('collection')) collection($art);
					echo '</li>';
				}
			}
		?>
	</ul>

<script src="assets/scripts/sizer.js"></script>
<script src="assets/scripts/logic.js"></script>

</body>
</html>

<?php

//2 columns (7 images)
//simple, organized, generic
function twoColumns($art) {
	echo '<div class="left-column-large">';
	if ($art->attributes['embed'] === 'true') echo $art->attributes['images'];
	else echo '<img class="head-image" src="'. $art->attributes['images'][0] .'"></img>';
	for ($j = 1; $j < sizeof($art->attributes['images']); $j++) {
		if ($j == 3 || $j == 6) echo '<div class="small-image small-image-limited collection-dry" style="background-image: url('. $art->attributes['images'][$j] .')"></div>';
		else echo '<div class="small-image small-image-limited collection-push" style="background-image: url('. $art->attributes['images'][$j] .')"></div>';
	}
	echo '</div>';

	echo '<div class="right-column-small">';
		echo '<span class="title">'. $art->attributes['title'] .'</span>';
		echo '<span class="author">'. $art->attributes['author'] . '</span>';
		echo $art->attributes['content'];
	echo '</div>';
}

//2 columns with 2 large images (5 images)
//simple, organized, generic, with 2 header images
function twoColumnsDouble($art) {
	echo '<div class="left-column-large">';
	echo '<img class="head-image" src="'. $art->attributes['images'][0] .'"></img>';
	echo '<img class="head-image" src="'. $art->attributes['images'][1] .'"></img>';
	for ($j = 2; $j < sizeof($art->attributes['images']); $j++) {
		if ($j == 4) echo '<div class="small-image small-image-limited collection-dry" style="background-image: url('. $art->attributes['images'][$j] .')"></div>';
		else echo '<div class="small-image small-image-limited collection-push" style="background-image: url('. $art->attributes['images'][$j] .')"></div>';
	}
	echo '</div>';

	echo '<div class="right-column-small">';
		echo '<span class="title">'. $art->attributes['title'] .'</span>';
		echo '<span class="author">'. $art->attributes['author'] . '</span>';
		echo $art->attributes['content'];
	echo '</div>';
}

//2 columns with 3 large images (3 images)
//simple, organized, generic, with 3 header images
function twoColumnsTriple($art) {
	echo '<div class="left-column-large">';
	echo '<img class="head-image" src="'. $art->attributes['images'][0] .'"></img>';
	echo '<img class="head-image" src="'. $art->attributes['images'][1] .'"></img>';
	echo '<img class="head-image" src="'. $art->attributes['images'][2] .'"></img>';
	echo '</div>';

	echo '<div class="right-column-small">';
		echo '<span class="title">'. $art->attributes['title'] .'</span>';
		echo '<span class="author">'. $art->attributes['author'] . '</span>';
		echo $art->attributes['content'];
	echo '</div>';
}

//text-heavy, no header (4 images)
//text-centric, small images act as support to text
function textHeavy($art) {
	echo '<div class="left-column-extra-large">';
	echo '<span class="title">'. $art->attributes['title'] .'</span>';
	echo '<span class="author">'. $art->attributes['author'] . '</span>';
	echo $art->attributes['content'];
	echo '</div>';

	echo '<div class="right-column-extra-small">';
	for ($j = 0; $j < sizeof($art->attributes['images']); $j++) {
		echo '<div class="small-image small-image-full collection-dry" style="background-image: url('. $art->attributes['images'][$j] .')"></div>';
	}
	echo '</div>';
}

//text heavy, header (3 images)
//text heavy with header-image
function textHeavyHeader($art) {
	echo '<span class="title">'. $art->attributes['title'] .'</span>';
	echo '<span class="author">'. $art->attributes['author'] . '</span>';

	echo '<div class="wide-full">';
	echo $art->attributes['content'];
	echo '</div>';

	echo '<div class="left-column-extra-small">';
	for ($j = 1; $j < sizeof($art->attributes['images']); $j++) {
		echo '<div class="small-image small-image-full collection-dry" style="background-image: url('. $art->attributes['images'][$j] .')"></div>';
	}
	echo '</div>';

	echo '<div class="right-column-extra-large">';
	echo '<img class="head-image" src="'. $art->attributes['images'][0] .'"></img>';
	echo '</div>';
}

//full-width image (1 image)
//one full-width image, for image-centric pieces
function wide($art) {
	if ($art->attributes['embed'] === 'true') echo $art->attributes['images'];
	else echo '<div class="small-image head-image-standalone" style="background-image: url('. $art->attributes['images'][0] .')"></div>';

	echo '<div class="wide-full">';
	echo '<span class="title">'. $art->attributes['title'] .'</span>';
	echo '<span class="author">'. $art->attributes['author'] . '</span>';
	echo $art->attributes['content']; 
	echo '</div>';
}

//2 full-width images (2 images)
//full focus on two dual, full-width images
function wideDouble($art) {
	echo '<div class="wide-limited">';
	echo '<div class="left-column-medium">';
	echo '<img class="head-image" src="'. $art->attributes['images'][0] .'"></img>';
	echo '</div>';
	echo '<div class="right-column-medium">';
	echo '<img class="head-image" src="'. $art->attributes['images'][1] .'"></img>';
	echo '</div>';
	echo '</div>';

	echo '<div class="wide-full">';
	echo '<span class="title">'. $art->attributes['title'] .'</span>';
	echo '<span class="author">'. $art->attributes['author'] . '</span>';
	echo $art->attributes['content']; 
	echo '</div>';
}

//collection of images, no header (8 images)
//for collection projects
function collection($art) {
	echo '<span class="title">'. $art->attributes['title'] .'</span>';
	echo '<span class="author">'. $art->attributes['author'] . '</span>';

	echo '<div class="wide-full">';
	for ($j = 0; $j < sizeof($art->attributes['images']); $j++) {
		if ($j == 3 || $j == 7) echo '<div class="small-image small-image-partial-limited collection-dry" style="background-image: url('. $art->attributes['images'][$j] .')"></div>';
		else echo '<div class="small-image small-image-partial-limited collection-push" style="background-image: url('. $art->attributes['images'][$j] .')"></div>';
	}
	echo '</div>';

	echo '<div class="wide-full">';
	echo $art->attributes['content']; 
	echo '</div>';
}
?>