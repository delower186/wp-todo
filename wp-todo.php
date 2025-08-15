<?php
/**
* @package wptodo
*/
/*
Plugin Name: WP To Do 2
Plugin URI: https://sandalia.com.bd/apps
Description: A full featured plugin for creating and managing a "to do" list.
Version:2.0.0
Author: Delower
Author URI: https://sandalia.com.bd/apps
License: GPLv2 or later
Text Domain: wptodo
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright (C) 2018  delower.

*/
defined('ABSPATH') or die('Hey, What are you doing here? You Silly Man!');

define('PLUGIN_DIR_PATH', plugin_dir_path( __FILE__));
define('PLUGIN_DIR_URL', plugin_dir_url( __FILE__));


include PLUGIN_DIR_PATH . "todo/wptodo_custom_post_type.php";
include PLUGIN_DIR_PATH . "meta_boxes/wptodo_meta_boxe.php";
include PLUGIN_DIR_PATH . "list_table/custom_columns.php";
include PLUGIN_DIR_PATH . "notification/notify.php";
include PLUGIN_DIR_PATH . "inc/enqueue.php";
include PLUGIN_DIR_PATH . "todo/modal_view.php";
include PLUGIN_DIR_PATH . "todo/count_down_timer.php";