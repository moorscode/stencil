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
	}
}