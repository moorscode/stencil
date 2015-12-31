<?php
/**
 * Interface for a Handler
 *
 * @package Stencil
 */

/**
 * Interface HandlersInterface
 */
interface Stencil_Handlers_Interface {
	/**
	 * Set a hierarchy handler for a page
	 *
	 * @param string            $page Page to set.
	 * @param array|Traversable $handler Handler to use.
	 *
	 * @return void
	 */
	public function set_hierarchy( $page, $handler );

	/**
	 * Set a page type handler
	 *
	 * @param string   $page Page to set.
	 * @param callable $handler Handler to use.
	 *
	 * @return void
	 */
	public function set_page_type_handler( $page, $handler );

	/**
	 * Set page type hooking function
	 *
	 * @param string   $page Page to set to.
	 * @param callable $handler Handler to use.
	 *
	 * @return void
	 */
	public function set_page_type_hooker( $page, $handler );

	/**
	 * Remove handler for a page.
	 *
	 * @param string $page Page to remove from.
	 *
	 * @return void
	 */
	public function remove_page_type_handler( $page );

	/**
	 * Remove page hooker from page
	 *
	 * @param string $page Page to remove from.
	 *
	 * @return void
	 */
	public function remove_page_type_hooker( $page );
}
