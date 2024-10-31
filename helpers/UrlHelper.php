<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UrlHelper' ) ) {

	class UrlHelper {

		public $sheme;
		public $login;
		public $password;
		public $host;
		public $port;
		public $path;
		public $params;
		public $anchor;
		public $loadHostFromPathIfEmpty = true;

		public function __construct( $url = null ) {
			if ( ! empty( $url ) ) {
				$this->setUrl( $url );
			}
		}

		public function __toString() {
			return $this->toString();
		}

		public function setUrl( $string ) {
			$url = parse_url( $string );
			$this->sheme    = isset( $url['scheme'] ) ? $url['scheme'] : 'http';
			$this->login    = isset( $url['user'] ) ? $url['user'] : null;
			$this->password = isset( $url['pass'] ) ? $url['pass'] : null;
			$this->host     = isset( $url['host'] ) ? mb_strtolower( $url['host'] ) : '';
			$this->port     = isset( $url['port'] ) ? $url['port'] : null;
			$this->path     = isset( $url['path'] ) ? $url['path'] : null;
			if ( $this->loadHostFromPathIfEmpty ) {
				$this->setHostFromPath();
			}
			if ( isset( $url['query'] ) ) {
				parse_str( $url['query'], $this->params );
			} else {
				$this->params = array();
			}
			$this->anchor = isset( $url['fragment'] ) ? $url['fragment'] : null;
		}

		public function setHostFromPath() {
			if ( empty( $this->host ) && ! empty( $this->path ) ) {
				$path = explode( '/', $this->path );
				$this->host = array_shift( $path );
				$this->path = '/' . implode( '/', $path );
			}
		}

		public function toString() {
			$res = $this->sheme . '://';
			if ( ! empty( $this->login ) ) {
				$res .= $this->login;
				if ( ! empty( $this->password ) ) {
					$res .= ':' . $this->password;
				}
				$res .= '@';
			}
			$res .= $this->host;
			$afterHost = '';
			if ( ! empty( $this->port ) ) {
				$res .= ':' . $this->port;
			}
			if ( empty( $this->path ) && ( ! empty( $this->params ) || ! empty( $this->anchor ) ) ) {
				$path = '/';
			} else {
				$path = $this->path;
			}
			$res .= $path;
			if ( ! empty( $this->params ) ) {
				$res .= '?' . http_build_query( $this->params );
			}
			if ( ! empty( $this->anchor ) ) {
				$res .= '#' . $this->anchor;
			}
			return $res;
		}

		public static function compare( $url1, $url2 ) {
			if ( ! ( $url1 instanceof UrlHelper ) ) {
				$url1 = new UrlHelper( $url1 );
			}
			if ( ! ( $url2 instanceof UrlHelper ) ) {
				$url2 = new UrlHelper( $url2 );
			}
			return $url1->toString() == $url2->toString();
		}

	}

}
