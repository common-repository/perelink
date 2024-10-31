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
<?php echo $options->afterTextOutputStyle; ?>
</style>
<?php echo $options->afterTextOutputTitle; ?>
<?php echo $this->render( 'after_text_' . $options->afterTextOutputLayout, compact( 'options', 'data' ) ); ?>