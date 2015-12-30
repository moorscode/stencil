<?php
/**
 * Recorder to use in a Handler to record output into a variable
 *
 * @package Stencil
 */

/**
 * Class Recorder
 */
class Stencil_Recorder implements Stencil_Recorder_Interface {
	/**
	 * Recorded output saved
	 *
	 * @var string
	 */
	protected $recorded = '';

	/**
	 * Status
	 *
	 * @var bool
	 */
	protected $recording = false;

	/**
	 * Start recording
	 */
	public function start_recording() {
		$this->recorded = '';

		ob_start();

		$this->recording = true;
	}

	/**
	 * Finish recording and return recorded data
	 *
	 * @return mixed
	 */
	public function finish_recording() {
		$this->recorded  = ob_get_clean();
		$this->recording = false;

		return $this->get_recording();
	}

	/**
	 * Get the recorded data
	 *
	 * @return mixed
	 * @throws LogicException When we are still recording.
	 */
	public function get_recording() {
		if ( $this->recording ) {
			throw new LogicException( 'Tried to get recorded output while still recording.' );
		}

		return $this->recorded;
	}
}
