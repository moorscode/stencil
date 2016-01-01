<?php
/**
 * Installable interface
 *
 * @package Stencil
 * @subpackage Upgrader
 */

/**
 * Interface Stencil_Installable_Interface
 */
interface Stencil_Installable_Interface {
	/**
	 * Get slug name.
	 *
	 * @return string
	 */
	public function get_slug();

	/**
	 * Is this installable installed.
	 *
	 * @return bool
	 */
	public function is_installed();

	/**
	 * Get the download link to install or upgrade.
	 *
	 * @return string Download link.
	 */
	public function get_download_link();

	/**
	 * Do all requirements pass so it is usable.
	 *
	 * @return bool|array TRUE if passed, array of errors if failed.
	 */
	public function passed_requirements();

	/**
	 * Is there an upgrade available
	 *
	 * @return bool TRUE for new version, FALSE for no new version.
	 */
	public function has_upgrade();
}