<?php
/**
 * Make sure all files required can be loaded.
 *
 * @package Stencil
 */

// Auto- or manually load classes.
if ( function_exists( 'spl_autoload_register' ) ) {
	require_once 'load-spl.php';
} else {
	require_once 'load-manual.php';
}
