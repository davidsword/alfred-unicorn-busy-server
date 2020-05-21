<?php

function search_keyword_in_string( $needle, $haystack ) {
	return strstr( strtolower( $haystack ), strtolower( $needle ) );
}

function get_unicorn_phat_stats() {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, getenv( 'RPI_UNICORN_PHAT_URL' ) . '/api/status' );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$buffer = curl_exec( $ch );
	curl_close( $ch );
	return json_decode( $buffer, JSON_OBJECT_AS_ARRAY );
}

function rgbToWord( $val ) {
	$colours = json_decode( file_get_contents( 'colour-lib.json' ), JSON_OBJECT_AS_ARRAY );
	$distances = array();
	foreach ( $colours as $c )
		$distances[ $c[0] ] = distancel2( $c[1], $val );
	$mincolor = '';
	$minval   = pow( 2, 30 ); /*big value*/
	foreach ( $distances as $k => $v ) {
		if ( $v < $minval ) {
			$minval   = $v;
			$mincolor = $k;
		}
	}
	return $mincolor;
}

function distancel2( array $color1, array $color2 ) {
	return sqrt(
		pow( $color1[0] - $color2[0], 2 ) +
		pow( $color1[1] - $color2[1], 2 ) +
		pow( $color1[2] - $color2[2], 2 )
	);
}

// via https://stackoverflow.com/questions/15202079/convert-hex-color-to-rgb-values-in-php .
function hexToRgb( $hex, $alpha = false ) {
	$hex      = str_replace( '#', '', $hex );
	$length   = strlen( $hex );
	$rgb['r'] = hexdec( $length == 6 ? substr( $hex, 0, 2 ) : ( $length == 3 ? str_repeat( substr( $hex, 0, 1 ), 2 ) : 0 ) );
	$rgb['g'] = hexdec( $length == 6 ? substr( $hex, 2, 2 ) : ( $length == 3 ? str_repeat( substr( $hex, 1, 1 ), 2 ) : 0 ) );
	$rgb['b'] = hexdec( $length == 6 ? substr( $hex, 4, 2 ) : ( $length == 3 ? str_repeat( substr( $hex, 2, 1 ), 2 ) : 0 ) );
	if ( $alpha ) {
		$rgb['a'] = $alpha;
	}
	return $rgb;
}
