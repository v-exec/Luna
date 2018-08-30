<?php
/*
Parser holds a variety of functions made to parse the various attributes of an artifact (typically into the corresponding html).
It features a series of generalized formatting functions.

In the event of an expansion or customization of this system,
new parsing criteria and features can seamlessly be introduced as a basic addition or modifications to the existing code.
*/
class Parser {
	//image directory
	private $imageDirectory = 'images';

	//goes through all artifact attributes that are independant from other artifacts and formats each one according to existing formatting rules
	public function firstFormat($artifact) {
		//trim tags
		if ($artifact->tags) {
			for ($i = 0; $i < sizeof($artifact->tags); $i++) {
				$artifact->tags[$i] = trim($artifact->tags[$i]);
			}
		}

		//format images collection
		if ($artifact->attributes['embed'] === 'false') {
			if ($artifact->attributes['images']) $artifact->attributes['images'] = $this->collectImages($artifact->attributes['images']);
		}

		//format title
		if ($artifact->attributes['title']) {
			$this->formatText($artifact, 'title', '@');
			$this->formatText($artifact, 'title', '_');
			$this->formatText($artifact, 'title', '*');
			$this->formatText($artifact, 'title', '>');
		}

		//format author
		if ($artifact->attributes['author']) {
			$this->formatText($artifact, 'author', '@');
			$this->formatText($artifact, 'author', '_');
			$this->formatText($artifact, 'author', '*');
			$this->formatText($artifact, 'author', '>');
		}

		//format content
		if ($artifact->attributes['content']) {
			$this->formatText($artifact, 'content', '@');
			$this->formatText($artifact, 'content', '_');
			$this->formatText($artifact, 'content', '*');
			$this->formatText($artifact, 'content', '~');
			$this->formatText($artifact, 'content', '?');
			$this->formatText($artifact, 'content', '%');
			$this->formatText($artifact, 'content', '!');
			$this->formatText($artifact, 'content', '>');
		}

		//clean paragraphs
		$artifact->attributes['content'] = $this->cleanParagraphs($artifact->attributes['content']);
	}

	//finds all instances of $symbol[] within $artifact->attributes[$attribute], and replaces it with the appropriate html element
	//manages nested brackets
	private function formatText($artifact, $attribute, $symbol) {
		//check open vs closed brackets by using counter to match corresponding brackets
		//if number of opening brackets and closing brackets is uneven count, display error
		if (sizeof($this->allStringPositions($artifact->attributes[$attribute], '[')) != sizeof($this->allStringPositions($artifact->attributes[$attribute], ']'))) {
			$artifact->attributes['image'] = null;
			$artifact->attributes['images'] = null;
			$artifact->attributes['content'] = null;
			$artifact->brokenPath = null;
			$artifact->path = null;
			$artifact->tags = null;
			$artifact->attributes['title'] = 'There was an error loading this page. Please contact <a href="LOGO">LOGO</a>.';
			return;
		}

		//get first instance of '$symbol['
		$position = strpos($artifact->attributes[$attribute], $symbol.'[');

		while ($position !== false) {
			//find closing ']'
			$end = strpos($artifact->attributes[$attribute], ']', $position);

			//check if any other '[]' pairs exist within substring, suggesting we haven't found the proper ']'
			//find next ']' until we've found the proper ']'
			while (sizeof($this->allStringPositions(substr($artifact->attributes[$attribute], $position, $end - $position + 1), '[')) != sizeof($this->allStringPositions(substr($artifact->attributes[$attribute], $position, $end - $position + 1), ']'))) {
				$end = strpos($artifact->attributes[$attribute], ']', $end + 1);
			}

			//depending on $symbol, run proper format rule
			$string = substr($artifact->attributes[$attribute], $position, $end - $position + 1);
			switch ($symbol) {
				case '!':
					$new = $this->createSubtitle($string);
					break;

				case '*':
					$new = $this->createBold($string);
					break;

				case '_':
					$new = $this->createItalic($string);
					break;

				case '%':
					$new = $this->createDivider($string);
					break;

				case '@':
					$new = $this->createCustomLink($string);
					break;

				case '~':
					$new = $this->createNote($string);
					break;

				case '?':
					$new = $this->createQuote($string);
					break;

				case '>':
					$new = $this->executePHP($string);
					break;

				default:
					return;
					break;
			}
			//replace attribute with formatted attribute
			$artifact->attributes[$attribute] = str_replace($string, $new, $artifact->attributes[$attribute]);
			//find next '$symbol[' to parse
			$position = strpos($artifact->attributes[$attribute], $symbol.'[');
		}
	}

	//header title
	private function formatTitle($string) {
		global $artifacts;

		$string = $this->cleanString($string);
		if ($this->artifactExist($string)) return '<a href="'.strtolower($string).'" class="header-title">'.$string.'</a>';
		return '<span class="header-title">'.$string.'</span>';
	}

