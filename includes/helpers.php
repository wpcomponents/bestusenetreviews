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
 * @param int $characters
 *
 * @return string
 */
function get_lorem_ipsum( $characters = 300 ) {
	return \substr( 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores duo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet clita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero voluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.', 0, $characters );
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
