<?php
/**
 * Output UI for Alfred
 */

 // phpcs:ignoreFile

require 'workflows.php';
$workflow = new Workflows();

$query = ! isset( $argv[1] ) || empty( $argv[1] ) ? false : $argv[1];

$shown = 0;
$ex = [ 'Red', 'Orange', 'Green', 'Off' ];
foreach ( $ex as $a ) {
	if ( ! $query || ( $query && search_keyword_in_string( $query, $a ) ) ) {
		$workflow->result(
			md5( $a ),
			strtolower( $a ),
			$a,
			'',
			'' // @TODO dynamic svg icons here would be cool
		);
		$shown++;
	}
}

if ( $query && $shown < 1  ) {
	$workflow->result(
		md5( $query ),
		strtolower( $query ),
		"Set colour to \"{$query}\"",
		"",
		'' // @TODO dynamic svg icons here would be cool
	);
}

echo $workflow->toxml(); 

function search_keyword_in_string( $needle, $haystack ) {
	return strstr( strtolower( $haystack ), strtolower( $needle ) );
}
