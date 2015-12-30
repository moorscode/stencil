<?php
/**
 * Interface for Flow implementations
 *
 * @package Stencil
 */

/**
 * Interface FlowInterface
 */
interface Stencil_Flow_Interface {
	/**
	 * Get Actions
	 *
	 * @param string $page Page to get Action for.
	 *
	 * @return array
	 */
	public function get_page_actions( $page );

	/**
	 * Get View Hierarchy tree
	 *
	 * @param string $page Page to get Hierarchy of.
	 *
	 * @return array
	 */
	public function get_view_hierarchy( $page );
}
