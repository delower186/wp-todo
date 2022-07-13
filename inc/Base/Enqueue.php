<?php
/**
* @package wptodo
*/
namespace Inc\Base;

use \Inc\Base\BaseController;

class Enqueue extends BaseController
{
	
	public function register(){
		//enqueue scripts in the admin panel
		add_action('admin_enqueue_scripts', array($this, 'enqueue'),9999);

		//enqueue scripts in the frontend
		add_action('wp_enqueue_scripts', array($this, 'enqueue'),9999);
	}

	public function enqueue(){
		//css stylesheets
		wp_enqueue_style('datatable', parent::$plugin_url . 'scripts/DataTables/datatables.min.css');
		wp_enqueue_style('style', parent::$plugin_url . 'scripts/css/style.css');
		wp_enqueue_style('jquery-ui', parent::$plugin_url . 'scripts/jquery-ui-1.13.1/jquery-ui.min.css');
		wp_enqueue_style('flipclock', parent::$plugin_url . 'scripts/css/flipclock.css');
		//js scripts
		wp_enqueue_script('jquery-3.6.0', parent::$plugin_url . 'scripts/js/jquery-3.6.0.min.js',false,array(), false, false);
		wp_enqueue_script('datatable', parent::$plugin_url . 'scripts/DataTables/datatables.min.js', array(), false, true);
		wp_enqueue_script('flipclock', parent::$plugin_url . 'scripts/js/flipclock.min.js', array(), false, true);
		wp_enqueue_script('jquery-ui', parent::$plugin_url . 'scripts/jquery-ui-1.13.1/jquery-ui.min.js', array(), false, true);
	}

}