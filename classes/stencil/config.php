<?php
/**
 * Configure Implementations
 *
 * @package Stencil
 * @subpackage CMS
 */

/**
 * Class Stencil_Config
 */
class Stencil_Config {

	/**
	 * All installables available
	 *
	 * @var Stencil_Installables
	 */
	private $installables;

	/**
	 * Option page
	 *
	 * @var string
	 */
	private $option_page = 'stencil-options';

	/**
	 * Option group
	 *
	 * @var string
	 */
	private $option_group = 'stencil-installables';

	/**
	 * The name of the option
	 *
	 * @var string
	 */
	private $option_name = 'install';

	/**
	 * Stencil_Config constructor.
	 */
	public function __construct() {
		// Register hooks for config page.
		add_action( 'admin_menu', array( $this, 'create_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_options' ) );
		add_action( 'admin_init', array( $this, 'register_installables' ) );
	}

	/**
	 * Create the menu item
	 */
	public function create_admin_menu() {
		// Create new top-level menu.
		add_menu_page(
			__( 'Stencil implementations', 'stencil' ),
			'Stencil',
			'install_plugins',
			'stencil-installables',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Register settings
	 */
	public function register_options() {

		register_setting( $this->option_group, $this->option_name );

		add_settings_section(
			'stencil-installables',
			'',
			'',
			$this->option_page
		);

		add_settings_field(
			'plugins',
			__( 'Plugins', 'stencil' ),
			array( $this, 'option_plugins' ),
			$this->option_page,
			'stencil-installables'
		);

		add_settings_field(
			'themes',
			__( 'Sample themes', 'stencil' ),
			array( $this, 'option_themes' ),
			$this->option_page,
			'stencil-installables'
		);
	}

	/**
	 * Register installables.
	 */
	public function register_installables() {
		$this->installables = new Stencil_Installables();
	}

	/**
	 * Show the settings
	 */
	public function settings_page() {

		/**
		 * Show a list of known implementations
		 * Mark installed ones
		 * Provide checkbox to (bulk) install optional ones
		 */

		print( '<div class="wrap">' );
		printf( '<h2>%s</h2>', __( 'Stencil settings', 'stencil' ) );

		$this->maybe_install_plugins();
		$this->maybe_install_themes();

		print( '<form method="post" action="options.php">' );

		settings_fields( $this->option_group );
		do_settings_sections( $this->option_page );
		submit_button( __( 'Install selected item(s)', 'stencil' ) );

		print( '</form>' );

		printf( '<p><em>%s</em></p>', __( 'Note that plugins do not update because they are provided via github, not the WordPress plugin directory.', 'stencil' ) );

		print( '</div>' );
	}

	/**
	 * Show plugins that are not installed yet (but tracked)
	 * Check to install; installed plugins are grayed out and checked
	 * but are ignored on save.
	 */
	public function option_plugins() {
		$plugins = $this->installables->get_plugins();
		foreach ( $plugins as $plugin ) {

			$attributes = array();
			$available  = true;
			$base       = $this->option_name;
			$upgrade    = $plugin->has_upgrade();
			$exists     = $plugin->is_installed();
			$message    = '';

			$passed = $plugin->passed_requirements();
			if ( is_array( $passed ) ) {
				$message   = implode( '<br>', $passed );
				$available = false;
			}

			if ( $available && $exists && $upgrade ) {
				$message = __( 'Upgrade available!', 'stencil' );
			}

			/**
			 * Check if installed.
			 */
			if ( $exists ) {
				$attributes[] = 'checked="checked"';
			}

			/**
			 * Disable input if plugin is installed.
			 * Disable if not available for installation.
			 */
			if ( ( $exists && ! $upgrade ) || ! $available ) {
				$attributes[] = 'disabled="disabled"';
				$base         = 'dummy';
			}

			printf(
				'<label><input type="checkbox" name="%s"%s>%s%s</label><br>',
				esc_attr( sprintf( '%s[plugin][%s]', $base, $plugin->get_slug() ) ),
				implode( ' ', $attributes ),
				esc_html( $plugin ),
				! empty( $message ) ? sprintf( ' <small><strong>(%s)</strong></small>', $message ) : ''
			);
		}
	}

	/**
	 * Show plugins that are not installed yet (but tracked)
	 * Check to install; installed plugins are grayed out and checked
	 * but are ignored on save.
	 */
	public function option_themes() {
		$themes = $this->installables->get_themes();
		foreach ( $themes as $theme ) {

			$base       = $this->option_name;
			$exists     = $theme->is_installed();
			$attributes = array();

			/**
			 * Check if installed.
			 */
			if ( $exists ) {
				$attributes[] = 'checked="checked"';
				$attributes[] = 'disabled="disabled"';
				$base         = 'dummy';
			}

			/**
			 * Disable input if plugin is installed.
			 */
			printf(
				'<label><input type="checkbox" name="%s"%s>%s</label><br>',
				esc_attr( sprintf( '%s[theme][%s]', $base, $theme->get_slug() ) ),
				implode( ' ', $attributes ),
				esc_html( $theme )
			);
		}
	}

	/**
	 * Install selected plugins
	 */
	private function maybe_install_plugins() {
		/**
		 * When a plugin can be updated; the field will be check on the settings
		 * When all plugins have been installed, they disappear from the list.
		 */
		$install_plugins = get_option( $this->option_name );

		if (
			empty( $install_plugins ) ||
			! isset( $install_plugins['plugin'] ) ||
			! is_array( $install_plugins['plugin'] )
		) {
			return;
		}

		printf( '<h2>%s</h2>', __( 'Installing plugins...', 'stencil' ) );

		foreach ( $install_plugins['plugin'] as $slug => $on ) {

			$plugin = $this->installables->get_by_slug( $slug );

			if ( ! $plugin->is_installed() ) {
				$success = $plugin->install();
				$message = __( 'Plugin %s could not be installed!', 'stencil' );
			} else {
				$success = $plugin->upgrade();
				$message = __( 'Plugin %s could not be upgraded!', 'stencil' );
			}

			if ( ! $success ) {
				printf( '<em>%s</em><br>', sprintf( $message, $plugin ) );
			}

			unset( $install_plugins['plugin'][ $slug ] );
		}

		printf( '<b>%s</b>', __( 'Done.', 'stencil' ) );

		if ( empty( $install_plugins['plugin'] ) ) {
			unset( $install_plugins['plugin'] );
		}

		update_option( $this->option_name, $install_plugins );

	}

	/**
	 * Install selected themes
	 */
	private function maybe_install_themes() {
		/**
		 * When a plugin can be updated; the field will be check on the settings
		 * When all plugins have been installed, they disappear from the list.
		 */
		$install_plugins = get_option( $this->option_name );

		if (
			empty( $install_plugins ) ||
			! isset( $install_plugins['theme'] ) ||
			! is_array( $install_plugins['theme'] )
		) {
			return;
		}

		printf( '<h2>%s</h2>', __( 'Installing themes...', 'stencil' ) );

		foreach ( $install_plugins['theme'] as $slug => $on ) {

			$theme = $this->installables->get_by_slug( $slug );

			if ( $theme->is_installed() ) {
				continue;
			}

			$installed = $theme->install();

			if ( ! $installed ) {
				printf(
					'<em>%s</em><br>',
					sprintf(
						__( 'Theme %s could not be installed!', 'stencil' ),
						$theme
					)
				);
			}

			unset( $install_plugins['theme'][ $slug ] );
		}

		printf( '<b>%s</b>', __( 'Done.', 'stencil' ) );

		if ( empty( $install_plugins['theme'] ) ) {
			unset( $install_plugins['theme'] );
		}

		update_option( $this->option_name, $install_plugins );

	}
}
