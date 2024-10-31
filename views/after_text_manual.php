<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* @var $options PerelinkPluginOptions */
/* @var $data \PerelinkPluginRelation[] */
/* @var $this PerelinkPluginWorkAfterText */

echo $options->afterTextOutputBeforeList;
foreach ( $data as $item ) {
	$currentPost = $this->getPostByUrl( $item->url );
	echo $options->afterTextOutputBeforeListItem;
	switch ( $options->afterTextOutputMode ) {
		case 'list':
			echo '<a href="' . $item->url . '"' . ( $options->targetBlank === 'yes' ? ' target="_blank"' : '' ) . '>' . mb_ucfirst( $item->anchor ) . '</a>';
			break;
		case 'image':
			$source = [
				'{{url}}'        => $item->url,
				'{{img}}'        => $this->getImageByPostUrl( $item->url ),
				'{{anchor}}'     => mb_ucfirst( $item->anchor ),
				'{{img_width}}'  => $options->afterTextOutputImageSizeX,
				'{{img_height}}' => $options->afterTextOutputImageSizeY,
				'{{date}}'       => get_post_time( get_option( 'date_format' ), false, $currentPost, true ),
			];
			echo str_replace( array_keys( $source ), $source, $options->afterTextOutputImageTemplate );
			break;
	}
	echo $options->afterTextOutputAfterListItem;
}
echo $options->afterTextOutputAfterList;
