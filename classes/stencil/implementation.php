<?php
/**
 * Base class for extending Stencil Implementations
 *
 * Author: Jip Moors (jhp.moors@gmail.com)
 * Date: 4 juli 2015
 *
 * @package Stencil
 */

/**
 * Class Implementation
 */
abstract class Stencil_Implementation implements Stencil_Interface, Stencil_Implementation_Interface {
	/**
	 * The selected template engine
	 *
	 * @var $engine
	 */
	protected $engine;

	/**
	 * Path to save caching files
	 *
	 * @var string
	 */
	protected $cache_path;

	/**
	 * Path to save compiled template files
	 *
	 * @var string
	 */
	protected $compile_path;

	/**
	 * Path to look for templates/views
	 *
	 * @var array
	 */
	protected $template_path;

	/**
	 * Template file extension to check is_file against
	 * This is needed to determine what template should be loaded
	 * according to the WordPress hierarchy
	 *
	 * @var string
	 */
	protected $template_extension = 'tpl';

	/**
	 * Sets defaults like cache, compile and template directory paths.
	 *
	 * @throws Exception When cache path cannot be used.
	 */
	public function __construct() {
		$upload_dir = wp_upload_dir();

		$current_theme = wp_get_theme();
		$theme_slug    = $current_theme->get_stylesheet();

		/**
		 * Make the upload folder the root for changing files, this is the place
		 * that is most likely writable.
		 *
		 * Keeping the theme name as container prevents accidental problems with
		 * caching or compiling files when switching themes.
		 */
		$root = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . $theme_slug;

		$this->cache_path   = implode( DIRECTORY_SEPARATOR, array( $root, 'cache', '' ) );
		$this->compile_path = implode( DIRECTORY_SEPARATOR, array( $root, 'compiled', '' ) );

		/**
		 * Filter: views directory
		 */
		$views_path = Stencil_Environment::filter( 'path-views', 'views' );

		/**
		 * Get all directories (root + optional child theme)
		 */
		$this->template_path = Stencil_File_System::get_potential_directories( $views_path );

		/**
		 * Attempt to make the directories
		 */
		if ( ! wp_mkdir_p( $this->cache_path ) ) {
			throw new Exception( 'Cache path could not be created.' );
		}

		if ( ! wp_mkdir_p( $this->compile_path ) ) {
			throw new Exception( 'Compile path could not be created.' );
		}
	}

	/**
	 * Called when the implementation is ready
	 */
	protected function ready() {
		Stencil_Environment::trigger( 'engine_ready', $this );
	}

	/**
	 * Fetch the engine so interaction is possible
	 *
	 * @return mixed
	 */
	public function get_engine() {
		return $this->engine;
	}

	/**
	 * Get the template path
	 *
	 * @return string|array
	 */
	public function get_template_path() {
		return $this->template_path;
	}

	/**
	 * Get the file extension of the templates
	 *
	 * @return string
	 */
	public function get_template_extension() {
		return $this->template_extension;
	}

	/**
	 * Displays a chosen template
	 *
	 * Uses fetch to fetch the output
	 *
	 * @param string $template Template file to use.
	 */
	public function display( $template ) {
		echo $this->fetch( $template );
	}
}
