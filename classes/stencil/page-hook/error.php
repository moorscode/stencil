<?php
/**
 * Error page hook
 *
 * @package Stencil\PageHook
 */

/**
 * Class Stencil_Page_Hook_Error
 */
class Stencil_Page_Hook_Error extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		Stencil_Environment::trigger( 'error', $controller );
		Stencil_Environment::trigger( '404', $controller );
	}
}
