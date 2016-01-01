<?php
/**
 * Update the upgrader
 *
 * @package Stencil
 * @subpackage Upgrader
 */

/**
 * Class Stencil_Upgrader
 */
class Stencil_Upgrader {

	/**
	 * Check for upgrades once a day.
	 */
	const DAY_TIMESTAMP = 86400; // 60*60*24 = one da

	/**
	 * Packages that can be upgraded
	 *
	 * @var array
	 */
	protected $upgrades = array();

	/**
	 * Installables instance.
	 *
	 * @var Stencil_Installables
	 */
	protected $installables;

	/**
	 * * Transient name to use for periodical upgrade checks.
	 */
	const TRANSIENT_NAME = 'stencil_upgrader_upgrader:last_check_timestamp';

	/**
	 * Stencil_Upgrader constructor.
	 */
	public function __construct() {
		// Periodically check for upgrades.
		$option_name = $this->get_option_name();
		$timeout     = $this->get_upgrade_timeout();

		$this->installables = new Stencil_Installables();

		// Get saved information.
		$info = get_option( $option_name );

		if ( false !== $info ) {
			$this->upgrades = $info['upgrades'];
		}

		// Check if we need to read version info.
		if ( false === $info || $info['last_check_timestamp'] + $timeout < time() ) {
			$this->check_for_upgrades();
			$this->save_upgrade_information();
		}
	}

	/**
	 * Get transient name to use.
	 *
	 * @return string
	 */
	protected function get_option_name() {
		return self::TRANSIENT_NAME;
	}

	/**
	 * Get the periodically check timeout.
	 *
	 * @return int
	 */
	protected function get_upgrade_timeout() {
		return self::DAY_TIMESTAMP;
	}

	/**
	 * Get available upgrades.
	 *
	 * @return array
	 */
	public function get_upgrades() {
		return $this->upgrades;
	}

	/**
	 * Check for all upgrades
	 *
	 * @return bool
	 */
	public function check_for_upgrades() {
		// Don't check twice.
		static $checked = false;

		if ( false !== $checked ) {
			return false;
		}

		$checked = true;

		$this->upgrades = $this->installables->get_upgradable();

		return true;
	}

	/**
	 * Upgrade packages
	 */
	public function upgrade_all() {
		if ( empty( $this->upgrades ) ) {
			return;
		}

		foreach ( $this->upgrades as $installable ) {
			$installable->upgrade();
		}
	}

	/**
	 * Save upgrade information to the database.
	 */
	private function save_upgrade_information() {
		$option_name = $this->get_option_name();

		$info = array(
			'last_check_timestamp' => time(),
			'upgrades'             => $this->upgrades,
		);

		update_option( $option_name, $info );
	}
}
