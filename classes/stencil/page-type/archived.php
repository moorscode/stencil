<?php
/**
 * Archived page type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Archived
 */
class Stencil_Page_Type_Archived extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		$list = array();

		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();

				$object = get_post();

				/**
				 * Respect post_as_object filter
				 */
				$post_as_object = Stencil_Environment::filter( 'post_as_object', true );
				if ( $post_as_object ) {
					/**
					 * Apply fancy Human Made Post object if loaded
					 */
					if ( class_exists( 'Post', false ) ) {
						$item = Post::get( $object->ID );
					} else {
						$item = $object;
					}
				} else {
					$item = (array) $object;
				}

				// Add item to the list.
				$list[] = $item;

				// Clean up.
				unset( $item );
			}
		}

		wp_reset_postdata();

		$controller->set( 'posts', $list );
	}
}
