<?php
/**
 * Implementation Interface
 *
 * Displaying and fetching
 *
 * @package Stencil
 */

/**
 * Interface ImplementationInterface
 */
interface Stencil_Implementation_Interface {
	/**
	 * Displays a chosen template
	 *
	 * Uses fetch to fetch the output
	 *
	 * @param string $template Template file to show.
	 *
	 * @return void
	 */
	public function display( $template );

	/**
	 * Fetch the template
	 *
	 * Sets the wp_head and wp_footer variables with captured data
	 *
	 * @param string $template Template file to retrieve.
	 *
	 * @return string
	 */
	public function fetch( $template );
}
