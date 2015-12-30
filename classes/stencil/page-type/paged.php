<?php
/**
 * Paged page type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Paged
 */
class Stencil_Page_Type_Paged extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		Stencil_Environment::trigger( 'paged', $controller );
	}
}
