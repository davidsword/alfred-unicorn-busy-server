<?php

function search_keyword_in_string( $needle, $haystack ) {
	return strstr( strtolower( $haystack ), strtolower( $needle ) );
}
