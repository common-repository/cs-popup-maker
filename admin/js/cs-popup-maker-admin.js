(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(function (jQuery) {
		$(document).on('click', '#cs-popup-image-button', function (e) {
			e.preventDefault();

			var custom_uploader = wp.media({
				title: 'Popup Image',
				button: {
					text: 'Upload Image'
				},
				multiple: false  // Set this to true to allow multiple files to be selected
			})
				.on('select', function () {
					var attachment = custom_uploader.state().get('selection').first().toJSON();
					$('#cs-popup-image-show').attr('src', attachment.url);
					$('#cs_popup_image').val(attachment.url);

				})
				.open();
		});
	

        
        $('.cs-popup-maker .ui.checkbox').checkbox();
        $('.cs-popup-maker .ui.radio.checkbox').checkbox();
        $('.cs-popup-maker select.ui.dropdown').dropdown()
        
        $('.cs-popup-maker .menu .item').tab({
            onVisible: function (tabPath) { 
                if ('cs-popup-html' == tabPath) {
                    cs_popup_html_template_preview();
                }
            }
        });

		//Initiate Color Picker
        $('.wp-color-picker-field').wpColorPicker({
            change: function(hsb, hex, rgb) {
                //do something on color change here
                cs_popup_html_template_preview()
            }
        });

        // Switches option sections
        $('.group').hide();
        var activetab = '';
        if (typeof(localStorage) != 'undefined' ) {
            activetab = localStorage.getItem("activetab");
        }

        //if url has section id as hash then set it as active or override the current local storage value
        if(window.location.hash){
            activetab = window.location.hash;
            if (typeof(localStorage) != 'undefined' ) {
                localStorage.setItem("activetab", activetab);
            }
        }

        if (activetab != '' && $(activetab).length ) {
            $(activetab).fadeIn();
        } else {
            $('.group:first').fadeIn();
        }
        $('.group .collapsed').each(function(){
            $(this).find('input:checked').parent().parent().parent().nextAll().each(
            function(){
                if ($(this).hasClass('last')) {
                    $(this).removeClass('hidden');
                    return false;
                }
                $(this).filter('.hidden').removeClass('hidden');
            });
        });

        if (activetab != '' && $(activetab + '-tab').length ) {
            $(activetab + '-tab').addClass('nav-tab-active');
        }
        else {
            $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
        }
        $('.nav-tab-wrapper a').click(function(evt) {
            $('.nav-tab-wrapper a').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active').blur();
            var clicked_group = $(this).attr('href');
            if (typeof(localStorage) != 'undefined' ) {
                localStorage.setItem("activetab", $(this).attr('href'));
            }
            $('.group').hide();
            $(clicked_group).fadeIn();
            evt.preventDefault();
        });

        $('.cs-popup-maker-browse').on('click', function (event) {
            event.preventDefault();

            var self = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: self.data('uploader_title'),
                button: {
                    text: self.data('uploader_button_text'),
                },
                multiple: false
            });

            file_frame.on('select', function () {
                let attachment = file_frame.state().get('selection').first().toJSON();                            
                if (attachment) {
                    $('#'+self.data('id')).val(attachment.id);
                    var img = document.createElement('img');
                    img.src = attachment.url;
                    img.width = 250;
                    img.height = 150;
                    document.getElementsByClassName('cs-popup-maker-image')[0].innerHTML = "";
                    document.getElementsByClassName('cs-popup-maker-image')[0].appendChild(img);
                }
            });

            // Finally, open the modal
            file_frame.open();
        });



        $(document).on('keyup', 'input[name="cs-popup-html[html_heading_title]"],input[name="cs-popup-html[html_subheading_title]"],input[name="cs-popup-html[html_content]"],input[name="cs-popup-html[html_content]"],input[name="cs-popup-html[heading_target]"],input[name="cs-popup-html[heading_target_label]"]', function (e) {
            cs_popup_html_template_preview();
         });
        
        $(document).on('change', 'input[name="cs-popup-html[Popup]"],input[name="cs-popup-html[html_add_overlay]"],input[name="cs-popup-html[heading_target]"],select[name="cs-popup-html[html_target_openin]"]', function (e) {
            cs_popup_html_template_preview();
        });

        tinyMCE.activeEditor.on('keyup', function (e) {
            cs_popup_html_template_preview();
            
        })
       
        
        function cs_popup_html_template_preview() {
            let body = '<!doctype html> \
                        <link rel="stylesheet" href="' + cs_admin_obj.plugin_popup_css_url + '" type="text/css" media="all" /> \
						<html> \
						<body id="cs-body">';
            
			
			body += getHtmlTemplate();
			body += '</body> \
			</html>';
           
            let el = document.getElementById('html-template-popup-preview'),
                
                ifrm = document.createElement("iframe");
                ifrm.setAttribute("id", "cs-preview-iframe");

                ifrm.src = "about:blank";
                ifrm.style.width = "100%";
                ifrm.style['min-height'] = "600px";
                el.innerHTML = '';
                el.appendChild(ifrm);
			let outty = ifrm.contentWindow.document;
			outty.open('text/html', 'replace');
			outty.write(body);
			outty.close();
        }

        function getHtmlTemplate() {
            let popupTemplate           = $('input[name="cs-popup-html[Popup]"]:checked').val(),
                overlay                 = $('input[name="cs-popup-html[html_add_overlay]"]:checked').val(),
                heading                 = $('input[name="cs-popup-html[html_heading_title]"]').val(),
                subheading              = $('input[name="cs-popup-html[html_subheading_title]"]').val(),
                html_content            = tinyMCE.activeEditor.getContent(),
                heading_target          = $('input[name="cs-popup-html[heading_target]"]').val(),
                heading_target_label    = $('input[name="cs-popup-html[heading_target_label]"]').val(),
                heading_target_openin = $('select[name="cs-popup-html[html_target_openin]"]').val(),
                
                html_background_color   = $('input[name="cs-popup-html[html_background_color]"]').val(),
                html_text_color   = $('input[name="cs-popup-html[html_text_color]"]').val(),
                body = '';
            
                
                if ( 'undefined' == html_background_color || '' == html_background_color) {
                    html_background_color = '#e1404';
                }
                if ( 'undefined' == html_text_color || '' == html_text_color) {
                    html_text_color = '#000000';
                }
         
            body = '<style> \
                .cs-wrapper-full .cs-pop-up,\
				.cs-wrapper-full .cs-pop-up-left-bottom, \
				.cs-wrapper-full .cs-pop-up-center-bottom, \
				.cs-wrapper-full .cs-pop-up-full-bottom, \
                .cs-wrapper-full .cs-pop-up-right-bottom \
                { \
                    background: ' + html_background_color + ' !important; \
                    color: ' + html_text_color + ' !important; \
                } \
                .cs-wrapper-full .cs-button { \
                    color: ' + html_text_color + ' !important; \
                } \
                </style>';
            
            if ( 'undefined' == heading || '' == heading) {
                heading = ''
            }

            let overlay_div_open = '';
            let overlay_div_close = '';
            if ( '1' == overlay) {
                overlay_div_open = '<div class="cs-overlay">';
                overlay_div_close = ';</div>';
            }

            if ( 'undefined' == subheading || '' == subheading ) {
                subheading = ''
            }

            if ( 'undefined' == html_content || '' == html_content) {
                html_content = ''
            }

            if ('undefined' == html_content || '' == heading_target_label) {
                heading_target_label = ''
            }

             if ( 'undefined' == heading_target || '' == heading_target) {
                heading_target = '#'
            }


            if ( 'undefined' == heading_target_openin || '' == heading_target_openin) {
                heading_target_openin = '_self'
            }

            if (2 == popupTemplate) {
                body += '<div class="cs-wrapper-full"> \
                            '+ overlay_div_open + '<div class="cs-pop-up"> \
                                <a class="cs-close-pop" href="#">&times;</a> \
                                <div class="cs-heading"> \
                                    <h1>' + heading + '</h1> \
                                </div> \
                                <div class="cs-subheading"> \
                                    <h2>' + subheading + '</h2> \
                                </div> \
                                <div class="cs-content"> \
                                    ' + html_content + ' \
                                </div> \
                                <a class="cs-button" href="' + heading_target + '" target="' + heading_target_openin+ '"> ' + heading_target_label + ' \
                                </a> \
                            </div>' + overlay_div_close +'\
                        </div>';
            } else if (3 == popupTemplate) {
                body += '<div class="cs-wrapper-full"> \
                            '+ overlay_div_open + '<div class="cs-pop-up-left-bottom"> \
                                <a class="cs-close-pop" href="#">&times;</a> \
                                <div class="cs-heading"> \
                                    <h1>' + heading + '</h1> \
                                </div> \
                                <div class="cs-subheading"> \
                                    <h2>' + subheading + '</h2> \
                                </div> \
                                <div class="cs-content"> \
                                    ' + html_content + ' \
                                </div> \
                                <a class="cs-button" href="' + heading_target + '" target="' + heading_target_openin+ '"> ' + heading_target_label + ' \
                                </a> \
                            </div>' + overlay_div_close +' \
                        </div>';
            } else if (4 == popupTemplate) {
                 body += '<div class="cs-wrapper-full"> \
                           '+ overlay_div_open + '<div class="cs-pop-up-right-bottom"> \
                                <a class="cs-close-pop" href="#">&times;</a> \
                                <div class="cs-heading"> \
                                    <h1>' + heading + '</h1> \
                                </div> \
                                <div class="cs-subheading"> \
                                    <h2>' + subheading + '</h2> \
                                </div> \
                                <div class="cs-content"> \
                                    ' + html_content + ' \
                                </div> \
                                <a class="cs-button" href="' + heading_target + '" target="' + heading_target_openin+ '"> ' + heading_target_label + ' \
                                </a> \
                            </div>' + overlay_div_close +' \
                        </div>';
            } else if (5 == popupTemplate) {
                body += '<div class="cs-wrapper-full"> \
                             '+ overlay_div_open + '<div class="cs-pop-up-center-bottom"> \
                                <a class="cs-close-pop" href="#">&times;</a> \
                                <div class="cs-heading"> \
                                    <h1>' + heading + '</h1> \
                                </div> \
                                <div class="cs-subheading"> \
                                    <h2>' + subheading + '</h2> \
                                </div> \
                                <div class="cs-content"> \
                                    ' + html_content + ' \
                                </div> \
                                <a class="cs-button" href="' + heading_target + '" target="' + heading_target_openin+ '"> ' + heading_target_label + ' \
                                </a> \
                            </div>' + overlay_div_close +' \
                        </div>';
            } else if (6 == popupTemplate) {
                body += '<div class="cs-wrapper-full"> \
                             '+ overlay_div_open + '<div class="cs-pop-up-full-bottom"> \
                                <a class="cs-close-pop" href="#">&times;</a> \
                                <div class="cs-heading"> \
                                    <h1>' + heading + '</h1> \
                                </div> \
                                <div class="cs-subheading"> \
                                    <h2>' + subheading + '</h2> \
                                </div> \
                                <div class="cs-content"> \
                                    ' + html_content + ' \
                                </div> \
                                <a class="cs-button" href="' + heading_target + '" target="' + heading_target_openin+ '"> ' + heading_target_label + ' \
                                </a> \
                            </div>' + overlay_div_close +' \
                        </div>';
            } else {
                // default 1
                body += '<div class="cs-wrapper-full"> \
                           '+ overlay_div_open + ' \
                                <div class="cs-pop-up"> \
                                    <a class="cs-close-pop" href="#">&times;</a> \
                                    <div class="cs-heading"> \
                                        <h1>' + heading + '</h1> \
                                    </div> \
                                    <div class="cs-subheading"> \
                                        <h2>' + subheading + '</h2> \
                                    </div> \
                                    <div class="cs-content"> \
                                        ' + html_content + ' \
                                    </div> \
                                    <a class="cs-button" href="' + heading_target + '" target="' + heading_target_openin+ '"> ' + heading_target_label + ' \
                                    </a> \
                                </div> \
                            ' + overlay_div_close +' \
                        </div>';
            }

            return body;
        }

	});

})( jQuery );
