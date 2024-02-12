<br>
<h2><?php _e("Task #$id", 'wptodo'); ?></h2>
<br>
<div class="clock" id="timer"></div>
<br>
<h5 class="title"><?php echo $wptodo_view_item['0']->title; ?></h5>
<p class="desc"><?php echo $wptodo_view_item['0']->desc; ?></p>
<p>By <strong><?php echo self::wptodo_from((int) $wptodo_view_item['0']->from); ?></strong> on <em><?php echo self::wptodo_date($wptodo_view_item['0']->date); ?></em> Deadline <em><?php echo self::wptodo_date($wptodo_view_item['0']->until); ?></em>, currently assigned to <em><strong><?php echo self::wptodo_from((int) $wptodo_view_item['0']->for); ?></strong></em></p>
<br>
<?php
if ($wptodo_view_item_comments) {
	echo "<h6>Comments</h6>";
	$wptodo_counted = count($wptodo_view_item_comments);
	$c = 0;
	echo '<p><ol>';
	while ($c != $wptodo_counted) {
		echo '<li><p>' . $wptodo_view_item_comments["$c"]->body . '</p>
									<small>On ' . self::wptodo_date($wptodo_view_item_comments["$c"]->date) . ' by ' . self::wptodo_from((int) $wptodo_view_item_comments["$c"]->from) . '</small></li>';
		$c++;
	}
	echo '</ol></p>';
}
\Inc\Pages\Admin::wptodo_cancel();
?>
<br>
<form action="" id="wptodo_addnewcomment" method="post">
	<input name="wptodo_comment_author" type="hidden" value="<?php echo \Inc\Pages\Admin::get_user_id(); ?>" />
	<input name="wptodo_comment_task" type="hidden" value="<?php echo $wptodo_view_item['0']->id; ?>" />
	<!--table starts-->
	<table>
		<tr>
			<td><textarea cols="40" rows="5" name="wptodo_comment_body" id="wptodo_comment_body" placeholder="Add a Comment" required></textarea></td>
		</tr>
		<tr>
			<td>
				<input name="wptodo_comment_submit" id="wptodo_comment_submit" value="Add" type="submit">
			</td>
</form>
<td>
	<form action="" method="post">
		<input name="cancel" value="Cancel" type="submit" />
	</form>
</td>
</tr>
</table>
<!--table Ends-->
<?php
\Inc\Pages\Admin::wptodo_countdown_timer(self::wptodo_date($wptodo_view_item['0']->until), $wptodo_view_item['0']->status);
if (isset($_POST['wptodo_comment_submit'])) {
	\Inc\Base\Model::wptodo_email($wptodo_view_item['0']->from, $wptodo_view_item['0']->for);
}
?>