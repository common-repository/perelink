<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'PerelinkPluginOptions.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'PerelinkPluginAdmin.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'PerelinkPluginWork.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'PerelinkPluginSource.php';

class PerelinkPlugin {

	/**
	 * @var PerelinkPluginOptions
	 */
	private static $options;

	/**
	 * @var PerelinkPluginAdmin
	 */
	private static $admin;

	/**
	 * @var PerelinkPluginWork
	 */
	private static $work;

	/**
	 * @var PerelinkPluginSource
	 */
	private static $source;

	public static function init( $pluginFile ) {
		if ( self::$options instanceof PerelinkPluginOptions ) {
			return;
		}
		self::$options = new PerelinkPluginOptions( $pluginFile );
		if ( is_admin() ) {
			self::$admin  = new PerelinkPluginAdmin();
		}
		self::$work         = new PerelinkPluginWork();
		self::$source       = new PerelinkPluginSource();
		self::registerHooks();
	}

	private static function registerHooks() {
		register_activation_hook( self::$options->pluginFile, [ 'PerelinkPlugin', 'activate' ] );
		register_deactivation_hook( self::$options->pluginFile, [ 'PerelinkPlugin', 'deactivate' ] );
		register_uninstall_hook( self::$options->pluginFile, [ 'PerelinkPlugin', 'uninstall' ] );
	}

	public static function activate() {
		self::$options->saveOptions();
	}

	public static function deactivate() {

	}

	public static function uninstall() {
		self::$options->deleteOptions();
	}

	/**
	 *
	 * @return PerelinkPluginOptions
	 */
	public static function getOptions() {
		return self::$options;
	}

	/**
	 *
	 * @return PerelinkPluginSource
	 */
	public static function getSource() {
		return self::$source;
	}

	/**
	 * @param array $params
	 * array(
	 *   'limit' => 'Количество отображаемых элементов, null (по умолчанию) - отображать все',
	 *   'offset' => 'Количество элементов, которые следует пропустить, по умолчанию 0'
	 * );
	 *
	 * @param bool $return
	 *
	 * @return string
	 */
	public static function getAfterText( $params = [], $return = false ) {
		if ( $return ) {
			return self::$work->getAfterText( $params );
		} else {
			echo self::$work->getAfterText( $params );
		}
	}

	public static function getVersion() {
		$data = get_plugin_data( self::$options->getPluginFile() );
		return $data['Version'];
	}

	public static function getName() {
		$data = get_plugin_data( self::$options->getPluginFile() );
		return $data['Name'];
	}

}
