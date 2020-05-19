<?php
/**
 * Output UI for Alfred
 */

 // phpcs:ignoreFile

require 'workflows.php';
$workflow = new Workflows();

$query = ! isset( $argv[1] ) || empty( $argv[1] ) ? false : $argv[1];

$stats = get_unicorn_phat_stats();
$workflow->result(
	md5( $a ),
	'#',
	"Stats: rgb({$stats['red']},{$stats['green']},{$stats['blue']}), ".round($stats['cpuTemp'],1)."°",
	date('h:ia',strtotime( $stats['lastCalled'] )),
	'' // @TODO dynamic svg icons here would be cool
);

$shown = 0;
$ex = [ ]; // 'On', 'Off', 'Red', 'Green' 
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


// if ( $query && $shown < 1  ) {

// 	// maybe a brightness param
// 	if ( strstr( $query, ' ' ) ) {
// 		$query_args = explode(' ',$query);
// 		$query = "\"{$query_args[0]}\"";
// 		$subtitle = "@{$query_args[1]}% brightness";
// 	} else {
// 		$query = "\"{$query}\"";
// 		$subtitle = '';
// 	}

// 	$workflow->result(
// 		md5( $query ),
// 		strtolower( $query ),
// 		"Set colour to {$query}",
// 		$subtitle,
// 		'' // @TODO dynamic svg icons here would be cool
// 	);
// }



echo $workflow->toxml(); 

function search_keyword_in_string( $needle, $haystack ) {
	return strstr( strtolower( $haystack ), strtolower( $needle ) );
}

function get_unicorn_phat_stats(  ) {
	$ch   = curl_init();
	curl_setopt( $ch, CURLOPT_URL, getenv( 'RPI_UNICORN_PHAT_URL' ) . '/api/status' );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	$buffer = curl_exec( $ch );
	curl_close( $ch );
	return json_decode( $buffer, JSON_OBJECT_AS_ARRAY );
}
