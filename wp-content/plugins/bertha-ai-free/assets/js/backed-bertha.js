(function ($) {
    var speed = 10;
    var visualEditor = 'true';
    var redo_last_dec = '';

    focus();
    var iframeSelector = "iframe";


    var iframeListener = window.addEventListener('blur', function() {
        if (document.activeElement === document.querySelector(iframeSelector) && document.activeElement.id != 'ct-artificial-viewport') {
            visualEditor = document.activeElement.id;
            detectfame(document.activeElement.id);
        }
        window.removeEventListener('blur', iframeListener);
    });

    function detectfame(bertha_iframe) {
        if($('#'+bertha_iframe).next('.bertha').length == 0) {
            //$(".bertha").offset({ top: bertha_offset.top+5, left: bertha_offset.left+5});//top left position
            $('#bertha_backend_canvas').attr('data-block', '#'+bertha_iframe);
        }
    }

    $(document).click(function() {
        if(visualEditor) {
            var aa = $('iframe').contents().find('body');
            $(aa).focusin(function() {
                var bertha_iframe = $(this).attr('data-id');
                if($('#'+bertha_iframe+'_ifr').next('.bertha').length == 0) {
                    $('#bertha_backend_canvas').attr('data-block', '#'+bertha_iframe+'_ifr');
                }
            });
        }
    }); 

    $(document).on('focusin', '#bertha_backend_title', function() {
        var input_id = $(this).attr('id');
        if($(this).next('.bertha').length == 0 && $(this).closest('#bertha_canvas').length == 0 ) {    
            if(input_id){
                $('#bertha_backend_canvas').attr('data-block', '#'+input_id);
            }
        }
    });

    $(document).on('click', '.ber-nav-link', function() {
        var control = $(this).attr('aria-controls');
        if(control == 'LongForm') {
            $('.ber-nav-item #templates-tab').removeClass('ber-active');
            $('.ber-tab-content #templates').removeClass('ber-show ber-active');
            $('.ber-nav-item #favourite-tab').removeClass('ber-active');
            $('.ber-tab-content #favourite').removeClass('ber-show ber-active');
            $('.ber-nav-item #backedn-tab').removeClass('ber-active');
            $('.ber-tab-content #backedn').removeClass('ber-show ber-active');
            $('.ber-nav-item #history-tab').removeClass('ber-active');
            $('.ber-tab-content #history').removeClass('ber-show ber-active');
            $('.wa-generate-long-form').removeClass('hide');
            $('.wa-generate-long-form').addClass('ber_half');
            $('.bertha_backend_body_save').addClass('bertha_sec_btn');
            $('.bertha_backend_body_save').addClass('long-form');
            $('.bertha_backend_body_save').removeClass('ber-btn-primary');
            $('.wa-redo-long-form').show();
        }else if(control == 'history') {
            $('.ber-nav-item #templates-tab').removeClass('ber-active');
            $('.ber-tab-content #templates').removeClass('ber-show ber-active');
            $('.ber-nav-item #favourite-tab').removeClass('ber-active');
            $('.ber-tab-content #favourite').removeClass('ber-show ber-active');
            $('.ber-nav-item #backedn-tab').removeClass('ber-active');
            $('.ber-tab-content #backedn').removeClass('ber-show ber-active');
            $('.ber-nav-item #long-form-tab').removeClass('ber-active');
            $('.ber-tab-content #LongForm').removeClass('ber-show ber-active');
            $('.wa-generate-long-form').addClass('hide');
            $('.wa-generate-long-form').removeClass('ber_half');
            $('.bertha_backend_body_save').removeClass('bertha_sec_btn');
            $('.bertha_backend_body_save').removeClass('long-form');
            $('.bertha_backend_body_save').addClass('ber-btn-primary');
            $('.wa-redo-long-form').hide();
        } else if(control == 'templates') {
            $('.ber-nav-item #history-tab').removeClass('ber-active');
            $('.ber-tab-content #history').removeClass('ber-show ber-active');
            $('.ber-nav-item #favourite-tab').removeClass('ber-active');
            $('.ber-tab-content #favourite').removeClass('ber-show ber-active');
            $('.ber-nav-item #backedn-tab').removeClass('ber-active');
            $('.ber-tab-content #backedn').removeClass('ber-show ber-active');
            $('.ber-nav-item #long-form-tab').removeClass('ber-active');
            $('.ber-tab-content #LongForm').removeClass('ber-show ber-active');
            $('.wa-generate-long-form').addClass('hide');
            $('.wa-generate-long-form').removeClass('ber_half');
            $('.bertha_backend_body_save').removeClass('bertha_sec_btn');
            $('.bertha_backend_body_save').removeClass('long-form');
            $('.bertha_backend_body_save').addClass('ber-btn-primary');
            $('.wa-redo-long-form').hide();
        }else if(control == 'favourite') {
            $('.ber-nav-item #templates-tab').removeClass('ber-active');
            $('.ber-tab-content #templates').removeClass('ber-show ber-active');
            $('.ber-nav-item #history-tab').removeClass('ber-active');
            $('.ber-tab-content #history').removeClass('ber-show ber-active');
            $('.ber-nav-item #backedn-tab').removeClass('ber-active');
            $('.ber-tab-content #backedn').removeClass('ber-show ber-active');
            $('.ber-nav-item #long-form-tab').removeClass('ber-active');
            $('.ber-tab-content #LongForm').removeClass('ber-show ber-active');
            $('.wa-generate-long-form').addClass('hide');
            $('.wa-generate-long-form').removeClass('ber_half');
            $('.bertha_backend_body_save').removeClass('bertha_sec_btn');
            $('.bertha_backend_body_save').removeClass('long-form');
            $('.bertha_backend_body_save').addClass('ber-btn-primary');
            $('.wa-redo-long-form').hide();
        }else if(control == 'backedn') {
            $('.ber-nav-item #templates-tab').removeClass('ber-active');
            $('.ber-tab-content #templates').removeClass('ber-show ber-active');
            $('.ber-nav-item #history-tab').removeClass('ber-active');
            $('.ber-tab-content #history').removeClass('ber-show ber-active');
            $('.ber-nav-item #favourite-tab').removeClass('ber-active');
            $('.ber-tab-content #favourite').removeClass('ber-show ber-active');
            $('.ber-nav-item #long-form-tab').removeClass('ber-active');
            $('.ber-tab-content #LongForm').removeClass('ber-show ber-active');
            $('.wa-generate-long-form').addClass('hide');
            $('.wa-generate-long-form').removeClass('ber_half');
            $('.bertha_backend_body_save').removeClass('bertha_sec_btn');
            $('.bertha_backend_body_save').removeClass('long-form');
            $('.bertha_backend_body_save').addClass('ber-btn-primary');
            $('.wa-redo-long-form').hide();
        }
        $(this).addClass('ber-active');
        $('.ber-tab-content #'+control).addClass('ber-show ber-active');
    });

    $(document).on('click', '.bertha_draft_edit', function(e) {
        e.preventDefault();
        var body_text = $(this).closest('.ber-d-grid').find('.bertha_draft_body pre').html();
        var title_text = $(this).closest('.ber-d-grid').find('.bertha_draft_number').html();
        var draft_id = $(this).attr('data-id');
        $('.ber_full_content button').prop('disabled', true);
        $(".ber_full_content").find('.ber-overlay-container').show();
        var ajaxurl = bertha_backedn_object.ajax_url;
        var data = {
            action   : 'long_form_edit_draft_ai_action',
            draft_id : draft_id,
            bertha_draft_edit_nonce: bertha_backedn_object.edit_darft_nonce
        }  

        $.post(ajaxurl, data, function(response) {
            var result = JSON.parse(response);
            if(result['draft_body'] != 'false') {
                $('.ber_full_content button').prop('disabled', false);
                $(".ber_full_content").find('.ber-overlay-container').hide();
                $('#bertha_backend_title').val(title_text);
                $('#bertha_backend_body_ifr').contents().find('body').html(result['draft_body']);
                $('#edited_draft').val(draft_id);
            }
        });
    });

    function getCaretCharacterOffsetWithin(element) {
        var doc = element.ownerDocument || element.document;
        var win = doc.defaultView || doc.parentWindow;
        var sel, range, preCaretRange, caretOffset = 0;
        if (typeof win.getSelection != "undefined") {
            sel = win.getSelection();
            if (sel.rangeCount) {
                range = sel.getRangeAt(0);
                preCaretRange = range.cloneRange();
                preCaretRange.selectNodeContents(element);
                preCaretRange.setEnd(range.endContainer, range.endOffset);
                caretOffset = preCaretRange.toString();
            }
        } else if ( (sel = doc.selection) && sel.type != "Control") {
            range = doc.selection.createRange();
            preCaretRange = doc.body.createTextRange();
            preCaretRange.moveToElementText(element);
            preCaretRange.setEndPoint("EndToEnd", textRange);
            caretOffset = preCaretTextRange.text;
        }
        return caretOffset;
    }

    $(document).on('click', '.wa-generate-long-form, .wa-redo-long-form', function(e) {
        e.preventDefault();
        $(".ber_full_content").find('.ber-overlay-container').show();
        var templates = bertha_setup_object.free_options;
        var plugin_type = bertha_setup_object.plugin_type ? bertha_setup_object.plugin_type : '';
        if(templates.long_form_version || plugin_type == 'pro') {
            var b_this = $(this);
            var bertha_iframe = document.getElementById("bertha_backend_body_ifr");
            var bertha_body = (bertha_iframe.contentDocument || bertha_iframe.contentWindow.document).body;
            bertha_body_offset = getCaretCharacterOffsetWithin(bertha_body);
            var offset = bertha_body_offset.length;
            var win = '';
            var sel, range, preCaretRange, caretOffset = redo_element = 0;
            if(offset && offset > 1) {
                var doc = bertha_body.ownerDocument || bertha_body.document;
                var win = doc.defaultView || doc.parentWindow;
                sel = win.getSelection();
                range = sel.getRangeAt(0);
            }
            var keyword = $('.long_form_keyword').val();
            var title = $('.long_form_title').val();
            var desc = $('.long_form_desc').val();
            var audience = $('.long_form_audience').val();
            var tone = $('.long_form_tone').val();
            var cur_html = $("#bertha_backend_body_ifr").contents().find("body").html();
            if($("#bertha_backend_body_ifr").contents().find("body").text()) {
                if(offset && offset > 1) var last_dec = bertha_body_offset;
                else var last_dec = $("#bertha_backend_body_ifr").contents().find("body").text();

                if($(this).hasClass('wa-redo-long-form')) {
                    $("#bertha_backend_body_ifr").contents().find("body p").each(function() {
                        if($(this).text() == redo_last_dec) redo_element = $(this).index() + 1;
                    })
                    var aa = cur_html.replace(redo_last_dec, '');
                    $("#bertha_backend_body_ifr").contents().find("body").html(aa);
                }

            } else {
                var last_dec = '';
            }
            if(title == '' || desc == '' || audience == '' || tone == '') {
                $(this).after("<div class='ber-notice-content'>Kindly fill in all the Long Form fields for optimal results</div>");
                setTimeout(function(){
                  $('.ber-notice-content').remove();
                }, 5000);
            } else {
                $('.ber_full_content button').prop('disabled', true);
                var ajaxurl = bertha_backedn_object.ajax_url;
                var data = {
                    action   : 'long_form_ai_action',
                    bertha_keyword : keyword,
                    bertha_title : title,
                    bertha_desc : desc,
                    bertha_audience : audience,
                    bertha_tone : tone,
                    bertha_body_offset : bertha_body_offset,
                    bertha_form_body  :  $("#bertha_backend_body_ifr").contents().find("body").text(),
                    bertha_desc_index : $(this).attr('data-index'),
                    data_title : $(this).attr('data-title'),
                    data_keyword : $(this).attr('data-keyword'),
                    data_desc : $(this).attr('data-desc'),
                    data_audience : $(this).attr('data-audience'),
                    data_tone : $(this).attr('data-tone'),
                    last_dec : last_dec,
                    bertha_long_form_nonce: $('#bertha_long_form_nonce').val()
                }  
                $.post(ajaxurl, data, function(response) {
                    var result = JSON.parse(response);
                    $('.ber_full_content button').prop('disabled', false);
                    $(".ber_full_content").find('.ber-overlay-container').hide();
                    if(result['initial_token_covered']) {
                        var modal = '<div class="ber-modal ber-fade ber_modal" id="ber_token_covered_modal" tabindex="-1" role="dialog" aria-labelledby="berIdeaTitle" aria-hidden="true"><div class="ber-modal-dialog ber-modal-dialog-centered" role="document"><div class="ber-modal-content"><div class="ber-modal-header"><div class="ber-modal-title" id="berIdeaLongTitle"><div class="ber-token-primary-title">UH oh... Looks like you ran out of your daily words allocations.</div><div class="ber-report-secondary-title">Don\'t worry, you can wait untill tomorrow and get a new batch of words OR... you can click the big button below to upgrade and unlock more words from Bertha and speed up your writing even more!</div></div><button type="button" class="ber-token-covered-btn-close ber-token-close"><span aria-hidden="true">&times;</span></button></div><div class="ber-modal-footer"><button type="button" class="ber-btn ber_half bertha_sec_btn ber-token-close" data-dismiss="ber-modal">I\'ll wait</button><button type="button" class="ber-btn ber_half ber-btn-primary ber-token-covered-continue" data-dismiss="ber-modal">Upgrade now</button></div><div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div></div></div></div></div>';
                        $(top.document).find('body').append(modal);
                        $(top.document).find('body #ber_token_covered_modal').show();
                    } else if(result['license_expired']) {
                        var modal = '<div class="ber-modal ber-fade ber_modal" id="ber_token_covered_modal" tabindex="-1" role="dialog" aria-labelledby="berIdeaTitle" aria-hidden="true"><div class="ber-modal-dialog ber-modal-dialog-centered" role="document"><div class="ber-modal-content"><div class="ber-modal-header"><div class="ber-modal-title" id="berIdeaLongTitle"><div class="ber-token-primary-title">Oops, Looks like your license has expired.</div><div class="ber-report-secondary-title">please click <a href="https://bertha.ai/#doit">UPGRADE</a> to renew.</div></div><button type="button" class="ber-token-covered-btn-close ber-token-close"><span aria-hidden="true">&times;</span></button></div><div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div></div></div></div></div>';
                            $(top.document).find('body').append(modal);
                            $(top.document).find('body #ber_token_covered_modal').show();
                    } else{
                        if(result['token_denied']) {
                            b_this.after("<div class='ber-notice-content'>"+result['token_denied']+"</div>");
                            setTimeout(function(){
                              $('.ber-notice-content').remove();
                            }, 5000);
                        } else {
                            $('.bertha_draft_format_notice').show();
                            setTimeout(function(){
                                $('.bertha_draft_format_notice').hide();
                            }, 5000);
                            var draftAudio = new Audio(bertha_backedn_object.bertha_sound);
                            draftAudio.play();
                            var html = result['html'];
                            $('#bertha_backend_title').val(title);
                            redo_last_dec = html;
                            if(html.length > 1) $('#bertha_backend_body_ifr').addClass('ber-disabled-click');
                            insertDraft(html, 0, 0, draftAudio, offset, range, win, redo_element);
                        }
                    }
                });
            }
        } else {
            $(".ber_full_content").find('.ber-overlay-container').hide();
            var modal = '<div class="ber-modal ber-fade ber_modal" id="ber_token_covered_modal" tabindex="-1" role="dialog" aria-labelledby="berIdeaTitle" aria-hidden="true"><div class="ber-modal-dialog ber-modal-dialog-centered" role="document"><div class="ber-modal-content"><div class="ber-modal-header"><div class="ber-modal-title" id="berIdeaLongTitle"><div class="ber-token-primary-title">This is premium feature.</div></div><button type="button" class="ber-token-covered-btn-close ber-token-close"><span aria-hidden="true">&times;</span></button></div><div class="ber-modal-footer"><a href="https://bertha.ai/#doit" target="_blank"><button type="button" class="ber-btn ber-btn-primary" data-dismiss="ber-modal">Click To Upgrade</button></a></div><div class="ber-overlay-container"><div class="ber-loader"><div></div><div></div></div></div></div></div></div>';
            $(top.document).find('body').append(modal);
            $(top.document).find('body #ber_token_covered_modal').show();
        }
    });

    function insertDraft(txt, i, j, draftAudio, offset, range, win, redo_element) {
        if (i <= txt.length) {
            var cur = $('#bertha_backend_body_ifr').contents().find('body').html();
            if(redo_element) {
                var redo_cur = $('#bertha_backend_body_ifr').contents().find('body p:nth-child('+redo_element+')');
                var redo_cur_html = redo_cur.text();
                redo_cur.text(redo_cur_html+txt.charAt(i));
            } else {
                if(offset && offset > 1) {
                    range.insertNode(win.document.createTextNode(txt.charAt(i)));
                    range.collapse(false);
                } else {
                    $('#bertha_backend_body_ifr').contents().find('body').html(cur+txt.charAt(i));
                }
            }
            i++;
            setTimeout(function() {
                insertDraft(txt, i, j, draftAudio, offset, range, win, redo_element);
            }, speed);
            if(i == txt.length) {
                var cur = $('#bertha_backend_body_ifr').contents().find('body').html();
                $('#bertha_backend_body_ifr').contents().find('body').html(cur);
                $('#bertha_backend_body_ifr').removeClass('ber-disabled-click');
                draftAudio.pause();
            }
        }
    }

    $(document).on('click', '.bertha_backend_body_save', function(e) {
        e.preventDefault();
        var title = $('#bertha_backend_title').val();
        var body = $('#bertha_backend_body_ifr').contents().find('body').html();
        var edited_draft = $('#edited_draft').val();
        var save_type ='';
        if($(this).hasClass('bertha_post_draft')) save_type = 'post_draft';
        else save_type = 'form_draft';
        if(title == '' || body == '') {
            $(this).after("<div class='ber-notice-content'>All Fields are Required.</div>");
            setTimeout(function(){
              $('.ber-notice-content').remove();
            }, 5000);
        } else {
            $('.ber_full_content button').prop('disabled', true);
            $(".ber_full_content").find('.ber-overlay-container').show();
            var ajaxurl = bertha_backedn_object.ajax_url;
            var data = {
                action   : 'long_form_save_draft_ai_action',
                bertha_body : body,
                bertha_title : title,
                bertha_draft : edited_draft,
                save_type : save_type,
                bertha_long_form_nonce: $('#bertha_long_form_nonce').val()
            }  

            $.post(ajaxurl, data, function(response) {
                var result = JSON.parse(response);
                $('.ber_full_content button').prop('disabled', false);
                $(".ber_full_content").find('.ber-overlay-container').hide();
                $(document).find('.bertha-backend-content').html(result['drafts']);
            });
        }
    });
})(jQuery);