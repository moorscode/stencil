<?php
/**
 * Taxonomy hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class Stencil_Hierarchy_Taxonomy
 */
class Stencil_Hierarchy_Taxonomy extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Taxonomy constructor.
	 */
	public function __construct() {
		$term = get_queried_object();

		$options = array();

		if ( ! empty( $term->slug ) ) {
			$taxonomy = $term->taxonomy;

			$options[] = 'archive/taxonomy-' . $taxonomy . '-' . $term->slug;
			$options[] = 'taxonomy-' . $taxonomy . '-' . $term->slug;

			$options[] = 'archive/taxonomy-' . $taxonomy;
			$options[] = 'taxonomy-' . $taxonomy;
		}
		$options[] = 'archive/taxonomy';
		$options[] = 'taxonomy';

		$this->set_options( $options );
	}
}