	//custom link
	private function createCustomLink($string) {
		global $artifacts;

		$string = $this->cleanString($string);
		$accessor = strpos($string, '>');

		//if accessor not found, return empty
		if ($accessor == false) return '';

		$word = trim(substr($string, 0, $accessor));
		$link = trim(substr($string, $accessor + 1, strlen($string)));

		if ($this->artifactExist($link)) return '<a href="'.strtolower($link).'">'.$word.'</a>';
		return '<a href="'.$link.'" class="external">'.$word.'</a>';
	}

	//monospaced note (note: breaks flow of page, redeclaring '<p>' to keep flow)
	private function createNote($string) {
		$string = $this->cleanString($string);
		$string = '</p><div class="note">'.$string.'</div><p>';
		return $string;
	}

	//indented quote (note: breaks flow of page, redeclaring '<p>' to keep flow)
	private function createQuote($string) {
		$string = $this->cleanString($string);
		$string = '</p><div class="quote">'.$string.'</div><p>';
		return $string;
	}

	//executes PHP code (use at your own risk)
	private function executePHP($string) {
		$string = $this->cleanString($string);
		$string = '$string = ' . $string;
		eval($string);

		return $string;
	}

	//image path
	private function createImagePath($string) {
		$strings = array();
		$strings = explode('>', trim($string));

		$image = $this->imageDirectory;

		for ($i = 0; $i < sizeof($strings); $i++) {
			$image = $image.'/'.$strings[$i];
		}

		$image = $image.'.png';
		if (!file_exists($image)) $image = substr($image, 0, strlen($image) - 4).'.jpg';
		if (!file_exists($image)) $image = substr($image, 0, strlen($image) - 4).'.JPG';
		if (!file_exists($image)) $image = substr($image, 0, strlen($image) - 4).'.gif';
		if (!file_exists($image)) $image = substr($image, 0, strlen($image) - 4).'.svg';

		$image = str_replace(' ', '%20', $image);

		return $image;
	}

	//subtitle (note: breaks flow of page, redeclaring '<p>' to keep flow)
	private function createSubtitle($string) {
		$string = $this->cleanString($string);
		$string = '</p><h1>'.$string.'</h1><p>';
		return $string;
	}

	//italic
	private function createItalic($string) {
		$string = $this->cleanString($string);
		$string = '<em>'.$string.'</em>';
		return $string;
	}

	//bold
	private function createBold($string) {
		$string = $this->cleanString($string);
		$string = '<strong>'.$string.'</strong>';
		return $string;
	}

	//divider (note: breaks flow of page, redeclaring '<p>' to keep flow)
	private function createDivider($string) {
		$string = $this->cleanString($string);
		$string = '</p><div class="divider"></div><p>';
		return $string;
	}

	private function collectImages($string) {
		$images = explode(',', trim($string));

		for ($i = 0; $i < sizeof($images); $i++) {
			$images[$i] = $this->createImagePath($images[$i]);
		}
		return $images;
	}

	//cleans page tags
	private function cleanParagraphs($string) {
		//removes potential beginning closing paragraph tag if flow breaking element is first in string
		if (substr($string, 0, 4) === '</p>') $string = substr($string, 4);
		//add beginning paragraph open
		else $string = '<p>'. $string;

		//remove last opening paragraph tag if no text is present
		if (substr($string, -3, 3) === '<p>') $string = substr($string, 0, sizeof($string) - 4);
		//add paragraph closer at end if paragraph tag not empty
		else $string = $string . '</p>';

		//unclosed tags - ignore warnings when using DOMDocument for parsing
		//add meta info to foce utf-8 encoding
		$doc = new DOMDocument();
		@$doc->loadHTML('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $string);
		$string = $doc->saveHTML();

		//empty <p> tags
		$paragraphPattern = '/<p[^>]*>([\s]|&nbsp;)*<\/p>/';

		$string = preg_replace($paragraphPattern, '', $string);
		$string = trim($string);

		return $string;
	}

	//finds all instances of a substring($needle) in a string($haystack)
	private function allStringPositions($haystack, $needle) {
		$offset = 0;
		$all = array();

		while (($pos = strpos($haystack, $needle, $offset)) !== false) {
			$offset = $pos + 1;
			array_push($all, $pos);
		}
		return $all;
	}

	//removes symbol and [] (first two characters and last character) from $string
	private function cleanString($string) {
		$string = substr_replace($string, '', -1);
		$string = substr_replace($string, '', 0, 2);
		return $string;
	}

	//check if artifact exists
	private function artifactExist($string) {
		global $artifacts;

		for ($i = 0; $i < sizeof($artifacts); $i++) {
			if (strtolower($artifacts[$i]->attributes['name']) === strtolower($string)) return true;
		}
		return false;
	}

	//find artifact
	private function getArtifact($string) {
		global $artifacts;

		for ($i = 0; $i < sizeof($artifacts); $i++) {
			if (strtolower($artifacts[$i]->attributes['name']) === strtolower($string)) return $artifacts[$i];
		}
		return null;
	}
}
?>