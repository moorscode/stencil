<?php
/**
 * Handler class
 *
 * The handler holds an Implementation and controls recording for it.
 * Filtering for variables also happens here.
 *
 * @package Stencil
 */

/**
 * Class Stencil_Handler
 */
class Stencil_Handler implements Stencil_Handler_Interface, Stencil_Implementation_Interface {

	/**
	 * Use ob_start / ob_end_clean to record a variable.
	 *
	 * @var string
	 */
	protected $recording_for = null;

	/**
	 * Revert to recorder after finishing recording.
	 *
	 * @var null|Stencil_Recorder_Interface
	 */
	protected $revert_recorder_to;

	/**
	 * The instance of the proxy
	 *
	 * The proxy communicates with the engine
	 *
	 * @var Stencil_Implementation $proxy
	 */
	protected $proxy;

	/**
	 * Recorder instance
	 *
	 * @var Stencil_Recorder_Interface
	 */
	protected $recorder;

	/**
	 * Control if the wp_head and wp_footer are being loaded
	 *
	 * Default disabled, Stencil will enable this for the core handler
	 *
	 * @var bool
	 */
	protected $load_wp_header_and_footer = false;

	/**
	 * StencilHandler constructor.
	 *
	 * @param Stencil_Implementation          $implementation Implementation to use.
	 * @param Stencil_Recorder_Interface|null $recorder Recorder to use.
	 */
	public function __construct( Stencil_Implementation $implementation, Stencil_Recorder_Interface $recorder = null ) {
		$this->proxy    = $implementation;
		$this->recorder = is_null( $recorder ) ? new Stencil_Recorder() : $recorder;
	}

	/**
	 * Control loading the wp_head and wp_footer into variable
	 *
	 * @param bool|true $yes_or_no Wheter to load them or not.
	 */
	public function load_wp_header_and_footer( $yes_or_no = true ) {
		if ( $this->load_wp_header_and_footer === $yes_or_no ) {
			return;
		}

		if ( $yes_or_no ) {
			$this->hook_wp_header_and_footer();
		} else {
			$this->unhook_wp_header_and_footer();
		}

		$this->load_wp_header_and_footer = (bool) $yes_or_no;
	}

	/**
	 * Get the used implementation
	 *
	 * @return Stencil_Implementation
	 */
	public function get_implementation() {
		return $this->proxy;
	}

	/**
	 * Get the default recorder
	 *
	 * @return Stencil_Recorder_Interface
	 */
	public function get_recorder() {
		return $this->recorder;
	}

	/**
	 * Set the new default Recorder
	 *
	 * @param Stencil_Recorder_Interface $recorder The new Recorder to use as default.
	 */
	public function set_recorder( Stencil_Recorder_Interface $recorder ) {
		$this->recorder = $recorder;
	}

	/**
	 * Get the engine from the implementation
	 */
	public function get_engine() {
		return $this->get_implementation()->get_engine();
	}

	/**
	 * Find the view that is implemented
	 *
	 * @param array|null $options Options that were collected from the hierarchy.
	 *
	 * @return string
	 */
	public function get_usable_view( array $options = null ) {
		/**
		 * Clean up
		 */
		$options = array_unique( $options );
		$options = array_filter( $options );

		if ( empty( $options ) ) {
			return 'index';
		}

		$implementation = $this->get_implementation();

		$base = $implementation->get_template_path();
		$base = ! is_array( $base ) ? array( $base ) : $base;

		foreach ( $options as $option ) {
			foreach ( $base as $root ) {
				if ( is_file( $root . $option . '.' . $implementation->get_template_extension() ) ) {
					return $option;
				}
			}
		}

		return 'index';
	}

	/**
	 * Sets a variable to the template engine
	 *
	 * @param string $variable Name of the variable.
	 * @param mixed  $value Value to assign to the variable.
	 * @param bool   $override Optional. Allowed override the variable if already set.
	 *
	 * @return mixed Template known value of the provided variable name
	 */
	public function set( $variable, $value, $override = true ) {
		if ( ! $override ) {
			$current = $this->get( $variable );

			// Exit if variable has a value.
			if ( ! is_null( $current ) ) {
				return $current;
			}
		}

		/**
		 * Filter: stencil:set
		 *
		 * Allows for global filtering of template data
		 * Questionable if this is the place to do this but
		 * you never know what people would use this for..
		 */
		$value = Stencil_Environment::filter( 'set', $value, $variable );

		/**
		 * Filter: stencil:set-{variable_name}
		 *
		 * Allows for global filtering of template data
		 * Questionable if this is the place to do this but
		 * you never know what people would use this for..
		 */
		$value = Stencil_Environment::filter( 'set-' . $variable, $value );

		/**
		 * Set the variable
		 */
		$this->get_implementation()->set( $variable, $value );

		/**
		 * Return the value of the variable in the engine
		 */
		return $this->get( $variable );
	}

	/**
	 * Gets a variable from the template engine
	 *
	 * @param string $variable Name of the variable to get.
	 *
	 * @return mixed
	 */
	public function get( $variable ) {
		return $this->get_implementation()->get( $variable );
	}

