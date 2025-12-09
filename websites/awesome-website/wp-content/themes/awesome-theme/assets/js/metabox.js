jQuery(document).ready(function($) {
    'use strict';
    
    var file_frame;
    
    // Upload image button
    $(document).on('click', '.awesome-upload-image', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var fieldId = button.data('field');
        var field = $('#' + fieldId);
        var preview = button.siblings('.awesome-image-preview');
        
        // If the media frame already exists, reopen it
        if (file_frame) {
            file_frame.open();
            return;
        }
        
        // Create the media frame
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        // When an image is selected, run a callback
        file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            
            // Set the field value
            field.val(attachment.id);
            
            // Update preview
            preview.html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto; display: block; margin: 10px 0;">');
            
            // Show remove button
            if (!button.siblings('.awesome-remove-image').length) {
                button.after('<button type="button" class="button awesome-remove-image" data-field="' + fieldId + '">Remove Image</button>');
            }
        });
        
        // Finally, open the modal
        file_frame.open();
    });
    
    // Remove image button
    $(document).on('click', '.awesome-remove-image', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var fieldId = button.data('field');
        var field = $('#' + fieldId);
        var preview = button.siblings('.awesome-image-preview');
        
        // Clear the field value
        field.val('');
        
        // Clear preview
        preview.html('');
        
        // Remove the button
        button.remove();
    });
});
