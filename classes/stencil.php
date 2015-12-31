<?php
/**
 * Stencil core class
 *
 * This class contains all the logic of the interface between the theme and the template engine
 * Selecting views, loading the proper files and triggering the hooks and filters.
 *
 * Author: Jip Moors (jhp.moors@gmail.com)
 * Date: 4 juli 2015
 *
 * @package Stencil
 */

/**
 * Class stencil
 */
final class Stencil implements Stencil_Interface, Stencil_Handlers_Interface, Stencil_Handler_Interface {
	/**
	 * Instance for singleton
	 *
	 * @var Stencil
	 */
	private static $instance;

	/**
	 * Handler instance
	 *
	 * @var Stencil_Handler
	 */
	private static $handler;

	/**
	 * Flow instance
	 *
	 * @var Stencil_Flow_Interface
	 */
	private static $flow;

	/**
	 * The default implementation class
	 *
	 * @var string
	 */
	private static $default_implementation_class;

	/**
	 * Boot up the main Stencil flow
	 *
	 * Triggered by the {FILTER_PREFIX}engine_ready hook
	 * @see Environment::format_hook
	 *
	 * @param Stencil_Implementation $engine Boot Stencil with the supplied engine.
	 */
	public static function boot( Stencil_Implementation $engine ) {
		remove_action( Stencil_Environment::format_hook( 'engine_ready' ), array( __CLASS__, __FUNCTION__ ) );

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Stencil( $engine );

			/**
			 * This cannot be inside the constructor:
			 */
			Stencil_Environment::trigger( 'initialize', self::$instance );
		}
	}

	/**
	 * Construct the class and initialize engine
	 *
	 * @param Stencil_Implementation $engine Initilize Stencil with the supplied engine.
	 */
	private function __construct( Stencil_Implementation $engine ) {
		// Set the handler class.
		self::$default_implementation_class = get_class( $engine );

		// Set the internal handler.
		self::$handler = $this->get_handler( $engine );

		// Flow is irrelevant for AJAX calls; as are header and footers.
		if ( ! defined( 'DOING_AJAX' ) || false === DOING_AJAX ) {
			// For the default engine, load the wp_head and wp_footer into variables.
			self::$handler->load_wp_header_and_footer( true );

			// Set default flow.
			self::$flow = new Stencil_Flow();
		}

		/**
		 * Append the 'assets' directory to the template URI for easy access
		 */
		add_filter( 'template_directory_uri', array( $this, 'append_assets_directory' ) );
		add_filter( 'stylesheet_directory_uri', array( $this, 'append_assets_directory' ) );

		/**
		 * Make sure we only load index.php
		 *
		 * This removes the need for the use of the constant to skip the default loading
		 * which would be a very inconvenient thing to do when somebody wants to switch
		 * themes.
		 */
		add_filter( 'template_include', array( $this, 'template_include_override' ) );
	}

	/**
	 * Get the template engine instance
	 *
	 * Named controller for usability
	 *
	 * @return Stencil
	 * @throws Exception If Stencil was not initialized properly.
	 */
	public static function controller() {
		if ( ! isset( self::$instance ) ) {
			throw new Exception( 'Stencil not booted properly.' );
		}

		return self::$instance;
	}

	/**
	 * Get a new Handler
	 *
	 * @param Stencil_Implementation|null     $implementation Optional. Set Implementation on new Handler.
	 * @param Stencil_Recorder_Interface|null $recorder Optional. Supply a custom Recorder.
	 *
	 * @return Stencil_Handler
	 */
	public static function get_handler(
		Stencil_Implementation $implementation = null,
		Stencil_Recorder_Interface $recorder = null
	) {
		/**
		 * Use "default" implementation if none supplied
		 */
		$implementation = ! is_null( $implementation ) ? $implementation : new self::$default_implementation_class();

		return new Stencil_Handler( $implementation, $recorder );
	}

	/**
	 * Set the flow controller
	 *
	 * Handler proxy functions
	 *
	 * @param Stencil_Flow_Interface $flow The new Flow to set to Stencil.
	 */
	public function set_flow( Stencil_Flow_Interface $flow ) {
		self::$flow = $flow;
	}

	/**
	 * Get the used flow
	 *
	 * Handler proxy functions
	 *
	 * @return Stencil_Flow_Interface
	 */
	public function get_flow() {
		return self::$flow;
	}


	/**
	 * Get a variable value from the template
	 *
	 * Handler proxy function
	 *
	 * @param string $variable Variable name to retrieve.
	 *
	 * @return mixed
	 */
	public function get( $variable ) {
		return self::$handler->get( $variable );
	}

	/**
	 * Set a variable to the template
	 *
	 * Handler proxy function
	 *
	 * @param string $variable Name of the variable to set.
	 * @param mixed  $value Value of the variable to set.
	 *
	 * @return mixed The value of the variable after it being set.
	 */
	public function set( $variable, $value ) {
		return self::$handler->set( $variable, $value );
	}

	/**
	 * Get the implementation
	 *
	 * Handler proxy functions
	 *
	 * @return Stencil_Implementation
	 */
	public function get_implementation() {
		return self::$handler->get_implementation();
	}

	/**
	 * Get the reference to the engine of the loaded Proxy
	 *
	 * This way theme developers can apply services and other
	 * engine specific functionality easily
	 *
	 * @return mixed
	 */
	public function get_engine() {
		return self::$handler->get_engine();
	}

	/**
	 * Set the recorder
	 *
	 * Handler proxy functions
	 *
	 * @param Stencil_Recorder_Interface $recorder New Recorder to use.
	 */
	public function set_recorder( Stencil_Recorder_Interface $recorder ) {
		self::$handler->set_recorder( $recorder );
	}

	/**
	 * Handler proxy functions
	 *
	 * Get the used recorder
	 *
	 * @return Stencil_Recorder_Interface
	 */
	public function get_recorder() {
		return self::$handler->get_recorder();
	}

	/**
	 * Start recording for a variable
	 *
	 * Handler proxy functions
	 *
	 * @param string                          $variable Variable to Record into.
	 * @param Stencil_Recorder_Interface|null $recorder Optional. Custom recorder to use for this action.
	 */
	public function start_recording( $variable, Stencil_Recorder_Interface $recorder = null ) {
		self::$handler->start_recording( $variable, $recorder );
	}

	/**
	 * Finish a recording
	 *
	 * Handler proxy functions
	 *
	 * @returns mixed Value of Recording.
	 */
	public function finish_recording() {
		return self::$handler->finish_recording();
	}

	/**
	 * Set the hierarchy handler for a page type
	 *
	 * HandlerFactory proxy functions
	 *
	 * @param string                 $page Page Type to set for.
	 * @param array|Traversable|null $handler Optional. Handler that will provide hierarchy for specified page.
	 */
	public function set_hierarchy( $page, $handler ) {
		Stencil_Handler_Factory::set_hierarchy_handler( $page, $handler = null );
	}

	/**
	 * Remove all view options for a page
	 *
	 * This will make the page be displayed with the 'index' view
	 *
	 * @param string $page Page to remove hierarchy of
	 */
	public function remove_hierarchy( $page ) {
		Stencil_Handler_Factory::set_hierarchy_handler( $page );
	}

	/**
	 * Set the page type handler for a page
	 *
	 * HandlerFactory proxy functions
	 *
	 * @param string   $page Page to set for.
	 * @param callable $handler Handler that will be executed for specified page.
	 */
	public function set_page_type_handler( $page, $handler ) {
		Stencil_Handler_Factory::set_page_type_handler( $page, $handler );
	}

	/**
	 * Set the page type hooker for a page
	 *
	 * HandlerFactory proxy functions
	 *
	 * @param string   $page Page to set for.
	 * @param callable $handler Hooker that will be executed for specified page.
	 */
	public function set_page_type_hooker( $page, $handler ) {
		Stencil_Handler_Factory::set_page_type_hooker( $page, $handler );
	}

	/**
	 * Remove the page type handler for a page
	 *
	 * HandlerFactory proxy functions
	 *
	 * @param string $page Page Type to remove handler of.
	 */
	public function remove_page_type_handler( $page ) {
		Stencil_Handler_Factory::remove_page_type_handler( $page );
	}

	/**
	 * Remove the page hooker for a page
	 *
	 * HandlerFactory proxy functions
	 *
	 * @param string $page Page Type to remove hooker of.
	 */
	public function remove_page_type_hooker( $page ) {
		Stencil_Handler_Factory::remove_page_type_hooker( $page );
	}

	/**
	 * Run the main process
	 *
	 * @param string|null $page Optional. Fake a certain page.
	 */
	public function run( $page = null ) {
		if ( empty( $page ) || ! is_string( $page ) ) {
			$page = Stencil_Environment::get_page();
		}

		// Add to template.
		$this->set( 'page', $page );

		// Get actions that need to be run.
		$actions = self::$flow->get_page_actions( $page );

		// Execute actions.
		if ( is_array( $actions ) && array() !== $actions ) {
			foreach ( $actions as $action ) {
				// Apply variables.
				Stencil_Handler_Factory::run_page_type_handler( $action, $this );
				Stencil_Handler_Factory::run_page_type_hook( $action, $this );
			}
		}

		// Get available views.
		$options = self::$flow->get_view_hierarchy( $page );
		$view    = self::$handler->get_usable_view( $options );

		// Display the view.
		self::$handler->display( $view );
	}

	/**
	 * Append assets directory to the template directory uri
	 *
	 * @param string $base Base Path to append the assets directory to.
	 *
	 * @return string
	 */
	public static function append_assets_directory( $base ) {
		static $cache;

		if ( ! isset( $cache ) ) {

			/**
			 * Only apply if theme is Stencil ready
			 */
			$theme = Stencil_Environment::filter( 'require', false );
			if ( false !== $theme ) {

				/**
				 * Filter: stencil_assets_path
				 *
				 * Default template dir uri is appended by 'assets' for a structured theme directory
				 * Themes can disable or modify the default
				 */
				$assets_path = Stencil_Environment::filter( 'assets_path', 'assets' );
				if ( ! empty( $assets_path ) ) {
					$cache = $base . DIRECTORY_SEPARATOR . $assets_path;
				}
			}

			$cache = isset( $cache ) ? $cache : $base;
		}

		return $cache;
	}

	/**
	 * Rewrite all scripts to index.php of the theme
	 *
	 * @param string $template Template that is being loaded.
	 *
	 * @return mixed
	 */
	public static function template_include_override( $template ) {
		/**
		 * Only apply if theme uses Stencil
		 */
		$theme = Stencil_Environment::filter( 'require', false );
		if ( false !== $theme ) {

			/**
			 * Make it optional with default disabled
			 */
			$force = Stencil_Environment::filter( 'template_index_only', false );
			if ( $force ) {
				return get_index_template();
			}
		}

		return $template;
	}
}
