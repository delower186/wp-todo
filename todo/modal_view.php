<?php 
add_action( 'wp_ajax_wp-todo_quick_view', function() {
    $nonce = isset( $_POST['wp-todo_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wp-todo_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $nonce, 'wp-todo_action' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed' ), 400 );
    }

    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
    if ( ! $post_id ) wp_send_json_error('Invalid post ID');

    $post = get_post( $post_id );
    if ( ! $post || $post->post_type !== 'wp-todo' ) wp_send_json_error('Post not found');

    $assignee_id   = get_post_meta( $post_id, '_todo_assignee', true );
    $assignee_name = $assignee_id ? get_the_author_meta('display_name', $assignee_id) : '—';
    $priority      = get_post_meta( $post_id, '_todo_priority', true ) ?: 'Normal';
    $status        = get_post_meta( $post_id, '_todo_status', true ) ?: 'Not Started';
    $deadline      = get_post_meta( $post_id, '_todo_deadline', true ) ?: '—';

    $content = apply_filters('the_content', $post->post_content);

    if ( ! $deadline ) {
        $deadline = gmdate('Y-m-d');
    }

    $now         = new DateTime();
    $deadline_dt = new DateTime($deadline);
    $interval    = $now->diff($deadline_dt);

    if ( $now > $deadline_dt ) {
        $time_left = 'Deadline passed';
    } else {
        $time_left = $interval->days . 'd ' . $interval->h . 'h ' . $interval->i . 'm left';
    }

    if ( $status == 'Completed' ) {
        $time_left = "🎉";
    } elseif ( $status == "Cancelled" ) {
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
            <h3>Comments</h3>
            <?php
                $comments = get_comments( array( 'post_id' => $post_id ) );
                foreach ( $comments as $comment ) {
                    echo '<div class="wptodo-comment" id="comment-' . esc_attr($comment->comment_ID) . '">';
                    echo '<strong>' . esc_html($comment->comment_author) . ':</strong> ';
                    echo '<p>' . esc_html($comment->comment_content) . '</p>';
                    echo '</div>';
                }
            ?>
            <form id="wptodo-comment-form">
                <textarea name="comment" rows="3" style="width:100%;" required></textarea>
                <input type="hidden" name="action" value="wp-todo_add_comment" />
                <input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>" />
                <input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-todo_action' ) ); ?>" />
                <button type="submit" class="button button-primary">Post Comment</button>
            </form>
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
        // Add clickable class to rows
        $('#the-list tr').each(function(){
            var post_id = $(this).attr('id');
            if(post_id){
                post_id = post_id.replace('post-', '');
                $(this).attr('data-post-id', post_id).addClass('wptodo-clickable');
            }
        });

        // Quick view modal
        $('#the-list').on('click', 'tr.wptodo-clickable', function(e){
            if($(e.target).closest('th, td:first-child, td:nth-child(2)').length) return;

            var post_id = $(this).data('post-id');
            if(!post_id) return;

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

        // Handle AJAX comment submit
        $(document).on('submit', '#wptodo-comment-form', function(e){
            e.preventDefault();
            var form = $(this);
            $.post(wptodo_ajax.ajax_url, form.serialize(), function(response){
                if(response.success){
                    $('#wptodo-comments h3').after(response.data.html);
                    form[0].reset();
                } else {
                    alert(response.data);
                }
            });
        });
    });
    </script>
    <?php
});

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_style('wp-admin');
});

add_action('wp_ajax_wp-todo_add_comment', function() {
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if ( ! wp_verify_nonce($nonce, 'wp-todo_action') ) {
        wp_send_json_error('Security check failed');
    }

    $commentdata = array(
        'comment_post_ID'      => intval($_POST['post_id']),
        'comment_content'      => sanitize_textarea_field($_POST['comment']),
        'user_id'              => get_current_user_id(),
        'comment_author'       => wp_get_current_user()->display_name,
        'comment_author_email' => wp_get_current_user()->user_email,
        'comment_approved'     => 1,
    );

    $comment_id = wp_new_comment($commentdata);

    if ($comment_id) {
        $comment = get_comment($comment_id);

        ob_start();
        echo '<div class="wptodo-comment" id="comment-' . esc_attr($comment->comment_ID) . '">';
        echo '<strong>' . esc_html($comment->comment_author) . ':</strong> ';
        echo '<p>' . esc_html($comment->comment_content) . '</p>';
        echo '</div>';
        $html = ob_get_clean();

        wp_send_json_success( array( 'html' => $html ) );
    } else {
        wp_send_json_error('Failed to add comment');
    }
});


// disable comments moderation for wp-todo CPT
add_filter('pre_comment_approved', function($approved, $commentdata) {
    if (isset($commentdata['comment_post_ID'])) {
        $post_type = get_post_type($commentdata['comment_post_ID']);
        if ($post_type === 'wp-todo') {
            return 1;
        }
    }
    return $approved;
}, 10, 2);
