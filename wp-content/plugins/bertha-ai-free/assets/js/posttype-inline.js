jQuery(document).ready(function($) {
	$(document).on('click', '.ber-edit-descrition', function(e) {
		e.preventDefault();
		var ths = $(this);
		var post_id = ths.attr('data-id');
		
		var ajaxurl = bertha_posttype_object.ajax_url;
        var data = {
            action   : 'wa_bertha_edit_description',
            post_id  : post_id,
            bertha_edit_description_nonce: bertha_posttype_object.description_nonce
        } 
        $.post(ajaxurl, data, function(response) {
            ths.closest('.column-bthai-product-header, .column-bthai-download-header').html("<div class='ber-edit-desc-container'><textarea class='ber-edit-description-content' rows='6' cols='35'>"+response+"</textarea><div class='ber-actions'><input type='button' class='button button-primary save ber-desc-update' value='Update' data-id='"+post_id+"' /><input type='button' class='button cancel ber-desc-cancel' value='Cancel' data-id='"+post_id+"' /></div></div>");
        });
	});

	$(document).on('click', '.ber-desc-cancel', function(e) {
		e.preventDefault();
		var content = $(this).closest('.ber-actions').prev('.ber-edit-description-content').val();
		var post_id = $(this).attr('data-id');
		if(content) {
			if(content.length > 199) {
				var more_tags = content.substr(0, 199)+' <a href="'+bertha_posttype_object.admin_url+'post.php?post='+post_id+'&action=edit">[See More]</a>';
			} else {
				var more_tags = content;
			}
			more_tags = more_tags+' <a href="#"><span data-id="'+post_id+'" class="ber-edit-descrition">Edit</span></a>';
		} else {
			var more_tags = '';
		}
		$(this).closest('.column-bthai-product-header, .column-bthai-download-header').html(more_tags);
	});

	$(document).on('click', '.ber-desc-update', function(e) {
		e.preventDefault();
		var ths = $(this);
		ths.prop('disabled', true);
		var post_id = ths.attr('data-id');
		var content = ths.closest('.ber-actions').prev('.ber-edit-description-content').val();
		
		var ajaxurl = bertha_posttype_object.ajax_url;
        var data = {
            action   : 'wa_bertha_update_description',
            post_id  : post_id,
            content  : content,
            bertha_update_description_nonce: bertha_posttype_object.description_nonce
        } 
        $.post(ajaxurl, data, function(response) {
            if(content) {
				if(content.length > 199) {
					var more_tags = content.substr(0, 199)+' <a href="'+bertha_posttype_object.admin_url+'post.php?post='+post_id+'&action=edit">[See More]</a>';
				} else {
					var more_tags = content;
				}
				more_tags = more_tags+' <a href="#"><span data-id="'+post_id+'" class="ber-edit-descrition">Edit</span></a>';
			} else {
				var more_tags = '';
			}
			ths.closest('.column-bthai-product-header, .column-bthai-download-header').html(more_tags);
        });
	});
});