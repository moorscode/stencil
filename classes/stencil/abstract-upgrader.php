<?php

/**
 * Upgrader base
 *
 * @package Stencil
 * @subpackage Upgrader
 */

/**
 * Class Stencil_Abstract_Upgrader
 */
abstract class Stencil_Abstract_Upgrader {

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
	 * Get the transient name to use.
	 *
	 * @return string
	 */
	abstract protected function get_option_name();

	/**
	 * Get the periodically check timeout.
	 *
	 * @return int
	 */
	protected function get_upgrade_timeout() {
		return 1;

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
	 */
	public function check_for_upgrades() {
		// Don't check twice.
		static $checked = false;

		if ( false !== $checked ) {
			return;
		}

		$checked = true;

		$this->upgrades = $this->installables->get_upgradable();
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
	 * Install theme by slug
	 *
	 * @param Stencil_Installable_Theme $theme Installable.
	 *
	 * @return bool
	 */
	public function install_theme( Stencil_Installable_Theme $theme ) {
		$download_link = $theme->get_download_link();

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		iframe_header();
		$upgrader = new Theme_Upgrader( new Theme_Installer_Skin( array() ) );
		$upgrader->install( $download_link );
		iframe_footer();

		return true;
	}

	/**
	 * Remove theme
	 *
	 * @param Stencil_Installable_Theme $theme Theme to remove.
	 *
	 * @return bool
	 */
	public function remove_theme( Stencil_Installable_Theme $theme ) {
		return $this->remove( $theme->get_directory() );
	}

	/**
	 * Install plugin
	 *
	 * @param Stencil_Installable_Plugin $plugin Plugin to install.
	 *
	 * @return bool
	 */
	public function install_plugin( Stencil_Installable_Plugin $plugin ) {
		$download_link = $plugin->get_download_link();
		$target_path   = $plugin->get_directory();

		return true;
	}

	/**
	 * Upgrade plugin
	 *
	 * @param Stencil_Installable_Plugin $plugin Plugin to upgrade.
	 *
	 * @return bool
	 */
	public function upgrade_plugin( Stencil_Installable_Plugin $plugin ) {
		$download_link = $plugin->get_download_link();
		$target_path   = $plugin->get_directory();

		// Use install_plugin.
		$this->install_plugin( $plugin );

		return true;
	}

	/**
	 * Remove plugin
	 *
	 * @param Stencil_Installable_Plugin $plugin Plugin to remove.
	 *
	 * @return bool
	 */
	public function remove_plugin( Stencil_Installable_Plugin $plugin ) {
		return $this->remove( $plugin->get_directory() );
	}

	/**
	 * Remove directory
	 *
	 * @param string $directory Absolute path to remove.
	 *
	 * @return bool
	 */
	private function remove( $directory ) {
		return true;
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
