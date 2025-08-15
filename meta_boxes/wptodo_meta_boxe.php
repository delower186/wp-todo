<?php
/**
 * About meta box Information
 *
 */
function wptodo_add_meta_box() {


    add_meta_box(
        'todo_deadline',                         // ID
        'Info',               // Title
        'wptodo_meta_box_callback',     // Callback function
        'wptodo',                       // Post type
        'normal',                          // Context
        'default'                          // Priority
    );

    add_meta_box(
        'todo_assignee',                         // ID
        '',                             // Title
        'wptodo_meta_box_callback',     // Callback function
        'wptodo',                       // Post type
        'normal',                          // Context
        'default'                          // Priority
    );

    add_meta_box(
        'todo_status',                         // ID
        '',                             // Title
        'wptodo_meta_box_callback',     // Callback function
        'wptodo',                       // Post type
        'normal',                          // Context
        'default'                          // Priority
    );

    add_meta_box(
        'todo_priority',                         // ID
        '',                             // Title
        'wptodo_meta_box_callback',     // Callback function
        'wptodo',                       // Post type
        'normal',                          // Context
        'default'                          // Priority
    );

}
add_action('add_meta_boxes', 'wptodo_add_meta_box');

function wptodo_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('wptodo_add_meta_box_nonce', 'wptodo_add_meta_box_nonce_field');

    // Retrieve existing value
    $todo_deadline = get_post_meta($post->ID, '_todo_deadline', true);
    $todo_assignee = get_post_meta($post->ID, '_todo_assignee', true);
    $todo_status = get_post_meta($post->ID, '_todo_status', true);
    $todo_priority = get_post_meta($post->ID, '_todo_priority', true);


    ?>
    <p>
        <label for="deadline">Deadline:</label><br>
        <?php
            // Example: load deadline from DB
            // $todo_deadline = get_post_meta( $post_id, 'todo_deadline', true );

            // Default to today's date if empty
            $todo_deadline = !empty($todo_deadline) ? $todo_deadline : date('Y-m-d');
        ?>
        <input type="date" name="todo_deadline" id="todo_deadline" value="<?php echo esc_attr($todo_deadline); ?>" style="width:100%;">
    </p>
    <p>
        <label for="assignee">Assignee:</label><br>
        <?php
            // Get all users
            $users = get_users();

            // Get current logged-in user
            $current_user = wp_get_current_user();

            // Decide which user should be pre-selected
            $selected_user_id = !empty($todo_assignee) ? $todo_assignee : $current_user->ID;
        ?>
        <select name="todo_assignee" id="todo_assignee" style="width:100%;">
            <?php foreach ( $users as $user ) : ?>
                <option value="<?php echo esc_attr( $user->ID ); ?>" 
                    <?php selected( $selected_user_id, $user->ID ); ?>>
                    <?php echo esc_html( $user->display_name ); ?>
                </option>
            <?php endforeach; ?>
        </select>

    </p>
    <p>
        <label for="status">Status:</label><br>
        <?php
            // Example: load status from DB (post meta, options, etc.)
            // $todo_status = get_post_meta( $post_id, 'todo_status', true );

            // Default value if no status is set
            $todo_status = !empty($todo_status) ? $todo_status : 'New';
        ?>
        <select name="todo_status" id="todo_status" style="width:100%;">
            <option value="New"    <?php selected( $todo_status, 'New' ); ?>>New</option>
            <option value="Open"   <?php selected( $todo_status, 'Open' ); ?>>Open</option>
            <option value="Buggy"  <?php selected( $todo_status, 'Buggy' ); ?>>Buggy</option>
            <option value="Solved" <?php selected( $todo_status, 'Solved' ); ?>>Solved</option>
            <option value="Closed" <?php selected( $todo_status, 'Closed' ); ?>>Closed</option>
        </select>
    </p>
    <p>
        <label for="Priority">Priority:</label><br>
        <?php
            // Example: load priority from DB
            // $todo_priority = get_post_meta( $post_id, 'todo_priority', true );

            // Default to "Normal" if empty
            $todo_priority = !empty($todo_priority) ? $todo_priority : 'Normal';
        ?>
        <select name="todo_priority" id="todo_priority" style="width:100%;">
            <option value="Low"       <?php selected( $todo_priority, 'Low' ); ?>>Low</option>
            <option value="Normal"    <?php selected( $todo_priority, 'Normal' ); ?>>Normal</option>
            <option value="High"      <?php selected( $todo_priority, 'High' ); ?>>High</option>
            <option value="Important" <?php selected( $todo_priority, 'Important' ); ?>>Important</option>
        </select>
    </p>
    <?php
}

function wptodo_save_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['wptodo_add_meta_box_nonce_field']) ||
        !wp_verify_nonce($_POST['wptodo_add_meta_box_nonce_field'], 'wptodo_add_meta_box_nonce')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) return;

    // Save field
    if (isset($_POST['todo_deadline'])) {
        update_post_meta($post_id, '_todo_deadline', sanitize_text_field($_POST['todo_deadline']));
    }
    if (isset($_POST['todo_assignee'])) {
        update_post_meta($post_id, '_todo_assignee', sanitize_text_field($_POST['todo_assignee']));
    }
    if (isset($_POST['todo_status'])) {
        update_post_meta($post_id, '_todo_status', sanitize_text_field($_POST['todo_status']));
    }
    if (isset($_POST['todo_priority'])) {
        update_post_meta($post_id, '_todo_priority', sanitize_text_field($_POST['todo_priority']));
    }
}
add_action('save_post', 'wptodo_save_meta_box');