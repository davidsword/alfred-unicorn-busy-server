<?php
require 'inc/workflows.php';
require 'inc/unicorn-busy-server.php';
require 'inc/helpers.php';

$workflow = new Workflows();
$unicorn  = new Unicorn_Busy_Server();

$query = ! isset( $argv[1] ) || empty( $argv[1] ) ? false : trim( $argv[1] );
$subtitle = '';

foreach ( $unicorn->statuses as $status => $status_meta ) {

	if ( ! $query || ( $query && search_keyword_in_string( $query, $status ) ) ) {

		$png  = $unicorn->is_valid_status( $status ) ? $status : "images/icons/{$status}.png" : 'icon.png';
		$icon = file_exists( $png ) ? $png : '';

		$workflow->result(
			md5( $status ),
			$status,
			ucfirst( $status ),
			$subtitle,
			$icon
		);
		$shown++;
	}
}

echo $workflow->toxml();
