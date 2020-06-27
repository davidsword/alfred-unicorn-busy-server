<?php

class Unicorn_Busy_Server {

	var $loaded      = false;
	var $status      = '';
	var $temp        = '';
	var $last_called = '';
	var $brightness  = '';
	var $error       = '';
	var $query       = '';

	var $statuses = [
		'Available' => [
			'emoji' => '🟢',
		],
		'Away'      => [
			'emoji' => '🟡',
		],
		'Busy'      => [
			'emoji' => '🔴',
		],
		// currently 500s, not sure why.
		// 'rainbow' => [
		// 'emoji' => '🌈',
		// ],
		'Off'       => [
			'emoji' => '⚫️',
		],
	];

	function __construct() {
		$this->query      = getenv( 'STATUS' );
		$this->brightness = $this->set_brightness();
	}

	function load_status() {
		$api_status = $this->curl_unicorn_busy_server_api( 'status' );

		if ( ! is_array( $api_status ) )
			return false;

		$this->loaded = true;

		// setters here.
		$this->status      = isset( $api_status['status'] ) ? preg_replace( '/[^a-zA-Z0-9_\-]/', '', $api_status['status'] ) : null;
		$this->temp        = round( $api_status['cpuTemp'], 1 );
		$this->last_called = date( 'r', strtotime( $api_status['lastCalled'] ) );
	}

	function get_temp() {
		return $this->temp;
	}

	function get_last_modified() {
		return $this->last_called;
	}

	function get_status() {
		return $this->status;
	}

	function get_status_emoji() {
		return isset( $this->statuses[ $this->status ]['emoji'] ) ?
			$this->statuses[ $this->status ]['emoji'] : '??';
	}

	function is_valid_status( $validate ) {
		return isset( $this->statuses[ $validate ] );
	}

	function change_status( $to ) {
		$to = trim( strtolower( $to ) );
		return $this->curl_unicorn_busy_server_api( $to );
	}

	function set_brightness() {
		$brightness = floatval( getenv( 'UBS_BRIGHTNESS' ) );
		$brightness = ( $brightness > 0.5 ) ? 0.5 : $brightness; // safegaurd cap @50%.
		return ( $brightness < 0.172 ) ? 0.172 : $brightness;   // lowest for basic colours.
	}

	function curl_unicorn_busy_server_api( $endpoint ) {
		$url = getenv( 'UBS_ADDRESS' ) . '/api/' . $endpoint;

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		if ( ! in_array( $endpoint, [ 'status', 'off' ] ) ) {
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
			curl_setopt(
				$ch,
				CURLOPT_POSTFIELDS,
				json_encode(
					[
						'brightness' => $this->brightness,
					]
				)
			);
		}
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

		if ( $buffer = curl_exec( $ch ) ) {
			if ( 'status' === $endpoint ) {
				$return = json_decode( $buffer, JSON_OBJECT_AS_ARRAY );
			} else {
				$return = $this->statuses[ $this->query ]['emoji'] . ' ' . $this->query; // pass through.
			}
		} else {
			$this->error = "❌ " . curl_error( $ch ) . " ( {$url} ) ";
			$return = $this->error;
		}
		curl_close( $ch );
		return $return;
	}

}
