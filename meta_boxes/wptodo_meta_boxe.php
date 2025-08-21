<?php
/**
 * Register custom taxonomies for Status & Priority
 */
function wptodo_register_taxonomies() {
    // Status
    register_taxonomy('todo_status', 'wp-todo', [
        'labels' => [
            'name'          => 'Statuses',
            'singular_name' => 'Status',
        ],
        'public'       => false,
        'show_ui'      => false, // hide default taxonomy meta box
        'hierarchical' => false,
    ]);

    // Priority
    register_taxonomy('todo_priority', 'wp-todo', [
        'labels' => [
            'name'          => 'Priorities',
            'singular_name' => 'Priority',
        ],
        'public'       => false,
        'show_ui'      => false, // hide default taxonomy meta box
        'hierarchical' => false,
    ]);
}
add_action('init', 'wptodo_register_taxonomies');


/**
 * Add custom meta boxes
 */
function wptodo_add_meta_box() {
    add_meta_box('todo_deadline', 'Deadline', 'wptodo_meta_box_callback', 'wp-todo', 'normal', 'default');
    add_meta_box('todo_assignee', 'Assignee', 'wptodo_meta_box_callback', 'wp-todo', 'normal', 'default');
    add_meta_box('todo_status', 'Status', 'wptodo_meta_box_callback', 'wp-todo', 'normal', 'default');
    add_meta_box('todo_priority', 'Priority', 'wptodo_meta_box_callback', 'wp-todo', 'normal', 'default');
}
add_action('add_meta_boxes', 'wptodo_add_meta_box');


/**
 * Meta box content
 */
function wptodo_meta_box_callback($post, $meta) {
    wp_nonce_field('wp-todo_add_meta_box_nonce', 'wp-todo_add_meta_box_nonce_field');

    switch ($meta['id']) {
        case 'todo_deadline':
            $value = get_post_meta($post->ID, '_todo_deadline', true) ?: gmdate('Y-m-d');
            echo '<input type="date" name="todo_deadline" value="' . esc_attr($value) . '" style="width:100%;">';
            break;

        case 'todo_assignee':
            $users = get_users();
            $selected = get_post_meta($post->ID, '_todo_assignee', true) ?: get_current_user_id();
            echo '<select name="todo_assignee" style="width:100%">';
            foreach ($users as $user) {
                echo '<option value="' . esc_attr($user->ID) . '" ' . selected($selected, $user->ID, false) . '>' . esc_html($user->display_name) . '</option>';
            }
            echo '</select>';
            break;

        case 'todo_status':
            $terms   = get_terms(['taxonomy' => 'todo_status', 'hide_empty' => false]);
            $current = wp_get_post_terms($post->ID, 'todo_status', ['fields' => 'ids']);
            $current = $current ? $current[0] : '';
            echo '<select name="todo_status" style="width:100%">';
            foreach ($terms as $term) {
                echo '<option value="' . esc_attr($term->term_id) . '" ' . selected($current, $term->term_id, false) . '>' . esc_html($term->name) . '</option>';
            }
            echo '</select>';
            break;

        case 'todo_priority':
            $terms   = get_terms(['taxonomy' => 'todo_priority', 'hide_empty' => false]);
            $current = wp_get_post_terms($post->ID, 'todo_priority', ['fields' => 'ids']);
            $current = $current ? $current[0] : '';
            echo '<select name="todo_priority" style="width:100%">';
            foreach ($terms as $term) {
                echo '<option value="' . esc_attr($term->term_id) . '" ' . selected($current, $term->term_id, false) . '>' . esc_html($term->name) . '</option>';
            }
            echo '</select>';
            break;
    }
}


/**
 * Save meta box fields
 */
function wptodo_save_meta_box($post_id) {
    if (!isset($_POST['wp-todo_add_meta_box_nonce_field']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wp-todo_add_meta_box_nonce_field'])), 'wp-todo_add_meta_box_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Save deadline
    if (isset($_POST['todo_deadline'])) {
        update_post_meta($post_id, '_todo_deadline', sanitize_text_field(wp_unslash($_POST['todo_deadline'])));
    }

    // Save assignee
    if (isset($_POST['todo_assignee'])) {
        update_post_meta($post_id, '_todo_assignee', intval($_POST['todo_assignee']));
    }

    // Save taxonomy terms
    if (isset($_POST['todo_status'])) {
        wp_set_post_terms($post_id, [(int) $_POST['todo_status']], 'todo_status', false);
    }
    if (isset($_POST['todo_priority'])) {
        wp_set_post_terms($post_id, [(int) $_POST['todo_priority']], 'todo_priority', false);
    }
}
add_action('save_post', 'wptodo_save_meta_box');


/**
 * Pre-populate default taxonomy terms on plugin/theme activation
 */
function wptodo_register_default_terms() {
    // Statuses
    $statuses = ['Not Started','In Progress','In Review','Completed'];
    foreach ($statuses as $status) {
        if (!term_exists($status, 'todo_status')) {
            wp_insert_term($status, 'todo_status');
        }
    }

    // Priorities
    $priorities = ['Low','Normal','High','Critical'];
    foreach ($priorities as $priority) {
        if (!term_exists($priority, 'todo_priority')) {
            wp_insert_term($priority, 'todo_priority');
        }
    }
}
add_action('init', 'wptodo_register_default_terms');

