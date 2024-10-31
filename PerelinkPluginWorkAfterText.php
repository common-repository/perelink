<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'PerelinkPluginImages.php';

class PerelinkPluginWorkAfterText extends PerelinkPluginBaseClass {

	/**
	 * @var PerelinkPluginOptions
	 */
	private $options;

	/**
	 *
	 * @var PerelinkPluginSource
	 */
	private $source;
	private $excludePosts = [];
	private $data;
	private static $level = 0;

	public function __construct() {
		$this->options      = PerelinkPlugin::getOptions();
		$this->source       = PerelinkPlugin::getSource();
		$this->excludePosts = array_merge( $this->options->afterTextExcludePostArray, [ get_the_ID() ] );
	}

	public function work( $limit = null, $offset = 0 ) {
		if ( self::$level == 0 ) {
			self::$level++;
			$preData = $this->getData();
			if ( is_null( $limit ) || $limit + $offset > count( $preData ) ) {
				$limit = count( $preData ) - $offset;
			}
			$data = [];
			for ( $i = $offset; $i < $limit + $offset; $i++ ) {
				$data[] = $preData[$i];
			}
			self::$level--;
			return $this->render( 'after_text', [ 'data' => $data, 'options' => $this->options ], true );
		}
	}

	private function getData() {
		if ( empty( $this->data ) ) {
			$this->loadData();
		}
		return $this->data;
	}

	private function loadData() {
		$data   = $this->source->getAfterText( $this->getCurrentUrl(), $this->options->afterTextLinkCount );
		$result = array();
		foreach ( $data as $item ) {
			if ( $this->checkItem( $item ) ) {
				$result[] = $item;
			}
			if ( count( $result ) >= $this->options->afterTextLinkCount ) {
				break;
			}
		}
		if ( $this->options->afterTextRelatedPostIfShort ) {
			$this->completeData( $result );
		}
		$this->data = $result;
	}

	private function completeData( &$result ) {
		if ( count( $result ) < $this->options->afterTextLinkCount ) {
			$result = array_merge( $result, $this->getYarppPosts( $this->options->afterTextLinkCount - count( $result ) ) );
		}
		if ( count( $result ) < $this->options->afterTextLinkCount ) {
			$result = array_merge( $result, $this->getPostsByCats( $this->options->afterTextLinkCount - count( $result ) ) );
		}
		if ( count( $result ) < $this->options->afterTextLinkCount ) {
			$result = array_merge( $result, $this->getRandomPosts( $this->options->afterTextLinkCount - count( $result ) ) );
		}
	}

	private function getYarppPosts( $count ) {

		$result = [];
		if ( function_exists( 'yarpp_get_related' ) ) {
			$posts = yarpp_get_related( [ 'limit' => $count ], get_the_ID() );
			foreach ( $posts as $post ) {
				if ( in_array( $post->ID, $this->excludePosts ) || in_array( $post->ID, $this->options->afterTextExcludePostArray ) || ! $this->checkCategories( $post ) ) {
					continue;
				}
				$result[]             = $this->mapWpPostToRelation( $post );
				$this->excludePosts[] = $post->ID;
			}
		}
		return $result;
	}

	private function checkCategories( $post ) {
		$post_cats = get_the_category( $post->ID );
		foreach ( $post_cats as $cat ) {
			if ( in_array( $cat->cat_ID, $this->options->afterTextExcludeCategoryArray ) ) {
				return false;
			}
		}
		return true;
	}

	private function getRandomPosts( $count ) {
		$args   = $this->getArgsForRandomPosts( $count );
		$query  = new WP_Query();
		$posts  = $query->query( $args );
		$result = [];
		foreach ( $posts as $post ) {
			$result[]             = $this->mapWpPostToRelation( $post );
			$this->excludePosts[] = $post->ID;
		}
		return $result;
	}

	private function getArgsForRandomPosts( $count ) {
		$args = [
			'posts_per_page'   => $count,
			'post__not_in'     => $this->excludePosts,
			'category__not_in' => $this->options->afterTextExcludeCategoryArray,
			'orderby'          => 'rand',
		];
		return $args;
	}

	private function getPostsByCats( $count ) {
		$result = [];
		$args   = $this->getArgsForPostsByCats( $count );
		$query  = new WP_Query();
		$posts  = $query->query( $args );
		foreach ( $posts as $post ) {
			$result[]             = $this->mapWpPostToRelation( $post );
			$this->excludePosts[] = $post->ID;
		}
		return $result;
	}

	private function getArgsForPostsByCats( $count ) {
		$categories      = get_the_category( get_the_ID() );
		$categoriesArray = [];
		foreach ( $categories as $category ) {
			$categoriesArray[] = $category->term_id;
		}
		$args = [
			'posts_per_page'   => $count,
			'category__in'     => $categoriesArray,
			'post__not_in'     => $this->excludePosts,
			'category__not_in' => $this->options->afterTextExcludeCategoryArray,
		];
		return $args;
	}

	private function mapWpPostToRelation( $post ) {
		$item         = new PerelinkPluginRelation();
		$item->url    = get_permalink( $post->ID );
		$item->anchor = $post->post_title;
		return $item;
	}

	private function checkItem( PerelinkPluginRelation $item ) {
		$url = new UrlHelper( $item->url );
		$url->host = $_SERVER['HTTP_HOST'];
		$postId    = url_to_postid( $url->toString() );
		if ( in_array( $postId, $this->excludePosts ) ) {
			return false;
		}
		$catogories = get_the_category( $postId );
		foreach ( $catogories as $category ) {
			if ( in_array( $category->term_id, $this->options->afterTextExcludeCategoryArray ) ) {
				return false;
			}
		}
		$this->excludePosts[] = $postId;
		return true;
	}

	public function getPostByUrl( $postUrl ) {
		$postId = url_to_postid( $postUrl );
		return get_post( $postId );
	}

	public function getImageByPostUrl( $postUrl ) {
		$postId   = url_to_postid( $postUrl );
		$attachId = $this->getPostThumbnailId( $postId );
		$imgUrl   = false;

		if ( ! empty( $attachId ) ) {
			$imgUrl = PerelinkPluginImages::getImageUrl( $attachId, [ $this->options->afterTextOutputImageSizeX, $this->options->afterTextOutputImageSizeY ] );
		}
		if ( empty( $imgUrl ) && $this->options->afterTextOutputUseFirstImage ) {
			foreach ( $this->getPostFirstAttachmentImageId( $postId ) as $attachId ) {
				$imgUrl = PerelinkPluginImages::getImageUrl( $attachId, [ $this->options->afterTextOutputImageSizeX, $this->options->afterTextOutputImageSizeY ] );
				if ( ! empty( $imgUrl ) ) {
					break;
				}
			}
		}
		if ( ! $imgUrl ) {
			$imgUrl = $this->options->afterTextOutputImageDefault;
		}
		return $imgUrl;
	}

	private function getPostThumbnailId( $postId ) {
		return get_post_thumbnail_id( $postId );
	}

	private function getPostFirstAttachmentImageId( $postId ) {
		$args = [
			'post_type'   => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $postId,
		];
		$attachments = get_posts( $args );
		if ( empty( $attachments ) ) {
			return [];
		}
		$result = [];
		foreach ( $attachments as $attach ) {
			$result[] = $attach->ID;
		}
		return $result;
	}

}
