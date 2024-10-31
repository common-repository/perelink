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
?>
<style>
	#perelink-vertical{
		border:none;
		margin-bottom: 15px;
	}
	#perelink-vertical .perelink-vertical-item {
		text-align: left;
		border-bottom: 1px solid #e5e5e5;
		padding: 10px 0;
	}
	#perelink-vertical .perelink-vertical-item a {
		display: block;
		text-decoration: none;
	}
	#perelink-vertical .perelink-vertical-item a:hover .perelink-anchor {
		text-decoration: underline;
	}
	#perelink-vertical .perelink-vertical-item .perelink-image {
		display: inline-block;
		margin-right: 10px;
	}
	#perelink-vertical .perelink-vertical-item .perelink-anchor {
		display: inline-block;
		vertical-align: top;
		padding: 5px 0;
	}
	#perelink-vertical .perelink-image, #perelink-horizontal .perelink-anchor {
		vertical-align: middle;
	}
	#perelink-vertical .perelink-image,#perelink-vertical .perelink-image img{
		width:<?php echo $options->afterTextOutputImageSizeX; ?>px;
		height:<?php echo $options->afterTextOutputImageSizeY; ?>px;
	}
	#perelink-vertical .perelink-image,#perelink-horizontal .perelink-anchor{
		vertical-align: middle;
	}
</style>
<div id="perelink-vertical">
	<?php foreach ( $data as $item ) { ?>
		<div class="perelink-vertical-item">
			<a href="<?php echo $item->url; ?>"<?php echo $options->targetBlank === 'yes' ? ' target="_blank"' : ''; ?>>
				<div class="perelink-image">
					<img src="<?php echo $this->getImageByPostUrl( $item->url ); ?>" height="<?php echo $options->afterTextOutputImageSizeY; ?>" width="<?php echo $options->afterTextOutputImageSizeX; ?>">
				</div>
				<div class="perelink-anchor">
					<span><?php echo mb_ucfirst( $item->anchor ); ?></span>
				</div>
			</a>
		</div>
	<?php } ?>
</div>