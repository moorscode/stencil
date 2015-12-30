<?php
/**
 * Interface for a Handler
 *
 * @package Stencil
 */

/**
 * Interface HandlerInterface
 */
interface Stencil_Handler_Interface extends Stencil_Interface {

	/**
	 * Get the implementation
	 *
	 * Handler proxy functions
	 *
	 * @return Stencil_Implementation
	 */
	public function get_implementation();

	/**
	 * Set the recorder
	 *
	 * Handler proxy functions
	 *
	 * @param Stencil_Recorder_Interface $recorder New Recorder to use.
	 *
	 * @return void
	 */
	public function set_recorder( Stencil_Recorder_Interface $recorder );

	/**
	 * Handler proxy functions
	 *
	 * Get the used recorder
	 *
	 * @return Stencil_Recorder_Interface
	 */
	public function get_recorder();

	/**
	 * Start recording for a variable
	 *
	 * Handler proxy functions
	 *
	 * @param string $variable Variable to Record into.
	 *
	 * @return void
	 */
	public function start_recording( $variable );

	/**
	 * Finish a recording
	 *
	 * Handler proxy functions
	 *
	 * @return mixed Value of Recording.
	 */
	public function finish_recording();
}
