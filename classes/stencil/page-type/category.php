<?php
/**
 * Category page type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Category
 */
class Stencil_Page_Type_Category extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		$category = array(
			'id'          => get_query_var( 'cat' ),
			'name'        => get_cat_name( get_query_var( 'cat' ) ),
			'title'       => single_cat_title( '', false ),
			'description' => term_description(),
		);

		$controller->set( 'category', $category );
	}
}
