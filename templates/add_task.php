<h3 id="addnewtodo"><?php _e("Add New Task", "wptodo"); ?></h3>
			<div class="narrow">
				<form name="addnewtask" id="addnewtask" action="" method="post">
					<input name="wptodo_addtask" id="wptodo_addtask" value="true" type="hidden" required/>
					<input name="wptodo_from" id="wptodo_from" value="<?php echo self::get_user_id(); ?>" type="hidden" required/>
					<table>
					<tbody><tr>
						<th width="33%"><label for="wptodo_title">Title:</label></th>
						<td width="67%"><input name="wptodo_title" id="wptodo_title" value="" size="38" type="text" required/></td>
					</tr>
					<tr>
						<th><label for="wptodo_description">Description:</label></th>
						<td><textarea name="wptodo_description" id="wptodo_description" rows="5" cols="40" required></textarea></td>
					</tr>
					<tr>
						<th width="33%"><label for="wptodo_deadline">Deadline:</label></th>
						<td width="67%"><input name="wptodo_deadline" id="wptodo_deadline" value="0000-00-00" type="date" required/></td>
					</tr>
					<tr>
						<th width="33%"><label for="wptodo_for">Assigned to:</label></th>
						<td width="67%">
								<?php wp_dropdown_users("name=wptodo_for&selected="); ?>
							</select>
						</td>
					</tr>
					<tr>
					<th><label for="wptodo_status">Status:</label></th>
						<td>
			  			<select name="wptodo_status" id="wptodo_status" class="postform" required>
						<option value="1">New</option>
						<option value="2">Open</option>
						<option value="3">Buggy</option>
						<option value="4">Solved</option>
						<option value="5">Closed</option>
						</select>
			  			</td>
					</tr>
					<th><label for="wptodo_priority">Priority:</label></th>
						<td>
			  			<select name="wptodo_priority" id="wptodo_priority" class="postform" required>
						<option value="1">Low</option>
						<option value="2">Normal</option>
						<option value="3">High</option>
						<option value="4">Important</option>
						</select>
			  			</td>
					</tr>
					<th><label for="wptodo_notify">Send alerts through email?</label></th>
						<td>
							<input name="wptodo_notify" id="wptodo_notify" value="1" type="checkbox" />
						</td>
					</tr>
					</tbody></table>
					<p class="submit"><input class="button button-primary" name="submit" value="Add Task" type="submit"></p>
				</form>
			</div>