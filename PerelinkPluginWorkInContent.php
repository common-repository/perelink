<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PerelinkPluginWorkInContent extends PerelinkPluginBaseClass {

	/**
	 *
	 * @var PerelinkPluginOptions
	 */
	private $options;

	/**
	 *
	 * @var PerelinkPluginSource
	 */
	private $source;
	private $content;
	private $contentCopy;
	private $currentLength;
	private $findCount;

	/**
	 *
	 * @var PerelinkPluginRelation
	 */
	private $item;

	public function __construct() {
		$this->options = PerelinkPlugin::getOptions();
		$this->source  = PerelinkPlugin::getSource();
	}

	public function work( $content ) {
		$this->content = $content;
		$data          = $this->source->getInContent( $this->getCurrentUrl() );
		$this->log( $data );
		foreach ( $data as $this->item ) {
			$this->prepareCopyContent();
			$this->searchAndReplace();
		}
		return $this->content;
	}

	private function searchAndReplace() {
		$this->log( $this->item );
		$this->currentLength = 0;
		$this->findCount     = 0;
		$anchor              = preg_quote( $this->item->anchor );
		$pattern             = '#\b' . mb_strtolower( $anchor ) . '\b#uis';
		$this->log( $pattern );
		$this->contentCopy = preg_replace_callback( $pattern, [ $this, 'getHashesForPregReplace' ], $this->contentCopy );
		if ( ! $this->currentLength ) {
			$pattern = '#' . mb_strtolower( $anchor ) . '#uis';
			$this->log( $pattern );
			$this->contentCopy = preg_replace_callback( $pattern, [ $this, 'getHashesForPregReplace' ], $this->contentCopy );
		}
		$this->log( $this->currentLength );
		if ( $this->currentLength ) {
			if ( $this->findCount > 1 ) {
				$offset = $this->getOffset();
			} else {
				$offset = 0;
			}
			$pos           = mb_strpos( $this->contentCopy, '#', $offset );
			$link          = $this->getLinkTag() . mb_substr( $this->content, $pos, $this->currentLength ) . '</a>';
			$newContent    = mb_substr( $this->content, 0, $pos ) . $link . mb_substr( $this->content, $pos + $this->currentLength );
			$this->content = $newContent;
		}
	}

	private function getOffset() {
		$offset     = 0;
		$result     = 0;
		$baseVector = $this->getNVector( $this->item->environment );
		$metriks    = $this->calcMetriks( $baseVector, [] );
		for ( $i = 0; $i < $this->findCount; $i++ ) {
			$pos         = mb_strpos( $this->contentCopy, '#', $offset );
			$environment = $this->getEnvironment( $pos );
			$vector      = $this->getNVector( $environment );
			$newMetriks  = $this->calcMetriks( $vector, $baseVector );
			if ( $newMetriks < $metriks ) {
				$metriks = $newMetriks;
				$result  = $offset;
			}
			$offset = $pos + $this->currentLength;
		}
		return $result;
	}

	private function calcMetriks( $vector1, $vector2 ) {
		$keys = array_keys( $vector1 + $vector2 );
		$acc  = 0;
		foreach ( $keys as $key ) {
			$acc += abs( (int) @$vector1[$key] - (int) @$vector2[$key] );
		}
		return $acc;
	}

	private function getNVector( $str ) {
		$str          = mb_strtolower( $str );
		$notCharacter = [' ', ',', '.', '\'', '"', '-', ':', ';',];
		$strLen       = mb_strlen( $str );
		$vector       = [];
		for ( $i = 0; $i < $strLen; $i++ ) {
			$char = mb_substr( $str, $i, 1 );
			if ( in_array( $char, $notCharacter ) ) {
				continue;
			}
			if ( array_key_exists( $char, $vector ) ) {
				$vector[$char] ++;
			} else {
				$vector[$char] = 1;
			}
		}
		return $vector;
	}

	private function getEnvironment( $pos = 0 ) {
		$cc                 = $this->contentCopy;
		$environment        = new stdClass();
		$environment->begin = mb_substr( $cc, $pos < 50 ? 0 : $pos - 50, $pos < 50 ? $pos : 50 );
		$environment->midle = $this->item->anchor;
		$environment->end   = mb_substr( $cc, $pos + mb_strlen( $this->item->anchor ), 50 );
		return $environment->begin . $environment->midle . $environment->end;
	}

	public function getHashesForPregReplace( $matches ) {
		$this->findCount++;
		$this->currentLength = mb_strlen( $matches[0] );
		return str_repeat( '#', $this->currentLength );
	}

	private function getLinkTag() {
		$title = null;
		if ( $this->options->inContentTitleSource == 'keyword' ) {
			$title = $this->item->keywords;
		} elseif ( $this->options->inContentTitleSource == 'article' ) {
			$postId = url_to_postid( $this->item->url );
			$post   = get_post( $postId );
			$title  = $post->post_title;
		}
		return '<a href="' . $this->item->url . '"' . ( empty( $title ) ? '' : ' title="' . $title . '"' ) . ' class="perelink"' . ( $this->options->targetBlank === 'yes' ? ' target="_blank"' : '' ) . '>';
	}

	private function prepareCopyContent() {
		$this->contentCopy = $this->content;
		$this->clearLinks();
		$this->clearImages();
		$this->clearHeaders();
		$this->clearIndex();
		$this->clearNoIndex();
		$this->clearChars();
	}

	private function clearLinks() {
		$this->clearTag( 'a' );
	}

	private function clearImages() {
		$this->clearTag( 'img', false );
		$this->clearTag( 'figcaption' );
	}

	private function clearHeaders() {
		$headers = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];
		foreach ( $headers as $tag ) {
			$this->clearTag( $tag );
		}
	}

	private function clearIndex() {
		$this->clearTag( 'index' );
	}

	private function clearNoIndex() {
		$this->clearTag( 'noindex' );
		$this->clearCommentTag( 'noindex' );
		$this->clearCommentTag( 'exclude', 'start_', 'end_' );
		$this->clearCommentTag( 'link', 'start_', 'end_' );
	}

	private function clearChars() {
		$chars = [ '#' ];
		foreach ( $chars as $char ) {
			$this->contentCopy = str_replace( $char, '*', $this->contentCopy );
		}
	}

	private function clearTag( $tag, $needClosed = true ) {
		if ( $needClosed ) {
			$pattern = '#<' . $tag . '.*>.*<\/' . $tag . '>#USi';
		} else {
			$pattern = '#<' . $tag . '.*\/>#USi';
		}
		$this->clearByPattern( $pattern );
	}

	private function clearCommentTag( $commentTag, $prefixPattern = '', $postfixPattern = '\/' ) {
		$pattern = '#<!--' . $prefixPattern . $commentTag . '-->.*<!--' . $postfixPattern . $commentTag . '-->#USi';
		$this->clearByPattern( $pattern );
	}

	private function clearByPattern( $pattern ) {
		$this->contentCopy = preg_replace_callback( $pattern, 'perelinkPlugin_matchesToStar', $this->contentCopy );
	}

}
