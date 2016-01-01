<?php
/**
 * Stencil Installable package
 *
 * @package Stencil
 * @subpackage Upgrader
 */

/**
 * Class Stencil_Installable
 */
class Stencil_Installables {

	/**
	 * List of registered installables.
	 *
	 * @var array
	 */
	private $installables = array();

	/**
	 * Add an installable to the list.
	 *
	 * @param Stencil_Installable_Interface $installable Installable to add.
	 */
	public function add_installable( Stencil_Installable_Interface $installable ) {
		$this->installables[] = $installable;
	}

	/**
	 * Retrieve all plugins
	 *
	 * @return Iterator list of Plugins.
	 */
	public function get_plugins() {
		return $this->get_installable_filtered( 'Stencil_Installable_Plugin' );
	}

	/**
	 * Get all registered themes.
	 *
	 * @return Iterator list of Themes.
	 */
	public function get_themes() {
		return $this->get_installable_filtered( 'Stencil_Installable_Theme' );
	}

	/**
	 * Get specific installables.
	 *
	 * @param string $class Class to get objects of.
	 *
	 * @return array
	 */
	private function get_installable_filtered( $class ) {

		$output = array();
		foreach ( $this->installables as $installable ) {
			if ( is_a( $installable, $class ) ) {
				$output[] = $installable;
			}
		}

		return $output;
	}
}
