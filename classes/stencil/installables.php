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
	 * Register all installable items.
	 */
	public function __construct() {
		$this->add_installable( new Stencil_Installable_Plugin( 'stencil', 'Stencil' ) );
		$this->add_installable( new Stencil_Installable_Plugin( 'stencil-dwoo2', 'Dwoo 2', '5.4.0' ) );
		$this->add_installable( new Stencil_Installable_Plugin( 'stencil-mustache', 'Mustache' ) );
		$this->add_installable( new Stencil_Installable_Plugin( 'stencil-savant3', 'Savant 3' ) );
		$this->add_installable( new Stencil_Installable_Plugin( 'stencil-smarty2', 'Smarty 2.x' ) );
		$this->add_installable( new Stencil_Installable_Plugin( 'stencil-smarty3', 'Smarty 3.x' ) );
		$this->add_installable( new Stencil_Installable_Plugin( 'stencil-twig', 'Twig' ) );

		$this->add_installable( new Stencil_Installable_Theme( 'dwoo2', 'Dwoo' ) );
		$this->add_installable( new Stencil_Installable_Theme( 'mustache', 'Mustache' ) );
		$this->add_installable( new Stencil_Installable_Theme( 'smarty', 'Smarty' ) );
		$this->add_installable( new Stencil_Installable_Theme( 'twig', 'Twig' ) );
	}

	/**
	 * Add an installable to the list.
	 *
	 * @param Stencil_Installable_Interface $installable Installable to add.
	 */
	public function add_installable( Stencil_Installable_Interface $installable ) {
		$this->installables[] = $installable;
	}

	/**
	 * Get Installable by slug.
	 *
	 * @param string $slug Slug to find.
	 *
	 * @return mixed|null
	 */
	public function get_by_slug( $slug ) {
		foreach ( $this->installables as $installable ) {
			if ( $installable->get_slug() === $slug ) {
				return $installable;
			}
		}

		return null;
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
	 * Get upgradable installables
	 *
	 * @return array of upgradable installables.
	 */
	public function get_upgradable() {
		$output = array();

		foreach ( $this->installables as $installable ) {
			if ( $installable->has_upgrade() ) {
				$output[] = $installable;
			}
		}

		return $output;
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
