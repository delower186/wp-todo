<div id="editForm">
	<br>
	<h2><?php _e("Task #$id", 'wptodo'); ?></h2>
	<br>
	<div class="clock" id="timer"></div>
	<br>

	<form id="edit_form" action="" method="post">
		<input name="wptodo_updatetask" id="wptodo_updatetask" value="true" type="hidden" />
		<input name="wptodo_taskid" id="wptodo_taskid" value="<?php echo $id; ?>" type="hidden" />
		<table>
			<tr>
				<td><label for="wptodo_title">Title:</label></td>
				<td><input name="wptodo_title" id="wptodo_title" value="<?php echo $wptodo_edit_item['0']->title; ?>" type="text" /></td>
			</tr>
			<tr>
				<td><label for="wptodo_description">Description:</label></td>
				<td><textarea name="wptodo_description" id="wptodo_description" rows="5" cols="40"><?php echo $wptodo_edit_item['0']->desc; ?></textarea></td>
			</tr>
			<tr>
				<td><label for="wptodo_date">Since:</label><br></td>
				<td>
					<h6 class="ui-state-error"><?php echo $wptodo_edit_item['0']->date; ?> (<?php echo self::wptodo_date($wptodo_edit_item['0']->date); ?>)</h6>
				</td>
			</tr>
			<tr>
				<td><label for="wptodo_deadline">Deadline:</label></td>
				<td><input name="wptodo_deadline" id="wptodo_deadline" value="<?php echo $wptodo_edit_item['0']->until; ?>" type="date" /></td>
			</tr>
			<tr>
				<td><label for="wptodo_for">Assigned to:</label></td>
				<td> <?php $for = $wptodo_edit_item['0']->for;
						wp_dropdown_users("name=wptodo_for&selected=$for"); ?>
					</select></td>
			</tr>
			<tr>
				<td><label for="wptodo_status">Status:</label></td>
				<td> <select name="wptodo_status" id="wptodo_status">
						<option value="1" <?php if ($wptodo_edit_item['0']->status == 1) echo "selected=\"selected\""; ?>>New</option>
						<option value="2" <?php if ($wptodo_edit_item['0']->status == 2) echo "selected=\"selected\""; ?>>Open</option>
						<option value="3" <?php if ($wptodo_edit_item['0']->status == 3) echo "selected=\"selected\""; ?>>Buggy</option>
						<option value="4" <?php if ($wptodo_edit_item['0']->status == 4) echo "selected=\"selected\""; ?>>Solved</option>
						<option value="5" <?php if ($wptodo_edit_item['0']->status == 5) echo "selected=\"selected\""; ?>>Closed</option>
					</select></td>
			</tr>
			<tr>
				<td><label for="wptodo_priority">Priority:</label></td>
				<td> <select name="wptodo_priority" id="wptodo_priority">
						<option value="1" <?php if ($wptodo_edit_item['0']->priority == 1) echo "selected=\"selected\""; ?>>Low</option>
						<option value="2" <?php if ($wptodo_edit_item['0']->priority == 2) echo "selected=\"selected\""; ?>>Normal</option>
						<option value="3" <?php if ($wptodo_edit_item['0']->priority == 3) echo "selected=\"selected\""; ?>>High</option>
						<option value="4" <?php if ($wptodo_edit_item['0']->priority == 4) echo "selected=\"selected\""; ?>>Important</option>
					</select></td>
			</tr>
			<tr>
				<td><label for="wptodo_notify">Send alerts through email?</label></td>
				<td><input name="wptodo_notify" id="wptodo_notify" class="form-control" value="1" <?php if ($wptodo_edit_item['0']->notify == 1) echo "checked=\"checked\""; ?> type="checkbox" /></td>
			</tr>
		</table>
		<!-- table starts -->
		<table>
			<tbody>
				<tr style="border: 0">
					<td style="border: 0">
						<input name="Submit" value="Update" type="submit" />
	</form>
	</td>
	<td style="border: 0">
		<form action="" method="post">
			<input name="wptodo_taskid" id="wptodo_taskid" value="<?php echo $id; ?>" type="hidden" />
			<?php
			$delete = '<input name="wptodo_deletetask" value="Delete" type="submit" />';
			\Inc\Pages\Admin::wptodo_delete_button($delete);
			\Inc\Pages\Admin::wptodo_cancel();
			?>
	</td>
	<td style="border: 0">
		<input name="cancel" value="Cancel" type="submit" />
		</form>
	</td>
	</tr>
	</tbody>
	</table>
	<!-- table ends -->
</div>
<?php \Inc\Pages\Admin::wptodo_countdown_timer($wptodo_edit_item['0']->until, $wptodo_edit_item['0']->status); ?>