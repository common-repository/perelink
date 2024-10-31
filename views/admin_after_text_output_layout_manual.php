<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<ul class="show-checked subitem">
	<li>
		<input type="radio" name="afterTextOutputMode" value="list" <?php echo $options->afterTextOutputMode == 'list' ? 'checked="checked"' : ''; ?> id="afterTextOutputMode-list"/>
		<label for="afterTextOutputMode-list">Списком</label>
	</li>
	<li>
		<input type="radio" name="afterTextOutputMode" value="image" <?php echo $options->afterTextOutputMode == 'image' ? 'checked="checked"' : ''; ?> id="afterTextOutputMode-image" />
		<label for="afterTextOutputMode-image">С миниатюрами</label>
		<ul class="show-checked subitem">
			<li style="vertical-align: top">
				Шаблон <span class="perelink-help" title="Допустимые замены:{{url}},{{img}},{{img_width}},{{img_height}},{{anchor}},{{date}}"></span><br />
				<textarea name="afterTextOutputImageTemplate" style="width:500px;"><?php echo htmlspecialchars( $options->afterTextOutputImageTemplate ); ?></textarea>
			</li>
		</ul>
	</li>
	<li>
		<ul>
			<li>
				<table>
					<tr>
						<td>До связанных постов:</td>
						<td>После связанных постов:</td>
					</tr>
					<tr>
						<td><input type="text" name="afterTextOutputBeforeList" value="<?php echo htmlspecialchars( $options->afterTextOutputBeforeList ); ?>" /></td>
						<td><input type="text" name="afterTextOutputAfterList" value="<?php echo htmlspecialchars( $options->afterTextOutputAfterList ); ?>" /></td>
					</tr>
				</table>
			</li>
			<li>
				<table>
					<tr>
						<td>До каждого связанного поста:</td>
						<td>После каждого связанного поста:</td>
					</tr>
					<tr>
						<td><input type="text" name="afterTextOutputBeforeListItem" value="<?php echo htmlspecialchars( $options->afterTextOutputBeforeListItem ); ?>" /></td>
						<td><input type="text" name="afterTextOutputAfterListItem" value="<?php echo htmlspecialchars( $options->afterTextOutputAfterListItem ); ?>" /></td>
					</tr>
				</table>
			</li>
		</ul>
	</li>
</ul>