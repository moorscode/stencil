<?php
/**
 * Subclass factory
 *
 * @package Stencil
 */

/**
 * Class SubclassFactory
 */
class Stencil_Subclass_Factory {
	const CLASS_FORMAT = '%s_%s';

	/**
	 * Create Null object
	 *
	 * @param string $prefix Prefix for the Class to use (directory).
	 *
	 * @return mixed
	 * @throws InvalidArgumentException When something else but a non-empty string is passed.
	 */
	public static function create_null( $prefix ) {
		if ( ! is_string( $prefix ) || empty( $prefix ) ) {
			throw new InvalidArgumentException( 'Expected a non-empty string.' );
		}

		$class = sprintf( self::CLASS_FORMAT, $prefix, 'Null' );

		return new $class;
	}

	/**
	 * Create a new class if it exists
	 *
	 * @param string $class Class name.
	 * @param string $prefix Prefix of the class (directory).
	 *
	 * @return null
	 * @throws InvalidArgumentException For invalid class or prefix parameters.
	 */
	public static function create_if_exists( $class, $prefix ) {
		if ( ! is_string( $class ) || empty( $class ) ) {
			throw new InvalidArgumentException( 'Expected non-empty string for $class.' );
		}

		if ( ! is_string( $prefix ) || empty( $prefix ) ) {
			throw new InvalidArgumentException( 'Expected non-empty string for $prefix.' );
		}

		$class = self::format_class_name( $class );
		$class = sprintf( self::CLASS_FORMAT, $prefix, $class );

		if ( class_exists( $class ) ) {
			return new $class;
		}

		return null;
	}

	/**
	 * Create new class or Null object if it does not exist
	 *
	 * @param string $class Name of the class.
	 * @param string $prefix Prefix of the class.
	 *
	 * @return null
	 */
	public static function create_or_null( $class, $prefix ) {
		$result = self::create_if_exists( $class, $prefix );

		if ( is_null( $result ) ) {
			$result = self::create_null( $prefix );
		}

		return $result;
	}

	/**
	 * Uniform class name
	 *
	 * Class format: UpperCaseWords
	 *
	 * @param string $source Class name to format.
	 *
	 * @return string
	 */
	private static function format_class_name( $source ) {

		$class = strtolower( $source );
		$class = preg_replace( '~[^a-z]~', '_', $class );

		// Replace spaces with uppercase next character.
		if ( false !== strpos( $class, ' ' ) ) {

			/**
			 * Using callback via class because Anonymous functions are not guaranteed to be present
			 *
			 * @see: http://php.net/manual/en/functions.anonymous.php
			 * 5.3.0    Anonymous functions become available.
			 */
			$class = preg_replace_callback(
				'~_[a-z]~',
				array( __CLASS__, 'camel_case_callback' ),
				$class
			);

			$class = trim( $class );
		}

		$class = ucfirst( $class );

		return $class;
	}

	/**
	 * Replace ' {alphanum}' with uppercase of alphanum
	 *
	 * Example: 'stencil example' => 'stencilExample'
	 *
	 * @param array $matches Matched items in string.
	 *
	 * @return string
	 */
	private function camel_case_callback( $matches ) {
		return '_' . trim( strtoupper( $matches[0] ) );
	}
}
