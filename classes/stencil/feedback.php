<?php
/**
 * Feedback class
 *
 * Depending on the location of the page (CMS or Website) show a notification to the user
 *
 * @package Stencil
 */

/**
 * Class Feedback
 */
class Stencil_Feedback {
	/**
	 * Admin notice queue
	 *
	 * @var array
	 */
	private static $notices = array();

	/**
	 * Display a notification
	 *
	 * @param string $type Type of message.
	 * @param string $message Message to show.
	 */
	public static function notification( $type, $message ) {
		if ( is_admin() ) {
			self::admin_notification( $type, $message );
		} else {
			self::site_notification( $type, $message );
		}
	}

	/**
	 * Display a notification on the site
	 *
	 * Using trigger_error so developers see this message but visitors don't.
	 * If the server has been configured properly ofcourse..
	 *
	 * @param string $type Type of notification.
	 * @param string $message Message to show.
	 */
	private static function site_notification( $type, $message ) {
		switch ( $type ) {
			case 'error':
				$error_type = E_USER_ERROR;
				break;

			case 'warning':
			case 'notification':
				$error_type = E_USER_WARNING;
				break;

			default:
				$error_type = E_USER_NOTICE;
				break;
		}

		/**
		 * Sanitize for logfile
		 */
		$message = sanitize_text_field( $message );

		trigger_error( $message, $error_type );
	}

	/**
	 * Display a notification on the CMS
	 *
	 * @param string $type Type of mesesage.
	 * @param string $message Message to show.
	 */
	private static function admin_notification( $type, $message ) {
		static $admin_notices_hooked = false;
		if ( ! $admin_notices_hooked ) {
			$admin_notices_hooked = true;

			add_action( 'admin_notices', array( __CLASS__, 'show_admin_notices' ) );
		}

		self::$notices[] = array( 'type' => $type, 'message' => $message );
	}

	/**
	 * Display queued notices
	 */
	public static function show_admin_notices() {
		while ( array() !== self::$notices ) {
			$notice = array_shift( self::$notices );
			echo '<div class="' . esc_attr( $notice['type'] ) . '"><p>Stencil: ' . $notice['message'] . '</p></div>';
		}
	}
}
