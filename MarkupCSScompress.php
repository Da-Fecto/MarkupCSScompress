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

$array = $session->get('MarkupCSScompress');

// get variable doesn't exist, check for $session
if( count($array)) {

	$array = $session->get('MarkupCSScompress');
	$files = $array['files'];
	$time = $array['time'];
	
} 

/**
 * Each file get it's own cache file with the URL as it's name.
 *
 */
$out = "";

if(count($files)) {
	foreach($files as $key => $file) {
		
		// create $cache object
		$cache = $modules->get("MarkupCache");

		// if there's no cache, store it with css url in the title
		if(!$data = $cache->get(str_replace("/", "_", $file), $time )) {
			 
			// (array) put file & folders chunks in array 
			$fragments = explode( "/", $file );
			// (array) strip of unwanted, leave folders in array 
			$folders = array_slice($fragments, 1, sizeof($fragments) - 2);
			// (string) path to the CSS file, without the actual CSS file
			$path = $config->paths->root .implode("/", $folders );

			// load the Google Minify CSS class (small piece of it)
			require_once( $config->paths->MarkupCSScompress . "Minify/CSS.php" );

			$options = array(
				'preserveComments' => false,
				'currentDir' => $path,
            	'prependRelativePath' => null,
				);
			
			$css = new Minify_CSS;
			$data = $css->minify(file_get_contents($config->paths->root . $file), $options);
			
			// save created cache
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

echo $out;

// release the buffer & destroy the output

ob_end_flush();