<?php

// check if MarkupCSScompress is installed.
if($modules->isInstalled('MarkupCSScompress') == false) {
	echo "<h1>Oops.</h1";	
	echo "<p>This file can only be used in collaboration with MarkupCSScompress.</p>";	
	die();
}

// check if MarkupCache is installed.
if($modules->isInstalled('MarkupCache') == false) {
	echo "body { background: red;}";	
	echo "body:after { content: 'MarkupCSScomress needs MarkupCache to be installed.'; color: #FFF;}";	
	die();
}

$files = array();

// get variable doesn't exist, check for $session
if( $input->get->f === null) {

	$array = $session->get('MarkupCSScompress');
	$files = $array['files'];
	$time = $array['time'];
	
// try it with $input->get
} else if($input->get->f) {

	$rawFiles = explode("|", $input->get->f);
	// check if files exists
	foreach($rawFiles as $file) {
		$cssFile = $config->paths->root . ltrim($file, '/');
		if(is_file( $cssFile )) $files[] = $file;
	}
	
	$time = ctype_digit( $input->get->t ) ? (int) $input->get->t : 0;
}

/**
 * Simple CSS compression
 * 
 */
function compress($css) {
	// remove comments
	$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
	// remove tabs, spaces, newlines, etc.
	$css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
	//remove extra single spaces
    $css = preg_replace('/[\s]*([\{\},;:])[\s]*/', '\1', $css);
	return $css;
}

/**
 * Each file get it's own cache file with the URL as it's name.
 *
 */
$out = "";

if(count($files)) {
	foreach($files as $file) {

		$cache = $modules->get("MarkupCache");
	
		// if there's no cache, store it with css url in the title
		if(!$data = $cache->get(str_replace("/", "_", $file), $time )) {
				
			// read & compress css file
			$data = compress(file_get_contents($config->paths->root . $file));
			// save the minified CSS 
			$cache->save($data);
		}
	
		$out .= $data;
	}
}

// collect all output in buffer
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
	ob_start("ob_gzhandler");
} else {
	ob_start();
}

header("Content-type: text/css; charset: UTF-8");

// Don't see any difference, leave them here for reference now
// header("Cache-Control: must-revalidate");
// header("Expires: " . gmdate("D, d M Y H:i:s", time() + $time) . " GMT");

echo $out;

// release the buffer & destroy the output

ob_end_flush();