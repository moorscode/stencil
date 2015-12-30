<?php
/**
 * Post Type Archive hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class Stencil_Hierarchy_Post_Type_Archive
 */
class Stencil_Hierarchy_Post_Type_Archive extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Post_Type_Archive constructor.
	 */
	public function __construct() {
		$post_type = get_post_type();

		$options = array();

		$options[] = 'archive/' . $post_type;
		$options[] = $post_type;

		$this->set_options( $options );
	}
}
