<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PerelinkPluginBaseClass {

	private static $_currentUrl;

	public static function setCurrentUrl( $url ) {
		self::$_currentUrl = $url;
	}

	public function render( $view, $params = [], $return = false ) {
		if ( $return ) {
			ob_start();
		}
		extract( $params );
		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php';
		if ( $return ) {
			return ob_get_clean();
		}
	}

	public function getCurrentUrl() {
		if ( ! empty( self::$_currentUrl ) ) {
			return self::$_currentUrl;
		} else {
			return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}
	}

	public function log( $data ) {
		if ( PERELINK_DEBUG ) {
			echo '<!-- perelink_debug' . "\n";
			var_export( $data );
			echo '-->';
		}
	}

}
