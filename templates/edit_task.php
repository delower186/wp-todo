	<div class="wrap">
		<h2><?php _e("Edit task #$id", 'wptodo'); ?></h2>
		<div class="narrow">
		<form name="updatetask" id="updatetask" action="" method="post">
			<input name="wptodo_updatetask" id="wptodo_updatetask" value="true" type="hidden" />
			<input name="wptodo_taskid" id="wptodo_taskid" value="<?php echo $id; ?>" type="hidden" />
			<table>
			<tbody><tr>
				<th width="33%"><label for="wptodo_title">Title:</label></th>
				<td width="67%"><input name="wptodo_title" id="wptodo_title" value="<?php echo $wptodo_edit_item['0']->title; ?>" size="40" type="text" /></td>
			</tr>
			<tr>
				<th><label for="wptodo_description">Description:</label></th>
				<td><textarea name="wptodo_description" id="wptodo_description" rows="5" cols="40"><?php echo $wptodo_edit_item['0']->desc; ?></textarea></td>
			</tr>
			<tr>
				<th width="33%"><label for="wptodo_date">Since:</label></th>
				<td width="67%"><?php echo $wptodo_edit_item['0']->date; ?> (<?php echo self::wptodo_date($wptodo_edit_item['0']->date); ?>)</td>
			</tr>
			<tr>
				<th width="33%"><label for="wptodo_deadline">Deadline:</label></th>
				<td width="67%"><input name="wptodo_deadline" id="wptodo_deadline" value="<?php echo $wptodo_edit_item['0']->until; ?>" type="date" /></td>
			</tr>
			<tr>
				<th width="33%"><label for="wptodo_for">Assigned to:</label></th>
				<td width="67%">
						<?php $for=$wptodo_edit_item['0']->for; wp_dropdown_users("name=wptodo_for&selected=$for"); ?>
					</select>
				</td>
			</tr>
			<tr>
			<th><label for="wptodo_status">Status:</label></th>
				<td>
	  			<select name="wptodo_status" id="wptodo_status" class="postform">
				<option value="1" <?php if ($wptodo_edit_item['0']->status == 1) echo "selected=\"selected\""; ?> >New</option>
				<option value="2" <?php if ($wptodo_edit_item['0']->status == 2) echo "selected=\"selected\""; ?> >Open</option>
				<option value="3" <?php if ($wptodo_edit_item['0']->status == 3) echo "selected=\"selected\""; ?> >Buggy</option>
				<option value="4" <?php if ($wptodo_edit_item['0']->status == 4) echo "selected=\"selected\""; ?> >Solved</option>
				<option value="5" <?php if ($wptodo_edit_item['0']->status == 5) echo "selected=\"selected\""; ?> >Closed</option>
				</select>
	  			</td>
			</tr>
				<th><label for="wptodo_priority">Priority:</label></th>
				<td>
	  			<select name="wptodo_priority" id="wptodo_priority" class="postform">
				<option value="1" <?php if ($wptodo_edit_item['0']->priority == 1) echo "selected=\"selected\""; ?> >Low</option>
				<option value="2" <?php if ($wptodo_edit_item['0']->priority == 2) echo "selected=\"selected\""; ?> >Normal</option>
				<option value="3" <?php if ($wptodo_edit_item['0']->priority == 3) echo "selected=\"selected\""; ?> >High</option>
				<option value="4" <?php if ($wptodo_edit_item['0']->priority == 4) echo "selected=\"selected\""; ?> >Important</option>
				</select>
	  			</td>
			</tr>
			<tr>
			<th><label for="wptodo_notify">Send alerts through email?</label></th>
				<td>
					<input name="wptodo_notify" id="wptodo_notify" value="1" <?php if ($wptodo_edit_item['0']->notify == 1) echo "checked=\"checked\""; ?> type="checkbox" />
				</td>
			</tr>
			</tbody></table>
			<p class="submit">
				<?php
					$delete = '<input name="wptodo_deletetask" class="button button-primary" value="Delete Task" type="submit" />';
					\Inc\Pages\Admin::wptodo_delete_button($delete);
				?>
				<input name="Submit" class="button button-primary" value="Update Task" type="submit" />
			</p>
		</form>
		</div>
	</div>