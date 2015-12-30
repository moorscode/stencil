<?php
/**
 * Front Page hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class FrontPage
 */
class Stencil_Hierarchy_Front_Page extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Front_Page constructor.
	 */
	public function __construct() {
		$this->set_options( array( 'front-page' ) );
	}
}
