<div id="addForm" title="Add New Task" style="background-color: #F6F6F6;">
	<form action="" method="post">
		<input name="wptodo_addtask" id="wptodo_addtask" value="true" type="hidden" />
		<input name="wptodo_from" id="wptodo_from" value="<?php echo self::get_user_id(); ?>" type="hidden" />
		<table>
			<tr>
				<td><label for="wptodo_title">Title:</label></td>
				<td><input type="text" name="wptodo_title" id="wptodo_title" placeholder="Enter Title" required /></td>
			</tr>
			<tr>
				<td><label for="wptodo_description">Description:</label></td>
				<td><textarea name="wptodo_description" id="wptodo_description" placeholder="Enter Description" required></textarea></td>
			</tr>
			<tr>
				<td><label for="wptodo_deadline">Deadline:</label></td>
				<td><input name="wptodo_deadline" id="wptodo_deadline" value="yyyy-MM-dd" type="date" required /></td>
			</tr>
			<tr>
				<td><label for="wptodo_for">Assigned to:</label></td>
				<td><?php wp_dropdown_users("name=wptodo_for&seleted"); ?> </select></td>
			</tr>
			<tr>
				<td><label for="wptodo_status">Status:</label></td>
				<td>
					<select name="wptodo_status" id="wptodo_status" required>
						<option value="1">New</option>
						<option value="2">Open</option>
						<option value="3">Buggy</option>
						<option value="4">Solved</option>
						<option value="5">Closed</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="wptodo_priority">Priority:</label></td>
				<td>
					<select name="wptodo_priority" id="wptodo_priority" required>
						<option value="1">Low</option>
						<option value="2">Normal</option>
						<option value="3">High</option>
						<option value="4">Important</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="wptodo_notify">Send alerts</label></td>
				<td><input name="wptodo_notify" id="wptodo_notify" value="1" type="checkbox" /></td>
			</tr>
			<!-- <tr>
				<td><button name="submit" type="submit" value="Add Task">Submit</button></td>
			</tr> -->
		</table>
	</form>
</div>
<script>
	jQuery(document).ready(function() {
		var dialog, form;

		form = jQuery("form");

		function formSubmit() {
			var errors;
			if (!jQuery("#wptodo_title").val()) {
				jQuery("#wptodo_title").addClass('ui-state-error');
				errors = 1;
			} else {
				jQuery("#wptodo_title").removeClass('ui-state-error');
				errors = 0;
			}

			if (!jQuery("#wptodo_description").val()) {
				jQuery("#wptodo_description").addClass('ui-state-error');
				errors = 1;
			} else {
				jQuery("#wptodo_description").removeClass('ui-state-error');
				errors = 0;
			}

			if (!jQuery("#wptodo_deadline").val()) {
				jQuery("#wptodo_deadline").addClass('ui-state-error');
				errors = 1;
			} else {
				jQuery("#wptodo_deadline").removeClass('ui-state-error');
				errors = 0;
			}

			if (!errors) {
				form.submit();
				dialog.dialog("close");
			}
		}

		dialog = jQuery("#addForm").dialog({
			autoOpen: false,
			height: 400,
			width: 350,
			modal: true,
			buttons: {
				"Submit": formSubmit,
				Cancel: function() {
					form[0].reset();
					dialog.dialog("close");
				}
			},
			close: function() {
				form[0].reset();
				dialog.dialog("close");
			}
		});

		jQuery("#addTask-button").button().on("click", function() {
			dialog.dialog("open");
		});
	});
</script>