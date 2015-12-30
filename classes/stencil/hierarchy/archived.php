<?php
/**
 * Archived hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class Stencil_Hierarchy_Archived
 */
class Stencil_Hierarchy_Archived extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Archived constructor.
	 */
	public function __construct() {
		$this->set_options( array( 'archive' ) );
	}
}
