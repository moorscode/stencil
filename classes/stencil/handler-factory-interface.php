<?php
/**
 * All functions a HandlerFactory needs to have.
 *
 * @package Stencil
 */

/**
 * Interface HandlerFactoryInterface
 */
interface Stencil_Handler_Factory_Interface {
	/**
	 * Set a hierarchy handler for a page
	 *
	 * @param string            $page Page to set.
	 * @param array|Traversable $handler Handler to use.
	 *
	 * @return void
	 */
	public static function set_hierarchy_handler( $page, $handler );

	/**
	 * Set a page type handler
	 *
	 * @param string   $page Page to set.
	 * @param callable $handler Handler to use.
	 *
	 * @return void
	 */
	public static function set_page_type_handler( $page, $handler );

	/**
	 * Set page type hooking function
	 *
	 * @param string   $page Page to set to.
	 * @param callable $handler Handler to use.
	 *
	 * @return void
	 */
	public static function set_page_type_hooker( $page, $handler );

	/**
	 * Remove handler for a page.
	 *
	 * @param string $page Page to remove from.
	 *
	 * @return void
	 */
	public static function remove_page_type_handler( $page );

	/**
	 * Remove page hooker from page
	 *
	 * @param string $page Page to remove from.
	 *
	 * @return void
	 */
	public static function remove_page_type_hooker( $page );

	/**
	 * Get current hierarchy handler for page
	 *
	 * @param string $page Page to get from.
	 *
	 * @return array|Traversable
	 */
	public static function get_hierarchy_handler( $page );

	/**
	 * Get the current page type handler
	 *
	 * @param string $page Page to get from.
	 *
	 * @return callable
	 */
	public static function get_page_type_handler( $page );

	/**
	 * Get the current page type hooker
	 *
	 * @param string $page Page to get from.
	 *
	 * @return callable
	 */
	public static function get_page_type_hooker( $page );
}
