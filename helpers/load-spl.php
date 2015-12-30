<?php
/**
 * Autoloading Stencil classes in our domain
 *
 * @package Stencil
 */

// Make sure WordPress is loaded.
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

spl_autoload_register( '__stencil_autoload' );

/**
 * Stencil autoloader
 *
 * @param string $class Class to find.
 */
function __stencil_autoload( $class ) {

	if ( 0 === strpos( $class, 'Stencil' ) ) {

		if ( 'Stencil' !== $class ) {
			$class = str_replace( 'Stencil_', 'Stencil\\', $class );
		}

		if ( false === strpos( $class, 'Interface' ) ) {
			$class = str_replace(
				array( 'Hierarchy_', 'Page_Hook_', 'Page_Type_' ),
				array( 'Hierarchy\\', 'Page_Hook\\', 'Page_Type\\' ),
				$class
			);
		}

		// WordPress style filenames.
		$class = str_replace( '_', '-', strtolower( $class ) );

		// Build absolute path.
		$parts = array(
			STENCIL_PATH,
			'classes',
			str_replace( '\\', DIRECTORY_SEPARATOR, $class ),
		);

		$path = implode( DIRECTORY_SEPARATOR, $parts ) . '.php';
		if ( is_file( $path ) ) {
			require_once( $path );
		}
	}
}
