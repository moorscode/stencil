<?php
/**
 * If the autoloader (spl) is not available for some reason
 * we need to load all files manually
 *
 * This is -not- the preferred way and should be avoided if possible
 *
 * @package Stencil
 */

// Make sure WordPress is loaded.
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

__stencil_manual_load();

/**
 * Stencil manual class loader
 *
 * Load all classes in the order so all dependencies are met
 *
 * @internal
 */
function __stencil_manual_load() {
	$directory              = new RecursiveDirectoryIterator( dirname( __FILE__ ) . '/classes/' );
	$directory_iterator     = new RecursiveIteratorIterator( $directory );
	$php_directory_iterator = new RegexIterator( $directory_iterator, '/.*?\.php$/i', RecursiveRegexIterator::GET_MATCH );

	$files = array();
	foreach ( $php_directory_iterator as $file ) {
		$files[] = $file[0];
	}

	if ( ! empty( $files ) ) {
		usort( $files, '__stencil_sort_include_files' );
		foreach ( $files as $file ) {
			include $file;
		}
	}
}

/**
 * Custom sort for manual class loading
 *
 * First load interfaces, Stencil Interface top priority
 * Stencil classes first
 * Lowest depth classes first
 * Custom.php after Single.php because of dependency
 * Longest filename after shorter ones
 * Same length on alfabetical order
 *
 * @param string $a Compare A.
 * @param string $b Compare B.
 *
 * @return int
 */
function __stencil_sort_include_files( $a, $b ) {
	$a_file = basename( $a );
	$b_file = basename( $b );

	// Always load interfaces first.
	$a_interface = strpos( $a_file, '_interface.php' );
	$b_interface = strpos( $b_file, '_interface.php' );
	// ^ = xor, a or b but not both.
	if ( $a_interface ^ $b_interface ) {
		return ( false !== $a_interface && false === $b_interface ) ? - 1 : 1;
	}

	// Same with abstract classes.
	$a_abstract = strpos( $a_file, 'abstract_' ) === 0;
	$b_abstract = strpos( $b_file, 'abstract_' ) === 0;
	// ^ = xor, a or b but not both.
	if ( $a_abstract ^ $b_abstract ) {
		return ( false !== $a_abstract && false === $b_abstract ) ? - 1 : 1;
	}

	// Load Stencil before anything else.
	if ( $a_interface && $b_interface ) {
		$a_stencil = strpos( $a_file, 'stencil' );
		$b_stencil = strpos( $b_file, 'stencil' );

		if ( 0 === $a_stencil || 0 === $b_stencil && $a_stencil !== $b_stencil ) {
			return ( false !== $a_stencil && false === $b_stencil ) ? - 1 : 1;
		}
	}

	// Sort by directory depth.
	$a_depth = substr_count( $a, '/' );
	$b_depth = substr_count( $b, '/' );

	if ( $a_depth !== $b_depth ) {
		return ( $a_depth < $b_depth ) ? - 1 : 1;
	}

	// Custom after Single.
	if ( 'custom.php' === $a_file && 'single.php' === $b_file ) {
		return 1;
	}

	if ( 'custom.php' === $b_file && 'single.php' === $a_file ) {
		return - 1;
	}

	// Longer files are more likely to be sub implementations.
	$a_length = strlen( $a );
	$b_length = strlen( $b );
	if ( $a_length === $b_length ) {
		return strcmp( $a, $b );
	}

	return ( strlen( $a ) < strlen( $b ) ) ? - 1 : 1;
}
