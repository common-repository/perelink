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
	#perelink-horizontal{
		vertical-align: top;
		margin: 0 -5px;
		text-align: left;
	}
	#perelink-horizontal .perelink-horizontal-item {
		vertical-align: top;
		display: inline-block;
		width: <?php echo $options->afterTextOutputImageSizeX; ?>px;
		margin: 0 5px 10px;
	}
	#perelink-horizontal .perelink-horizontal-item a span {
		display: block;
		margin-top: 10px;
	}
	#perelink-horizontal img{
		width:<?php echo $options->afterTextOutputImageSizeX; ?>px;
		height:<?php echo $options->afterTextOutputImageSizeY; ?>px;
		margin-bottom:10px;
	}
</style>
<div id="perelink-horizontal">
	<?php foreach ( $data as $item ) { ?>
		<div class="perelink-horizontal-item">
			<a href="<?php echo $item->url; ?>"<?php echo $options->targetBlank === 'yes' ? ' target="_blank"' : ''; ?>>
				<img src="<?php echo $this->getImageByPostUrl( $item->url ); ?>" height="<?php echo $options->afterTextOutputImageSizeY; ?>" width="<?php echo $options->afterTextOutputImageSizeX; ?>">
				<span><?php echo mb_ucfirst( $item->anchor ); ?></span>
			</a>
		</div>
	<?php } ?>
</div>