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

$this->render( 'after_text_auto_' . $options->afterTextOutputOrientation, compact( 'options', 'data' ) );
