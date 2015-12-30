<?php
/**
 * Created by PhpStorm.
 * User: jip
 * Date: 27/12/15
 * Time: 16:47
 *
 * @package Stencil
 */

/**
 * Class AbstractHierarchy
 */
abstract class Stencil_Abstract_Hierarchy implements Iterator {
	/**
	 * Current index
	 *
	 * @var int
	 */
	protected $index = 0;

	/**
	 * The options for this item
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Set the options
	 *
	 * @param array $options Set the options available.
	 */
	protected function set_options( $options ) {
		$this->options = $options;
	}

	/**
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 * @since 5.0.0
	 */
	public function current() {
		return $this->options[ $this->index ];
	}

	/**
	 * Move forward to next element
	 */
	public function next() {
		++ $this->index;
	}

	/**
	 * Return the key of the current element
	 *
	 * @return mixed|null scalar on success, null on failure.
	 */
	public function key() {
		return $this->index;
	}

	/**
	 * Checks if current position is valid
	 *
	 * Returns true on success or false on failure.
	 */
	public function valid() {
		return isset( $this->options[ $this->index ] );
	}

	/**
	 * Rewind the Iterator to the first element
	 */
	public function rewind() {
		$this->index = 0;
	}
}
