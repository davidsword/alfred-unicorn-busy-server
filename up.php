<?php
/**
 * Output UI for Alfred
 */

require 'workflows.php';
require 'helpers.php';
$workflow = new Workflows();

$query = ! isset( $argv[1] ) || empty( $argv[1] ) ? false : $argv[1];

$stats = get_unicorn_phat_stats();

$workflow->result(
	md5( $a ),
	! $stats ? 'error' : '',
	'Control Unicorn pHAT',
	! $stats ? '❌ Unable to connect to RPI' : sprintf( 
		'Currently: %s | %s | %d°',
		rgbToWord( [ $stats['red'], $stats['green'], $stats['blue'] ] ),
		date( 'h:ia', strtotime( $stats['lastCalled'] ) ),
		round( $stats['cpuTemp'], 1 ),
	),
	''
);

echo $workflow->toxml(); 
