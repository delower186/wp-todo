<?php
	if(isset($_POST['submit'])){
		\Inc\Base\Model::wptodo_message($_POST);
	}
	//retrieve messages from database
	$messages = \Inc\Base\Model::wptodo_message_retrieve();
	if(is_array($messages) || is_object($messages)){
		foreach($messages as $message){
			$subject = $message->subject;
			$body = $message->body;
		}
	}
?>
<div class="container">
	<br>
		<h2><?php _e("Email Template", 'wptodo'); ?></h2>
	<br>
	<div class="row">
		<form action="" method="post">
			<div class="form-group">
				<label>Email Subject</label>
				<input type="text" name="subject" class="form-control" value="<?=@$subject; ?>">
			</div>
			<div class="form-group">
				<label>Email Body</label>
				<textarea name="body" rows="10" cols="20" class="form-control" ><?=@$body; ?></textarea>
			</div>
			<input type="submit" name="submit" class="btn btn-primary btn-sm">
		</form>
	</div>
</div>