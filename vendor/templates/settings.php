<?php
if (isset($_POST['submit'])) {
	\Inc\Base\Model::wptodo_message($_POST);
}
//retrieve messages from database
$messages = \Inc\Base\Model::wptodo_message_retrieve();
if (is_array($messages) || is_object($messages)) {
	foreach ($messages as $message) {
		$subject = $message->subject;
		$body = $message->body;
	}
}
?>
<div>
	<br>
	<h2><?php _e("Email Template", 'wptodo'); ?></h2>
	<br>

	<form action="" method="post">
		<table>
			<tr>
				<td><label>Email Subject</label></td>
				<td><input type="text" name="subject" class="form-control" value="<?= @$subject; ?>"></td>
			</tr>
			<tr>
				<td><label>Email Body</label></td>
				<td><textarea name="body" rows="10" cols="22" class="form-control"><?= @$body; ?></textarea></td>
			</tr>
		</table>
		<input type="submit" name="submit">
	</form>
</div>