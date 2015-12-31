<?php
/**
 * Singular page type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Singular
 */
class Stencil_Page_Type_Singular extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		/**
		 * Filter: force the page to have the permalink presentation
		 */
		$force_permalink = Stencil_Environment::filter( 'force_permalink', false );
		if ( $force_permalink ) {
			$url       = $controller->get( 'self' );
			$permalink = get_permalink();

			if ( $url && $permalink ) {
				if ( $permalink !== $url ) {
					wp_redirect( $permalink );
					die();
				}
			}
		}

		Stencil_Environment::trigger( 'singular', $controller, get_queried_object() );
	}
}
