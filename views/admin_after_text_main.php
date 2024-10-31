<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
Вывод перелинковки
<ul class="subitem">
	<li>
		<input type="radio" name="afterTextMode" value="auto" <?php echo $options->afterTextMode == 'auto' ? 'checked="checked"' : ''; ?> id="afterTextMode-auto" />
		<label for="afterTextMode-auto">Автоматически (сразу после текста)</label>
	</li>
	<li>
		<input type="radio" name="afterTextMode" value="manual" <?php echo $options->afterTextMode == 'manual' ? 'checked="checked"' : ''; ?> id="afterTextMode-manual" />
		<label for="afterTextMode-manual">Вручную (вставка кода)</label>
		<code class="show-checked">
			&lt;?php
			if ( class_exists( 'PerelinkPlugin' ) ) {
				PerelinkPlugin::getAfterText();
			}
			?&gt;
		</code>
	</li>
	<li>
		Количество связей "после текста"
		<select name="afterTextLinkCount">
			<?php for ( $i = 1; $i <= 30; $i++ ) { ?>
				<option value="<?php echo $i; ?>" <?php echo $options->afterTextLinkCount == $i ? 'selected="selected"' : ''; ?> ><?php echo $i; ?></option>
			<?php } ?>
		</select>
	</li>
	<li>
		<input type="hidden" name="afterTextRelatedPostIfShort" value="0" />
		<input type="checkbox" name="afterTextRelatedPostIfShort" <?php echo $options->afterTextRelatedPostIfShort ? 'checked="checked"' : ''; ?> id="afterTextRelatedPostIfShort" value="1"/>
		<label for="afterTextRelatedPostIfShort">Дополнять список, если не хватает?</label>
	</li>
</ul>
