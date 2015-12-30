<?php
/**
 * Category hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class Stencil_Hierarchy_Category
 */
class Stencil_Hierarchy_Category extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Category constructor.
	 */
	public function __construct() {
		$category = get_queried_object();

		$options = array();

		if ( ! empty( $category->slug ) ) {
			$options[] = 'archive/category-' . $category->slug;
			$options[] = 'category-' . $category->slug;

			$options[] = 'archive/category-' . $category->term_id;
			$options[] = 'category-' . $category->term_id;
		}
		$options[] = 'archive/category';
		$options[] = 'category';

		$this->set_options( $options );
	}
}
