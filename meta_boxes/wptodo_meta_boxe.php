<?php
/**
 * About meta box Information
 *
 */
function wptodo_add_meta_box() {
    add_meta_box('todo_deadline', 'Deadline', 'wptodo_meta_box_callback', 'wp-todo', 'normal', 'default');
    add_meta_box('todo_assignee', 'Assignee', 'wptodo_meta_box_callback', 'wp-todo', 'normal', 'default');
    add_meta_box('todo_status', 'Status', 'wptodo_meta_box_callback', 'wp-todo', 'normal', 'default');
    add_meta_box('todo_priority', 'Priority', 'wptodo_meta_box_callback', 'wp-todo', 'normal', 'default');
}
add_action('add_meta_boxes', 'wptodo_add_meta_box');

function wptodo_meta_box_callback($post, $meta) {
    wp_nonce_field('wp-todo_add_meta_box_nonce', 'wp-todo_add_meta_box_nonce_field');

    switch ($meta['id']) {
        case 'todo_deadline':
            $value = get_post_meta($post->ID, '_todo_deadline', true) ?: gmdate('Y-m-d');
            echo '<input type="date" name="todo_deadline" value="'.esc_attr($value).'" style="width:100%;">';
            break;

        case 'todo_assignee':
            $users = get_users();
            $selected = get_post_meta($post->ID, '_todo_assignee', true) ?: get_current_user_id();
            echo '<select name="todo_assignee" style="width:100%">';
            foreach ($users as $user) {
                echo '<option value="' . esc_attr( $user->ID ) . '" ' . selected( $selected, $user->ID, false ) . '>' . esc_html( $user->display_name ) . '</option>';
            }
            echo '</select>';
            break;

        case 'todo_status':
            $status = get_post_meta($post->ID, '_todo_status', true) ?: 'Not Started';
            $options = ['Not Started','In Progress','Pending','In Review','Completed','Cancelled'];
            echo '<select name="todo_status" style="width:100%">';
            foreach ($options as $opt) {
                echo '<option value="' . esc_attr( $opt ) . '" ' . selected( $status, $opt, false ) . '>' . esc_html( $opt ) . '</option>';
            }
            echo '</select>';
            break;

        case 'todo_priority':
            $priority = get_post_meta($post->ID, '_todo_priority', true) ?: 'Normal';
            $options = ['Low','Normal','High','Important'];
            echo '<select name="todo_priority" style="width:100%">';
            foreach ($options as $opt) {
                echo '<option value="' . esc_attr( $opt ) . '" ' . selected( $priority, $opt, false ) . '>' . esc_html( $opt ) . '</option>';
            }
            echo '</select>';
            break;
    }
}


function wptodo_save_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['wp-todo_add_meta_box_nonce_field']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash( $_POST['wp-todo_add_meta_box_nonce_field'] )), 'wp-todo_add_meta_box_nonce')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) return;

    // Save field
    if (isset($_POST['todo_deadline'])) {
        update_post_meta($post_id, '_todo_deadline', sanitize_text_field(wp_unslash($_POST['todo_deadline'])));
    }
    if (isset($_POST['todo_assignee'])) {
        update_post_meta($post_id, '_todo_assignee', sanitize_text_field(wp_unslash($_POST['todo_assignee'])));
    }
    if (isset($_POST['todo_status'])) {
        update_post_meta($post_id, '_todo_status', sanitize_text_field(wp_unslash($_POST['todo_status'])));
    }
    if (isset($_POST['todo_priority'])) {
        update_post_meta($post_id, '_todo_priority', sanitize_text_field(wp_unslash($_POST['todo_priority'])));
    }
}
add_action('save_post', 'wptodo_save_meta_box');