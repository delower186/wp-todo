<?php 
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( 'edit.php' !== $hook || get_post_type() !== 'wptodo' ) return;

    // jQuery UI for modal
    wp_enqueue_script( 'jquery-ui-dialog' );
    wp_enqueue_style( 'jquery-ui-css', '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css' );

    // Custom JS
    wp_enqueue_script( 'wptodo-admin-js', PLUGIN_DIR_URL . 'assets/js/wptodo-admin.js', ['jquery', 'jquery-ui-dialog'], null, true );

    // Localize AJAX URL
    wp_localize_script( 'wptodo-admin-js', 'wptodo_ajax', [
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ] );

    // Custom CSS for modal
    wp_add_inline_style( 'jquery-ui-css', '.wptodo-modal { display:none; }' );
});