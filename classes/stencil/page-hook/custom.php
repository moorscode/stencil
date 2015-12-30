<?php
/**
 * Custom page hook
 *
 * @package Stencil\PageHook
 */

/**
 * Class Stencil_Page_Hook_Custom
 */
class Stencil_Page_Hook_Custom extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		add_filter( Stencil_Environment::format_filter( 'views-custom' ), array( $this, 'set_template_as_first_option' ) );
	}

	/**
	 * Prepend {page_template} on the view stack for custom page
	 *
	 * @param array $options Current options.
	 *
	 * @return mixed
	 */
	public function set_template_as_first_option( $options ) {
		$page_template = get_page_template();

		if ( $page_template ) {
			$page_template_base = basename( $page_template );
			$page_template_pure = str_replace( '.php', '', $page_template_base );

			// Reversed order; first look for 'custom', then 'single'.
			array_unshift( $options, 'single/' . $page_template_pure );
			array_unshift( $options, 'custom/' . $page_template_pure );
		}

		return $options;
	}
}
