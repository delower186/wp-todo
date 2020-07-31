<?php
/**
* @package wptodo
*/
namespace Inc\Base;
class Activate{
	public static function activate(){
		flush_rewrite_rules();
		Model::wptodo_install();
	}
}