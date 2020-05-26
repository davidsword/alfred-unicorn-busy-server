<?php
/**
 * Output UI for Alfred
 */

require 'workflows.php';
require 'helpers.php';

$workflow = new Workflows();

$query = ! isset( $argv[1] ) || empty( $argv[1] ) ? false : $argv[1];

$query    = trim( $query );
$subtitle = '';

$colours = json_decode( file_get_contents( 'colour-lib.json' ), JSON_OBJECT_AS_ARRAY );
foreach ( $colours as $colour ) {

	$name = $colour[0];
	$rgb  = $colour[1];
	$hex  = $colour[2];

	if ( ! $query || ( $query && search_keyword_in_string( $query, $name ) ) ) {

		$text_name = trim( preg_replace( '/[^a-z0-9_\-]/', '', $name ) );
		$icon      = file_exists( "images/icons/{$text_name}.png" ) ? "images/icons/{$text_name}.png" : '';

		$workflow->result(
			md5( $hex ),
			json_encode(
				[
					'name'      => $name,
					'text_name' => $text_name,
					'rgb'       => $rgb,
					'hex'       => $hex,
				]
			),
			ucfirst( $text_name ),
			$subtitle,
			$icon
		);
		$shown++;
	}
}

echo $workflow->toxml(); 
