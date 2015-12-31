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
	 * Implementations provided by us.
	 *
	 * @var array
	 */
	private $known_implementations = array(
		'stencil-dwoo'     => 'Dwoo',
		'stencil-dwoo2'    => 'Dwoo 2',
		'stencil-mustache' => 'Mustache',
		'stencil-savant3'  => 'Savant 3',
		'stencil-smarty2'  => 'Smarty 2.x',
		'stencil-smarty3'  => 'Smarty 3.x',
		'stencil-twig'     => 'Twig',
	);

	/**
	 * Implementations that require a specific minimal PHP version
	 *
	 * @var array
	 */
	private $implementation_php_requirements = array(
		'stencil-dwoo'  => '5.3.0',
		'stencil-dwoo2' => '5.3.0',
	);

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
	private $option_group = 'stencil-implementations';

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
			'stencil-implementations',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Register settings
	 */
	public function register_options() {

		register_setting( $this->option_group, $this->option_name );

		add_settings_section(
			'stencil-implementations',
			'',
			'',
			$this->option_page
		);

		add_settings_field(
			'implementations',
			__( 'Implementations', 'stencil' ),
			array( $this, 'option_implementations' ),
			$this->option_page,
			'stencil-implementations'
		);
	}

	/**
	 * Show plugins that are not installed yet (but tracked)
	 * Check to install; installed plugins are grayed out and checked
	 * but are ignored on save.
	 */
	public function option_implementations() {
		foreach ( $this->known_implementations as $slug => $name ) {

			$attributes = array();
			$available  = true;
			$base       = $this->option_name;
			$error      = '';

			if ( isset( $this->implementation_php_requirements[ $slug ] ) ) {
				if ( version_compare( PHP_VERSION, $this->implementation_php_requirements[ $slug ], '<' ) ) {
					$available = false;
					$error     = sprintf( __( 'PHP version %s required, %s available.' ), $this->implementation_php_requirements[ $slug ], PHP_VERSION );
				}
			}

			$exists = is_dir( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $slug );

			/**
			 * Disable input if plugin is installed.
			 */
			if ( $exists ) {
				$attributes[] = 'checked="checked"';
			}

			if ( $exists || ! $available ) {
				$attributes[] = 'disabled="disabled"';
				$base         = 'dummy';
			}

			printf(
				'<label><input type="checkbox" name="%s"%s>%s%s</label><br>',
				esc_attr( sprintf( '%s[plugin][%s]', $base, $slug ) ),
				implode( ' ', $attributes ),
				esc_html( $name ),
				! empty( $error ) ? sprintf( ' <small>(%s)</small>', $error ) : ''
			);
		}
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

		print( '<form method="post" action="options.php">' );

		settings_fields( $this->option_group );
		do_settings_sections( $this->option_page );
		submit_button( __( 'Install selected implementation(s)', 'stencil' ) );

		print( '</form>' );

		print( '</div>' );
	}

	/**
	 * Install selected plugins
	 */
	public function maybe_install_plugins() {
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

			$installed = $this->install_plugin( $slug );

			if ( ! $installed ) {
				printf(
					'<em>%s</em><br>',
					sprintf(
						__( 'Plugin %s could not be installed!', 'stencil' ),
						$this->known_implementations[ $slug ]
					)
				);
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
	 * Install plugin by slug
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return bool
	 */
	public function install_plugin( $slug ) {

		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $slug,
				'fields' => array(
					'short_description' => false,
					'sections'          => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'donate_link'       => false,
				),
			)
		);

		if ( is_wp_error( $api ) ) {
			return false;
		}

		iframe_header();
		$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin( array() ) );
		$upgrader->install( $api->download_link );
		iframe_footer();

		return true;
	}
}
