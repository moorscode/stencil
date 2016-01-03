<?php
/**
 * Bootstrap class
 *
 * Add required Hooks and Filters to provide:
 *  - Theme required implementation
 *  - Implementation registration
 *  - Implementation ready status
 *  - Translations
 *
 * @package Stencil
 */

/**
 * Class Bootstrap
 */
class Stencil_Bootstrap {

	/**
	 * Did we bootstrap?
	 *
	 * @var bool
	 */
	private static $bootstrapped = false;

	/**
	 * Bootstrap constructor.
	 */
	public function __construct() {

		// Make sure we never bootstrap multiple times.
		if ( true === self::$bootstrapped ) {
			return;
		}

		/**
		 * Initialize the plugin to check for registered addons
		 *
		 * Checking if a plugin is active with the "stencil-" prefix is not enough
		 * The plugin could have not implemented the proper hook and could not be usable in the theme
		 */
		add_action( 'after_setup_theme', array( __CLASS__, 'boot' ) );

		/**
		 * Allow implementation to be filtered
		 */
		add_filter( Stencil_Environment::format_filter( 'implementation' ), array( __CLASS__, 'implementation' ) );

		/**
		 * Boot Stencil when an engine is ready
		 */
		add_action( Stencil_Environment::format_hook( 'engine_ready' ), array( 'Stencil', 'boot' ) );

		/**
		 * Load plugin textdomain
		 */
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );

		/**
		 * Boot up config if we are in the CMS.
		 */
		if ( is_admin() ) {
			new Stencil_Config();
			new Stencil_Upgrader();
		}

		self::$bootstrapped = true;
	}

	/**
	 * Get the classname of the required Implementation
	 */
	public static function boot() {
		$implementation = Stencil_Environment::filter( 'implementation', false );
		$required       = Stencil_Environment::filter( 'require', false );

		if ( $implementation !== $required ) {
			$message = __( '<em>Theme - Implementation conflict</em>. The active theme requires the Stencil Implementation: <em>%s</em> to be active.', 'stencil' );
			$message = sprintf( $message, $required );

			Stencil_Feedback::notification( 'error', $message );

			return null;
		}

		// Tell the implementation to active itself.
		Stencil_Environment::trigger( 'activate-' . $required );
	}

	/**
	 * Get the required implementation if found, otherwise first in list.
	 *
	 * @return bool
	 */
	public static function implementation() {
		$engines = Stencil_Environment::filter( 'register-engine', array() );
		if ( ! is_array( $engines ) || array() === $engines ) {
			return false;
		}

		/**
		 * Get theme required addon information
		 *
		 * If the addon that the theme needs is registered, return it
		 */
		$required = Stencil_Environment::filter( 'require', false );
		if ( in_array( $required, $engines, true ) ) {
			return $required;
		}

		/**
		 * Otherwise return first registered one
		 */
		return false;
	}

	/**
	 * Load translations for Stencil
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'stencil', false, STENCIL_PATH . '/languages' );
	}
}
