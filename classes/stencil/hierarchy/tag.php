<?php
/**
 * Tag hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class Stencil_Hierarchy_Tag
 */
class Stencil_Hierarchy_Tag extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Tag constructor.
	 */
	public function __construct() {
		$this->set_options(
			array(
				'archive/date',
				'date',
			)
		);
	}
}
