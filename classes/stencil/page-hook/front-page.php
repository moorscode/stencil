<?php
/**
 * Front Page page hook
 *
 * @package Stencil\PageHook
 */

/**
 * Class Stencil_Page_Hook_Front_Page
 */
class Stencil_Page_Hook_Front_Page extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		Stencil_Environment::trigger( 'front_page', $controller );
	}
}
