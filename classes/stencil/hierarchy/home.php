<?php
/**
 * Home hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class Stencil_Hierarchy_Home
 */
class Stencil_Hierarchy_Home extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Home constructor.
	 */
	public function __construct() {
		$this->set_options( array( 'home' ) );
	}
}
