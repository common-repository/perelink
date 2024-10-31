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
<style>
	.show-checked{
		display: none;
	}
	input:checked + label + .show-checked{
		display: block;
	}
	.subitem{
		margin-top: 5px;
		margin-left: 40px;
	}
	.perelink-admin th{
		width:200px;
		text-align: left;
		vertical-align: top;
	}
	.perelink-admin, .perelink-admin tr{
		border-spacing:0;
	}
	input[type=text],textarea{
		width:300px;
	}
	input[type=number]{
		width:142px;
	}
	.perelink-block{
		border: 1px solid gray;
		padding: 10px;
		max-width: 800px;
	}
	.perelink-help{
		display: inline-block;
		width: 16px;
		height: 16px;
		border-radius: 8px;
		border: 1px solid gray;
		text-align: center;
		cursor: pointer;
		position: relative;
	}
	.perelink-help:after{
		content: "?";
		position: absolute;
		width: 10px;
		height: 12px;
		top:0;
		left:3px;
		color:gray;
		font-size: 12px;
		font-weight: bold;
	}
</style>
<h1>Настройки плагина <?php echo PerelinkPlugin::getName(); ?></h1>
<form method="post">
	<?php echo $this->render( 'admin_main', compact( 'options' ) ); ?>
	<?php echo $this->render( 'admin_in_content', compact( 'options' ) ); ?>
	<?php echo $this->render( 'admin_after_text', compact( 'options' ) ); ?>
	<?php echo $this->render( 'admin_expert', compact( 'options' ) ); ?>
	<br />
	<input type="submit" value="Сохранить" />
</form>