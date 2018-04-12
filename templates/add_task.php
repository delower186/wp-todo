<div class="row">
	<!-- The Modal -->
	<div class="modal fade" id="addTask">
	  <div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content">

	      <!-- Modal Header -->
	      <div class="modal-header">
	        <h4 class="modal-title"><?php _e("Add New Task", "wptodo"); ?></h4>
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	      </div>

	      <!-- Modal body -->
	      <div class="modal-body">
				<form action="" method="post">
					  <input name="wptodo_addtask" id="wptodo_addtask" value="true" type="hidden"/>
					  <input name="wptodo_from" id="wptodo_from" value="<?php echo self::get_user_id(); ?>" type="hidden"/>
				  <div class="form-group">
					  <label for="wptodo_title">Title:</label>
					  <input type="text" name="wptodo_title" id="wptodo_title" class="form-control" placeholder="Enter Title" required/>
				  </div>
				  <div class="form-group">
					  <label for="wptodo_description">Description:</label>
					  <textarea class="form-control" name="wptodo_description" id="wptodo_description" rows="5" cols="40" placeholder="Enter Description" required></textarea>
				  </div>
				  <div class="form-group">
					  <label for="wptodo_deadline">Deadline:</label>
					  <input class="form-control" name="wptodo_deadline" id="wptodo_deadline" value="0000-00-00" type="date" required/>
				  </div>
				  <div class="form-group">
				    	<label for="wptodo_for">Assigned to:</label>
				    	<?php wp_dropdown_users("name=wptodo_for&seleted=&class=form-control"); ?>
						</select>
				  </div>
				  <div class="form-group">
				  		<label for="wptodo_status">Status:</label>
						<select class="form-control" name="wptodo_status" id="wptodo_status" required>
							<option value="1">New</option>
							<option value="2">Open</option>
							<option value="3">Buggy</option>
							<option value="4">Solved</option>
							<option value="5">Closed</option>
						</select>
				  </div>
				  <div class="form-group">
				  		<label for="wptodo_priority">Priority:</label>
						<select class="form-control" name="wptodo_priority" id="wptodo_priority" required>
							<option value="1">Low</option>
							<option value="2">Normal</option>
							<option value="3">High</option>
							<option value="4">Important</option>
						</select>
				  </div>
				  <div class="form-group">
				  		<label for="wptodo_notify">Send alerts through email?</label>
				  		<input class="form-control" name="wptodo_notify" id="wptodo_notify" value="1" type="checkbox" />
				  </div>
				  <div class="form-group">
				  </div>
				  <div class="form-group">
				  </div>
				  <button name="submit" type="submit" class="btn btn-primary" value="Add Task">Submit</button>
				</form>
	      </div>

	      <!-- Modal footer -->
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
	      </div>

	    </div>
	  </div>
	</div>
</div>