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

	public function __construct()
	{
		$this->settings = new SettingsApi();
		add_shortcode('wp-todo', array($this, 'wptodo_short_main'));
	}

	public function register()
	{

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
			array(
				'parent_slug' => 'wp-todo',
				'page_title' => __('Settings', 'wptodo'),
				'menu_title' => __('Settings', 'wptodo'),
				'capability' => 'manage_options',
				'menu_slug' =>  'wptodo_settings',
				'function' =>  array($this, 'wptodo_settings')
			)
		);
		$this->settings->AddPage($this->pages)->register();
		$this->settings->AddSubPage($this->subpages)->register();
	}

	public function wptodo_manage()
	{
		Model::wptodo_manage();
	}

	public function wptodo_settings()
	{
		Model::wptodo_settings();
	}

	public static function get_role()
	{
		$current_user = wp_get_current_user();
		foreach ($current_user->roles as $role) {
			if ($role = "administrator" || $role = "editor") { return $role; }
		}
		return $role;
	}

	public static function get_user_id()
	{
		$current_user = wp_get_current_user();
		return $current_user->ID;
	}

	public static function wptodo_add_form()
	{
		$role = self::get_role();
		if ($role == 'administrator' || $role == 'editor') {
			require_once(parent::$plugin_path . 'templates/add_task.php');
		} else {
			echo '<div class="narrow"></div>';
		}
	}

	public static function wptodo_add_button()
	{
		$role = self::get_role();
		if ($role == 'administrator' || $role == 'editor') {
			echo '<button class="addTask" type="button" id="addTask-button">Add Task</button><br><br>';
		}
	}

	public static function wptodo_delete_button($delete)
	{
		$role = self::get_role();
		if ($role == 'administrator') {
			echo $delete;
		}
	}

	public function wptodo_short_main()
	{
		return Model::wptodo_manage();
	}

	// redirect to tasks
	public static function wptodo_cancel()
	{
		if (isset($_POST['cancel'])) {
			echo '<script>window.location.href="?page=wp-todo"</script>';
		}
	}

	//countdown timer
	public static function wptodo_countdown_timer($item, $status)
	{
		$now = date('Y-m-d H:i:s');
		$deadline = $item;
		$timefirst = strtotime($now);
		$timesecond = strtotime($deadline);
		$difference = $timesecond - $timefirst;
?>
		<script type="text/javascript">
			//countdown timer
			jQuery(document).ready(function() {
				var clock = jQuery('#timer').FlipClock(<?php echo $difference; ?>, {
					clockFace: 'DailyCounter',
					countdown: true
				});
				if (0 > <?php echo $difference; ?>) {
					clock.setTime(0);
					jQuery("#timer").replaceWith(function(n) {
						return '<div class="danger"> <strong class="bold">Danger!</strong> This Task is OverDue </div>';
					});
				} else if (<?php echo $status; ?> == 5) {
					clock.setTime(0);
					jQuery("#timer").replaceWith(function(n) {
						return '<div class="info"> <strong class="bold">Info!</strong> This Task is Closed </div>';
					});
				} else if (<?php echo $status; ?> == 4) {
					clock.setTime(0);
					jQuery("#timer").replaceWith(function(n) {
						return '<div class="success"> <strong class="bold">Success!</strong> This Task is Solved </div>';
					});
				}
			});
		</script>
<?php
	}
}
