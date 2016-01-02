<?php
/**
Plugin Name: Stencil
Plugin URI: https://github.com/moorscode/stencil/
Description: Ever wanted to use a "real" templating engine to build your theme on? With Stencil you can! Enable the Addon that provides the engine you want to use and be up and running in no time. Addons available for Smarty 2, Smarty 3, Twig and more!
Version: 2.0.0
Author: Jip Moors (moorscode)
Author URI: http://www.jipmoors.nl
Text Domain: stencil
License: GPL2

Stencil is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Stencil is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Stencil. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
 *
 * @package Stencil
 */

// Make sure WordPress is loaded.
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * We haven't loaded anything, but a class named 'Stencil' already exists:
 */
if ( class_exists( 'Stencil' ) ) {
	// If this is an alternative implementation, STENCIL_PATH exist.
	if ( ! defined( 'STENCIL_PATH' ) ) {
		/**
		 * Function to display "something" used our class name.
		 */
		function __stencil_class_name_taken() {
			echo '<div class="error"><p>' . __( 'A class named "Stencil" exists. This means that the plugin "Stencil" can not operate.', 'stencil' ) . '</p></div>';
		}

		add_action( 'admin_notices', '__stencil_class_name_taken' );
	}
} else {

	define( 'STENCIL_PATH', dirname( __FILE__ ) );
	require 'helpers/loader.php';

	// Everything went well.
	if ( class_exists( 'Stencil_Bootstrap' ) ) {

		require 'helpers/front-end-functions.php';

		// Boot up the bootstrapper.
		new Stencil_Bootstrap();
	}
}
