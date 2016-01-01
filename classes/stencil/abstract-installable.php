<?php
/**
 * Abstract Installable
 */

/**
 * Class Stencil_Installable
 */
abstract class Stencil_Abstract_Installable {
	/**
	 * Slug of this module.
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Readable name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Version of this module.
	 *
	 * @var string
	 */
	protected $version = '0.0.0';

	/**
	 * Stencil_Installable constructor.
	 *
	 * @param string $slug Slug of the module.
	 * @param string $name Name of this module.
	 */
	public function __construct( $slug, $name ) {
		$this->slug = $slug;
		$this->name = $name;
	}

	/**
	 * Get slug name.
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Is this installable installed.
	 *
	 * @return bool
	 */
	public function is_installed() {
		// @todo add check for path?
		return false;
	}

	/**
	 * Check if there is an upgrade available
	 *
	 * @return bool|mixed
	 */
	public function has_upgrade() {
		return false;
	}

	/**
	 * Do all requirements pass so it is usable.
	 *
	 * @return bool|array TRUE if passed, array of errors if failed.
	 */
	public function passed_requirements() {
		return true;
	}

	/**
	 * Get the name.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->name;
	}
}
