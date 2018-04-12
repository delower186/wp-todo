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
		add_action('wp_enqueue_scripts', array($this, 'enqueue'));
	}

	public function enqueue(){
		//css stylesheets
		wp_enqueue_style('datatable', parent::$plugin_url . 'scripts/DataTables/datatables.min.css');
		wp_enqueue_style('bootstrap', parent::$plugin_url . 'scripts/Bootstrap/css/bootstrap.min.css');
		wp_enqueue_style('style', parent::$plugin_url . 'scripts/css/style.css');
		wp_enqueue_style('flipclock', parent::$plugin_url . 'scripts/css/flipclock.css');
		//js scripts
		wp_enqueue_script('datatable', parent::$plugin_url . 'scripts/DataTables/datatables.min.js', array(), false, true);
		wp_enqueue_script('flipclock', parent::$plugin_url . 'scripts/js/flipclock.min.js', array(), false, true);
		wp_enqueue_script('bootstrap', parent::$plugin_url . 'scripts/Bootstrap/js/bootstrap.min.js', array(), false, true);
	}
}