	/**
	 * Displays a chosen template
	 *
	 * Uses fetch to fetch the output
	 *
	 * @param string $template Template file to use.
	 */
	public function display( $template ) {
		$this->internal_fetch( $template, 'display' );
	}

	/**
	 * Build and display the template
	 *
	 * @param string $template Template file to fetch.
	 *
	 * @return bool|string|void
	 */
	public function fetch( $template ) {
		return $this->internal_fetch( $template, 'fetch' );
	}

	/**
	 * Set the wp_head and wp_footer variables
	 *
	 * This function is being triggered by
	 * stencil.pre_display and stencil.pre_fetch
	 * so it can be disabled if preferred
	 */
	public function set_wp_head_footer() {
		/**
		 * Could split this up in a head/footer
		 * but when the header is required the footer
		 * finishes the complete scope.
		 */

		$this->start_recording( 'wp_head' );
		wp_head();
		$this->finish_recording();

		$this->start_recording( 'wp_footer' );
		wp_footer();
		$this->finish_recording();
	}

	/**
	 * Recorder for inline HTML cathing
	 *
	 * @param string                          $variable Variable to record into.
	 * @param Stencil_Recorder_Interface|null $temporary_recorder Optional. Recorder to use for this recording.
	 *
	 * @throws Exception When already recording for other variable.
	 * @throws InvalidArgumentException When the variable name is not a string.
	 */
	public function start_recording( $variable, Stencil_Recorder_Interface $temporary_recorder = null ) {
		/**
		 * Throw exception or error?
		 */
		if ( ! empty( $this->recording_for ) ) {
			throw new Exception( sprintf( 'Already recording variable "%s".', $this->recording_for ) );
		}

		if ( ! is_string( $variable ) || empty( $variable ) ) {
			throw new InvalidArgumentException( 'Expected variable name to record for.' );
		}

		// Set temp recorder as active.
		if ( ! is_null( $temporary_recorder ) ) {
			$swap                     = $this->recorder;
			$this->recorder           = $temporary_recorder;
			$this->revert_recorder_to = $swap;
		}

		$this->recording_for = $variable;
		$this->recorder->start_recording();
	}

	/**
	 * Finish recording raw input
	 *
	 * @return mixed
	 * @throws Exception When we are not recording.
	 */
	public function finish_recording() {
		if ( is_null( $this->recording_for ) ) {
			throw new Exception( 'Not recording.' );
		}

		$this->recorder->finish_recording();
		$recording = $this->recorder->get_recording();

		$this->set( $this->recording_for, $recording );

		/**
		 * Re-set original recorder
		 */
		if ( isset( $this->revert_recorder_to ) ) {
			$this->recorder = $this->revert_recorder_to;
			unset( $this->revert_recorder_to );
		}

		/**
		 * Clear variable holder
		 */
		$this->recording_for = null;

		return $recording;
	}

	/**
	 * Unified function for fetching and displaying a template
	 *
	 * @param string $template Template file to load.
	 * @param string $from Source of this request.
	 *
	 * @return mixed|WP_Error
	 *
	 * @throws LogicException When we are still recording a variable.
	 */
	protected function internal_fetch( $template, $from ) {
		if ( ! is_null( $this->recording_for ) ) {
			throw new LogicException( sprintf( 'Stencil: trying to fetch view %s but still recording for "%s".', $template, $this->recording_for ) );
		}

		$implementation = $this->get_implementation();

		// Hook pre_fetch / pre_display.
		Stencil_Environment::trigger( 'pre_' . $from, $template );

		// Make sure undefined index errors are not caught; template engines don't check for these.
		$error_reporting = error_reporting();
		error_reporting( error_reporting() & ~E_NOTICE );

		// Fetch.
		$fetched = $implementation->fetch( $template . '.' . $implementation->get_template_extension() );

		// Restore error_reporting.
		error_reporting( $error_reporting );

		/**
		 * Apply filtering
		 */
		$fetched = Stencil_Environment::filter( 'content', $fetched );

		/**
		 * Echo if we are displaying
		 */
		if ( 'display' === $from ) {
			echo $fetched;
		}

		// Hook post_fetch / post_display.
		Stencil_Environment::trigger( 'post_' . $from, $template );

		if ( 'fetch' === $from ) {
			return $fetched;
		}

		return '';
	}

	/**
	 * Attach hooks to load the wp_head and wp_footer variables
	 */
	private function hook_wp_header_and_footer() {
		/**
		 * Add wp_head and wp_footer variable recording
		 */
		add_action( Stencil_Environment::format_hook( 'pre_display' ), array( $this, 'set_wp_head_footer' ) );
		add_action( Stencil_Environment::format_hook( 'pre_fetch' ), array( $this, 'set_wp_head_footer' ) );
	}

	/**
	 * Detach hooks to load the wp_head and wp_footer variables
	 */
	private function unhook_wp_header_and_footer() {
		/**
		 * Add wp_head and wp_footer variable recording
		 */
		remove_action( Stencil_Environment::format_hook( 'pre_display' ), array( $this, 'set_wp_head_footer' ) );
		remove_action( Stencil_Environment::format_hook( 'pre_fetch' ), array( $this, 'set_wp_head_footer' ) );
	}
}
