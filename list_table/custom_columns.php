<?php 
// 1️⃣ Add new columns to the CPT list table
add_filter( 'manage_wptodo_posts_columns', function( $columns ) {
    // Optional: remove default Date column
    // unset($columns['date']);
    $columns['todo_assigned_by']   = __( 'Assigned By', 'wptodo' );
    $columns['todo_deadline'] = __( 'Deadline', 'wptodo' );
    $columns['todo_assignee']   = __( 'Assigned To', 'wptodo' );
    $columns['todo_priority'] = __( 'Priority', 'wptodo' );
    $columns['todo_status']   = __( 'Status', 'wptodo' );

    return $columns;
} );

// 2️⃣ Fill the custom columns with data
add_action( 'manage_wptodo_posts_custom_column', function( $column, $post_id  ) {
    switch ( $column ) {
        case 'todo_assigned_by':
            // Get author ID
            $author_id = get_post_field( 'post_author', $post_id );

            // Get display name
            $author_name = get_the_author_meta( 'display_name', $author_id );
            echo esc_html( $author_name );        
            break;

        case 'todo_deadline':
            echo esc_html( get_post_meta( $post_id , '_todo_deadline', true ) );
            break;

        case 'todo_assignee':
            $assignee_info = get_userdata( get_post_meta( $post_id , '_todo_assignee', true ) );
            if ( $assignee_info ) {
                // echo $user_info->user_login;   // Username (login name)
                echo esc_html( $assignee_info->display_name ); // Display name
                // echo $user_info->user_email;   // Email
            }
            break;

        case 'todo_priority':
            echo esc_html( get_post_meta( $post_id , '_todo_priority', true ) );
            break;

        case 'todo_status':
            echo esc_html( get_post_meta( $post_id , '_todo_status', true ) );
            break;
    }
}, 10, 2 );

// 3️⃣ Make columns sortable
add_filter( 'manage_edit-wptodo_sortable_columns', function( $columns ) {
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

    if ( in_array( $orderby, ['todo_deadline','todo_assignee', 'todo_priority', 'todo_status'], true ) ) {
        $query->set( 'meta_key', $orderby );
        $query->set( 'orderby', 'meta_value' );
    }
} );