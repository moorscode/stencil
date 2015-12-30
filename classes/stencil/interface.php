<?php
/**
 * Stencil Interface
 *
 * Setting and getting
 *
 * @package Stencil
 */

/**
 * Interface StencilInterface
 */
interface Stencil_Interface {
	/**
	 * Set a variable to the template
	 *
	 * Handler proxy function
	 *
	 * @param string $variable Name of the variable to set.
	 * @param mixed  $value Value of the variable to set.
	 *
	 * @return mixed The value of the variable after it being set.
	 */
	public function set( $variable, $value );

	/**
	 * Get a variable value from the template
	 *
	 * Handler proxy function
	 *
	 * @param string $variable Variable name to retrieve.
	 *
	 * @return mixed
	 */
	public function get( $variable );

	/**
	 * Get the implemented final engine
	 *
	 * @return mixed
	 */
	public function get_engine();
}
