<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* @var $options PerelinkPluginOptions */
/* @var $this PerelinkPluginWorkAfterText */
?>
<h2>После текста</h2>
<div class="perelink-block">
	<input type="hidden" name="afterTextEnabled" value="0"/>
	<input type="checkbox" name="afterTextEnabled" <?php echo $options->afterTextEnabled ? 'checked="checked"' : ''; ?> id="afterTextEnabled" />
	<label for="afterTextEnabled">Включено</label>
	<ul class="show-checked">
		<li><?php echo $this->render( 'admin_after_text_main', compact( 'options' ) ); ?></li>
		<li><?php echo $this->render( 'admin_after_text_output', compact( 'options' ) ); ?></li>
		<li><?php echo $this->render( 'admin_after_text_exclude', compact( 'options' ) ); ?></li>
	</ul>
</div>