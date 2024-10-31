<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'PerelinkPluginBaseClass.php';

class PerelinkPluginAdmin extends PerelinkPluginBaseClass {

	private $options;

	public function __construct() {
		$this->options = PerelinkPlugin::getOptions();
		$this->registerHooks();
	}

	private function registerHooks() {
		add_action( 'admin_menu', [ $this, 'adminMenu' ] );
		add_filter( 'plugin_action_links_' . $this->options->pluginBasename, [ $this, 'pluginLinksFilter' ], 10, 2 );
	}

	private function getPage() {
		return 'perelink_admin';
	}

	public function adminMenu() {
		add_options_page( 'Perelink Pro', 'Perelink Pro', 8, 'perelink_admin', [ $this, 'adminPage' ] );
	}

	public function adminPage() {
		if ( ! empty( $_POST ) ) {
			$this->saveOptionsFromPost();
		}
		$this->render( 'admin', [ 'options' => $this->options ] );
	}

	public function pluginLinksFilter( $links ) {
		array_unshift(
				$links, '<a href="options-general.php?page=' . $this->getPage() . '">Параметры</a>'
		);

		return $links;
	}

	public function saveOptionsFromPost() {
		$post = stripslashes_recursive( $_POST );
		$this->options->setOptions( $post );
	}

}
