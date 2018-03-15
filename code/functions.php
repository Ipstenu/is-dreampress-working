<?php

/* Is DreamPress Working?
 *
 * These are the functions and defines
 *
 */

if( !defined('ISDREAMPRESSWORKING') ) {
   die('Direct access not permitted');
}

// Icons
$icon_awesome   = '<i class="far fa-heartbeat" style="color:#008000;"></i>';
$icon_good      = '<i class="far fa-beer" style="color:#008000;"></i>';
$icon_warning   = '<i class="fas fa-exclamation-triangle" style="color:#FF9933"></i>';
$icon_awkward   = '<i class="far fa-meh" style="color:#FF9933;"></i>';
$icon_bad       = '<i class="far fa-bomb" style="color:#990000;"></i>';

// This function gets the curl headers
function curl_headers ( $url ) {
	$curl = curl_init();
	curl_setopt_array( $curl, array(
		CURLOPT_FAILONERROR => true,
		CURLOPT_CONNECTTIMEOUT => 30,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_FOLLOWLOCATION => false,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_HEADER => true,
		CURLOPT_NOBODY => true,
		CURLOPT_VERBOSE => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => 'gzip, deflate',
		CURLOPT_URL => $url ) );
	
	return $curl;
	curl_close($curl);
}

// This function checks the responses
function curl_response ( $connection ) {
	$varnish_result = curl_exec($connection);
	$varnish_headerinfo = curl_getinfo($connection);
	$varnish_headers = array();		
	$varnish_responseheader = explode( "\n" , trim( mb_substr($varnish_result, 0, $varnish_headerinfo['header_size'] ) ) );

	// Reformatting 0 entry for playback
	$varnish_headers[0] = $varnish_responseheader[0];
	unset($varnish_responseheader[0]);

	foreach( $varnish_responseheader as $line ) {
		list( $key, $val) = explode( ':' , $line , 2 );
		$varnish_headers[$key] = trim($val);
	}

	return $varnish_headers;		
}

// This function calculates grades
function get_letter_grade ( $numeric_grade ) {
	$letter_grade = 'F';
	$scale = array ( 89 => 'A', 79  => 'B', 69  => 'C', 59  => 'D' );
	foreach ($scale as $cutoff => $grade) {
		if ($numeric_grade >= $cutoff) {
			$letter_grade = $grade;
			break;
		}
	}
	return $letter_grade;
}