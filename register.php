<?php
/**
 * Unified registering script for Addons to register themselves to Stencil
 *
 * Why this setup?
 *  1. If this file does not exist, the core plugin is not installed
 *  2. When all Addons use the same functions, all output is unified
 *  3. When the logic has to change it can be done from one location in the core plugin
 *
 * PHP 5.2
 *  Because we aim to get this plugin into the plugin repository it has to be
 *  PHP 5.2 compatible.
 *
 * Would love to use closures to make it all a bit more readable and
 * shorter, but for now this has to do.
 *
 *
 * Author: Jip Moors (jhp.moors@gmail.com)
 * Date: 4 juli 2015
 *
 * @package Stencil
 */

// Make sure WordPress is loaded.
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! function_exists( '__stencil_not_active' ) ) :

	/**
	 * Function to notify that the plugin is not active.
	 */
	function __stencil_not_active() {
		echo '<div class="error"><p>' . __( 'The plugin <em>"Stencil"</em> needs to be activated.', 'stencil' ) . '</p></div>';
	}

endif;

if ( ! function_exists( '__stencil_plugins_loaded' ) ) :

	/**
	 * Trigger notification if the plugin is not active.
	 */
	function __stencil_plugins_loaded() {
		$plugin_directory = basename( dirname( __FILE__ ) );
		$plugin_name      = sprintf( '%1$s/%1$s.php', $plugin_directory );

		if ( ! is_plugin_active( $plugin_name ) ) {
			add_action( 'admin_notices', '__stencil_not_active' );
		}
	}

	/**
	 * If this file exists stencil is installed
	 * But if the plugin has not been activated it is still not usable.
	 */
	add_action( 'admin_init', '__stencil_plugins_loaded' );

endif;
