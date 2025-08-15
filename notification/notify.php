<?php 
// Send notification emails to assignee and author on first assignment and updates
add_action( 'save_post_wptodo', function( $post_id, $post, $update ) {

    // Prevent autosave and revision loops
    if ( wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
        return;
    }

    // Get task data
    $title    = get_the_title( $post_id );
    $priority = get_post_meta( $post_id, 'todo_priority', true ) ?: 'Normal';
    $status   = get_post_meta( $post_id, 'todo_status', true ) ?: 'New';
    $deadline = get_post_meta( $post_id, 'todo_deadline', true ) ?: date( 'Y-m-d' );
    $link     = get_permalink( $post_id );

    // Get recipients
    $assignee_id = get_post_meta( $post_id, 'todo_assignee', true );
    $assignee    = $assignee_id ? get_userdata( $assignee_id ) : null;
    $author      = get_userdata( $post->post_author );

    if ( ( ! $assignee || ! $assignee->user_email ) && ( ! $author || ! $author->user_email ) ) {
        return;
    }

    // Determine email subject
    if ( ! $update ) {
        $subject = "New Task Assigned: {$title}";
        $intro   = "You have been assigned a new task.";
    } else {
        $subject = "Task Updated: {$title}";
        $intro   = "A task has been updated.";
    }

    // Load HTML email template from external file
    $template_path = PLUGIN_DIR_PATH . 'notification/email-templates/wptodo-notification.html';
    if ( ! file_exists( $template_path ) ) {
        return; // Fail silently if template not found
    }
    $template = file_get_contents( $template_path );

    // Replace placeholders in the template
    $replacements = array(
        '{{INTRO}}'    => esc_html( $intro ),
        '{{TITLE}}'    => esc_html( $title ),
        '{{PRIORITY}}' => esc_html( $priority ),
        '{{STATUS}}'   => esc_html( $status ),
        '{{DEADLINE}}' => esc_html( $deadline ),
        '{{LINK}}'     => esc_url( $link ),
    );
    $message = strtr( $template, $replacements );

    // Set headers for HTML email
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );

    // Send to assignee
    if ( $assignee && $assignee->user_email ) {
        wp_mail( $assignee->user_email, $subject, $message, $headers );
    }

    // Send to author
    if ( $author && $author->user_email && $author->ID !== $assignee_id ) {
        wp_mail( $author->user_email, $subject, $message, $headers );
    }

}, 10, 3 );

