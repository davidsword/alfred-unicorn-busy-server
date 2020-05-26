<?php
/**
 * Handler for building and sending request from Alfred to Raspberry Pi
 * 
 * Translate words of colours to LED decimal array.
 */

require 'helpers.php';

// From Alfred.
$query  = isset( $argv[1] ) && ! empty( $argv[1] ) ? $argv[1] : 'off';

if ( is_string( $query ) && in_array( $query, [ 'on', 'off' ] ) ) {
	$ch   = curl_init();
	curl_setopt( $ch, CURLOPT_URL, getenv( 'RPI_UNICORN_PHAT_URL' ) . '/api/' . $query );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$buffer = curl_exec( $ch );
	curl_close( $ch );
	return json_decode( $buffer );
}

// validate
$query = json_decode( $query, JSON_OBJECT_AS_ARRAY );
if ( ! is_array( $query ) ) {
	die( "error, passed in invalid data" ); // probably never.
}

$brightness = getenv( 'RPI_UNICORN_PHAT_BRIGHTNESS' );
$brightness = ( $brightness > 0.5 )   ? 0.5 : $brightness;   // safe gaurd cap @50.
$brightness = ( $brightness < 0.172 ) ? 0.172 : $brightness; // lowest for basic colours.

$body = json_encode(
	[
		'red'        => intval( $query['rgb'][0] ),
		'green'      => intval( $query['rgb'][1] ),
		'blue'       => intval( $query['rgb'][2] ),
		'brightness' => floatval( $brightness ),
		'Speed'      => null, // 🤷🏼‍♂️
	] 
);
echo print_r( $body );
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
echo $result ? preg_replace( '/[^a-zA-Z ]/', '', $query['name'] ) : json_encode( $result );
