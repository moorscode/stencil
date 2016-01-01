<?php
/**
 * Installable theme
 *
 * @package Stencil
 * @subpackage Upgrader
 */

/**
 * Class Stencil_Installable_Theme
 */
class Stencil_Installable_Theme extends Stencil_Abstract_Installable implements Stencil_Installable_Interface {
	/**
	 * Download format.
	 */
	const DOWNLOAD_FORMAT = 'https://github.com/moorscode/stencil-sample-theme-%s/archive/master.zip';

	/**
	 * Get the download link
	 *
	 * @return bool|string
	 */
	public function get_download_link() {
		return sprintf( self::DOWNLOAD_FORMAT, $this->slug );
	}

	/**
	 * Is this installable installed.
	 *
	 * @return bool
	 */
	public function is_installed() {
		// TODO: Implement is_installed() method.
		return false;
	}

	/**
	 * Get base directory
	 *
	 * @return string
	 */
	public function get_directory() {
		return get_theme_root() . DIRECTORY_SEPARATOR . $this->slug;
	}

	/**
	 * Get file headers
	 *
	 * @return array
	 */
	public function get_file_data() {
		$path = $this->get_directory() . DIRECTORY_SEPARATOR . 'style.css';
		return get_file_data( $path, array( 'version' => 'Version' ) );
	}

	/**
	 * Install
	 *
	 * @return bool
	 */
	public function install() {
		$upgrader = new Stencil_Upgrader();
		return $upgrader->install_theme( $this );
	}

	/**
	 * Upgrade
	 *
	 * @return bool
	 */
	public function upgrade() {
		return false;
	}

	/**
	 * Remove/uninstall
	 *
	 * @return bool
	 */
	public function remove() {
		$upgrader = new Stencil_Upgrader();
		return $upgrader->remove_theme( $this );
	}
}
