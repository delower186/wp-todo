<div class="container">
		<br>
		<h2><?php _e("Task #$id", 'wptodo'); ?></h2>
		<br>
		<div class="clock" id="timer"></div>
		<br>
		<div class="row">

	      		<form action="" method="post">
					<input name="wptodo_updatetask" id="wptodo_updatetask" value="true" type="hidden" />
					<input name="wptodo_taskid" id="wptodo_taskid" value="<?php echo $id; ?>" type="hidden" />
					<div class="form-group">
						<label class="font-weight-bold" for="wptodo_title">Title:</label>
						<input name="wptodo_title" id="wptodo_title" value="<?php echo $wptodo_edit_item['0']->title; ?>" type="text" class="form-control"/>
					</div>
					<div class="form-group">
						<label class="font-weight-bold" for="wptodo_description">Description:</label>
						<textarea name="wptodo_description" id="wptodo_description" class="form-control" rows="5" cols="40"><?php echo $wptodo_edit_item['0']->desc; ?></textarea>
					</div>
					<div class="form-group">
						<label class="font-weight-bold" for="wptodo_date">Since:</label><br>
						<h6 class="text-danger"><?php echo $wptodo_edit_item['0']->date; ?> (<?php echo self::wptodo_date($wptodo_edit_item['0']->date); ?>)</h6>
					</div>
					<div class="form-group">
						<label class="font-weight-bold" for="wptodo_deadline">Deadline:</label>
						<input name="wptodo_deadline" id="wptodo_deadline" class="form-control" value="<?php echo $wptodo_edit_item['0']->until; ?>" type="date" />
					</div>
					<div class="form-group">
						<label class="font-weight-bold " for="wptodo_for">Assigned to:</label>
						<?php $for=$wptodo_edit_item['0']->for; wp_dropdown_users("name=wptodo_for&selected=$for&class=form-control"); ?>
						</select>
					</div>
					<div class="form-group">
						<label class="font-weight-bold" for="wptodo_status">Status:</label>
				  			<select name="wptodo_status" id="wptodo_status" class="form-control">
								<option value="1" <?php if ($wptodo_edit_item['0']->status == 1) echo "selected=\"selected\""; ?> >New</option>
								<option value="2" <?php if ($wptodo_edit_item['0']->status == 2) echo "selected=\"selected\""; ?> >Open</option>
								<option value="3" <?php if ($wptodo_edit_item['0']->status == 3) echo "selected=\"selected\""; ?> >Buggy</option>
								<option value="4" <?php if ($wptodo_edit_item['0']->status == 4) echo "selected=\"selected\""; ?> >Solved</option>
								<option value="5" <?php if ($wptodo_edit_item['0']->status == 5) echo "selected=\"selected\""; ?> >Closed</option>
							</select>
					</div>
					<div class="form-group">
						<label class="font-weight-bold" for="wptodo_priority">Priority:</label>
			  			<select name="wptodo_priority" id="wptodo_priority" class="form-control">
							<option value="1" <?php if ($wptodo_edit_item['0']->priority == 1) echo "selected=\"selected\""; ?> >Low</option>
							<option value="2" <?php if ($wptodo_edit_item['0']->priority == 2) echo "selected=\"selected\""; ?> >Normal</option>
							<option value="3" <?php if ($wptodo_edit_item['0']->priority == 3) echo "selected=\"selected\""; ?> >High</option>
							<option value="4" <?php if ($wptodo_edit_item['0']->priority == 4) echo "selected=\"selected\""; ?> >Important</option>
						</select>
					</div>
					<div class="form-group">
						<label class="font-weight-bold" for="wptodo_notify">Send alerts through email?</label>
						<input name="wptodo_notify" id="wptodo_notify" class="form-control" value="1" <?php if ($wptodo_edit_item['0']->notify == 1) echo "checked=\"checked\""; ?> type="checkbox" />
					</div>
					<input name="Submit" class="btn btn-primary btn-sm" style="margin-left: 15px" value="Update" type="submit"/>
	      		</form>
		</div>
			<form action="" method="post">
				<input name="wptodo_taskid" id="wptodo_taskid" value="<?php echo $id; ?>" type="hidden" />
					<?php
						$delete = '<input name="wptodo_deletetask" class="btn btn-danger btn-sm" style="margin-left: 80px; margin-top: -60px" value="Delete" type="submit" />';
						\Inc\Pages\Admin::wptodo_delete_button($delete);
						\Inc\Pages\Admin::wptodo_cancel();
					?>
				<input name="cancel" class="btn btn-success btn-sm" style="margin-left: 15px; margin-top: -60px" value="Cancel" type="submit"/>
			</form>
	</div>
	<?php \Inc\Pages\Admin::wptodo_countdown_timer($wptodo_edit_item['0']->until,$wptodo_edit_item['0']->status); ?>