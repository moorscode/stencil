<?php
/**
 * Error hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class Stencil_Hierarchy_Error
 */
class Stencil_Hierarchy_Error extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Error constructor.
	 */
	public function __construct() {
		$this->set_options( array( '404' ) );
	}
}
