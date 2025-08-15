<?php 
add_action( 'init', 'wptodo_custom_post_type' );

function wptodo_custom_post_type() {

   $labels = array(

      'name'                     => __( 'Todos', 'wptodo' ),
      'singular_name'            => __( 'Todo', 'wptodo' ),
      'add_new'                  => __( 'Add New', 'wptodo' ),
      'add_new_item'             => __( 'Add New Todo', 'wptodo' ),
      'edit_item'                => __( 'Edit Todo', 'wptodo' ),
      'new_item'                 => __( 'New Todo', 'wptodo' ),
      'view_item'                => __( 'View Todo', 'wptodo' ),
      'view_items'               => __( 'View Todos', 'wptodo' ),
      'search_items'             => __( 'Search Todos', 'wptodo' ),
      'not_found'                => __( 'No Todos found.', 'wptodo' ),
      'not_found_in_trash'       => __( 'No Todos found in Trash.', 'wptodo' ),
      'parent_item_colon'        => __( 'Parent Todos:', 'wptodo' ),
      'all_items'                => __( 'All Todos', 'wptodo' ),
      'archives'                 => __( 'Todo Archives', 'wptodo' ),
      'attributes'               => __( 'Todo Attributes', 'wptodo' ),
      'insert_into_item'         => __( 'Insert into Todo', 'wptodo' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this Todo', 'wptodo' ),
      'featured_image'           => __( 'Featured Image', 'wptodo' ),
      'set_featured_image'       => __( 'Set featured image', 'wptodo' ),
      'remove_featured_image'    => __( 'Remove featured image', 'wptodo' ),
      'use_featured_image'       => __( 'Use as featured image', 'wptodo' ),
      'menu_name'                => __( 'WP Todo', 'wptodo' ),
      'filter_items_list'        => __( 'Filter Todo list', 'wptodo' ),
      'filter_by_date'           => __( 'Filter by date', 'wptodo' ),
      'items_list_navigation'    => __( 'Todos list navigation', 'wptodo' ),
      'items_list'               => __( 'Todos list', 'wptodo' ),
      'item_published'           => __( 'Todo published.', 'wptodo' ),
      'item_published_privately' => __( 'Todo published privately.', 'wptodo' ),
      'item_reverted_to_draft'   => __( 'Todo reverted to draft.', 'wptodo' ),
      'item_scheduled'           => __( 'Todo scheduled.', 'wptodo' ),
      'item_updated'             => __( 'Todo updated.', 'wptodo' ),
      'item_link'                => __( 'Todo Link', 'wptodo' ),
      'item_link_description'    => __( 'A link to an todo.', 'wptodo' ),

   );

   $args = array(

      'labels'                => $labels,
      'description'           => __( 'organize and manage company Todos', 'wptodo' ),
      'public'                => false,
      'hierarchical'          => false,
      'exclude_from_search'   => true,
      'publicly_queryable'    => false,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'show_in_nav_menus'     => false,
      'show_in_admin_bar'     => false,
      'show_in_rest'          => true,
      'menu_position'         => null,
      'menu_icon'             => 'dashicons-megaphone',
      'capability_type'       => 'post',
      'capabilities'          => array(),
      'supports'              => array( 'title', 'editor', 'revisions' ),
      'taxonomies'            => array(),
      'has_archive'           => true,
      'rewrite'               => array( 'slug' => 'wptodo' ),
      'query_var'             => true,
      'can_export'            => true,
      'delete_with_user'      => false,
      'template'              => array(),
      'template_lock'         => false,

   );

   register_post_type( 'wptodo', $args );

}