<?php
/**
* @package wptodo
*/
namespace Inc\Base;

use \Inc\Base\BaseController;

class Enqueue extends BaseController
{
	
	public function register(){
		add_action('admin_enqueue_scripts', array($this, 'enqueue'));
	}

	public function enqueue(){
		wp_enqueue_style('style', parent::$plugin_url . 'scripts/style.css');
		wp_enqueue_script('script', parent::$plugin_url . 'scripts/jquery.datatables.min.js');
		//wp_enqueue_script('script', parent::$plugin_url . 'scripts/script.js');
		//wp_enqueue_script('script', parent::$plugin_url . 'scripts/jquery.tinymce.min.js');
	}
}