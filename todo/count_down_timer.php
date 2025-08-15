<?php 
add_filter( 'manage_wptodo_posts_columns', function( $columns ) {
    $columns['todo_deadline_countdown'] = __( 'Time Left', 'wptodo' );
    return $columns;
} );

add_action( 'manage_wptodo_posts_custom_column', function( $column, $post_id ) {
    if ( $column === 'todo_deadline_countdown' ) {
        $deadline = get_post_meta( $post_id, '_todo_deadline', true );
        $status   = get_post_meta( $post_id, '_todo_status', true );
        if ( $deadline ) {
            echo '<span class="wptodo-countdown" data-deadline="'.esc_attr($deadline).'" data-status="'.esc_attr(strtolower($status)).'"></span>';
        } else {
            echo '—';
        }
    }
}, 10, 2 );

add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( 'edit.php' !== $hook || get_post_type() !== 'wptodo' ) return;

    wp_add_inline_style( 'wp-admin', "
        .wptodo-countdown {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            min-width: 100px;
            text-align: center;
        }

        /* Countdown colors */
        .wptodo-green { background: #d4edda; color: #155724; }
        .wptodo-orange { background: #fff3cd; color: #856404; animation: pulseOrange 1s infinite; }
        .wptodo-red { background: #f8d7da; color: #721c24; animation: pulseRed 0.5s infinite; }
        .wptodo-passed { background: #f5c6cb; color: #721c24; }

        /* Celebration & regret animations */
        .wptodo-completed { font-size: 20px; animation: pop 1s infinite; }
        .wptodo-cancelled { font-size: 20px; animation: shake 0.5s infinite; }

        @keyframes pulseOrange {
            0% { box-shadow: 0 0 0 0 rgba(255,165,0, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255,165,0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255,165,0, 0); }
        }
        @keyframes pulseRed {
            0% { box-shadow: 0 0 0 0 rgba(255,0,0, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255,0,0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255,0,0, 0); }
        }
        @keyframes pop {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-3px); }
            75% { transform: translateX(3px); }
        }
    " );

    wp_add_inline_script( 'jquery', "
        jQuery(document).ready(function($){
            function updateCountdown(element){
                var status = $(element).data('status');
                var deadline = new Date($(element).data('deadline'));
                var now = new Date();
                var diff = deadline - now;

                $(element).removeClass('wptodo-green wptodo-orange wptodo-red wptodo-passed wptodo-completed wptodo-cancelled');

                if(status === 'completed') {
                    $(element).html('🎉').addClass('wptodo-completed');
                    return;
                }

                if(status === 'cancelled') {
                    $(element).html('😢').addClass('wptodo-cancelled');
                    return;
                }

                if(diff <= 0){
                    $(element).text('Deadline passed').addClass('wptodo-passed');
                    $(element).closest('tr').css('background-color','#f8d7da');
                    return;
                }

                var days = Math.floor(diff / (1000*60*60*24));
                var hours = Math.floor((diff % (1000*60*60*24))/(1000*60*60));
                var minutes = Math.floor((diff % (1000*60*60))/(1000*60));
                var seconds = Math.floor((diff % (1000*60))/1000);
                $(element).text(days+'d '+hours+'h '+minutes+'m '+seconds+'s');

                if (diff <= 3600000) {
                    $(element).addClass('wptodo-red');
                } else if (diff <= 86400000) {
                    $(element).addClass('wptodo-orange');
                } else {
                    $(element).addClass('wptodo-green');
                }
            }

            $('.wptodo-countdown').each(function(){
                var el = this;
                updateCountdown(el);
                setInterval(function(){ updateCountdown(el); }, 1000);
            });
        });
    " );
});

