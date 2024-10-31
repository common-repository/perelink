<?php
/**
 * Perelink Plugin
 *
 * @package Perelink
 *
 * @wordpress-plugin
 * Plugin Name: Perelink Pro
 * Plugin URI:  https://perelink.pro
 * Description: Плагин для вывода перелинковки в тексте и после текста, сгенерированной в сервисе perelink.pro
 * Version:     2.1.4
 * License:     GPL v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PERELINK_DEBUG', false );

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'PerelinkPlugin.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'functions.php';

PerelinkPlugin::init( __FILE__ );
