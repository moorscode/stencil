<?php
/**
 * Functions a Recorder needs to implement
 *
 * @package Stencil
 */

/**
 * Interface RecorderInterface
 */
interface Stencil_Recorder_Interface {
	/**
	 * Start capturing output buffer
	 *
	 * @return void
	 */
	public function start_recording();

	/**
	 * Finish capturing output buffer and save captured data
	 *
	 * @return string
	 */
	public function finish_recording();
}
