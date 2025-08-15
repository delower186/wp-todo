<?php 
// 1️⃣ Add new columns to the CPT list table
add_filter( 'manage_wp-todo_posts_columns', function( $columns ) {
    // Optional: remove default Date column
    // unset($columns['date']);
    $columns['todo_assigned_by']   = __( 'Assigned By', 'wp-todo' );
    $columns['todo_deadline'] = __( 'Deadline', 'wp-todo' );
    $columns['todo_assignee']   = __( 'Assigned To', 'wp-todo' );
    $columns['todo_priority'] = __( 'Priority', 'wp-todo' );
    $columns['todo_status']   = __( 'Status', 'wp-todo' );

    return $columns;
} );

// 2️⃣ Fill the custom columns with colored data
add_action( 'manage_wp-todo_posts_custom_column', function( $column, $post_id  ) {
    switch ( $column ) {

        case 'todo_assigned_by':
            $author_id   = get_post_field( 'post_author', $post_id );
            $author_name = get_the_author_meta( 'display_name', $author_id );
            echo '<span class="wptodo-col-assigned-by">' . esc_html( $author_name ) . '</span>';
            break;

        case 'todo_deadline':
            $deadline = get_post_meta( $post_id , '_todo_deadline', true );
            echo '<span class="wptodo-col-deadline">' . esc_html( $deadline ) . '</span>';
            break;

        case 'todo_assignee':
            $assignee_info = get_userdata( get_post_meta( $post_id , '_todo_assignee', true ) );
            if ( $assignee_info ) {
                echo '<span class="wptodo-col-assignee">' . esc_html( $assignee_info->display_name ) . '</span>';
            }
            break;

        case 'todo_priority':
            $priority = get_post_meta( $post_id , '_todo_priority', true );
            $priority_class = '';
            switch ( strtolower( $priority ) ) {
                case 'high': $priority_class = 'priority-high'; break;
                case 'normal': $priority_class = 'priority-normal'; break;
                case 'low': $priority_class = 'priority-low'; break;
                case 'important': $priority_class = 'priority-important'; break;
            }
            echo '<span class="' . esc_attr($priority_class) . '">' . esc_html( $priority ) . '</span>';
            break;

        case 'todo_status':
            $status = get_post_meta( $post_id , '_todo_status', true );
            $status_class = '';
            switch ( strtolower( $status ) ) {
                case 'not started': $status_class = 'status-not-started'; break;
                case 'in progress': $status_class = 'status-in-progress'; break;
                case 'pending': $status_class = 'status-pending'; break;
                case 'in review': $status_class = 'status-in-review'; break;
                case 'completed': $status_class = 'status-completed'; break;
                case 'cancelled': $status_class = 'status-cancelled'; break;
            }
            echo '<span class="' . esc_attr($status_class) . '">' . esc_html( $status ) . '</span>';
            break;
    }
}, 10, 2 );

// 3️⃣ Make columns sortable
add_filter( 'manage_edit-wp-todo_sortable_columns', function( $columns ) {
    $columns['todo_deadline'] = 'todo_deadline';
    $columns['todo_assignee'] = 'todo_assignee';
    $columns['todo_priority'] = 'todo_priority';
    $columns['todo_status']   = 'todo_status';
    return $columns;
} );

// 4️⃣ Handle sorting by meta value
add_action( 'pre_get_posts', function( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) return;

    $orderby = $query->get( 'orderby' );

    if ( in_array( $orderby, ['_todo_deadline','_todo_assignee', '_todo_priority', '_todo_status'], true ) ) {
        $query->set( 'meta_key', $orderby );
        $query->set( 'orderby', 'meta_value' );
    }
} );

// 5️⃣ Admin CSS for colored columns
add_action( 'admin_head', function() {
    echo '<style>
        /* Assigned By & Assignee */
        .wptodo-col-assigned-by { color:#343a40; font-weight:bold; }
        .wptodo-col-assignee { color:#0073aa; font-weight:bold; }

        /* Deadline */
        .wptodo-col-deadline { color:#d9534f; font-weight:bold; }

        /* Priority */
        .priority-high { color:#fff; background:#d9534f; padding:3px 6px; border-radius:4px; font-weight:bold; }
        .priority-normal { color:#fff; background:#5bc0de; padding:3px 6px; border-radius:4px; font-weight:bold; }
        .priority-low { color:#fff; background:#5bc0de; padding:3px 6px; border-radius:4px; font-weight:bold; }
        .priority-important { color:#fff; background:#f0ad4e; padding:3px 6px; border-radius:4px; font-weight:bold; }

        /* Status */
        .status-not-started { background:#6c757d; color:#fff; padding:3px 6px; border-radius:4px; font-weight:bold; }
        .status-in-progress { background:#0275d8; color:#fff; padding:3px 6px; border-radius:4px; font-weight:bold; }
        .status-pending { background:#fd7e14; color:#fff; padding:3px 6px; border-radius:4px; font-weight:bold; }
        .status-in-review { background:#17a2b8; color:#fff; padding:3px 6px; border-radius:4px; font-weight:bold; }
        .status-completed { background:#5cb85c; color:#fff; padding:3px 6px; border-radius:4px; font-weight:bold; }
        .status-cancelled { background:#dc3545; color:#fff; padding:3px 6px; border-radius:4px; font-weight:bold; }
    </style>';
});