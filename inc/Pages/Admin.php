<?php
/**
* @package wptodo
*/
namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\Base\Model;
use \Inc\Api\SettingsApi;

class Admin extends BaseController
{
	public $settings;
	public $pages = array();
	public $subpages = array();

	public function __construct(){
		$this->settings = new SettingsApi();
	}

	public function register(){

		$this->pages = array(

			array(
				'page_title' => __('WP To Do', 'wptodo'),
				'menu_title' => __('WP To Do', 'wptodo'),
				'capability' => 'edit_posts',
				'menu_slug' => 'wp-todo',
				'callback' =>  array($this, 'wptodo_manage'),
				'icon_url' =>  'dashicons-editor-ol',
				'position' =>  5
			)

		);

		$this->subpages = array(
			// array(
			// 	'parent_slug' => 'wptodo',
			// 	'page_title' => __('', 'wptodo'),
			// 	'menu_title' => __('', 'wptodo'),
			// 	'capability' => 'manage_options',
			// 	'menu_slug' =>  '',
			// 	'function' =>  array($this, '')
			// )
		);
		$this->settings->AddPage( $this->pages )->register();
		$this->settings->AddSubPage( $this->subpages )->register();
	}

	public function wptodo_manage(){
		Model::wptodo_manage();
	}

	public function wptodo_settings(){
		Model::wptodo_settings();
	}

	public static function get_role(){
		$current_user = wp_get_current_user();
		foreach($current_user->roles as $role){
			$role;
		}
		return $role;
	}

	public static function get_user_id(){
		$current_user = wp_get_current_user();
		return $current_user->ID;
	}

	public static function wptodo_add_form(){
		$role =self::get_role();
		if($role == 'administrator' || $role == 'editor'){
			require_once(parent::$plugin_path . 'templates/add_task.php');
		}else{
			echo '<div class="narrow"></div>';
		}
	}

	public static function wptodo_delete_button($delete){
		$role =self::get_role();
		if($role == 'administrator'){
			echo $delete;
		}
	}

}
