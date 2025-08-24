<?php 
add_action('admin_menu', function() {
    // Main Dashboard Menu
    add_menu_page(
        'WP Todo Dashboard',
        'WP To do',
        'manage_options',
        'wp-todo-dashboard',
        'wptodo_render_dashboard',
        'dashicons-clipboard',
        6
    );

    // Submenu: All Todos
    add_submenu_page(
        'wp-todo-dashboard',          // Parent slug
        'All Todos',                  // Page title
        'All Todos',                  // Menu title
        'manage_options',             // Capability
        'edit.php?post_type=wp-todo' // Link to post type list
    );

    // Optional: Add New Todo
    add_submenu_page(
        'wp-todo-dashboard',
        'Add New Todo',
        'Add New Todo',
        'manage_options',
        'post-new.php?post_type=wp-todo'
    );
});


function wptodo_render_dashboard() {
    ?>
        <div id="wptodo-dashboard-card" style="margin: 20px 20px auto auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: #fff;">
        <h2>Kanban Board</h2>
        <div id="wptodo-kanban" style="display:flex;gap:20px;"></div>

        <h2 style="margin-top:40px;">Calendar</h2>
        <div id="wptodo-calendar"></div>
        </div>
    <?php
}


add_action('wp_ajax_get_todos', function() {
    check_ajax_referer('wp-todo_dashboard', 'nonce'); // match JS

    $todos = get_posts([
        'post_type' => 'wp-todo',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    $data = [];
    foreach ($todos as $todo) {
        $status_terms = wp_get_post_terms($todo->ID, 'todo_status', ['fields' => 'names']);
        $priority_terms = wp_get_post_terms($todo->ID, 'todo_priority', ['fields' => 'names']);
        $deadline       = get_post_meta($todo->ID, '_todo_deadline', true);

        // Allow Pro (or others) to modify extra meta for each card/event
        $extra_meta = apply_filters('wp_todo_todo_extra_meta', [], $todo->ID);

        $data[] = array_merge([
            'id'       => $todo->ID,
            'title'    => esc_html(get_the_title($todo)),
            'status'   => $status_terms ? $status_terms[0] : 'Not Started',
            'priority' => $priority_terms ? $priority_terms[0] : 'Normal',
            'date'     => get_the_date('Y-m-d', $todo->ID),
            'deadline' => $deadline,
        ], $extra_meta);
    }


    wp_send_json_success($data);
});


// AJAX: Update todo status
add_action('wp_ajax_update_todo_status', function() {
    check_ajax_referer('wp-todo_dashboard', 'nonce');

    $todo_id = isset($_POST['todo_id']) ? intval($_POST['todo_id']) : 0;
    $status  = isset($_POST['status']) ? sanitize_text_field(wp_unslash($_POST['status'])) : '';

    if (!$todo_id || !$status) {
        wp_send_json_error('Invalid data');
    }


    wp_set_post_terms($todo_id, [$status], 'todo_status', false);


    // Only trigger Pro hook if completed
    if ($status === 'Completed') {
        // Trigger recurrence check
        do_action('wp_todo_task_completed', $todo_id, $status);
    }

    wp_send_json_success('Status updated');
});

