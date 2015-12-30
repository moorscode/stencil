<?php
/**
 * Category page hook
 *
 * @package Stencil\PageHook
 */

/**
 * Class Stencil_Page_Hook_Category
 */
class Stencil_Page_Hook_Category extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		Stencil_Environment::trigger( 'category', $controller );
		Stencil_Environment::trigger( 'category.' . get_query_var( 'cat' ), $controller );
	}
}
