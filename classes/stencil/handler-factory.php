<?php
/**
 * Factory for PageType handlers
 *
 * @package Stencil
 */

/**
 * Class HandlerFactory
 */
class Stencil_Handler_Factory implements Stencil_Handler_Factory_Interface {
	/**
	 * List of registered Handlers
	 *
	 * @var array
	 */
	private static $handlers = array();

	/**
	 * Set a hierarchy handler for a page
	 *
	 * @param string            $page Page to set.
	 * @param array|Traversable $handler Handler to use.
	 */
	public static function set_hierarchy_handler( $page, $handler ) {
		self::set_settable_handler( 'Hierarchy', $page, $handler );
	}

	/**
	 * Set a page type handler
	 *
	 * @param string   $page Page to set.
	 * @param callable $handler Handler to use.
	 */
	public static function set_page_type_handler( $page, $handler ) {
		self::set_settable_handler( 'PageType', $page, $handler );
	}

	/**
	 * Remove handler for a page.
	 *
	 * @param string $page Page to remove from.
	 */
	public static function remove_page_type_handler( $page ) {
		self::set_settable_handler( 'PageType', $page );
	}

	/**
	 * Set page type hooking function
	 *
	 * @param string   $page Page to set to.
	 * @param callable $handler Handler to use.
	 */
	public static function set_page_type_hooker( $page, $handler ) {
		self::set_settable_handler( 'PageHook', $page, $handler );
	}

	/**
	 * Remove page hooker from page
	 *
	 * @param string $page Page to remove from.
	 */
	public static function remove_page_type_hooker( $page ) {
		self::set_settable_handler( 'PageHook', $page );
	}

	/**
	 * Get current hierarchy handler for page
	 *
	 * @param string $page Page to get from.
	 *
	 * @return array|Traversable
	 */
	public static function get_hierarchy_handler( $page ) {
		return self::get_settable_handler( $page, 'Hierarchy' );
	}

	/**
	 * Get the current page type handler
	 *
	 * @param string $page Page to get from.
	 *
	 * @return callable
	 */
	public static function get_page_type_handler( $page ) {
		return self::get_settable_handler( $page, 'Page_Type' );
	}

	/**
	 * Get the current page type hooker
	 *
	 * @param string $page Page to get from.
	 *
	 * @return callable
	 */
	public static function get_page_type_hooker( $page ) {
		return self::get_settable_handler( $page, 'Page_Hook' );
	}

	/**
	 * Unified setter function
	 *
	 * @param string $type Type of object.
	 * @param string $page Page name.
	 * @param mixed  $handler Handler to apply.
	 *
	 * @throws InvalidArgumentException For invalid argument types.
	 */
	private static function set_settable_handler( $type, $page, $handler = null ) {
		// Create NullObject for empty handler, i.e. remove functionality.
		if ( empty( $handler ) ) {
			$handler = Stencil_Subclass_Factory::create_null( sprintf( 'Stencil_%s', $type ) );
		}

		switch ( $type ) {
			case 'Hierarchy':
				if ( ! is_array( $handler ) && ! ( $handler instanceof Traversable ) ) {
					throw new InvalidArgumentException( 'Expected $handler to be array or Traversable.' );
				}
				break;

			default:
				if ( ! is_callable( $handler ) ) {
					throw new InvalidArgumentException( 'Expected $handler to be callable.' );
				}
				break;
		}

		self::$handlers[ $type ][ $page ] = $handler;
	}

	/**
	 * Unified getter function
	 *
	 * @param string $page Page to get from.
	 * @param string $type Type to get.
	 *
	 * @return array Callable function
	 */
	private static function get_settable_handler( $page, $type ) {
		if ( ! isset( self::$handlers[ $type ][ $page ] ) ) {
			$handler = Stencil_Cached_Subclass_Factory::create_or_null( $page, sprintf( 'Stencil_%s', $type ) );

			switch ( $type ) {
				case 'Hierarchy':
					break;
				default:
					$handler = array( $handler, 'execute' );
					break;
			}

			self::$handlers[ $type ][ $page ] = $handler;
		}

		return self::$handlers[ $type ][ $page ];
	}
}
