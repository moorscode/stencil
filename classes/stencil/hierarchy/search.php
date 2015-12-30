<?php
/**
 * Search hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class Stencil_Hierarchy_Search
 */
class Stencil_Hierarchy_Search extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Search constructor.
	 */
	public function __construct() {
		$this->set_options( array( 'search' ) );
	}
}
