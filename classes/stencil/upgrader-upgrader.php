<?php
/**
 * Update the upgrader
 *
 * @package Stencil
 * @subpackage Upgrader
 */

/**
 * Class Stencil_Upgrader_Upgrader
 */
class Stencil_Upgrader_Upgrader extends Stencil_Abstract_Upgrader {

	/**
	 * * Transient name to use for periodical upgrade checks.
	 */
	const TRANSIENT_NAME = 'stencil_upgrader_upgrader:last_check_timestamp';

	/**
	 * Versions of the plugins
	 *
	 * @var array
	 */
	protected $versions = array(
		'stencil-upgrader' => '1.0.0',
	);

	/**
	 * Get transient name to use.
	 *
	 * @return string
	 */
	protected function get_option_name() {
		return self::TRANSIENT_NAME;
	}

	/**
	 * Upgrade a plugin
	 *
	 * @param string $slug Plugin slug.
	 */
	protected function upgrade( $slug ) {
		// Use WordPress file system controls.
		// Download zip.
		// Rename old directory.
		// Move unpacked contents.
		// Remove old directory.
	}
}
