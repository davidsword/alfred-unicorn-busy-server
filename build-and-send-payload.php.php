<?php
/**
 * Handler for building and sending request from Alfred to Raspberry Pi
 * 
 * Translate words of colours to LED decimal array.
 */

// phpcs:ignoreFile
// @see https://github.com/Kylir/node-unicorn#the-led-driver.

// From Alfred.
$query   = isset( $argv[1] ) && ! empty( $argv[1] ) ? $argv[1] : 'off';
$rpi_url = getevn( 'RPI_NODEJS_ADDRESS' );

// short circut for now. @TODO enable once ready.
die( $query );

// Uses decimal colours.
// @see https://convertingcolors.com/
$colors = [
	'red'    => 16711680, // #FF0000
	'orange' => 16753920, // #FFA500
	'green'  => 1756160,  // #1ACC00
	'off'    => 0,        // #000000
];

// build the LED pixel array.
$leds = [];
for ( $i = 0; $i != 32; $i ++ )
	$leds[] = $colors[ $query ];

// ship it.
$ch = curl_init( $rpi_url . '/display' );
curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( [ 'leds' => $leds ] ) );
curl_setopt( $ch, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json' ] );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // response.
$result = curl_exec( $ch );
curl_close( $ch );

if ( $result )
	echo $query;
else
	var_dump( $result ); // 💩
