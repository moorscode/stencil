<?php
/**
 * Author hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class Stencil_Hierarchy_Author
 */
class Stencil_Hierarchy_Author extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Author constructor.
	 */
	public function __construct() {
		$author  = get_queried_object();
		$options = array();

		if ( class_exists( 'WP_User' ) && $author instanceof WP_User ) {
			$options[] = 'single/author' . $author->user_nicename;
			$options[] = 'author-' . $author->user_nicename;

			$options[] = 'single/author' . $author->ID;
			$options[] = 'author-' . $author->ID;
		}

		$options[] = 'archive/author';
		$options[] = 'author';

		$this->set_options( $options );
	}
}
