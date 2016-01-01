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
	const DAY_TIMESTAMP = 86400; // 60*60*24 = one day

	/**
	 * Versions of the plugins
	 *
	 * @var array
	 */
	protected $versions = array();

	/**
	 * Packages that can be upgraded
	 *
	 * @var array
	 */
	protected $upgrades = array();

	/**
	 * Stencil_Upgrader constructor.
	 */
	public function __construct() {
		// Periodically check for upgrades.
		$option_name = $this->get_option_name();
		$timeout     = $this->get_upgrade_timeout();

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

		foreach ( $this->versions as $slug => $version ) {
			if ( $this->can_upgrade( $slug ) ) {
				$this->upgrades[] = $slug;
			}
		}
	}

	/**
	 * Upgrade packages
	 */
	public function do_upgrades() {
		if ( empty( $this->upgrades ) ) {
			return;
		}

		foreach ( $this->upgrades as $slug ) {
			$this->upgrade( $slug );
		}
	}

	/**
	 * Upgrade a plugin
	 *
	 * Use WordPress file system controls.
	 * Download zip.
	 * Rename old directory.
	 * Move unpacked contents.
	 * Remove old directory.
	 *
	 * @param string $slug Plugin slug.
	 */
	abstract protected function upgrade( $slug );

	/**
	 * Is there an upgrade available?
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return mixed
	 */
	private function can_upgrade( $slug ) {
		if ( ! isset( $this->versions[ $slug ] ) ) {
			return false;
		}

		// Check if there are upgrades available.
		$path = dirname( STENCIL_PATH ) . DIRECTORY_SEPARATOR . sprintf( '%1$s/%1$s.php', $slug );

		if ( ! is_file( $path ) ) {
			$headers = array(
				'version' => '0.0.0',
			);
		} else {
			$headers = get_file_data( $path, array( 'version' => 'Version' ) );
		}

		return version_compare( $headers['version'], $this->versions[ $slug ], '<' );
	}

	/**
	 * Install theme by slug
	 *
	 * @param string $slug Theme slug.
	 *
	 * @return bool
	 */
	public function install_theme( $slug ) {
		$download_link = $this->get_theme_download_link( $slug );

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		iframe_header();
		$upgrader = new Theme_Upgrader( new Theme_Installer_Skin( array() ) );
		$upgrader->install( $download_link );
		iframe_footer();

		return true;
	}

	/**
	 * Install plugin by slug
	 *
	 * @param string $slug Plugin slug.
	 * @param bool $upgrade Installation or upgrade.
	 *
	 * @return bool
	 */
	public function upgrade_plugin( $slug, $upgrade = true ) {
		$download_link = $this->get_plugin_download_link( $slug );

		return true;
	}

	/**
	 * Get the download link for the plugin
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return string
	 */
	private function get_plugin_download_link( $slug ) {
		return sprintf( 'https://github.com/moorscode/%s/archive/master.zip', $slug );
	}

	/**
	 * Get the download link for the plugin
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return string
	 */
	private function get_theme_download_link( $slug ) {
		return sprintf( 'https://github.com/moorscode/stencil-sample-theme-%s/archive/master.zip', $slug );
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
