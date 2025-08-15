jQuery(document).ready(function($){

    // Click handler for entire row using event delegation
    $(document).on('click', '#the-list tr.wptodo-clickable', function(e){
        // Ignore clicks on checkbox/radio or title column
        if($(e.target).closest('th, td:first-child, td:nth-child(2)').length) return;

        var post_id = $(this).data('post-id');
        if(!post_id) return;

        var $modal = $('#wptodo-single-modal');

        // Clear old content and close previous dialog
        if($modal.hasClass('ui-dialog-content')){
            $modal.dialog('close');
        }
        $modal.html('');

        // Fetch new content via AJAX
        $.post(wptodo_ajax.ajax_url, { action: 'wptodo_quick_view', post_id: post_id }, function(response){
            if(response.success){
                $modal.html(response.data);

                // Open dialog without countdown
                $modal.dialog({
                    modal: true,
                    width: 600,
                    title: 'Todo Details',
                    close: function(){
                        $modal.html('');
                    }
                });
            } else {
                alert('Failed to load todo details.');
            }
        });
    });

});
