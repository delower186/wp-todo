<?php 
add_action( 'init', 'wptodo_custom_post_type' );

function wptodo_custom_post_type() {

   $labels = array(

      'name'                     => __( 'Todos', 'wp-todo' ),
      'singular_name'            => __( 'Todo', 'wp-todo' ),
      'add_new'                  => __( 'Add New', 'wp-todo' ),
      'add_new_item'             => __( 'Add New Todo', 'wp-todo' ),
      'edit_item'                => __( 'Edit Todo', 'wp-todo' ),
      'new_item'                 => __( 'New Todo', 'wp-todo' ),
      'view_item'                => __( 'View Todo', 'wp-todo' ),
      'view_items'               => __( 'View Todos', 'wp-todo' ),
      'search_items'             => __( 'Search Todos', 'wp-todo' ),
      'not_found'                => __( 'No Todos found.', 'wp-todo' ),
      'not_found_in_trash'       => __( 'No Todos found in Trash.', 'wp-todo' ),
      'parent_item_colon'        => __( 'Parent Todos:', 'wp-todo' ),
      'all_items'                => __( 'All Todos', 'wp-todo' ),
      'archives'                 => __( 'Todo Archives', 'wp-todo' ),
      'attributes'               => __( 'Todo Attributes', 'wp-todo' ),
      'insert_into_item'         => __( 'Insert into Todo', 'wp-todo' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this Todo', 'wp-todo' ),
      'featured_image'           => __( 'Featured Image', 'wp-todo' ),
      'set_featured_image'       => __( 'Set featured image', 'wp-todo' ),
      'remove_featured_image'    => __( 'Remove featured image', 'wp-todo' ),
      'use_featured_image'       => __( 'Use as featured image', 'wp-todo' ),
      'menu_name'                => __( 'WP Todo', 'wp-todo' ),
      'filter_items_list'        => __( 'Filter Todo list', 'wp-todo' ),
      'filter_by_date'           => __( 'Filter by date', 'wp-todo' ),
      'items_list_navigation'    => __( 'Todos list navigation', 'wp-todo' ),
      'items_list'               => __( 'Todos list', 'wp-todo' ),
      'item_published'           => __( 'Todo published.', 'wp-todo' ),
      'item_published_privately' => __( 'Todo published privately.', 'wp-todo' ),
      'item_reverted_to_draft'   => __( 'Todo reverted to draft.', 'wp-todo' ),
      'item_scheduled'           => __( 'Todo scheduled.', 'wp-todo' ),
      'item_updated'             => __( 'Todo updated.', 'wp-todo' ),
      'item_link'                => __( 'Todo Link', 'wp-todo' ),
      'item_link_description'    => __( 'A link to an todo.', 'wp-todo' ),

   );

   $args = array(

      'labels'                => $labels,
      'description'           => __( 'organize and manage company Todos', 'wp-todo' ),
      'public'                => false,
      'hierarchical'          => false,
      'exclude_from_search'   => true,
      'publicly_queryable'    => false,
      'show_ui'               => true,
      'show_in_menu'          => false,
      'show_in_nav_menus'     => false,
      'show_in_admin_bar'     => false,
      'show_in_rest'          => true,
      'menu_position'         => null,
      'menu_icon'             => 'dashicons-megaphone',
      'capability_type'       => 'post',
      'capabilities'          => array(),
      'supports'              => array( 'title', 'editor', 'revisions', 'author', 'comments' ),
      'taxonomies'            => array(),
      'has_archive'           => true,
      'rewrite'               => array( 'slug' => 'wp-todo' ),
      'query_var'             => true,
      'can_export'            => true,
      'delete_with_user'      => false,
      'template'              => array(),
      'template_lock'         => false,

   );

   register_post_type( 'wp-todo', $args );

}