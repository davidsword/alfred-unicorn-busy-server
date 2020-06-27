<?php
require 'inc/unicorn-busy-server.php';
$unicorn  = new Unicorn_Busy_Server();

$query  = isset( $argv[1] ) && ! empty( $argv[1] ) ? $argv[1] : 'off';

if ( $unicorn->is_valid_status( $query ) ) {
	echo $unicorn->change_status( $query );
} else {
	echo "❌ invalid status ( {$query} )";
}
