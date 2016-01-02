<?php
/**
 * Abstract Installable
 *
 * @package Stencil
 * @subpackage Upgrader
 */

/**
 * Class Stencil_Installable
 */
abstract class Stencil_Abstract_Installable implements Stencil_Installable_Interface {
	/**
	 * Slug of this module.
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Readable name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Version of this module.
	 *
	 * @var string
	 */
	protected $version = '0.0.0';

	/**
	 * Stencil_Installable constructor.
	 *
	 * @param string $slug Slug of the module.
	 * @param string $name Name of this module.
	 */
	public function __construct( $slug, $name ) {
		$this->slug = $slug;
		$this->name = $name;
	}

	/**
	 * Get slug name.
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Is this installable installed.
	 *
	 * @return bool
	 */
	public function is_installed() {
		return is_dir( $this->get_directory() );
	}

	/**
	 * Check if there is an upgrade available
	 *
	 * @return bool|mixed
	 */
	public function has_upgrade() {
		return false;
	}

	/**
	 * Do all requirements pass so it is usable.
	 *
	 * @return bool|array TRUE if passed, array of errors if failed.
	 */
	public function passed_requirements() {
		return true;
	}

	/**
	 * Get the name.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->name;
	}

	/**
	 * Remove/uninstall
	 *
	 * @return bool|WP_Error
	 */
	public function remove() {
		global $wp_filesystem;

		$target_path = $this->get_directory();

		$upgrader = $this->get_upgrader();
		$upgrader->init();

		// Connect to the Filesystem first.
		$res = $upgrader->fs_connect( array( WP_CONTENT_DIR, $target_path ) );

		// Mainly for non-connected filesystem.
		if ( ! $res || is_wp_error( $res ) ) {
			return false;
		}

		$deleted = $wp_filesystem->rmdir( $target_path, true );

		return $deleted;
	}

	/**
	 * Install
	 *
	 * @param bool $upgrading Installing or upgrading.
	 *
	 * @return bool|WP_Error True on succes, WP_Error on failure
	 */
	public function install( $upgrading = false ) {
		/**
		 * Themes cannot be ugpraded.
		 * So we never have to problem that the STENCIL_PATH is being moved.
		 *
		 * Though if a new theme is installed, should the installed implementations
		 * be copied to this new install aswel?
		 */

		global $wp_filesystem;

		$download_link = $this->get_download_link();
		$target_path   = $this->get_directory();

		require ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$upgrader = $this->get_upgrader();
		$upgrader->init();

		if ( ! $upgrading ) {
			$upgrader->install_strings();
		} else {
			$upgrader->upgrade_strings();
		}

		$skin = $upgrader->skin;

		$skin->header();

		// Connect to the Filesystem first.
		$res = $upgrader->fs_connect( array( WP_CONTENT_DIR, $target_path ) );

		// Mainly for non-connected filesystem.
		if ( ! $res ) {
			$skin->footer();

			return false;
		}

		$skin->before();

		if ( is_wp_error( $res ) ) {
			$this->cancel_installer( $skin, $res );

			return $res;
		}

		/**
		 * Download the package (Note, This just returns the filename
		 * of the file if the package is a local file)
		 */
		$download = $upgrader->download_package( $download_link );
		if ( is_wp_error( $download ) ) {
			$this->cancel_installer( $skin, $download );

			return $download;
		}

		// Unzips the file into a temporary directory.
		$working_dir = $upgrader->unpack_package( $download, true );
		if ( is_wp_error( $working_dir ) ) {
			$this->cancel_installer( $skin, $working_dir );

			return $working_dir;
		}

		$temporary_path = $target_path . '_upgrading';

		if ( $upgrading ) {
			$upgrader->maintenance_mode( true );

			$skin->feedback( 'remove_old' );

			if ( is_dir( $temporary_path ) ) {
				$wp_filesystem->rmdir( $temporary_path, true );
			}

			// Move current install.
			$wp_filesystem->move( $target_path, $temporary_path );
		}

		$skin->feedback( 'installing_package' );

		$installed = $wp_filesystem->move( $working_dir . DIRECTORY_SEPARATOR . $this->get_slug() . '-master', $target_path );

		if ( $upgrading ) {
			if ( false === $installed || is_wp_error( $installed ) ) {
				// Restore old install.
				$wp_filesystem->move( $temporary_path, $target_path );

				return false;
			} else {
				// Remove old install.
				$wp_filesystem->rmdir( $temporary_path, true );
			}
		}

		$upgrader->maintenance_mode( false );

		$skin->feedback( $installed ? 'process_success' : 'process_failed' );

		$skin->after();
		$skin->footer();

		// Done.
		return true;
	}

	/**
	 * Upgrade
	 *
	 * @return bool
	 * @throws Exception When an upgrade is already in progress for this package.
	 */
	public function upgrade() {
		return $this->install( true );
	}

	/**
	 * Cancel installer.
	 *
	 * @param WP_Upgrader_Skin $skin Skin to set message on.
	 * @param WP_Error|string  $error Error to display.
	 */
	protected function cancel_installer( $skin, $error ) {
		$skin->error( $error );
		$skin->after();
		$skin->footer();
	}
}
