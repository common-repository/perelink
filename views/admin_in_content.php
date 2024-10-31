<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h2>В тексте</h2>
<div class="perelink-block">
	Аттрибут title у ссылок в тексте
	<ul class="subitem">
		<li>
			<input type="radio" name="inContentTitleSource" value="" <?php echo empty( $options->inContentTitleSource ) ? 'checked="checked"' : ''; ?> id="titleSource-none" />
			<label for="titleSource-none">Не использовать (рекомендуется)</label>
		</li>
		<li>
			<input type="radio" name="inContentTitleSource" value="keyword" <?php echo $options->inContentTitleSource == 'keyword' ? 'checked="checked"' : ''; ?> id="titleSource-keyword" />
			<label for="titleSource-keyword">Ключ в системе</label>
		</li>
		<li>
			<input type="radio" name="inContentTitleSource" value="article" <?php echo $options->inContentTitleSource == 'article' ? 'checked="checked"' : ''; ?> id="titleSource-article" />
			<label for="titleSource-article">Название статьи</label>
		</li>
	</ul>
</div>