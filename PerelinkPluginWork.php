<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'PerelinkPluginWorkAfterText.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'PerelinkPluginWorkInContent.php';

class PerelinkPluginWork extends PerelinkPluginBaseClass {

	const OLD_START_TAG = '<!--start_content-->';
	const OLD_END_TAG   = '<!--end_content-->';
	const NEW_START_TAG = '<index>';
	const NEW_END_TAG   = '</index>';

	/**
	 * @var PerelinkPluginOptions
	 */
	private $options;
	private $afterText;

	public function __construct() {
		$this->options = PerelinkPlugin::getOptions();
		$this->registerHooks();
		if ( ! is_admin() ) {
			$this->log( $this->options );
		}
	}

	private function registerHooks() {
		add_filter( 'the_content', [ $this, 'content' ], 20 );
		add_action( 'wp_footer', [ $this, 'footer' ] );
	}

	public function footer() {
		$this->render( 'footer' );
	}

	public function content( $content ) {
		if ( ! $this->needWork() ) {
			return $content;
		}
		if ( $this->needWorkInContent() ) {
			if ( $this->options->wrapperType !== 'old' ) {
				$content = self::NEW_START_TAG . $this->workInContent( $content ) . self::NEW_END_TAG;
			} else {
				$content = self::OLD_START_TAG . $this->workInContent( $content ) . self::OLD_END_TAG;
			}
		}
		if ( $this->needWorkAfterText() ) {
			$content .= $this->getAfterText();
		}
		return $content;
	}

	private function needWork() {
		if ( in_array( 'post', $this->options->pageType ) && is_single() ) {
			return true;
		}
		if ( in_array( 'page', $this->options->pageType ) && is_page() ) {
			return true;
		}
		return false;
	}

	private function needWorkInContent() {
		return true;
	}

	private function needWorkAfterText() {
		if ( $this->options->afterTextMode !== 'auto' ) {
			return false;
		}
		return true;
	}

	public function getAfterText( $params = [] ) {
		if ( ! $this->options->afterTextEnabled ) {
			return '';
		}
		if ( empty( $this->afterText ) ) {
			$this->afterText = new PerelinkPluginWorkAfterText();
		}
		$params = array_merge( [ 'limit' => null, 'offset' => 0 ], $params );
		$text   = $this->afterText->work( $params['limit'], $params['offset'] );
		return $text;
	}

	public function workInContent( $content ) {
		$work = new PerelinkPluginWorkInContent();
		return $work->work( $content );
	}

}
