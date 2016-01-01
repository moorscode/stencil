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
	const DOWNLOAD_FORMAT = 'https://github.com/moorscode/%s/archive/master.zip';

	/**
	 * Get the theme slug.
	 *
	 * @return string
	 */
	public function get_slug() {
		return sprintf( 'stencil-sample-theme-%s', $this->slug );
	}

	/**
	 * Get the download link
	 *
	 * @return bool|string
	 */
	public function get_download_link() {
		return sprintf( self::DOWNLOAD_FORMAT, $this->get_slug() );
	}

	/**
	 * Get base directory
	 *
	 * @return string
	 */
	public function get_directory() {
		return get_theme_root() . DIRECTORY_SEPARATOR . $this->get_slug();
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
	 * Upgrade
	 *
	 * @return bool
	 */
	public function upgrade() {
		return false;
	}

	/**
	 * Get upgrader needed.
	 *
	 * @param bool $upgrading Installing or upgrading.
	 *
	 * @return WP_Upgrader
	 */
	public function get_upgrader( $upgrading = false ) {
		$skin = ( $upgrading ) ? new Theme_Upgrader_Skin() : new Theme_Installer_Skin();

		return new Theme_Upgrader( $skin );
	}
}
