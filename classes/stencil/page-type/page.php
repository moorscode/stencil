<?php
/**
 * Page page type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Page
 */
class Stencil_Page_Type_Page extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {

		$post_type = get_post_type();

		Stencil_Environment::trigger( 'single', $controller );
		Stencil_Environment::trigger( $post_type, $controller );
		Stencil_Environment::trigger( get_the_ID(), $controller );

		$page_template = get_page_template();
		if ( $page_template ) {

			// Always include template file.
			include $page_template;

			$page_template_base = basename( $page_template );

			Stencil_File_System::load( $page_template_base );

			Stencil_Environment::trigger( rtrim( $page_template_base, '.php' ), $controller );
			Stencil_Environment::trigger( $page_template_base, $controller );

		}
	}
}
