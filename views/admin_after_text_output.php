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
Вывод материалов
<ul class="subitem">
	<li>
		Заголовок перед выводом<br />
		<input type="text" name="afterTextOutputTitle" value="<?php echo htmlspecialchars( $options->afterTextOutputTitle ); ?>" style="width:300px;"/>
	</li>
	<li>
		Стили<span class="perelink-help" title="Стили CSS, текст из этого поля размещается между тегами &lt;style&gt; и &lt;&#47style&gt;"></span><br />
		<textarea name="afterTextOutputStyle"><?php echo htmlspecialchars( $options->afterTextOutputStyle ); ?></textarea>
	</li>
	<li>
		Верстка вывода
		<ul class="subitem">
			<li>
				<input type="radio" name="afterTextOutputLayout" value="auto" <?php echo $options->afterTextOutputLayout == 'auto' ? 'checked="checked"' : ''; ?> id="afterTextOutputLayout_auto" />
				<label for="afterTextOutputLayout_auto">Предустановленная</label>
				<div class="show-checked subitem">
					Ориентация
					<ul class="subitem">
						<li>
							<input type="radio" name="afterTextOutputOrientation" value="horizontal" <?php echo $options->afterTextOutputOrientation == 'horizontal' ? 'checked="checked"' : ''; ?> id="afterTextOutputOrientation-horizontal"/>
							<label for="afterTextOutputOrientation-horizontal">Горизонтальная</label>
						</li>
						<li>
							<input type="radio" name="afterTextOutputOrientation" value="vertical" <?php echo $options->afterTextOutputOrientation == 'vertical' ? 'checked="checked"' : ''; ?> id="afterTextOutputOrientation-vertical"/>
							<label for="afterTextOutputOrientation-vertical">Вертикальная</label>
						</li>
					</ul>
				</div>
			</li>
			<li>
				<input type="radio" name="afterTextOutputLayout" value="manual" <?php echo $options->afterTextOutputLayout == 'manual' ? 'checked="checked"' : ''; ?> id="afterTextOutputLayout_manual" />
				<label for="afterTextOutputLayout_manual">Произвольная</label>
				<?php echo $this->render( 'admin_after_text_output_layout_manual', compact( 'options' ) ); ?>
			</li>
		</ul>
	</li>
	<li>
		Размер миниатюр
		<table class="perelink-admin">
			<tr>
				<td>Ширина</td>
				<td></td>
				<td>Высота</td>
			</tr>
			<tr>
				<td><input type="number" name="afterTextOutputImageSizeX" value="<?php echo (int) $options->afterTextOutputImageSizeX; ?>" /></td>
				<td>X</td>
				<td><input type="number" name="afterTextOutputImageSizeY" value="<?php echo (int) $options->afterTextOutputImageSizeY; ?>" /></td>
			</tr>
		</table>
	</li>
	<li>
		<input type="hidden" name="afterTextOutputUseFirstImage" value="0" />
		<input type="checkbox" name="afterTextOutputUseFirstImage" <?php echo $options->afterTextOutputUseFirstImage ? 'checked="checked"' : ''; ?> id="afterTextOutputUseFirstImage" />
		<label for="afterTextOutputUseFirstImage">Использовать первое изображение из статьи (если не задана миниатюра)</label>
	</li>
	<li>
		Миниатюра по умолчанию (если не задана и нет изображений в статье)<br />
		<input type="text" name="afterTextOutputImageDefault" value="<?php echo htmlspecialchars( $options->afterTextOutputImageDefault ); ?>" />
	</li>
</ul>