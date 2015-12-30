<?php
/**
 * Search page type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Search
 */
class Stencil_Page_Type_Search extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		$controller->set( 'search', get_search_query( true ) );
	}
}
