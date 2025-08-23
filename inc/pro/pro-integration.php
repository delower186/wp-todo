<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Load Pro version if available
if ( defined( 'WP_TODO_PRO_VERSION' ) ) {

    add_action( 'admin_notices', function() {

        ?>
            <div class="notice notice-success is-dismissible">
                <p><strong>WP Todo Pro Active:</strong> Premium features are enabled! (v<?php echo esc_html( WP_TODO_PRO_VERSION ); ?>)</p>
            </div>
        <?php

    } );

    // load pro features
    require_once ABSPATH .'wp-content/plugins/wp-todo-pro/wp-todo-pro.php';

}else{

    add_action( 'admin_notices', function() {

        ?>
            <div class="notice notice-info">
                <p><strong>Unlock More Features:</strong> Upgrade to <a href="#" target="_blank">WP Todo Pro</a> for deadlines, Kanban enhancements, and more!</p>
            </div>
        <?php

    } );

}
