<?php
/**
* @package wptodo
*/
namespace Inc\Api;

class SettingsApi
{
	public $admin_pages = array();
	public $admin_subpages = array();
	
	public function register(){
		if ( ! empty($this->admin_pages ) ){
			add_action( 'admin_menu', array( $this, 'AddAdminMenu') );
			if(! empty($this->admin_subpages)){
				add_action( 'admin_menu', array( $this, 'AddAdminSubMenu') );
			}
		}
	}

	public function AddPage( array $pages)	{

		$this->admin_pages = $pages;

		return $this;
	}

	public function AddSubPage( array $subpages)	{

		$this->admin_subpages = $subpages;

		return $this;
	}

	public function AddAdminMenu(){
		foreach( $this->admin_pages as $page ){
			add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
		}
	}

	public function AddAdminSubMenu(){
		foreach( $this->admin_subpages as $subpage ){
			add_submenu_page( $subpage['parent_slug'], $subpage['page_title'], $subpage['menu_title'], $subpage['capability'], $subpage['menu_slug'], $subpage['function'] );
		}
	}
}