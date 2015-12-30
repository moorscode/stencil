<?php
/**
 * Environment class
 *
 * Use Filters and Hooks via this class. Prefixes are automatically applied to calls.
 *
 * @package Stencil
 */

/**
 * Class Environment
 */
class Stencil_Environment {

	/**
	 * Prefixed used for filters
	 *
	 * @const string
	 */
	const FILTER_PREFIX = 'stencil:';

	/**
	 * Prefix used for hooks
	 *
	 * @const string
	 */
	const HOOK_PREFIX = 'stencil.';

	/**
	 * Unified filter method with globalised prefix
	 *
	 * @param string $variable , ...
	 *
	 * @return mixed
	 */
	public static function filter( $variable ) {
		$arguments    = func_get_args();
		$arguments[0] = self::format_filter( $variable );

		return call_user_func_array( 'apply_filters', $arguments );
	}

	/**
	 * Unified hook method with globalised prefix
	 *
	 * @param string $hook , ...
	 */
	public static function trigger( $hook ) {
		$arguments    = func_get_args();
		$arguments[0] = self::format_hook( $hook );

		call_user_func_array( 'do_action', $arguments );
	}

	/**
	 * Provide unified filter format
	 *
	 * @param string $filter Filter to use to build.
	 *
	 * @return string
	 */
	public static function format_filter( $filter ) {
		if ( strpos( $filter, self::FILTER_PREFIX ) !== 0 ) {
			$filter = sprintf( self::FILTER_PREFIX . '%s', $filter );
		}

		return $filter;
	}

	/**
	 * Provide unified hook format
	 *
	 * @param string $hook Hook to format.
	 *
	 * @return string
	 */
	public static function format_hook( $hook ) {
		if ( strpos( $hook, self::HOOK_PREFIX ) !== 0 ) {
			$hook = sprintf( self::HOOK_PREFIX . '%s', $hook );
		}

		return $hook;
	}

	/**
	 * Get the identifier for the active page
	 *
	 * @return null|string
	 */
	public static function get_page() {
		$page = null;

		/**
		 * See: https://codex.wordpress.org/Conditional_Tags
		 */

		switch ( true ) {
			case ( is_404() ):
				$page = 'error';
				break;

			case ( is_search() ):
				$page = 'search';
				break;

			/**
			 * Case is_home() return true when on the posts list page,
			 * This is usually the page that shows the latest 10 posts.
			 */
			case ( is_home() ):
				$page = 'home';
				break;

			/**
			 * Case is_front_page() returns true if the user is on the page or page of posts that is set to the front page
			 * on Settings->Reading->Front page displays
			 */
			case ( is_front_page() ):
				$page = 'front_page';
				break;

			case ( is_post_type_archive() ):
				$page = 'post-type-archive';
				break;

			case ( is_page_template() ):
				$page = 'custom';
				break;

			case ( is_single() ):
				$page = 'single';
				break;

			case ( is_page() ):
				$page = 'page';
				break;

			case ( is_attachment() ):
				$page = 'attachment';
				break;

			case ( is_comments_popup() ):
				$page = 'comments-popup';
				break;

			case ( is_tax() ):
				$page = 'taxonomy';
				break;

			case ( is_tag() ):
				$page = 'tag';
				break;

			case ( is_date() ):
				$page = 'date';
				break;

			case ( is_category() ):
				$page = 'category';
				break;

			case ( is_author() ):
				$page = 'author';
				break;

			case ( is_archive() ):
				$page = 'archive';
				break;
		}

		/**
		 * Filter: override for page selection
		 */
		$page = self::filter( 'page-' . $page, $page );
		$page = self::filter( 'page', $page );

		return $page;
	}
}
