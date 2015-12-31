<?php
/**
 * Flow controller class
 *
 * Builds all the actions that need to be executed on a certain page request.
 * Builds the view hierarchy of a certain page.
 *
 * @package Stencil
 */

/**
 * Class Flow
 */
class Stencil_Flow implements Stencil_Flow_Interface {

	/**
	 * Pages that are considered of the 'archive' type
	 *
	 * Allows for view to fall through to 'archive'
	 * Allows for listing posts
	 *
	 * @var array
	 */
	protected $archive_pages = array(
		'author',
		'category',
		'taxonomy',
		'date',
		'tag',
		'archive',
		'post-type-archive',
	);

	/**
	 * Create a list of actions for the specified page
	 *
	 * @param string $page Page to get Actions for.
	 *
	 * @return array of actions
	 */
	public function get_page_actions( $page ) {
		/**
		 * Actions to be executed
		 */
		$actions = array(
			'always'
		);

		// Singular = single item (post, attachment, page, etc).
		if ( is_singular() ) {
			$actions[] = 'singular';
		}

		// Paged page (pagination).
		if ( is_paged() ) {
			$actions[] = 'paged';
		}

		if ( is_sticky() ) {
			$actions[] = 'sticky';
		}

		if ( is_user_logged_in() ) {
			$actions[] = 'logged-in';
			$actions[] = 'loggedin';
		}

		if ( in_array( $page, $this->archive_pages, true ) || 'home' === $page ) {
			$actions[] = 'archived';
		}

		// Add page action.
		$actions[] = $page;

		// Apply filters.
		$actions = Stencil_Environment::filter( 'actions-' . $page, $actions );
		$actions = Stencil_Environment::filter( 'actions', $actions );

		return $actions;
	}

	/**
	 * Determine which view is available for loading
	 *
	 * @param string $page Page to get hierarchy of.
	 *
	 * @return array
	 * @throws Exception When handler doesn't return expected type.
	 */
	public function get_view_hierarchy( $page ) {

		/**
		 * Get the possible views for specified page:
		 */
		$options = Stencil_Handler_Factory::get_hierarchy_handler( $page );
		if ( ! is_array( $options ) && ! ( $options instanceof Traversable ) ) {
			throw new Exception( 'Expected array got ' . gettype( $options ) );
		}

		if ( is_array( $options ) ) {
			$options = new ArrayIterator( $options );
		}

		/**
		 * Add archive option for archive pages:
		 */
		if ( 'archived' !== $page && in_array( $page, $this->archive_pages, true ) ) {
			$this->add_to_options( 'archived', $options );
		}

		/**
		 * Add paged option for paged pages:
		 */
		if ( 'paged' !== $page && is_paged() ) {
			$this->add_to_options( 'paged', $options );
		}

		/**
		 * Convert to array for filtering and return ouput
		 */
		$options = iterator_to_array( $options );

		// Apply filter.
		$options = Stencil_Environment::filter( 'views-' . $page, $options );

		return $options;
	}

	/**
	 * Add additional options to the existing array
	 *
	 * @param string         $type Type to get options for.
	 * @param array|Iterator $options Reference. Existing options.
	 *
	 * @throws Exception
	 */
	private function add_to_options( $type, & $options ) {
		$additive_options = $this->get_view_hierarchy( $type );

		if ( array() !== $additive_options ) {
			if ( ! ( $options instanceof AppendIterator ) ) {
				$all_options = new AppendIterator();
				$all_options->append( $options );
			} else {
				$all_options = $options;
			}

			$all_options->append( new ArrayIterator( $additive_options ) );

			$options = $all_options;
		}
	}
}
