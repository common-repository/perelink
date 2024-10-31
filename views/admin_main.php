<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h2>Общие настройки</h2>
<ul class="perelink-block">
	<li>Код защиты <input type="text" name="protectionCode" value="<?php echo $options->protectionCode; ?>" /><br /><em style="color:gray;">Для генерации кода защиты посетите настройки проекта в сервисе</em></li>
	<li>Типы страниц учавствующие в перелинковке
		<ul class="subitem">
			<li>
				<input type="checkbox" name="pageType[]" value="post" <?php echo in_array( 'post', $options->pageType ) ? 'checked="checked"' : ''; ?> id="pageType-post" />
				<label for="pageType-post">Статьи (посты)</label>
			</li>
			<li>
				<input type="checkbox" name="pageType[]" value="page" <?php echo in_array( 'page', $options->pageType ) ? 'checked="checked"' : ''; ?> id="pageType-page" />
				<label for="pageType-page">Страницы</label>
			</li>
		</ul>
	</li>
</ul>