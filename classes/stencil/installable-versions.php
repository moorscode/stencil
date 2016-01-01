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
	private static $versions = array(
		'plugins' => array(
			'stencil'          => '1.0.0',
			'stencil-dwoo2'    => '1.0.0',
			'stencil-mustache' => '1.0.0',
			'stencil-savant3'  => '1.0.0',
			'stencil-smarty2'  => '1.0.0',
			'stencil-smarty3'  => '2.0.0',
			'stencil-twig'     => '2.0.0',
			'stencil-upgrader' => '1.0.0',
		),
		'themes'  => array(
			'dwoo2'    => '1.0.0',
			'mustache' => '1.0.0',
			'savant'   => '1.0.0',
			'smarty'   => '1.0.0',
			'twig'     => '1.0.0',
		),
	);

	/**
	 * Get version for installable
	 *
	 * @param Stencil_Installable_Interface $installable Installable to get version of.
	 *
	 * @return string
	 */
	public static function get( Stencil_Installable_Interface $installable ) {
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
}