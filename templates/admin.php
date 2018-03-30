	<div class="wrap">
		<h2><?php _e("To Do Management", 'wptodo'); ?></h2>
		<h3><?php _e('Analyze tasks & Take Action!', 'wptodo'); ?></h3>
		<table id="todo" class="widefat display">
		  <thead>
		  <tr>
		    <th scope="col">ID</th>
		    <th scope="col">Title</th>
		    <th scope="col">Submitter</th>
			<th scope="col">Asigned</th>
			<th scope="col">Added</th>
			<th scope="col">Deadline</th>
			<th scope="col">Status</th>
			<th scope="col">Priority</th>
			<th scope="col">Notify</th>
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