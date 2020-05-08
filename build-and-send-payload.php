<?php
/**
 * Handler for building and sending request from Alfred to Raspberry Pi
 * 
 * Translate words of colours to LED decimal array.
 * 
 * @see https://github.com/davidsword/node-unicorn
 */

// phpcs:ignoreFile

// From Alfred.
$query      = isset( $argv[1] ) && ! empty( $argv[1] ) ? $argv[1] : 'off';

// maybe a brightness param
if ( strstr( $query, ' ' ) ) {
	$query_args = explode(' ',$query);
	$query      = $query_args[0];
	$brightness = $query_args[1];
} else {
	$brightness = getenv( 'BRIGHTNESS' );
}

$color      = preg_replace( '/[^a-zA-Z0-9_\-#]/', '', $query );
$rpi_url    = getenv( 'RPI_URL' );
$endpoint   = "/display/".rawurlencode( $color )."/".intval( $brightness );

if ( 'off' === $query || '0' === $query || 0 === $query ) {
	$endpoint   = "/display/off";
}

// ship it.
$ch = curl_init( $rpi_url . $endpoint );
curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( [ ] ) );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // response.
$result = curl_exec( $ch );
curl_close( $ch );

//print_r($result);

// send back the color, or the error.
echo $result ? $query :	json_encode( $result );
