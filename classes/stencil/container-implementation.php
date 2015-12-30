<?php
/**
 * Container Implementation class
 *
 * Some implementations need to hold all variables themselves.
 * This subclass provides that functionality to extend from.
 *
 * @package Stencil
 */

/**
 * Class ContainerImplementation
 */
abstract class Stencil_Container_Implementation extends Stencil_Implementation {
	/**
	 * Container for the variables set during run
	 *
	 * @var array
	 */
	protected $variables = array();

	/**
	 * Set the variable
	 *
	 * @param string $variable Name of the variable to set.
	 * @param mixed  $value Value of the variable.
	 *
	 * @return mixed Value of the variable after setting.
	 */
	public function set( $variable, $value ) {
		$this->variables[ $variable ] = $value;

		return $this->get( $variable );
	}

	/**
	 * Get the value of the variable
	 *
	 * @param string $variable Name of the variable.
	 *
	 * @return null
	 */
	public function get( $variable ) {
		if ( isset( $this->variables[ $variable ] ) ) {
			return $this->variables[ $variable ];
		}

		return null;
	}
}
