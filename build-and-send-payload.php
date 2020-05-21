<?php
/**
 * Handler for building and sending request from Alfred to Raspberry Pi
 * 
 * Translate words of colours to LED decimal array.
 */

require 'helpers.php';

// From Alfred.
$query  = isset( $argv[1] ) && ! empty( $argv[1] ) ? $argv[1] : 'off';

// request should be `on`, `off`, or string rgb w brightness: `255,255,255@75`
$request  = preg_replace( '/[^a-z0-9,@]/', '', strtolower( $query ) );

if ( in_array( $request, [ 'on', 'off' ] ) ) {
	$ch   = curl_init();
	curl_setopt( $ch, CURLOPT_URL, getenv( 'RPI_UNICORN_PHAT_URL' ) . '/api/' . $request );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$buffer = curl_exec( $ch );
	curl_close( $ch );
	return json_decode( $buffer );
}

$parts      = explode( '@', $request );
$colour     = explode( ',', $parts[0] );
$brightness = $parts[1];

// validate
if ( ! strstr( $request, '@' ) || ! isset( $colour[2] ) ) {
	die("error, passed in invalid data: {$query}");
}

$body = json_encode(
	[
		'red'        => $colour[0],
		'green'      => $colour[1],
		'blue'       => $colour[2],
		'brightness' => $brightness,
		'Speed'      => null, // 🤷🏼‍♂️
	] 
);
$ch   = curl_init();
curl_setopt( $ch, CURLOPT_URL, getenv( 'RPI_UNICORN_PHAT_URL' ) . '/api/switch' );
curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $body );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt(
	$ch,
	CURLOPT_HTTPHEADER,
	[
		'Content-Type: application/json',
		'Content-Length: ' . strlen( $body ),
	]
);
$buffer = curl_exec( $ch );
curl_close( $ch );
$result = json_decode( $buffer );

// send back the color, or the error.
echo $result ? rgbToWord( [ $colour[0], $colour[1], $colour[2] ] ) . " @ {$brightness}%" : json_encode( $result );
