<?php
/**
* @package wptodo
*/
/*
Plugin Name: WP To Do
Plugin URI: http://www.247-assistant.com/dev/
Description: A full featured plugin for creating and managing a "to do" list.
Version:1.0.1
Author: Delower
Author URI: http://www.247-assistant.com
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

if(file_exists(dirname(__FILE__).'/vendor/autoload.php')){
	require_once dirname(__FILE__).'/vendor/autoload.php';
}

//activate plugin
function activate_wptodo(){
	\Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_wptodo' );
//deactivate plugin
function deactivate_wptodo(){
	\Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_wptodo' );
//instantiate classes
if( class_exists( 'Inc\\Init' ) ){
	\Inc\Init::register_services();
}