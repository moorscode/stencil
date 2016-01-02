<?php
/**
 * Seperate place to keep track of latest versions
 *
 * @package Stencil
 * @subpackage Upgrader
 */

/**
 * Class Stencil_Installable_Versions
 */
class Stencil_Installable_Versions {
	/**
	 * Latest versions
	 *
	 * @var array
	 */
	private static $versions = array();

	/**
	 * Has the version file been loaded or not?
	 *
	 * @var bool
	 */
	private static $loaded = false;

	/**
	 * Get version for installable
	 *
	 * @param Stencil_Installable_Interface $installable Installable to get version of.
	 *
	 * @return string
	 */
	public static function get( Stencil_Installable_Interface $installable ) {
		self::load();

		if ( empty( self::$versions ) ) {
			return '0.0.0';
		}

		$slug = $installable->get_slug();

		if ( is_a( $installable, 'Stencil_Installable_Plugin' ) ) {
			if ( isset( self::$versions['plugins'][ $slug ] ) ) {
				return self::$versions['plugins'][ $slug ];
			}
		}

		if ( is_a( $installable, 'Stencil_Installable_Theme' ) ) {
			if ( isset( self::$versions['themes'][ $slug ] ) ) {
				return self::$versions['themes'][ $slug ];
			}
		}

		return '0.0.0';
	}

	/**
	 * Load versions from json file.
	 *
	 * @return array|bool
	 */
	private static function load() {
		if ( self::$loaded ) {
			return;
		}

		self::$loaded = true;

		$filename      = 'https://raw.githubusercontent.com/moorscode/stencil/master/config/versions.json';
		$versions_json = file_get_contents( $filename );
		if ( empty( $versions_json ) ) {
			return;
		}

		$decoded = json_decode( $versions_json, true );
		if ( is_array( $decoded ) ) {
			self::$versions = $decoded;
		}
	}
}
