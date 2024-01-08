jQuery(function ($) {

	/*****************************************************************/
	/* ADD WP COLOR PICKER */
	/*****************************************************************/
	
    $( '.cpa-color-picker' ).wpColorPicker(); // Add Color Picker to all inputs that have 'cpa-color-picker' class
	
	
	/*****************************************************************/
	/* CALL WP MEDIA UPLOADER */
	/*****************************************************************/
	
	$(document).ready(function() {
		
		/* LOGO UPLOADER */
		
		var file_frame; // variable for the wp.media file_frame
		
		$( '#logo_upload_button' ).on( 'click', function( event ) {
			event.preventDefault();
			
			if ( file_frame ) {
				file_frame.open();
				return;
			}
			
			file_frame = wp.media.frames.file_frame = wp.media({
				title: $(this).val(),
				button: {
					text: $(this).val(),
				},
				multiple: false
			});

			file_frame.on( 'select', function() {
				var attachment = file_frame.state().get('selection').first().toJSON();
				$('#logo_upload').val(attachment.url);
				$('#logo_upload ~ .img-upload-container').css("background-image", "url(" + attachment.url + ")");
			});

			file_frame.open();
		});
		
		/* BACKGROUND IMAGE UPLOADER */
		
		var file_frame2; // variable for the wp.media file_frame
		
		$( '#login_bg_upload_button' ).on( 'click', function( event ) {
			event.preventDefault();
			
			if ( file_frame2 ) {
				file_frame2.open();
				return;
			}
			
			file_frame2 = wp.media.frames.file_frame = wp.media({
				title: $(this).val(),
				button: {
					text: $(this).val(),
				},
				multiple: false
			});

			file_frame2.on( 'select', function() {
				var attachment = file_frame2.state().get('selection').first().toJSON();
				$('#login_bg').val(attachment.url);
				$('#login_bg ~ .img-upload-container').css("background-image", "url(" + attachment.url + ")");
			});

			file_frame2.open();
		});
		
		/* TOOLBAR ICON UPLOADER */
		
		var file_frame3; // variable for the wp.media file_frame
		
		$( '#toolbar_icon_upload_button' ).on( 'click', function( event ) {
			event.preventDefault();
			
			if ( file_frame3 ) {
				file_frame3.open();
				return;
			}
			
			file_frame3 = wp.media.frames.file_frame = wp.media({
				title: $(this).val(),
				button: {
					text: $(this).val(),
				},
				multiple: false
			});

			file_frame3.on( 'select', function() {
				var attachment = file_frame3.state().get('selection').first().toJSON();
				$('#toolbar_icon').val(attachment.url);
				$('#toolbar_icon ~ .img-upload-container').css("background-image", "url(" + attachment.url + ")");
			});

			file_frame3.open();
		});
		
		/* LEFT MENU COMPANY LOGO UPLOADER */
		
		var file_frame4; // variable for the wp.media file_frame
		
		$( '#company_box_logo_upload_button' ).on( 'click', function( event ) {
			event.preventDefault();
			
			if ( file_frame4 ) {
				file_frame4.open();
				return;
			}
			
			file_frame4 = wp.media.frames.file_frame = wp.media({
				title: $(this).val(),
				button: {
					text: $(this).val(),
				},
				multiple: false
			});

			file_frame4.on( 'select', function() {
				var attachment = file_frame4.state().get('selection').first().toJSON();
				$('#company_box_logo').val(attachment.url);
				$('#company_box_logo ~ .img-upload-container').css("background-image", "url(" + attachment.url + ")");
			});

			file_frame4.open();
		});
		
	});
	
	/*****************************************************************/
	/* OTHER */
	/*****************************************************************/
	
	$(document).ready(function() {
		
		// add body spacer
		//$( 'body.wp-admin' ).wrapInner( '<div class="body-spacer">' );

		// add subsubsub replace
		$('.subsubsub a .count').each(function() {
			var newValue = $(this).text().replace('(', '').replace(')', '');
			$(this).text( newValue );
		});
		
		$('.subsubsub a .count').fadeOut();
		$('.subsubsub a .count').fadeIn();
		
	});
	
	
	// stretch left admin menu
		
	/*$(window).on('load', function() {
		
		var getTheHeight = $( '#wpcontent' ).outerHeight();
		$( '#adminmenuwrap' ).css( 'height', getTheHeight );
		
	});*/
	
	// Avoiding flickering to reorder the first menu item (User Box) for left toolbar
	if( $("#adminmenu li:first-child").hasClass('adminmenu-container') ) {
		// nothing	
	} else {
		$("li.adminmenu-container").prependTo("#adminmenu");
		$("#adminmenu li.menu-top-first:first-child").show();
	}    
    
    // wrap container to left wp menu images
    $( '#adminmenu .wp-menu-image img' ).wrap( '<span class="wp-menu-img-wrap"></span>' );
	
	/*****************************************************************/
	/* QUERY MONITOR */
	/*****************************************************************/
	
	if ( $('#qm').length ) { 
	
		// add close button
		$( "#qm" ).prepend( $( "<div class='query-monitor-close'>x</div>" ) );

		// show query monitor overlay
		$('#wp-admin-bar-query-monitor a').on('click touchstart', function() {
			$( '#qm' ).addClass('qm-show');
		});
		
		// hide query monitor overlay
		$('.query-monitor-close').on('click touchstart', function() {
			$( '#qm' ).removeClass('qm-show');
		});
		
	}
		
});
