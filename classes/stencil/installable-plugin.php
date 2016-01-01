<?php
/**
 * Installable plugin
 *
 * @package Stencil
 * @subpackage Upgrader
 */

/**
 * Class Stencil_Installable_Plugin
 */
class Stencil_Installable_Plugin extends Stencil_Abstract_Installable implements Stencil_Installable_Interface {
	/**
	 * Download format.
	 */
	const DOWNLOAD_FORMAT = 'https://github.com/moorscode/%s/archive/master.zip';

	/**
	 * Required PHP version.
	 *
	 * @var string
	 */
	private $required_php_version = '0.0.0';

	/**
	 * Stencil_Installable constructor.
	 *
	 * @param string $slug Slug of the module.
	 * @param string $name Name of this module.
	 * @param string $required_php_version Minimal PHP version required.
	 */
	public function __construct( $slug, $name, $required_php_version = '5.2.0' ) {
		parent::__construct( $slug, $name );
		$this->required_php_version = $required_php_version;
	}

	/**
	 * Get the download link
	 *
	 * @return bool|string
	 */
	public function get_download_link() {
		return sprintf( self::DOWNLOAD_FORMAT, $this->slug );
	}

	/**
	 * Check if there is an upgrade available
	 *
	 * @return bool|mixed
	 */
	public function has_upgrade() {
		// Check if there are upgrades available.
		$path = dirname( STENCIL_PATH ) . DIRECTORY_SEPARATOR . sprintf( '%1$s/%1$s.php', $this->slug );

		if ( ! is_file( $path ) ) {
			$headers = array(
				'version' => '0.0.0',
			);
		} else {
			$headers = get_file_data( $path, array( 'version' => 'Version' ) );
		}

		$latest_version = Stencil_Installable_Versions::get( $this );

		return version_compare( $headers['version'], $latest_version, '<' );
	}

	/**
	 * Do all requirements pass so it is usable.
	 *
	 * @return bool|array TRUE if passed, array of errors if failed.
	 */
	public function passed_requirements() {
		$errors = array();

		if ( version_compare( PHP_VERSION, $this->required_php_version, '<' ) ) {
			$errors[] = sprintf( __( 'PHP version %s required, %s available.' ), $this->required_php_version, PHP_VERSION );
		}

		return empty( $errors ) ? true : $errors;
	}

	/**
	 * Is this installable installed.
	 *
	 * @return bool
	 */
	public function is_installed() {
		return is_dir( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->slug );
	}
}
