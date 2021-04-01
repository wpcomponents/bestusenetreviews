<?php

namespace BestUsenetReviews\Theme;

function get_dir() {
	static $dir = null;

	if ( \is_null( $dir ) ) {
		$dir = \trailingslashit( \get_template_directory() );
	}

	return $dir;
}

function get_url() {
	static $uri = null;

	if ( \is_null( $uri ) ) {
		$uri = \trailingslashit( \get_stylesheet_directory_uri() );
	}

	return $uri;
}

function get_slug() {
	static $slug = null;

	if ( \is_null( $slug ) ) {
		$slug = \basename( get_dir() );
	}

	return $slug;
}

function get_data( $header = '' ) {
	static $data = null;

	if ( \is_null( $data ) ) {
		$data = \wp_get_theme();
	}

	return $header && $data->get( $header ) ? $data->get( $header ) : $data;
}

function get_name() {
	static $name = null;

	return \is_null( $name ) ? get_data( 'Name' ) : $name;
}

function get_version() {
	static $version = null;

	return \is_null( $version ) ? get_data( 'Version' ) : $version;
}

function get_text_domain() {
	static $text_domain = null;

	return \is_null( $text_domain ) ? get_data( 'TextDomain' ) : $text_domain;
}

function get_domain_path() {
	static $domain_path = null;

	return \is_null( $domain_path ) ? get_data( 'DomainPath' ) : $domain_path;
}

function is_type_single() {
	return \is_front_page() || \is_single() || \is_page() || \is_404() || \is_attachment() || \is_singular();
}

function is_type_archive() {
	return \is_home() || \is_post_type_archive() || \is_category() || \is_tag() || \is_tax() || \is_author() || \is_date() || \is_year() || \is_month() || \is_day() || \is_time() || \is_archive() || \is_search();
}

function has_string( $needle, $haystack ) {
	return \strpos( $haystack, $needle ) !== false;
}

function apply_prefix( $string, $prefix = '' ) {
	return $prefix ? $prefix . '-' . $string : get_slug() . '-' . $string;
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $html
 *
 * @return \DOMDocument
 */
function get_dom_document( $html ) {
	$dom   = new \DOMDocument;
	$state = \libxml_use_internal_errors( true );
	$html  = \mb_convert_encoding( $html, 'HTML-ENTITIES', 'UTF-8' );
	$html  = $html ? $html : '<p>Lorem ipsum</p>';
	$dom->loadHTML( $html );
	$dom->removeChild( $dom->doctype );
	$dom->replaceChild( $dom->firstChild->firstChild->firstChild, $dom->firstChild );
	\libxml_clear_errors();
	\libxml_use_internal_errors( $state );

	return $dom;
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $hook Hook name.
 * @param array  $args Hook args (optional).
 *
 * @return string
 */
function get_action( $hook, $args = [] ) {
	\ob_start();
	\do_action( $hook, $args );

	return \ob_get_clean();
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param int $paragraphs Default 5.
 *
 * @return string
 */
function get_lorem_ipsum( $paragraphs = 1 ) {
	$lorem_ipsum = '';
	$paragraph   = '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>';

	for ( $i = 1; $i <= $paragraphs; $i++ ) {
		$lorem_ipsum .= $paragraph;
	}

	return $lorem_ipsum;
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $string
 *
 * @return string
 */
function unautop( $string ) {
	$string = \str_replace( "\n", "", $string );
	$string = \str_replace( "<p>", "", $string );
	$string = \str_replace( [ "<br />", "<br>", "<br/>", "</p>" ], "\n", $string );
	$string = \str_replace( ']]>', ']]&gt;', $string );

	return $string;
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param int $id
 *
 * @return int
 */
function get_en_id( $id ) {
	return \wpml_object_id_filter(
		(int) $id,
		'review',
		true,
		'en'
	);
}

/**
 * Converts a string to different naming conventions.
 *
 * Camel:    myNameIsBond.
 * Pascal:   MyNameIsBond.
 * Snake:    my_name_is_bond.
 * Ada:      My_Name_Is_Bond.
 * Macro:    MY_NAME_IS_BOND.
 * Kebab:    my-name-is-bond.
 * Train:    My-Name-Is-Bond.
 * Cobol:    MY-NAME-IS-BOND.
 * Lower:    my name is bond.
 * Upper:    MY NAME IS BOND.
 * Title:    My Name Is Bond.
 * Sentence: My name is bond.
 * Dot:      my.name.is.bond.
 *
 * @since  0.3.0
 *
 * @author Lee Anthony https://seothemes.com
 *
 * @param string $string String to convert.
 * @param string $case   Naming convention.
 *
 * @return string
 */
function convert_case( $string, $case = 'snake' ) {
	$delimiters = 'sentence' === $case ? [ ' ', '-', '_' ] : [ ' ', '-', '_', '.' ];
	$lower      = \trim( \str_replace( $delimiters, $delimiters[0], \strtolower( $string ) ), $delimiters[0] );
	$upper      = \trim( \ucwords( $lower ), $delimiters[0] );
	$pieces     = \explode( $delimiters[0], $lower );

	$cases = [
		'camel'    => \lcfirst( \str_replace( ' ', '', $upper ) ),
		'pascal'   => \str_replace( ' ', '', $upper ),
		'snake'    => \strtolower( \implode( '_', $pieces ) ),
		'ada'      => \str_replace( ' ', '_', $upper ),
		'macro'    => \strtoupper( \implode( '_', $pieces ) ),
		'kebab'    => \strtolower( \implode( '-', $pieces ) ),
		'train'    => \lcfirst( str_replace( ' ', '-', $upper ) ),
		'cobol'    => \strtoupper( \implode( '-', $pieces ) ),
		'lower'    => \strtolower( $string ),
		'upper'    => \strtoupper( $string ),
		'title'    => $upper,
		'sentence' => \ucfirst( $lower ),
		'dot'      => \strtolower( \implode( '.', $pieces ) ),
	];

	return isset( $cases[ $case ] ) ? $cases[ $case ] : $string;
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param mixed $data
 *
 * @return void
 */
function console_log( $data ) {
	echo "<script>console.log(" . \json_encode( $data ) . ")</script>";
}
