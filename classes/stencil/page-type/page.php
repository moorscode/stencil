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

			$page_template_base = basename( $page_template );
			$page_template_pure = str_replace( '.php', '', $page_template_base );

			$path = dirname( $page_template );

			$check = $path . '/models/' . $page_template_base;
			if ( is_file( $check ) ) {
				load_template( $check, true );
			} else {
				$check = $path . '/' . $page_template_base;
				if ( is_file( $check ) ) {
					load_template( $check, true );
				}
			}

			Stencil_Environment::trigger( $page_template_pure, $controller );
			Stencil_Environment::trigger( $page_template_base, $controller );

		}
	}
}
