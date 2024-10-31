<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property string $pluginFile
 * @property string $pluginBasename
 * @property string $protectionCode
 * @property array  $pageType
 * @property string $wrapperType
 * @property string $targetBlank
 * @property string $inContentTitleSource
 * @property bool   $afterTextEnabled
 * @property string $afterTextMode
 * @property int    $afterTextLinkCount
 * @property string $afterTextOutputMode
 * @property string $afterTextOutputStyle
 * @property string $afterTextOutputTitle
 * @property string $afterTextOutputLayout
 * @property string $afterTextOutputOrientation
 * @property bool   $afterTextOutputUseFirstImage
 * @property string $afterTextOutputImageTemplate
 * @property int    $afterTextOutputImageSizeX
 * @property int    $afterTextOutputImageSizeY
 * @property string $afterTextOutputImageDefault
 * @property string $afterTextOutputBeforeList
 * @property string $afterTextOutputAfterList
 * @property string $afterTextOutputBeforeListItem
 * @property string $afterTextOutputAfterListItem
 * @property string $afterTextExcludeCategory
 * @property array  $afterTextExcludeCategoryArray
 * @property string $afterTextExcludePost
 * @property array  $afterTextExcludePostArray
 * @property bool   $afterTextRelatedPostIfShort
 */
class PerelinkPluginOptions {

	private $pluginFile;

	/**
	 * @var PerelinkPluginOptionsStruct
	 */
	private $options;

	public function __construct( $pluginFile ) {
		$this->pluginFile = $pluginFile;
		$this->loadOptions();
	}

	public function loadOptions() {
		$this->options = unserialize( get_option( 'perelink_options' ) );
		if ( ! ( $this->options instanceof PerelinkPluginOptionsStruct ) ) {
			$this->options = new PerelinkPluginOptionsStruct();
			$this->saveOptions();
		}
	}

	public function __get( $name ) {
		$method = 'get' . ucfirst( $name );
		if ( method_exists( $this, $method ) ) {
			return $this->$method();
		}
		if ( property_exists( $this->options, $name ) ) {
			return $this->options->$name;
		}
	}

	public function getPluginFile() {
		return $this->pluginFile;
	}

	public function getPluginBasename() {
		return plugin_basename( $this->pluginFile );
	}

	public function setOptions( $array ) {
		foreach ( $array as $key => $value ) {
			if ( property_exists( $this->options, $key ) ) {
				$this->options->$key = $value;
			}
		}
		$this->saveOptions();
	}

	public function saveOptions() {
		update_option( 'perelink_options', serialize( $this->options ) );
	}

	public function deleteOptions() {
		delete_option( 'perelink_options' );
	}

	public function getAfterTextExcludeCategoryArray() {
		$cats = trim( $this->afterTextExcludeCategory );
		if ( empty( $cats ) ) {
			return [];
		}
		return explode( ',', $cats );
	}

	public function getAfterTextExcludePostArray() {
		$posts = trim( $this->afterTextExcludePost );
		if ( empty( $posts ) ) {
			return [];
		}
		return explode( ',', $posts );
	}

	public function getAfterTextOutputImageTemplate() {
		$template = trim( $this->options->afterTextOutputImageTemplate );
		if ( empty( $template ) ) {
			return '<a href="{{url}}"><img src="{{img}}" style="width:{{img_width}}px; height:{{img_height}}px;" />{{anchor}}</a>';
		}
		return $this->options->afterTextOutputImageTemplate;
	}

}

class PerelinkPluginOptionsStruct {

	public $protectionCode                = '';
	public $pageType                      = [ 'post', 'page' ];
	public $wrapperType                   = 'new';
	public $targetBlank                   = 'no';
	public $inContentTitleSource          = '';
	public $afterTextEnabled              = true;
	public $afterTextMode                 = 'auto';
	public $afterTextLinkCount            = 4;
	public $afterTextOutputMode           = 'image';
	public $afterTextOutputTitle          = '<h3>Похожие статьи</h3>';
	public $afterTextOutputStyle          = '';
	public $afterTextOutputLayout         = 'auto';
	public $afterTextOutputOrientation    = 'horizontal';
	public $afterTextOutputUseFirstImage  = true;
	public $afterTextOutputImageSizeX     = 100;
	public $afterTextOutputImageSizeY     = 100;
	public $afterTextOutputImageTemplate  = '';
	public $afterTextOutputImageDefault   = '';
	public $afterTextOutputBeforeList     = '<ul>';
	public $afterTextOutputAfterList      = '</ul>';
	public $afterTextOutputBeforeListItem = '<li>';
	public $afterTextOutputAfterListItem  = '</li>';
	public $afterTextExcludeCategory      = '';
	public $afterTextExcludePost          = '';
	public $afterTextRelatedPostIfShort   = true;

}
