<?php
/**
 * Single hierarchy tree
 *
 * @package Stencil\Hierarchy
 */

/**
 * Class Stencil_Hierarchy_Single
 */
class Stencil_Hierarchy_Single extends Stencil_Abstract_Hierarchy {
	/**
	 * Stencil_Hierarchy_Single constructor.
	 */
	public function __construct() {
		$post_type = get_post_type();

		$options = array();

		switch ( $post_type ) {
			case 'attachment':
				$attachment = get_queried_object();

				if ( ! empty( $attachment->post_mime_type ) ) {
					$mime_type = $attachment->post_mime_type;
					$types     = explode( '/', $mime_type, 2 );

					$options[] = 'single/' . $types[0];
					$options[] = $types[0];

					if ( ! empty( $types[1] ) ) {
						$options[] = 'single/' . $types[1];
						$options[] = $types[1];

						$options[] = 'single/' . $types[0] . '-' . $types[1];
						$options[] = $types[0] . '-' . $types[1];
					}
				}

				$options[] = 'single/attachment';
				$options[] = 'attachment';
				break;

			default:
				$post = get_queried_object();
				if ( ! empty( $post->post_name ) ) {
					$options[] = 'single/' . $post->post_name;
					$options[] = $post->post_name;
				}

				$options[] = 'single/' . $post->ID;
				$options[] = $post->ID;

				$options[] = 'single/' . $post_type;
				$options[] = $post_type;
				break;
		}

		if ( 'page' !== $post_type ) {
			$options[] = 'single';
		}

		$this->set_options( $options );
	}
}
