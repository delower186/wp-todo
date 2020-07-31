	<div class="wrap">
		<br>
		<h2 style="text-align: center"><?php _e("To Do Management", 'wptodo'); ?></h2>
		<br>
		<?php
		\Inc\Pages\Admin::wptodo_add_button();
		?>
		<table id="todo" class="display" style="width:100%">
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
	</div>
	<?php
	\Inc\Pages\Admin::wptodo_add_form();
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#todo').DataTable({
				responsive: true
			});
		});
	</script>