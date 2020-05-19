<?php
/**
 * Handler for building and sending request from Alfred to Raspberry Pi
 * 
 * Translate words of colours to LED decimal array.
 */

// From Alfred.
$query  = isset( $argv[1] ) && ! empty( $argv[1] ) ? $argv[1] : 'off';
$color  = preg_replace( '/[^a-zA-Z0-9]/', '', $query );
$result = change_unicorn_phat( $color );

// `red, `green` (or `off`)
function change_unicorn_phat( $colour ) {

	if ( in_array( $colour, [ 'on', 'off' ] ) ) {
		$ch   = curl_init();
		curl_setopt( $ch, CURLOPT_URL, getenv( 'RPI_UNICORN_PHAT_URL' ) . '/api/' . $colour );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$buffer = curl_exec( $ch );
		curl_close( $ch );
		return json_decode( $buffer );
	}

	// @TODO see if i need to factor in on/off.
	$body = json_encode(
		[
			'red'        => 'red' === $colour ? 255 : 0,
			'green'      => 'green' === $colour ? 255 : 0,
			'blue'       => 0,
			'brightness' => 0.5, // @TODO use getenv( 'RPI_UNICORN_PHAT_BRIGHTNESS' )
			'Speed'      => null,
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
	return json_decode( $buffer );
}


// send back the color, or the error.
echo $result ? $query : json_encode( $result );
