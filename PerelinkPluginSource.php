<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'CurlHelper.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'UrlHelper.php';

class PerelinkPluginSource {

	const SERVER_URL = 'https://perelink.pro';

	private static $data = [];

	/**
	 * @var PerelinkPluginOptions
	 */
	private $options;

	/**
	 * @var CurlHelper
	 */
	private $curl;

	public function __construct() {
		$this->options = PerelinkPlugin::getOptions();
		$this->initCurl();
	}

	private function initCurl() {
		$this->curl                 = new CurlHelper();
		$this->curl->header         = 0;
		$this->curl->timeout        = 3;
		$this->curl->ssl_verifypeer = false;
		$this->curl->returnTransfer = true;
	}

	private function preapareUrl( UrlHelper $url ) {
		if ( trim( $this->options->protectionCode ) ) {
			$url->params['code'] = trim( $this->options->protectionCode );
		}
	}

	private function createUrl() {
		$url = new UrlHelper( self::SERVER_URL );
		$this->preapareUrl( $url );
		return $url;
	}

	public function getDataByPage( $page ) {
		$pageUrl = new UrlHelper( $page );
		if ( ! array_key_exists( $pageUrl->toString(), self::$data ) ) {
			$url                 = $this->createUrl();
			$url->path           = '/service/otvet.php';
			$url->params['proj'] = $pageUrl->host;
			$url->params['url']  = $pageUrl->toString();
			$this->curl->url     = $url->toString();
			$this->curl->execute();

			$data  = $this->curl->getBody();
			$array = json_decode( $data );
			if ( ! is_array( $array ) ) {
				$array = [];
			}
			self::$data[$pageUrl->toString()] = $array;
		}
		return self::$data[$pageUrl->toString()];
	}

	public function getAfterText( $page, $count = 0 ) {
		$afterText = [];
		$data      = $this->getDataByPage( $page );
		if ( ! is_array( $data ) ) {
			return $afterText;
		}
		foreach ( $data as $item ) {
			if ( $this->isAfterText( $item ) ) {
				$afterText[] = $this->map( $item );
			}
		}

		if ( $count ) {
			return array_slice( $afterText, 0, $count );
		} else {
			return $afterText;
		}
	}

	private function isAfterText( $item ) {
		return $item[3] == 'posle';
	}

	public function getInContent( $page ) {
		$inContent = [];
		$data      = $this->getDataByPage( $page );
		if ( ! is_array( $data ) ) {
			return $inContent;
		}
		foreach ( $data as $item ) {
			if ( $this->isInContent( $item ) ) {
				$inContent[] = $this->map( $item );
			}
		}
		return $inContent;
	}

	private function isInContent( $item ) {
		return $item[3] == 'text';
	}

	private function map( $item ) {
		$res              = new PerelinkPluginRelation;
		$res->url         = $item[1];
		$res->anchor      = $item[4];
		$res->environment = $item[5];
		$res->keywords    = $item[2];
		return $res;
	}

}

class PerelinkPluginRelation {

	public $url;
	public $anchor;
	public $environment;
	public $keywords;

}
