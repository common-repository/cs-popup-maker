(function( $ ) {	
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 */
	$(function () {	

		let csPopupMaker = function() {
				let closeBtn = '.cs-popup-container .cs-popup-wrap .close-button',
					popupImg = '.cs-popup-container .cs-popup-wrap img',
					closed = false,
					csTemplate = _.template( '<div class="cs-popup-container"><div class="cs-popup-wrap"> \
												<a href="<%= target_url %>" target="<%= target %>"> \
												<img src="<%= image_url %>" alt="" > \
												</a> \
												<a class="close-button" > \
												<img src="<%= close_icon %>" alt="" > \
												</a> \
												</div></div> \
												<div class="cs-overlay"></div>'
											);

			this.init = function() {
				this.popUpContentTemplate();
				$(document).on('click', '.cs-popup-container a.close-button', () => {
					this.closePopup();
				});

				/*if click elsewhere*/
				$(window).on('click', (event) => {
				
					if ( $(closeBtn).has( event.target ).length == 0 && $( closeBtn ).is( event.target ) ) {
						/* clicked inside*/ 
					} else if ($(popupImg).has(event.target).length == 0 && $(popupImg).is(event.target) ) {
					/* clicked inside wrapper*/ 
					} else {
						if( closed == false ){
							this.closePopup();
						}                
					}
				});
			} 

			this.csPopupSession = function() {
				if (!sessionStorage.getItem('csPopupEnableSessionStatus')) {
					if ('on' == cs_obj.cs_popup_enable_session) {
						sessionStorage.setItem('csPopupEnableSessionStatus', 'yes');
						return true;					
					}
				} 
				return false;
			}		
				
			this.popUpContentTemplate = function () {
					
					if ( 1 == cs_obj.cs_popup_status && this.csPopupSession() ) {
						let content = csTemplate({
							target_url: ( '' != cs_obj.target_url ) ? cs_obj.target_url : '#',
							target: ( '' != cs_obj.target_open ) ? cs_obj.target_open : '_new',
							image_url: cs_obj.image_url,            
							close_icon : cs_obj.close_link
				
						});
						$('body').prepend(content);
					}
			}
			this.closePopup = function() {
				$('.cs-popup-container').fadeOut();
				$('.cs-overlay').fadeOut();
				$('.cs-popup-container').remove();
				$('.cs-overlay').remove();
				closed = true;
			}    
		};

		let csPopupHtmlMaker = function () {
			let closeBtn = '.cs-wrapper-full a.cs-close-pop',
				popupImg = '.cs-wrapper-full',
				closed = false;
			
			this.init = function () {
				if ( 1 == cs_html_obj.html_popup_status && this.csPopupHTMLSession() ) {
					$('body').prepend(this.getHtmlTemplate());					
					let self = this;
					$(document).on('click', '.cs-wrapper-full a.cs-close-pop',function() {
						self.closePopup();
					});

					/*if click elsewhere*/
					$(window).on('click', (event) => {
					
						if( $(closeBtn).has( event.target ).length == 0 && $( closeBtn ).is( event.target ) ){
							/* clicked inside*/ 
						}else if ($(popupImg).has(event.target).length == 0 && $(popupImg).is(event.target) ) {
						/* clicked inside wrapper*/ 
						} else {
							if( closed == false ){
								this.closePopup();
							}                
						}
					});
				}
			}

		

			this.closePopup = function() {
				$('.cs-wrapper-full').fadeOut();
				$('.cs-overlay').fadeOut();
				$('.cs-wrapper-full').remove();
				$('.cs-overlay').remove();
				closed = true;
			} 

			this.csPopupHTMLSession = function () {
			 	if ('off' == cs_html_obj.html_enable_session) {
					return true;
				} 
				if (!sessionStorage.getItem('csPopupHTMLEnableSessionStatus')) {
					if ('on' == cs_html_obj.html_enable_session) {
						sessionStorage.setItem('csPopupHTMLEnableSessionStatus', 'yes');
						return true;					
					} else {
						return true;
					}
				} 
				return false;
			}

			this.getHtmlTemplate = function() {
				let popupTemplate           = cs_html_obj.Popup,
					overlay                 = cs_html_obj.html_add_overlay,
					heading                 = cs_html_obj.html_heading_title,
					subheading              = cs_html_obj.html_subheading_title,
					html_content            = cs_html_obj.html_content,
					heading_target          = cs_html_obj.html_heading_target,
					heading_target_label    = cs_html_obj.heading_target_label,
					heading_target_openin   = cs_html_obj.html_target_openin,
					body                    = '';
				if ('' == heading) {
					heading = ''
				}

				if ('' == subheading) {
					subheading = ''
				}

				if ('' == html_content) {
					html_content = ''
				}

				if ('' == heading_target_label) {
					heading_target_label = ''
				}

				if ('' == heading_target) {
					heading_target = '#'
				}


				if ('' == heading_target_openin) {
					heading_target_openin = '_self'
				}
				  let overlay_div_open = '';
					let overlay_div_close = '';
					if ( '1' == overlay) {
						overlay_div_open = '<div class="cs-overlay">';
						overlay_div_close = ';</div>';
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
		}		
		
		new csPopupMaker().init();
		new csPopupHtmlMaker().init();
	
	});


})( jQuery );

