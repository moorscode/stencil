<?php
/**
 * Date page hook
 *
 * @package Stencil\PageHook
 */

/**
 * Class Stencil_Page_Hook_Date
 */
class Stencil_Page_Hook_Date extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		Stencil_Environment::trigger( 'date', $controller );
	}
}
