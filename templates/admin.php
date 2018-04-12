	<div class="wrap">
		<br>
		<h2 class="text-center"><?php _e("To Do Management", 'wptodo'); ?></h2>
		<br>
		<?php 
		\Inc\Pages\Admin::wptodo_add_button();
		?>
		<table id="todo" class="table table-striped table-bordered table-responsive" style="width:100%">
		  <thead>
		  <tr>
		    <th>ID</th>
		    <th>Title</th>
		    <th>Submitter</th>
			<th>Asigned</th>
			<th>Added</th>
			<th>Deadline</th>
			<th>Status</th>
			<th>Priority</th>
			<th>Notify</th>
		  </tr>
		  </thead>
		  <tbody>
		  <?php
		  		self::wptodo_tasks();
		  ?>
		  </tbody>
		</table>
		<?php 
		\Inc\Pages\Admin::wptodo_add_form();
		?>
	</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#todo').DataTable();
	});
</script>