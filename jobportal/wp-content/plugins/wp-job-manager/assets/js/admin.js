jQuery(document).ready(function($) {
	// Tooltips
	$( ".tips, .help_tip" ).tipTip({
		'attribute' : 'data-tip',
		'fadeIn' : 50,
		'fadeOut' : 50,
		'delay' : 200
	});

	// Datepicker
	$( "input#_job_expires" ).datepicker({
		dateFormat: 'yy-mm-dd',
		minDate: 0
	});

	// Uploading files
	var file_frame;
	var file_target_input;
	var file_target_wrapper;

	$('.wp_job_manager_add_another_file_button').live('click', function( event ){
		event.preventDefault();

		var wrapper           = $( this ).closest( '.form-field' );
		var field_name        = $( this ).data( 'field_name' );
		var field_placeholder = $( this ).data( 'field_placeholder' );
		var button_text       = $( this ).data( 'uploader_button_text' );
		var button            = $( this ).data( 'uploader_button' );

		wrapper.prepend('<span class="file_url"><input type="text" name="' + field_name + '[]" placeholder="' + field_placeholder + '" /> <button class="button wp_job_manager_upload_file_button" data-uploader_button_text="' + button_text + '">' + button + '</button></span>');
	});

	$('.wp_job_manager_upload_file_button').live('click', function( event ){
	    event.preventDefault();

	    file_target_wrapper = $( this ).closest('.file_url');
	    file_target_input   = file_target_wrapper.find('input');

	    // If the media frame already exists, reopen it.
	    if ( file_frame ) {
			file_frame.open();
			return;
	    }

	    // Create the media frame.
	    file_frame = wp.media.frames.file_frame = wp.media({
			title: $( this ).data( 'uploader_title' ),
			button: {
				text: $( this ).data( 'uploader_button_text' ),
			},
			multiple: false  // Set to true to allow multiple files to be selected
	    });

	    // When an image is selected, run a callback.
	    file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();

			$( file_target_input ).val( attachment.url );
	    });

	    // Finally, open the modal
	    file_frame.open();
	});
});