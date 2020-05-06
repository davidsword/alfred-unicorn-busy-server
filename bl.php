<?php
/**
 * Output UI for Alfred
 */

 // phpcs:ignoreFile

require 'workflows.php';
$workflow = new Workflows();

$query = ! isset( $argv[1] ) || empty( $argv[1] ) ? false : $argv[1];

$ex = [ 'Red', 'Yellow', 'Green', 'Off' ];
$state = file_get_contents( $workflow->path() . '/state.txt' );

foreach ( $ex as $a ) {
	if ( ! $query || ( $query && search_keyword_in_string( $query, $a ) ) ) {
		$current = $state === strtolower( $a ) ? ' (Current)' : '';
		$workflow->result(
			md5( $a ),
			strtolower( $a ),
			$a.$current,
			'',
			'' // @TODO icons here would be cool
		);
	}
}

echo $workflow->toxml(); 

function search_keyword_in_string( $needle, $haystack ) {
	return strstr( strtolower( $haystack ), strtolower( $needle ) );
}
