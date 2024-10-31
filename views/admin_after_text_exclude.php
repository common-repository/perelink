<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
Исключения
<ul class="subitem">
	<li>
		Не использовать статьи из рубрик в качестве акцепторов<span class="perelink-help" title="Список из идентификаторов, разделенных запятыми"></span><br />
		<input type="text" name="afterTextExcludeCategory" value="<?php echo htmlspecialchars( $options->afterTextExcludeCategory ); ?>"/>
	</li>
	<li>
		Не использовать статьи в качестве акцепторов<span class="perelink-help" title="Список из идентификаторов, разделенных запятыми"></span><br />
		<input type="text" name="afterTextExcludePost" value="<?php echo htmlspecialchars( $options->afterTextExcludePost ); ?>" />
	</li>
</ul>