<?php
require 'inc/workflows.php';
require 'inc/unicorn-busy-server.php';

$workflow = new Workflows();
$unicorn  = new Unicorn_Busy_Server();

$unicorn->load_status();

$workflow->result(
	md5( $a ),
	! $unicorn->loaded ? 'error' : '',
	'Unicorn Busy Server',
	! $unicorn->loaded ? $unicorn->error : sprintf(
		'%s | %d°',
		$unicorn->get_status_emoji() . ' ' . $unicorn->get_status(),
		$unicorn->get_temp()
	),
	''
);

echo $workflow->toxml();
