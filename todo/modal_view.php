<?php 
add_action( 'wp_ajax_wp-todo_quick_view', function() {
    // First, safely get and unslash the nonce
    $nonce = isset( $_POST['wp-todo_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wp-todo_nonce'] ) ) : '';

    // Verify the nonce
    if ( ! wp_verify_nonce( $nonce, 'wp-todo_action' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed' ), 400 );
        exit;
    }

    // Now process the post ID
    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

    if ( ! $post_id ) wp_send_json_error('Invalid post ID');

    $post = get_post( $post_id );
    if ( ! $post || $post->post_type !== 'wp-todo' ) wp_send_json_error('Post not found');

    $assignee_id = get_post_meta( $post_id, '_todo_assignee', true );
    $assignee_name = $assignee_id ? get_the_author_meta('display_name', $assignee_id) : '—';
    $priority = get_post_meta( $post_id, '_todo_priority', true ) ?: 'Normal';
    $status = get_post_meta( $post_id, '_todo_status', true ) ?: 'Not Started';
    $deadline = get_post_meta( $post_id, '_todo_deadline', true ) ?: '—';

    $content = apply_filters('the_content', $post->post_content);

    if (!$deadline) {
        $deadline = gmdate('Y-m-d');
    }

    $now = new DateTime();
    $deadline_dt = new DateTime($deadline);
    $interval = $now->diff($deadline_dt);

    // Calculate time left string
    if ($now > $deadline_dt) {
        $time_left = 'Deadline passed';
    } else {
        $time_left = $interval->days . 'd ' . $interval->h . 'h ' . $interval->i . 'm left';
    }

    // status on modal
    if ($status == 'Completed') {
        $time_left = "🎉";
    }elseif ($status == "Cancelled") {
        $time_left = "😢";
    }

    ob_start(); ?>
    <div>
        <h2><?php echo esc_html($post->post_title); ?></h2>
        <p><strong>Assignee:</strong> <?php echo esc_html($assignee_name); ?></p>
        <p><strong>Status:</strong> <?php echo esc_html($status); ?></p>
        <p><strong>Priority:</strong> <?php echo esc_html($priority); ?></p>
        <p><strong>Deadline:</strong> <?php echo esc_html($deadline); ?></p>
        <p><strong>Time Left:</strong> <?php echo esc_html($time_left); ?></p>
        <div><?php echo wp_kses_post($content); ?></div>
        <div id="wptodo-comments">
            <?php
                // Load WP's comment template for this post
                global $withcomments;
                $withcomments = true; // Force comments to load in non-single views
                comments_template();
            ?>
        </div>
    </div>
    <?php
    wp_send_json_success( ob_get_clean() );
});


add_action( 'admin_footer-edit.php', function() {
    $screen = get_current_screen();
    if ( $screen->post_type !== 'wp-todo' ) return;
    ?>
    <script>
    jQuery(document).ready(function($){
        // Add data-post-id and clickable class to rows
        $('#the-list tr').each(function(){
            var post_id = $(this).attr('id');
            if(post_id){
                post_id = post_id.replace('post-', '');
                $(this).attr('data-post-id', post_id).addClass('wptodo-clickable');
            }
        });

        // Click event for row
        $('#the-list').on('click', 'tr.wptodo-clickable', function(e){
            // Prevent click if it is on first 2 columns (checkbox/radio or title)
            if($(e.target).closest('th, td:first-child, td:nth-child(2)').length) return;

            var post_id = $(this).data('post-id');
            if(!post_id) return;

            // AJAX to fetch modal content
            $.post(wptodo_ajax.ajax_url, {
                action: 'wp-todo_quick_view',
                post_id: post_id,
                'wp-todo_nonce': wptodo_ajax.nonce
            }, function(response){
                if(response.success){
                    $('<div class="wptodo-modal"></div>').html(response.data).dialog({
                        modal: true,
                        width: 600,
                        title: 'Todo Details',
                        close: function() { $(this).remove(); }
                    });
                } else {
                    alert('Failed to load todo details.');
                }
            });
        });
    });
    </script>
    <?php
});

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script('comment-reply');
    wp_enqueue_style('wp-admin');
});
