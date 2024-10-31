<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h2>Настройки для экспертов</h2>
<ul class="perelink-block">
	<li>Обертка вокруг текста статьи для поиска связей
		<ul class="subitem">
			<li>
				<input type="radio" name="wrapperType" value="new" <?php echo $options->wrapperType == 'new' ? 'checked="checked"' : ''; ?> id="wrapperType-new" />
				<label for="wrapperType-new">&lt;index&gt;&lt;/index&gt; (рекомендуется)</label>
			</li>
			<li>
				<input type="radio" name="wrapperType" value="old" <?php echo $options->wrapperType == 'old' ? 'checked="checked"' : ''; ?> id="wrapperType-old" />
				<label for="wrapperType-old">&lt;!--start_content--&gt;&lt;!--end_content--&gt;</label>
			</li>
		</ul><em style="color:gray;">После изменения данной опции не забудьте сбросить кеш WordPress!</em>
	</li>
	<li>Открывать ссылки в новом окне
		<ul class="subitem">
			<li>
				<input type="radio" name="targetBlank" value="no" <?php echo $options->targetBlank == 'no' ? 'checked="checked"' : ''; ?> id="targetBlank-no" />
				<label for="wrapperType-no">нет (рекомендуется)</label>
			</li>
			<li>
				<input type="radio" name="targetBlank" value="yes" <?php echo $options->targetBlank == 'yes' ? 'checked="checked"' : ''; ?> id="targetBlank-yes" />
				<label for="targetBlank-yes">да</label>
			</li>
		</ul>
	</li>
</ul>