<?php
/**
* @package wptodo
*/

namespace Inc;

final class Init
{
	
	private static function get_services(){
		return [
			Pages\Admin::class,
			Base\Enqueue::class,
			Base\SettingLinks::class,
			Base\Model::class
		];
	}

	public static function register_services(){
		foreach( self::get_services() as $class){
			$service = self::instantiate( $class );
			if( method_exists($service, 'register') ){
				$service->register();
			}
		}
	}

	private static function instantiate( $class ){
		$service = new $class();
		return $service;
	}
}