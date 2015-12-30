<?php
/**
 * Functions to use in theme's
 *
 * @package Stencil
 */

/**
 * Get the instance of Stencil to work with
 *
 * @return Stencil
 */
function get_stencil() {
	return Stencil::controller();
}

/**
 * Get a new handler to create snippet HTML
 *
 * Usable in AJAX requests.
 *
 * @param Stencil_Implementation|null     $implementation Optional. Set Implementation on new Handler.
 * @param Stencil_Recorder_Interface|null $recorder Optional. Supply a custom Recorder.
 *
 * @return Stencil_Handler
 */
function get_stencil_handler( Stencil_Implementation $implementation = null, Stencil_Recorder_Interface $recorder = null ) {
	return get_stencil()->get_handler( $implementation, $recorder );
}

/**
 * Get the right controller and include it
 *
 * This is generally used inside a router.php Stencil hook
 *
 * @param string $file Control file to include.
 *
 * @return bool
 */
function include_stencil_controller( $file ) {
	return Stencil_File_System::load( $file );
}
