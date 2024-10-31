<?php

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mb_ucfirst' ) ) {
	function mb_ucfirst( $str, $enc = 'utf-8' ) {
		return mb_strtoupper( mb_substr( $str, 0, 1, $enc ), $enc ) . mb_substr( $str, 1, mb_strlen( $str, $enc ), $enc );
	}
}

/**
 * Создана для поддержки старых версий PHP в которых не было анонимных функций
 * Служебная функция для передачи параметорм в preg_replace_callback
 * Возвращает строку длиной равной длине результата для всего шаблона состоящей из *
 *
 * @param array $matches
 *
 * @return string
 */
function perelinkPlugin_matchesToStar( $matches ) {
	return str_repeat( '*', mb_strlen( $matches[0] ) );
}

/**
 * Создана для обратной совместимости со старой версией перелинковки
 *
 * @param array $params
 *
 * @return string
 */
function perelink_after_content( $params = [] ) {
	return PerelinkPlugin::getAfterText( $params );
}

if ( ! function_exists( 'stripslashes_recursive' ) ) {
	function stripslashes_recursive( $item ) {
		if ( is_array( $item ) ) {
			foreach ( $item as $key => $value ) {
				$item[$key] = stripslashes_recursive( $value );
			}
		} else {
			$item = stripslashes( $item );
		}
		return $item;
	}
}
