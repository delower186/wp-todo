<?php 
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( 'edit.php' !== $hook || get_post_type() !== 'wp-todo' ) return;

    // jQuery UI for modal
    wp_enqueue_script( 'jquery-ui-dialog' );
    wp_enqueue_style( 'jquery-ui-css', '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css', array(), "1.13.2" );

    // Custom JS
    wp_enqueue_script( 'wptodo-admin-js', PLUGIN_DIR_URL . 'assets/js/wptodo-admin.js', ['jquery', 'jquery-ui-dialog'], "1.0", true );

    // Localize AJAX URL
    wp_localize_script( 'wptodo-admin-js', 'wptodo_ajax', [
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'wp-todo_action' )
    ] );

    // Custom CSS for modal
    wp_add_inline_style( 'jquery-ui-css', '.wptodo-modal { display:none; }' );

    // Custom CSS Style
    wp_enqueue_style( "style", PLUGIN_DIR_URL ."assets/css/style.css", array(), "1.0", true );
});

add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'toplevel_page_wp-todo-dashboard') return;

    // FullCalendar
    wp_enqueue_style('fullcalendar-css', '//cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css', [], '6.1.8');
    wp_enqueue_script('fullcalendar-js', '//cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js', [], '6.1.8', true);

    // SortableJS for Kanban
    wp_enqueue_script('sortable-js', '//cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js', [], '1.15.0', true);

    // Custom dashboard script
    wp_enqueue_script('wptodo-dashboard-js', PLUGIN_DIR_URL.'assets/js/wptodo-dashboard.js', ['jquery','fullcalendar-js','sortable-js'], '1.0', true);

    wp_localize_script('wptodo-dashboard-js', 'wptodo_dashboard', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('wp-todo_dashboard'),
    ]);
});
