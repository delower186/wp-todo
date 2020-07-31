<?php
/**
*
* Trigger this file on Plugin uninstall
*
* @package wptodo
*/

if(! defined('WP_UNINSTALL_PLUGIN')){
	die;
}
global $wpdb;
$wptodo_table = $wpdb->prefix . "wptodo";
$wptodo_comments_table = $wpdb->prefix . "wptodo_comments";
$wptodo_email_table = $wpdb->prefix . "wptodo_email";
$tables = array($wptodo_table,$wptodo_comments_table, $wptodo_email_table);
	foreach ($tables as $table) {
		$wpdb->query("DROP TABLE IF EXISTS `$table`");
	}