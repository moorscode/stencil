<?php
/**
 * Created by PhpStorm.
 * User: jip
 * Date: 27/12/15
 * Time: 17:23
 *
 * @package Stencil
 */

/**
 * Class AbstractPageType
 */
abstract class Stencil_Abstract_Page_Type {
	/**
	 * Use class as callable
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 *
	 * @return void
	 */
	abstract public function execute( Stencil_Interface $controller );
}
