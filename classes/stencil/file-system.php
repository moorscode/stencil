<?php
/**
 * Load files from root or child theme
 *
 * @package Stencil
 */

/**
 * Class Stencil_File_System
 */
class Stencil_File_System {
	/**
	 * Include a file from child or root theme
	 *
	 * @param string $file File to include.
	 *
	 * @return bool True if a file was found.
	 */
	public static function load( $file ) {
		$file = rtrim( $file, '.php' ) . '.php';

		/**
		 * Filter controllers directory
		 */
		$directory = Stencil_Environment::filter( 'path-controllers', 'controllers' );

		if ( empty( $directory ) ) {
			return false;
		}

		$paths = self::get_potential_directories( $directory );
		foreach ( $paths as $path ) {
			if ( is_file( $path . $file ) ) {
				include $path . $file;

				return true;
			}
		}

		return false;
	}

	/**
	 * Get the paths available that might contain a subdirectory
	 *
	 * Depending on the theme being a child theme or not
	 * The child theme is being prepended to the paths list
	 *
	 * @param string $sub_directory Name of directory to test.
	 *
	 * @return array Root and optionally child path
	 */
	public static function get_potential_directories( $sub_directory ) {
		static $theme_root;
		static $child_root;

		if ( ! isset( $theme_root ) ) {
			$theme_root = get_template_directory();
		}
		if ( ! isset( $child_root ) ) {
			$child_root = get_stylesheet_directory();
		}

		$paths = array(
			implode( DIRECTORY_SEPARATOR, array( $theme_root, $sub_directory, '' ) ),
		);

		// First check child theme.
		if ( $theme_root !== $child_root ) {
			array_unshift( $paths, implode( DIRECTORY_SEPARATOR, array( $child_root, $sub_directory, '' ) ) );
		}

		return $paths;
	}
}
