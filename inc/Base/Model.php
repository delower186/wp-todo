<?php
/**
* @package wptodo
*/
namespace Inc\Base;

use \Inc\Base\BaseController;
use \Inc\Pages\Admin;

class Model extends BaseController
{
	private static $wpdb;

	public function __construct(){
		global $wpdb;
		self::$wpdb = $wpdb;
	}
/**
 * Creating database tables
 */
	public static function wptodo_install() {
		// where and what we will store - db structure
		$wptodo_table = self::$wpdb->prefix . "wptodo";
		$wptodo_comments_table = self::$wpdb->prefix . "wptodo_comments";
		$wptodo_structure = "
		CREATE TABLE IF NOT EXISTS `$wptodo_table` (
			`id` BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
			`date` DATE NOT NULL ,
			`title` TEXT NOT NULL ,
			`desc` TEXT NOT NULL ,
			`from` BIGINT( 20 ) UNSIGNED NOT NULL ,
			`for` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT '0',
			`until` DATE NOT NULL ,
			`status` TINYINT( 1 ) NOT NULL DEFAULT '0',
			`priority` TINYINT( 1 ) NOT NULL DEFAULT '0',
			`notify` BINARY NOT NULL DEFAULT '0',
			PRIMARY KEY ( `id` )
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";
		
		$wptodo_comments_structure = "
		CREATE TABLE IF NOT EXISTS `$wptodo_comments_table` (
			`id` BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
			`date` DATE NOT NULL ,
			`task` BIGINT( 20 ) UNSIGNED NOT NULL ,
			`body` TEXT NOT NULL ,
			`from` BIGINT( 20 ) UNSIGNED NOT NULL ,
			PRIMARY KEY ( `id` )
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci";
		
		// Sending all this to mysql queries
		self::$wpdb->query($wptodo_structure);
		self::$wpdb->query($wptodo_comments_structure);
		$today_date = gmdate('Y-m-d');
	}
	/**
	 * Users id -> nicename
	 */
	public static function wptodo_from($raw_from) {
		if(is_int($raw_from) && ($raw_from != '0')) {
			$from = get_userdata($raw_from);
			return $from->display_name;
		}
		else if (is_string($raw_from)) {
			$from = get_userdata($raw_from);
			return $from->ID;
		}
		else return "Nobody";
	}

	/**
	 * Users email
	 */
	public static function wptodo_to(int $id) {
		if(is_int($id) && ($id != '0')) {
			$to = get_userdata($id);
			return $to->user_email;
		}
	}

	/**
	 * send email
	 */
	public static function wptodo_email(){
		$to = self::wptodo_to($_POST['wptodo_for']);
		$subject = __('New Task','wptodo');
		$message = __( 'You are asigned to a new task!', 'wptodo' );
		wp_mail( $to, $subject, $message );
	}
	/**
	 * Displaying a nicer date
	 */
	public static function wptodo_date($raw_date) {
		if($raw_date != "0000-00-00") {
			return mysql2date(get_option('date_format'), $raw_date); //Let's use wordpress prefered date settings
		}
		else return "Not set";
	}

	/**
	 * Displaying a nicer status
	 */
	public static function wptodo_status($raw_status) {
		switch ($raw_status) {
		default: return "New";
		//case 1: return "New";
		case 2: return "Open";
		case 3: return "Buggy";
		case 4: return "Solved";
		case 5: return "Closed";
		}
	}

	/**
	 * Displaying a nicer priority
	 */
	public static function wptodo_priority($raw_priority) {
		switch ($raw_priority) {
		default: return "Low";
		//case 1: return "Low";
		case 2: return "Normal";
		case 3: return "High";
		case 4: return "Important";
		}
	}

	/**
	 * Displaying a nicer notice
	 */
	public static function wptodo_notice($raw_notice) {
		switch ($raw_notice) {
		default: return "No";
		case 1: return "Yes";
		}
	}

	/**
	 * Add a task to db
	 */
	public static function wptodo_addtask(array $newdata) {
		$wptodo_table = self::$wpdb->prefix . "wptodo";
		$today_date = gmdate('Y-m-d');
		$wptodo_query = "INSERT INTO `".$wptodo_table."` (`id`, `date`, `title`, `desc`, `from`, `for`, `until`,`status`,`priority`,`notify`)VALUES (NULL , '$today_date', '".$newdata['wptodo_title']."','".$newdata['wptodo_description']."','".$newdata['wptodo_from']."','".$newdata['wptodo_for']."','".$newdata['wptodo_deadline']."','".$newdata['wptodo_status']."','".$newdata['wptodo_priority']."','".!empty($newdata['wptodo_notify'])."')";
		self::$wpdb->query($wptodo_query);
		self::wptodo_email();
	}

	/**
	 * Update a task
	 */
	public static function wptodo_updatetask(array $newdata) {
		$wptodo_table = self::$wpdb->prefix . "wptodo";
		$wptodo_query = "UPDATE `".$wptodo_table."` SET `title`='".$newdata['wptodo_title']."', `desc`='".$newdata['wptodo_description']."', `for`='".$newdata['wptodo_for']."', `until`='".$newdata['wptodo_deadline']."', `status`='".$newdata['wptodo_status']."', `priority`='".$newdata['wptodo_priority']."', `notify`='".!empty($newdata['wptodo_notify'])."' WHERE `id`='".$newdata['wptodo_taskid']."'";
		self::$wpdb->query($wptodo_query);
		self::wptodo_email();

		echo '<script>window.location.href="admin.php?page=wp-todo"</script>';
	}
	/**
	 * Delete a task
	 */
	public static function wptodo_deletetask(int $id) {
		if(isset($id)){
			$wptodo_table = self::$wpdb->prefix . "wptodo";
			$wptodo_comments_table = self::$wpdb->prefix . "wptodo_comments";
			$q = self::$wpdb->query("DELETE FROM `".$wptodo_table."` WHERE `id`=$id");
			self::$wpdb->query("DELETE FROM `".$wptodo_comments_table."` WHERE `task`=$id");
			echo '<script>window.location.href="admin.php?page=wp-todo"</script>';
		}
	}
	/**
	 * Add a comment
	 */
	public static function wptodo_addcomment(array $newdata) {
		$wptodo_comments_table = self::$wpdb->prefix . "wptodo_comments";
		$today_date = gmdate('Y-m-d');
		self::$wpdb->query("INSERT INTO $wptodo_comments_table(`id`, `date`, `task`, `body`, `from`)
		VALUES(NULL, '$today_date', '".$newdata['wptodo_comment_task']."', '".$newdata['wptodo_comment_body']."', '".$newdata['wptodo_comment_author']."')");
	}
	/**
	 * Edit a task
	 */
	public static function wptodo_edit(int $id) {
		if(isset($id) && !empty($id)){
			$wptodo_table = self::$wpdb->prefix . "wptodo";
			$wptodo_edit_item = self::$wpdb->get_results("SELECT * FROM `$wptodo_table` WHERE `id`=$id");
			if(!$wptodo_edit_item) {
				echo'<div class="wrap"><h2>There is no such task to edit. Please add one first.</h2></div>';
			}
			else {
				require_once(parent::$plugin_path . 'templates/edit_task.php');
		 	}
		}
	}
	/**
	 * View a task
	 */
	public static function wptodo_view(int $id) {
		if(isset($id) && !empty($id)){
			$wptodo_table = self::$wpdb->prefix . "wptodo";
			$wptodo_comments_table = self::$wpdb->prefix . "wptodo_comments";
			$wptodo_view_item = self::$wpdb->get_results("SELECT * FROM `$wptodo_table` WHERE `id`=$id");
			$wptodo_view_item_comments = self::$wpdb->get_results("SELECT * FROM `$wptodo_comments_table` WHERE `task`=$id");
			if(!$wptodo_view_item) {
				echo'<div class="wrap"><h2>There is no such task to view. Please add one first.</h2></div>';
			}else{
				require_once(parent::$plugin_path . 'templates/view_task.php');
			}
		}
	}
	/**
	 * Main admin page
	 */
	public static function wptodo_manage_main(/*$wptodo_filter_status*/) {
		$wptodo_table = self::$wpdb->prefix . "wptodo";
		require_once(parent::$plugin_path . 'templates/admin.php');
	}
	/**
	 * Admin CP manage page
	 */
	public static function wptodo_manage() {
		$wptodo_table = self::$wpdb->prefix . "wptodo";
		if(isset($_POST['wptodo_addtask']) && isset($_POST['wptodo_title'])) self::wptodo_addtask($_POST); //If we have a new task let's add it
		if(isset($_POST['wptodo_updatetask'])) self::wptodo_updatetask($_POST); //Update my task
		if(isset($_POST['wptodo_comment_task'])) self::wptodo_addcomment($_POST); //Add comments to tasks
		//if(isset($_POST['wptodo_filter_status']) != NULL) self::wptodo_manage_main($_POST['wptodo_filter_status']); 
		if(isset($_POST['wptodo_deletetask'])) self::wptodo_deletetask($_POST['wptodo_taskid']); //Update my task
		if(isset($_GET['view'])) self::wptodo_view($_GET['view']);
		else if(isset($_GET['edit'])) self::wptodo_edit($_GET['edit']);
		else self::wptodo_manage_main();
	}
	public static function wptodo_settings(){
		require_once(parent::$plugin_path . 'templates/settings.php');
	}
	//vew taks
	public static function wptodo_edit_task(int $id){
		$edit = '';
		//$role = Admin::get_role();
		//if($role == 'administrator' || $role == 'editor'){
			$edit = '(<a href="?page=wp-todo&edit='.$id.'">Edit</a>)';
		//}
		return $edit;
	}
	public static function wptodo_tasks(){
		$wptodo_table = self::$wpdb->prefix . "wptodo";
		$wptodo_manage_items = self::$wpdb->get_results("SELECT * FROM $wptodo_table ORDER BY `priority` DESC");
		$wptodo_counted = count($wptodo_manage_items);
			$num = 0;
				while($num != $wptodo_counted) {
					switch ($wptodo_manage_items[$num]->status) {
						case 4:
								echo "<tr class= 'solved'>";
							  	echo "<td>".$wptodo_manage_items[$num]->id."</td>";
							  	echo "<td><span style=\"float:right; display: inline;\">".self::wptodo_edit_task($wptodo_manage_items[$num]->id)."</span><a class='solved' href=\"?page=wp-todo&view=".$wptodo_manage_items[$num]->id."\">".$wptodo_manage_items[$num]->title."</a></td>";
							break;
						case 5:
								echo "<tr class= 'closed'>";
							  	echo "<td>".$wptodo_manage_items[$num]->id."</td>";
							  	echo "<td><span style=\"float:right; display: inline;\">".self::wptodo_edit_task($wptodo_manage_items[$num]->id)."</span><a class='closed' href=\"?page=wp-todo&view=".$wptodo_manage_items[$num]->id."\">".$wptodo_manage_items[$num]->title."</a></td>";
							break;
						default:
							echo "<tr>";
						  	echo "<td>".$wptodo_manage_items[$num]->id."</td>";
						  	echo "<td><span style=\"float:right; display: inline;\">".self::wptodo_edit_task($wptodo_manage_items[$num]->id)."</span><a href=\"?page=wp-todo&view=".$wptodo_manage_items[$num]->id."\">".$wptodo_manage_items[$num]->title."</a></td>";
							break;
					}

				  	echo "<td>".self::wptodo_from((int)$wptodo_manage_items[$num]->from)."</td>"; //we have to send int not strings
				  	echo "<td>".self::wptodo_from((int)$wptodo_manage_items[$num]->for)."</td>";
					echo "<td>".self::wptodo_date($wptodo_manage_items[$num]->date)."</td>";
				  	echo "<td>".self::wptodo_date($wptodo_manage_items[$num]->until)."</td>";
				  	echo "<td>".self::wptodo_status($wptodo_manage_items[$num]->status)."</td>";
				  	echo "<td>".self::wptodo_priority($wptodo_manage_items[$num]->priority)."</td>";
				  	echo "<td>".self::wptodo_notice($wptodo_manage_items[$num]->notify)."</td>";
				  	echo "</tr>";
				  	echo "";
				  	$num++;
				}
	}	
}