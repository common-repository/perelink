<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PerelinkPluginImages {

	public static function getImageUrl( $attachId, $size ) {
		if ( is_array( $size ) ) {
			self::createIfNotExists( $attachId, $size );
		}
		$data = wp_get_attachment_image_src( $attachId, $size, false );
		return $data[0];
	}

	private static function createIfNotExists( $attachId, $size ) {
		$data = wp_get_attachment_metadata( $attachId );
		if ( ! $data ) {
			return false;
		}
		$width  = (int) $size[0];
		$height = (int) $size[1];
		$image  = false;
		if ( isset( $data['sizes'] ) && is_array( $data['sizes'] ) ) {
			foreach ( $data['sizes'] as $size ) {
				if ( $size['width'] == $width && $size['height'] == $height ) {
					$image = $size;
					break;
				}
			}
		}
		if ( ! $image ) {
			self::createNewSize( $attachId, $width, $height );
		}
	}

	private static function createNewSize( $attachId, $width, $height ) {
		$data = wp_get_attachment_metadata( $attachId );
		$uploadDir = wp_upload_dir();
		$editor = wp_get_image_editor( $uploadDir['basedir'] . DIRECTORY_SEPARATOR . $data['file'] );
		if ( is_wp_error( $editor ) ) {
			return false;
		}
		$editor->resize( $width, $height, true );
		$img = $editor->save();
		if ( is_wp_error( $img ) ) {
			return false;
		}
		$data['sizes'][$width . 'x' . $height] = [
			'file'      => $img['file'],
			'width'     => $img['width'],
			'height'    => $img['height'],
			'mime-type' => $img['mime-type'],
		];
		wp_update_attachment_metadata( $attachId, $data );
		return true;
	}

}
