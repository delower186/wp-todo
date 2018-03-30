<div class="wrap">
			<h2><?php _e("View task #$id", 'wptodo'); ?></h2>
			<div class="narrow">
				<p><h3><?php echo $wptodo_view_item['0']->title; ?></h3></p>
				<p class="alternate"><?php echo $wptodo_view_item['0']->desc; ?></p>
				<p><small>By <strong><?php echo self::wptodo_from((int)$wptodo_view_item['0']->from); ?></strong> on <em><?php echo self::wptodo_date($wptodo_view_item['0']->date); ?></em> until <em><?php echo self::wptodo_date($wptodo_view_item['0']->until); ?></em>, currently assigned to <em><strong><?php echo self::wptodo_from((int)$wptodo_view_item['0']->for); ?></strong></em></small></p>
				<?php if($wptodo_view_item_comments) { 
						echo "<h3>Comments</h3>"; 
						$wptodo_counted = count($wptodo_view_item_comments);
						$c = 0;
						echo '<p><ol>';
						while ($c != $wptodo_counted) {
							echo '<li><p>'.$wptodo_view_item_comments["$c"]->body.'</p>
							<small>On '.self::wptodo_date($wptodo_view_item_comments["$c"]->date).' by '.self::wptodo_from((int)$wptodo_view_item_comments["$c"]->from).'</small></li>';
							$c++;
						}
						echo '</ol></p>';
				}?>
				<h3>Submit a comment</h3>
				<form action="" id="wptodo_addnewcomment" method="post">
					<input name="wptodo_comment_author" type="hidden" value="<?php echo \Inc\Pages\Admin::get_user_id(); ?>" />
					<input name="wptodo_comment_task" type="hidden" value="<?php echo $wptodo_view_item['0']->id; ?>" />
					<p><textarea cols="40" rows="5" name="wptodo_comment_body" id="wptodo_comment_body" required></textarea></p>
					<p class="submit"><input name="wptodo_comment_submit" class="button button-primary" id="wptodo_comment_submit" value="Add Comment" type="submit">
				</form>
			</div>
		</div>