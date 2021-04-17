<?php

namespace BestUsenetReviews\Theme;

// Manually translate some strings.
\add_filter( 'gettext', function ( $translated_text, $text, $domain ) {

	// Already translated.
	if ( $translated_text !== $text ) {
		return $translated_text;
	}

	// Theme strings only.
	if ( 'bestusenetreviews' !== $domain ) {
		return $translated_text;
	}

	// Skip english strings.
	if ( 'en' === ICL_LANGUAGE_CODE ) {
		return $translated_text;
	}

	$strings = [
		'256-Bit SSL Security' => [
			'de' => '256-Bit-SSL-Sicherheit',
			'fr' => 'Sécurité SSL 256 bits',
			'nl' => '256-bits SSL-beveiliging',
		],
		'Up to'                => [
			'de' => 'Bis zu',
			'fr' => "Jusqu'à",
			'nl' => 'Maximaal',
		],
		'Gbps'                 => [
			'de' => 'Gbit / s',
			'fr' => 'Gbit / s',
			'nl' => 'Gbps',
		],
	];

	if ( ! isset( $strings[ $translated_text ][ ICL_LANGUAGE_CODE ] ) ) {
		return $translated_text;
	}

	return $strings[ $translated_text ][ ICL_LANGUAGE_CODE ];
}, 10, 3 );
