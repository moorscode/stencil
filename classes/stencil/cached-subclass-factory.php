<?php
/**
 * Cache layer for the subclass factory
 *
 * @package Stencil
 */

/**
 * Class CachedSubclassFactory
 */
class Stencil_Cached_Subclass_Factory extends Stencil_Subclass_Factory {
	/**
	 * Return cached object if exists
	 *
	 * @param string $page The name of the page.
	 * @param string $prefix Prefix to use in class structure.
	 *
	 * @return mixed
	 */
	public static function create_or_null( $page, $prefix ) {
		static $cache = array();

		if ( ! isset( $cache[ $prefix ] ) ) {
			$cache[ $prefix ] = array();
		}

		if ( ! isset( $cache[ $prefix ][ $page ] ) ) {
			$cache[ $prefix ][ $page ] = parent::create_or_null( $page, $prefix );
		}

		return $cache[ $prefix ][ $page ];
	}
}
