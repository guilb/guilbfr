jQuery.noConflict();

(function($, window, document){
	/*global ApolloParams, a13_no_ajax_pages, Modernizr, debounce, throttle, _, Backbone, a2a_config, a2a */
	"use strict";

	//Detect high res displays (Retina, HiDPI, etc...)
	Modernizr.addTest('highresdisplay', function(){
		if (window.matchMedia) {
			var mq = window.matchMedia("only screen and (-moz-min-device-pixel-ratio: 1.3), only screen and (-o-min-device-pixel-ratio: 2.6/2), only screen and (-webkit-min-device-pixel-ratio: 1.3), only screen and (min-device-pixel-ratio: 1.3), only screen and (min-resolution: 1.3dppx)");
			return (mq && mq.matches);
		}
		return false;
	});

	//for downloading new JavaScript files while working in AJAX mode
	window.GetScripts = function( scripts, onScript, onComplete )
	{
		this.async = true;
		this.cache = true;
		this.data = null;
		this.complete = function () {
			//append new script tag
			var new_script = document.createElement('script');
			new_script.type = 'text/javascript';
			new_script.src = this.scripts[ this.progress ];
			$body.append(new_script);

			$.scriptHandler.loaded();
		};
		this.scripts = scripts;
		this.onScript = onScript;
		this.onComplete = onComplete;
		this.total = scripts.length;
		this.progress = 0;
	};

	GetScripts.prototype.fetch = function() {
		$.scriptHandler = this;
		if(this.scripts.length === 0){
			this.complete();
		}
		else{
			var src = this.scripts[ this.progress ];
			//console.log('%cFetching %s','color:#ffbc2e;', src);

			$.ajax({
				crossDomain:true,
				async:this.async,
				cache:this.cache,
				type:'GET',
				url: src,
				data:this.data,
				statusCode: {
					200: this.complete()
				},
				dataType:'script'
			});
		}
	};

	GetScripts.prototype.loaded = function () {
		this.progress++;
		if( this.progress >= this.total ) {
			if(this.onComplete) this.onComplete();
		} else {
			this.fetch();
		}
		if(this.onScript) this.onScript();
	};

	//no hiding animation when loading filtered bricks in isotope
	Isotope.Item.prototype.hide = function() {
		// set flag
		this.isHidden = true;
		// just hide
		this.css({ display: 'none' });
	};

	window.getParameterByName = function(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	};

	var A13,
		$html       = $(document.documentElement),
		body        = document.body,
		$body       = $(body),
		$window     = $(window),
		G           = ApolloParams,
		is_touch    = 'ontouchstart' in window || !!(navigator.msMaxTouchPoints),
		is_hd_screen= Modernizr.highresdisplay,
		click_event = 'click touchstart',

		header, header_tools, footer, mid,//DOM elements

		break_point = [600,768,1024]; //media queries break points

	window.A13 = { //A13 = APOLLO 13
		//run after DOM is loaded
		onReady : function(){
			if(typeof $.fn.isotope !== 'undefined'){
				//add scroll bar to fix some delayed resize issues
				$html.addClass('show-scroll');
			}

			header 			= $('#header');
			header_tools 	= $('#header-tools');
			footer 			= $('#footer');
			mid 			= $('#mid');



			//bind resize
			$window.resize(debounce(A13.layout.resize, 250));
			$body.on('webfontsloaded', function(){
				$window.resize();
			});

			//set current size
			A13.layout.set();

			A13.runPlugins();
			if( parseInt(G.is_ajaxed, 10) ){
				A13.ajaxify();
			}
			A13.elementsActions.init();
		},

		//for resizing with media queries
		layout : {
			pointers : [],

			size : 0,

			add : function(f){
				A13.layout.pointers.push(f);
			},

			remove : function(f){
				var pointers = A13.layout.pointers;

				//call each registered for resize function
				for(var i = 0; i < pointers.length; i++){
					if(pointers[i] === f){
						delete pointers[i];
					}
				}
			},

			set : function(){
				var size = !window.getComputedStyle? null : getComputedStyle(body,':after').getPropertyValue('content'),
					width = $window.width(),
					index = (size === null)? -1 : size.indexOf("narrow"),
					to_return;

				//if we can get value of current media query(normal desktop browsers)
				if(index !== -1){
					to_return = parseInt(size.substr(index + 6), 10);
				}
				//most mobile browsers can't get above so we get normal window measure
				else{
					to_return = width;
				}

				A13.layout.size = to_return;

				return to_return;
			},

			resize : function(e){
				var A = A13.layout,
					size = A.set(),
					pointers = A.pointers;

                //console.log('window size: '+$window.width()+' X '+$window.height());

				//call each registered for resize function
				for(var i = 0; i < pointers.length; i++){
					if(pointers[i] !== undefined){
						pointers[i].call(this, e, size);
					}
				}
			}
		},

		runPlugins : function(){
			//Resize iframe videos (YT, VIMEO)
			$("div.post-media").fitVids();

			//cookie for HIGH DPI screens
			if(is_hd_screen){
				A13.cookieExpire('a13_screen_size=high', 365*24);
			}
		},

		cookieExpire : function(name_value, hours){
			var d = new Date(),
				expires;
			d.setTime(d.getTime()+(hours*60*60*1000));
			expires = d.toGMTString();
			document.cookie=name_value+"; expires="+expires+"; path=/";
		},

		ajaxify : function(){
			var current_url = location.href,
				site_url = G.site_url,
				site_hidden = true,

				cleanCurrentContent = function () {
					site_hidden = false;
					$('html, body').animate({scrollTop: 0}, 400, function(){
						site_hidden = true;
					});
				},

				//sliding in current page
				showNewContent = function(text) {
					//chrome is very fast in retriving cached pages
					if(!site_hidden){
						return setTimeout(function(){ showNewContent(text); }, 150);
					}

					var docType 		= /<\!DOCTYPE[^>]*>/i,
						tagso 			= /<(html|head|title|meta|script|link)([\s>])/gi,
						tagBodyO 		= /<(body).*?class="([^"]+)"/gi,// so we can copy body classes
						tagBodyR 		= '<div class="a13-ajax-$1 $2"',
						tagsc 			= /<\/(html|body|head|body|title|meta|script|link)\>/gi,
						div_replacement = '<div class="a13-ajax-$1"$2',

						//make safe HTML
						new_text	= String(text).replace(docType, "")
									.replace(tagso, div_replacement)
									.replace(tagsc, "</div>")
									.replace(tagBodyO, tagBodyR),
						safeHTML 	= $(new_text),
						unsafeHTML 	= $(text),
						new_mid 	= safeHTML.find('#mid').find('div.a13-ajax-script').remove().end(), //no script divs
						meta 		= $('meta'),
						new_meta 	= safeHTML.find('div.a13-ajax-meta'),

						//new styles
						head_tag 					= $html.find('head'),
						user_css_inline_part 		= $html.find('#user-css-inline-css'),
						new_user_css_inline_part 	= safeHTML.find('#user-css-inline-css'),
						style_links					= head_tag.find('link').filter('[rel="stylesheet"]'),
						new_style_links				= safeHTML.find('div.a13-ajax-link').filter('[rel="stylesheet"]'),
						styles_links_to_add,

						//new scripts
						scripts_to_get, scripts,
						current_scripts = $('script').filter('[src]'),
						new_scripts		= safeHTML.find('div.a13-ajax-script').filter('[src]'),
						afterGetScripts = function(){
							//refresh JavaScript
							unsafeHTML.filter('script').each(function(){
								var $this = $(this);
								//check if this is not template
								if($this.is('[id^="tmpl-"]')){
									//do nothing
								}
								//check if it isn't json type, text/html type(used in templates) or other not text/javascript type
								else if(!$this.is('[type="text/javascript"]')){
									//do nothing
								}
								//we can proceed
								else {
									try {
										$.globalEval(this.text || this.textContent || this.innerHTML || '');
									}
									catch (e) {
										//what can we do, lets proceed
									}
									finally {
										//what can we do, lets proceed
									}
								}
							});

							//End of javaScript processing

							$window.trigger('a13AjaxRender');
						};


					//setup body classes
					$body
						.removeClass()
						.attr('class', safeHTML.find('div.a13-ajax-body').attr('class'))
						.removeClass('a13-ajax-body');


					//setup content
					mid.html(new_mid.html())
						.removeClass()
						.attr('class', new_mid.attr('class'));
					header.html(safeHTML.find('#header').html());


					//setup head area
					document.title = safeHTML.find('div.a13-ajax-title').text();
					meta.filter('[name=description]').attr('content', new_meta.filter('[name=description]').attr('content'));
					meta.filter('[name=keywords]').attr('content', new_meta.filter('[name=keywords]').attr('content'));


					//insert all new link style tags
					styles_links_to_add = new_style_links.filter(function () {
						var id = $(this).attr("id");
						if(!id){
							return false;
						}
						else{
							//if such style is already inlcuded
							return !style_links.filter('#'+id).length;
						}
					});

					if(styles_links_to_add.length){
						styles_links_to_add.each(function(){
							var attrs = this.attributes,
								attributes = '';
							//get all attributes of style link tag
							$.each(attrs,function(index, value){
								if(value.value === 'class'){ return; } //no need for this
								attributes += ' '+value.name+'="'+value.value+'"';
							});

							//create new style link tag with ALL attributes
							head_tag.append('<link'+attributes+' />');
						});
					}


					//refresh styles that are only per page
					if(user_css_inline_part.length){
						user_css_inline_part.remove();
					}
					if(new_user_css_inline_part.length){
						new_user_css_inline_part.appendTo(head_tag);
					}


					//get new JavaScript files
					scripts_to_get = function(){
						var array = [],
							scripts_not_to_get = [
								'wp-mediaelement.' //no .js cause it may change to min.js or smth
							];

						for(var i = 0; i < new_scripts.length; i++){
							var src = new_scripts.eq(i).attr('src'),
								banned_script = false;

							//check if this script is not banned
							for(var j = 0; j < scripts_not_to_get.length; j++) {
								if(src.indexOf(scripts_not_to_get[j]) !== -1){
									banned_script = true;
								}
							}

							if(banned_script){// we have to do this outside above "for" loop
								continue; //we skip this script
							}

							//if such scripts is NOT already inlcuded
							if(!current_scripts.filter('[src="'+src+'"]').length){
								array.push(src);
							}
						}

						return array;
					}();

					//fetch new scripts
					if(scripts_to_get.length){
						scripts = new GetScripts(scripts_to_get, null, afterGetScripts);
						scripts.fetch();
					}
					else{
						afterGetScripts();
					}
				},

				linkClickEvent = function (event) {
					var href = $(this).attr('href') || '',
						url = href.split(site_url)[1];

					//we don't cancel below clicks on links
					if(
						//no href at all
						typeof href === 'undefined' ||

						//link to different site?
						href.indexOf(site_url) !== 0 ||

						//link to admin area?
						href.indexOf("wp-admin") >= 0 ||

						//link to to some resource?
						href.indexOf("wp-content") >= 0 ||

						//link to page that shouldn't be ajxified?
						$.inArray(href, a13_no_ajax_pages) !== -1 ||

						//deep links
						href.indexOf("#!") >= 0 ||

						//pure anchor(empty or not)
						href.indexOf("#") === 0 ||

						//type of empty anchor?
						href === 'http://#' ||
						href === '' ||

						//using shortcut to open link in new window?
						event.ctrlKey == 1
					){
						return;
					}

					//check if this isn't just anchor on same page
					var old_url_no_hash = current_url.split('#')[0],
						new_url_no_hash = href.split('#')[0];

					if(old_url_no_hash === new_url_no_hash){
						//we are just changing anchor then we don't load site
						return;
					}

					//we are now working on this link :-)
					event.stopPropagation();
					event.preventDefault();

					loadSite(url);
				},

				searchFormSubmit = function (event) {
					var form = $(this),
						input = form.find('input[type="search"]'),
						value = input.val(),
						url = '?s='+value,
						data = {'s' : value};

					event.stopPropagation();
					event.preventDefault();

					if(value.length){
						loadSite(url, data);
					}

				},

				passwordFormSubmit = function (event) {
					var form = $(this),
						input = form.find('input[type="password"]'),
						value = input.val(),
						url =  window.location.href.split(site_url)[1],
						data = {'post_password' : value};

					event.stopPropagation();
					event.preventDefault();
					if(value.length){
						loadSite(url, data, 'POST');
					}

				},

				loadSite = function (url, data, method) {
					$window.trigger('a13AjaxRequestNewSite');
					$.ajax({
						url: site_url+url,
						data : data || '',
						dataType: 'html',
						async : true,
						method : method || 'GET',
						success: function (text) {
							current_url = site_url+url;

							if(current_url !== window.location.href){
								window.history.pushState({path:current_url},'',current_url);
							}

							$(window).trigger('a13AjaxBeforeOldContentRemove');

							showNewContent(text);

							//maybe push some info to Google Analytics
							if(typeof _gaq !== 'undefined') {
								_gaq.push(['_trackPageview', current_url]);
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							alert('Problems loading page.'+'\n'+'Error code: '+textStatus+'\n'+errorThrown );
						}
					});

					cleanCurrentContent();
				};

			//DO WE NEED AJAX HERE?
			if (
				//too old browser - no AJAX
				!window.history.pushState ||
				//if woocommerce - no AJAX
				$body.hasClass('woocommerce')
			){
				return;
			}

			//BIND AND DO
			$(document)
				//clicking on links
				.on('click','a[target!="_blank"]:not(.no_ajax)', linkClickEvent)
				//search form
				.on('submit','form.search-form', searchFormSubmit);
			/*
				temporary off at it doesn't work proper for now
				//password form
				.on('submit','form.post-password-form', passwordFormSubmit);
			*/
			$window.bind('popstate', function(event) {
				//pop state is called on anchor change so we need to check it
				var url = location.href,
					old_url_no_hash = current_url.split('#')[0],
					new_url_no_hash = url.split('#')[0];

				if(old_url_no_hash === new_url_no_hash){
					//we are just changing anchor then we don't load site
				}
				else{
					loadSite(url.split(site_url)[1]);
				}
			});
		},

		elementsActions : {
			init : function(){
				var $e = A13.elementsActions;

				$e.run_once();
				$e.ajaxified();

				$(window).on('a13AjaxRequestNewSite', function() {
					$(window).trigger('a13AjaxBeforeRequestNewSite');
					$e.preloader(true);
				});

				$(window).on('a13AjaxRender', function() {
					//refresh theme elements that need JS actions
					$e.ajaxified();

					//refresh various plugins functions
					$e.perAjaxReCalls();

					//if anyone want to do anything else then we send event
					$window.trigger('a13AjaxRendered');

					//hide preloader
					$e.preloader(false);
				});

			},

			run_once : function(){
				var $e = A13.elementsActions;

				$e.preloader();
				$e.logo();
				$e.fullScreen();
				$e.sideMenu();
				$e.headerSearch();
				$e.footerAudio();

			},

			ajaxified : function(){
				var $e = A13.elementsActions;

				$e.topMenu();
				$e.footerSwitch();
				$e.scrollToAnchor();

				//before singleAlbumMasonry()
				$e.lightbox();

				//big(main) chunks of layout
				$e.blogMasonry();
				$e.albumsListMasonry();
				$e.singleAlbumMasonry();
				$e.singleAlbumSlider();

				/******* For widgets that have lots of content *********/
				$e.widgetSlider();
			},

			perAjaxReCalls : function(){
				//check for videos to resize
				$("div.post-media").fitVids();

				//audio video relunch
				if(typeof $.fn.mediaelementplayer !== 'undefined'){
					var settings = {};
					if ( typeof _wpmejsSettings !== 'undefined' ) {
						settings = _wpmejsSettings;
					}

					mid.find('.wp-audio-shortcode, .wp-video-shortcode').mediaelementplayer( settings );
				}


				//share plugin addToAny
				if(typeof a2a_config !== 'undefined' && typeof a2a.init_all !== 'undefined'){
					a2a.init_all("page");
				}

				//contact form 7
				if( typeof _wpcf7 !== 'undefined' && typeof $.fn.wpcf7InitForm !== 'undefined' ){
					_wpcf7.supportHtml5 = $.wpcf7SupportHtml5();
					$('div.wpcf7 > form').wpcf7InitForm();
				}

				//Visual Composer
				if(typeof vc_js === 'function' ){
					vc_js();
				}
			},

			preloader : function(show_it_now){
				var p = $('#preloader');
				if(p.length){
					var c = p.find('div.preload-content'),
						skip = p.find('a.skip-preloader'),
						hide_onready = p.is('.onReady'),
						hide_it = function(){ // makes sure the whole site is loaded
							c.fadeOut().promise().done(function(){
								p.fadeOut(400);
							})
						},
						show_it = function(){
							skip.hide();
							c.show();
							p.fadeIn();
						};


					//for showing/hidding on AJAX
					if(typeof show_it_now !== 'undefined'){
						if(show_it_now){
							show_it();
						}
						else{
							hide_it();
						}
					}

					//for other cases
					else{
						if(hide_onready){
							$(document).ready(hide_it);
						}
						else{
							//when this script is loaded then show link to skip preloader
							skip.fadeIn().on( click_event, function(ev){
								ev.stopPropagation();
								ev.preventDefault();
								hide_it();
							});

							$window.load(hide_it);
						}

						//show preloader when page changes
						$window.on('beforeunload', show_it);
					}
				}
			},

			logo:  function(){
				var logo = header.find('a.logo').children();

				if(logo.is('img')){
					//if img logo we need event for loading it
					logo.load(function(){
						//inform about possible resize
						$body.trigger('a13LogoLoaded');
					});
				}
				//text logo
				else{
					$body.trigger('a13LogoLoaded');
				}
			},
			
			topMenu : function(){
				var sub_menus       = header.find('ul.sub-menu'),
					menu            = header.find('div.menu-container'),
					menu_list       = menu.children(),
					access          = header.find('nav.navigation-bar'),
					sub_parents     = sub_menus.parent(),
				 	scrollable_class= 'scrollable-menu',
					menu_init       = $('#mobile-menu-opener'),
					size            = A13.layout.size;

				var desktopEvents = function(on){
						if(typeof on !== 'undefined' && on === false){
							resetMenu();
							sub_parents
								.off(click_event)
								.children('i.sub-mark, span.title').off(click_event);
						}
						else{
							sub_parents
								.not(function() {
									return $(this).parents('.mega-menu').length;
								})
								.children('i.sub-mark, span.title')
								.on(click_event, function(ev){
									ev.stopPropagation();
									ev.preventDefault();
										var this_li = $(this).parent(), //li
											sub = this_li.children('ul.sub-menu'),
											was_open = this_li.hasClass('open');

									resetMenu(this_li);

									//close this menu if it was open
									if(was_open){
										return;
									}

									this_li.addClass('open');

									measureSubmenu(sub);

									//show sub-menu
									sub.fadeIn(200);

									$body.off( click_event, bodyClickFn ); //turn off if there were any binds
									$body.on( click_event, bodyClickFn );
								});
						}
					},

					bodyClickFn = function(ev){
						//we don't want to block clicks in other single menu options
						if(! $(ev.target).parents().addBack().hasClass( 'menu-container' ) ) {
							ev.stopPropagation();
							ev.preventDefault();
							resetMenu();
						}
					},

					resetMenu = function(menu){
						if(typeof menu !== 'undefined'){
							//hide every menu that is open on same level
							menu.siblings('li').addBack().has('ul').removeClass('open')
								.children('ul').removeClass('otherway').hide();

							return;
						}

						var p = sub_parents.filter('.open');
						p.children('ul.sub-menu').fadeOut(150, function(){
							$(this).removeClass('otherway');
							p.removeClass('open');
						});

						$body.off( click_event, bodyClickFn );
					},

					measureSubmenu = function(sub){
						//values
						$html.css('overflow-x','hidden'); /* fixes resize of window issue */
						var width = $window.width(),
							sub_w = sub.css({
								visibility: 'hidden',
								display: 'block',
								left: '',
								width: ''
							}).width(),
							is_mega_menu = sub.parent().is('.mega-menu'),
							temp = 0,
							out = false,
							parents;

						//set back
						sub.css({
							visibility: 'visible',
							display: 'none',
							position: ''
						});


						if(is_mega_menu){
							//if menu is wider then window
							if(sub_w > width){
								sub.width(width);
								sub_w = width;
							}

							temp = sub.parent().offset().left + sub_w;
							if(temp > width){
								sub.css('left', -(temp-width));
							}
						}
						//out of right edge of screen, then show on other side
						else{
							//check on which level is this submenu
							parents = sub.parents('ul');
							temp = parents.length;
							//first level
							if(temp === 1 && (sub.parent().offset().left + sub_w > width)){
								out = true;
							}
							//next levels
							else if(temp > 1){
								if(parents.eq(0).offset().left + parents.eq(0).width() + sub_w > width){
									out = true;
								}
							}

							if(out){
								sub.addClass('otherway');
							}
						}

						//back to normal
						$html.css('overflow-x','');
					},

					mobile_menu_toggle = function(ev){
						ev.stopPropagation();
						ev.preventDefault();

						var opener = $(this);

						//hide menu
						if(menu.hasClass('open')){
							menu.slideUp(200, function(){
								menu.children().hide();//helps with menu 'flicker' on IOS
								sub_menus.attr('style','');
								sub_parents.removeClass('open').attr('style','');
								if_needed_make_scrolling_mobile_menu();
								opener.removeClass('open');
							});
							menu.removeClass('open');
							header_tools.removeClass('menu-open');
						}
						//show menu
						else{
							menu_list.show(); //helps with menu 'flicker' on IOS
							menu.slideDown(200,if_needed_make_scrolling_mobile_menu);
							menu.addClass('open');
							opener.addClass('open');
							header_tools.addClass('menu-open');
						}
					},

					mobile_submenu_toggle = function(ev){
						ev.stopPropagation();
						ev.preventDefault();

						var li   = $(this).parent(),
							menu = li.children('ul'),
							mega_menu_parent = li.parents('.mega-menu').length;

						//show
						if(!li.hasClass('open')){
							menu.slideDown(200, if_needed_make_scrolling_mobile_menu);
							li.addClass('open');
						}
						//hide
						else if(li.hasClass('open')){
							menu.slideUp(200, if_needed_make_scrolling_mobile_menu);
							li.removeClass('open');
						}
					},

					if_needed_make_scrolling_mobile_menu = function(){
						var parent_height   = header.height() + menu.height(),
							parent_top      = parseInt(header.css('top'),10),
							available_space = $window.height(),
							has_class       = header.hasClass(scrollable_class);

						//smallest screen width don't need this
						if(A13.layout.size <= break_point[0]){
							return;
						}

						//we have to make menu scrollable
						if(!has_class && parent_height > (available_space-parent_top)){
							header.add(header_tools).addClass(scrollable_class).css('margin-top',$window.scrollTop());
						}
						//normal fixed menu
						else if(has_class && parent_height <= (available_space-parent_top)){
							header.add(header_tools).removeClass(scrollable_class).css('margin-top','');
						}
					},

					mobileEvents = function(on){
						if(typeof on !== 'undefined' && on === false){
							access.removeClass('touch');
							menu_init.hide().off(click_event);
							//clean after touch menu
							menu.removeClass('open').attr('style','');
							menu_init.removeClass('open');
							menu_list.attr('style','');
							header_tools.removeClass('menu-open');
							header.add(header_tools).removeClass(scrollable_class).css('margin-top','');

							if(sub_menus.length){
								sub_parents.children('i.sub-mark, span.title').off(click_event);
								//clean after touch menu
								sub_menus.removeClass('open').attr('style','');
							}
						}
						else{
							access.addClass('touch');
							//bind open menu
							//no double binds!
							menu_init.off(click_event);
							menu_init.show().on(click_event, mobile_menu_toggle);

							if(sub_menus.length){
								//bind open submenu
								//no double binds!
								sub_parents.children('i.sub-mark, span.title').off(click_event);
								sub_parents.children('i.sub-mark, span.title').on(click_event, mobile_submenu_toggle);
							}
						}
					},

					//resize for menu
					layout = function(event, size){
						var menu_type = menu.data('menu-type');
						//if wide screen
						if(size > break_point[2] && menu_type !== 'desktop'){
							mobileEvents(false);
							desktopEvents(true);
							menu.data('menu-type', 'desktop');
						}
						//small screen
						else if(size <= break_point[2] && menu_type !== 'mobile'){
							//clean after desktop version
							desktopEvents(false);
							mobileEvents(true);
							menu.data('menu-type', 'mobile');
						}
					},

					detect_anchors = function(){
						var anchors = menu.find('a[href*="#"]').not('a[href="#"]'),
							anchors_on_this_page = $([]),
							ids_to_watch = [],

							onlyUnique = function(value, index, self) {
								return self.indexOf(value) === index;
							},

							element_on_screen = function(id){
								var $elem 			= $('#'+id),
									docViewTop 		= $window.scrollTop(),
									docViewBottom 	= docViewTop + $window.height(),
									elemTop 		= $elem.offset().top,
									elemBottom 		= elemTop + $elem.height();

								return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
							},

							scrolling = function(){
								var id,links;
								for(var i = 0; i < ids_to_watch.length; i++){
									id = ids_to_watch[i];
									links = anchors_on_this_page.filter('a[href*="#'+id+'"]');

									if(element_on_screen(id)){
										links.parent().addClass('current-menu-item');
									}
									else{
										links.parent().removeClass('current-menu-item');
									}
								}
							};

						if(anchors.length){
							//check if anchor is on this site
							anchors.each(function(){
								var $t = $(this),
									href = $t.attr('href').split('/#', 2),
									site, id;

								//http://site.com/#anchor
								if(href.length === 2){
									id = href[1];
									site = href[0];
								}
								//#anchor or http://site.com/page#anchor
								else{
									href = $t.attr('href').split('#', 2);
									site = href[0];
									id = href[1];
								}

								if(id.length){
									//is this anchor to this page?
									if((site.length && window.location.href.indexOf(site) > -1) || !site.length){
										//make sure that these ids really exist on page!
										if($('#'+id).length){
											anchors_on_this_page = anchors_on_this_page.add($t);
											ids_to_watch.push(id);
										}
									}
								}
							});

							//remove duplicates
							ids_to_watch = ids_to_watch.filter( onlyUnique );


							//should we watch for something?
							if(anchors_on_this_page.length){
								$window.scroll(throttle(scrolling, 500));
								scrolling();//initial call

								$(window).on('a13AjaxBeforeRequestNewSite', function() {
									//yes, we are deactivating ALL window scroll events here:-S
									$window.off('scroll a13AjaxBeforeRequestNewSite');
								});
							}
						}
					};

				//register resize
				A13.layout.add(layout);
				//desktopEvents(true);
				//initial layout
				layout({}, size);

				detect_anchors();

				//show menu
				menu.addClass('loaded');

				$(window).one('a13AjaxBeforeOldContentRemove', function() {
					A13.layout.remove(layout);
				});
			},

			scrollToAnchor : function(){
				var move = function(target,href){
					$('html,body').animate({
							scrollTop: parseInt(target.offset().top, 10)
						}, 1000
					);
				};

				//check if current page has hash and there is such element
				if(window.location.hash.length){
					var href = window.location.hash,
						target = $(href);

					target = target.length ? target : $('[name=' + href.slice(1) +']');
					if (target.length) {
						//empty hash
						window.location.hash = '';

						//delay scroll cause page is still loading
						setTimeout(function(){ move(target, href); }, 1500);
					}
				}

				//scan for anchors
				var anchors = $('ul.top-menu').find('a[href*="#"]').not('[href="#"]').add('#to-top');
				anchors.click(function() {
					if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
						var href = this.hash,
							target = $(href);

						target = target.length ? target : $('[name=' + href.slice(1) +']');
						if (target.length) {
							move(target, href);
							if(history.pushState) {
								history.pushState(null, null, hash);
							}
							return false;
						}
					}
				});
			},
			
			fullScreen : function(){
				var fs = $('#fs-switch'),
					t = 500,
					friends = fs.siblings(),
					to_hide,
					refreshToHide = function(){
						to_hide = $([]).add(header).add(footer).add(mid);

						//if this is album or albums list we act a bit different
						if( $body.is('.single-album, .albums-list-page, .posts-list, .single-product, .post-type-archive-product, .tax-product_cat') || G.full_screen_behaviour === 'content' ){
							to_hide = to_hide.not(mid);
						}
					};


				refreshToHide();
				fs.on( click_event, function(ev) {
					ev.stopPropagation();
					ev.preventDefault();

					if (!fs.hasClass('active')) {
						fs.addClass('active');

						//class of body
						$body.addClass('fullscreen');

						//hide main elements
						to_hide.animate({opacity: 0}, t, function () {
							to_hide.css({
								visibility: 'hidden'
							});
						});

						//move other icons one by one
						friends.each(function (i) {
							var self = $(this);

							self.animate(
								{
									marginTop: 100,
									opacity  : 0
								},
								t + i * 100, // each icon 100ms longer animation
								'easeInBack'
							);
						}).promise().done(function () {
							friends.hide();
						});
					}
					//reset
					else {
						fs.removeClass('active');

						//class of body
						$body.removeClass('fullscreen');

						//show main elements
						to_hide.css({
							visibility: ''
						});
						to_hide.animate({opacity: 1}, t);

						//move other icons one by one
						friends.each(function (i) {
							var self = $(this);

							self.css({
								display: ''
							})
								.animate(
								{
									marginTop: 0,
									opacity  : 1
								},
								t + i * 150, // each icon 150ms longer animation
								'easeOutBack',
								function () {
									self.css({
										marginTop: '',
										opacity  : ''
									});
								}
							);
						});
					}
				});

				$(window).on('a13AjaxRender', refreshToHide);
			},

			sideMenu : function(){
				var switches = $( '#side-menu-switch, #basket-menu-switch' );

				if( switches.length ){
					var postfix_size = '-switch'.length,
						closing_x = $('span.close-sidebar'),
						id,

					resetMenu = function() {
						switches.removeClass('active');
						var top = Math.abs(parseInt( $body.css('top'), 10 ));
						$body.removeClass( id+'-open').css('top','');
						$('body,html').scrollTop(top);
					},

					bodyClickFn = function(evt) {
						var target = $(evt.target);

						if(target.is(closing_x) || !target.parents().addBack().hasClass( 'side-widget-menu' ) ) {
							resetMenu();
							$('#content-overlay').off( click_event, bodyClickFn );
							closing_x.off( click_event, bodyClickFn );
						}
					};

					switches.on( click_event, function(ev) {
						ev.stopPropagation();
						ev.preventDefault();

						var sw 	= $(this).addClass('active'),
							sw_id = sw.attr('id');
						id = sw_id.slice(0, sw_id.length - postfix_size);

						$body.css('top', -parseInt($window.scrollTop(),10)).addClass(id+'-open');

						$('#content-overlay').on( click_event, bodyClickFn );
						closing_x.on( click_event, bodyClickFn );
					});

					$('#side-menu').niceScroll("div.scroll-wrap",{
						//zindex: 1111,
						//styler: 'fb',
						bouncescroll: false,
						background : "#000",
						autohidemode : true,
						cursorcolor : "#fff",
						cursoropacitymin: 1,
						cursorwidth : 3,
						cursorborder : "none",
						cursorborderradius : "0"
					});
					$('#basket-menu').niceScroll("div.scroll-wrap2",{
						//zindex: 1111,
						//styler: 'fb',
						bouncescroll: false,
						background : "#000",
						autohidemode : true,
						cursorcolor : "#fff",
						cursoropacitymin: 1,
						cursorwidth : 3,
						cursorborder : "none",
						cursorborderradius : "0"
					});
				}
			},

			headerSearch :  function(){
				var sf = header_tools.find('form.search-form');
				if(sf.length){
					var parent = sf.parent(),
						open = sf.prev(),//span.action
						close = sf.next();//span.action
					open.on( click_event, function(ev){
						ev.stopPropagation();
						ev.preventDefault();
						parent.addClass('opened');
						sf.find('input[name="s"]').focus();
					});
					close.on( click_event, function(ev){
						ev.stopPropagation();
						ev.preventDefault();
						parent.removeClass('opened');
					});
				}
			},

			footerAudio :  function(){
				if(parseInt(G.music, 10)){
					//copy of WP playlist shortcode JS init
					(function ($, _, Backbone) {
						var A13PlaylistView = Backbone.View.extend({
							initialize : function () {
								this.index = 0;
								this.settings = {};
								this.data = $.parseJSON( this.$('script.a13-playlist-script').html() );
								this.playerNode = this.$( this.data.type );

								this.tracks = new Backbone.Collection( this.data.tracks );
								this.current = this.tracks.first();

								this.currentTemplate = wp.template( 'wp-playlist-current-item' );
								this.currentNode = this.$( '.wp-playlist-current-item' );

								this.renderCurrent();

								this.playerNode.attr( 'src', this.current.get( 'src' ) );

								_.bindAll( this, 'bindPlayer', 'bindResetPlayer', 'setPlayer', 'ended' );

								this.settings = {
									// initial volume when the player starts
									startVolume: 0.2,
									// the order of controls you want on the control bar (and other plugins below)
									features: ['playpause','progress','volume'],
									audioVolume: 'vertical',
									audioHeight: 20,
									success : this.bindPlayer
								};
								this.setPlayer();

								//autoplay
								if(parseInt(G.music_autoplay, 10)){
									this.mejs.play();
								}
								var _this = this;
								//stop playing when slider video starts playing
								$body.on('a13SliderVideoStarts', function(){
									_this.mejs.pause();
								});
							},

							bindPlayer : function (mejs) {
								this.mejs = mejs;
								this.mejs.addEventListener( 'ended', this.ended );
							},

							bindResetPlayer : function (mejs) {
								this.bindPlayer( mejs );
								this.playCurrentSrc();
							},

							setPlayer: function (force) {
								if ( this.player ) {
									this.player.pause();
									this.player.remove();
									this.playerNode = this.$( this.data.type );
								}

								if (force) {
									this.playerNode.attr( 'src', this.current.get( 'src' ) );
									this.settings.success = this.bindResetPlayer;
								}

								/**
								 * This is also our bridge to the outside world
								 */
								this.player = new MediaElementPlayer( this.playerNode.get(0), this.settings );
							},

							playCurrentSrc : function () {
								this.renderCurrent();
								this.mejs.setSrc( this.playerNode.attr( 'src' ) );
								this.mejs.load();
								this.mejs.play();
							},

							renderCurrent : function () {
								if ( ! this.data.images ) {
									this.current.set( 'image', false );
								}
								this.currentNode.html( this.currentTemplate( this.current.toJSON() ) );
							},

							events : {
								'click .playlist-next' : 'next',
								'click .playlist-prev' : 'prev'
							},

							ended : function () {
								if ( this.index + 1 < this.tracks.length ) {
									this.next();
								} else {
									this.index = 0;
									this.setCurrent();
								}
							},


							next : function () {
								this.index = this.index + 1 >= this.tracks.length ? 0 : this.index + 1;
								this.setCurrent();
							},

							prev : function () {
								this.index = this.index - 1 < 0 ? this.tracks.length - 1 : this.index - 1;
								this.setCurrent();
							},

							loadCurrent : function () {
								var last = this.playerNode.attr( 'src' ) && this.playerNode.attr( 'src' ).split('.').pop(),
									current = this.current.get( 'src' ).split('.').pop();

								this.mejs && this.mejs.pause();

								if ( last !== current ) {
									this.setPlayer( true );
								} else {
									this.playerNode.attr( 'src', this.current.get( 'src' ) );
									this.playCurrentSrc();
								}
							},

							setCurrent : function () {
								this.current = this.tracks.at( this.index );
								this.loadCurrent();
							}
						});

						$(document).ready(function () {
							$('.a13-audio-playlist').each( function() {
								return new A13PlaylistView({ el: this });
							} );
						});

						window.A13PlaylistView = A13PlaylistView;

					}(jQuery, _, Backbone));
				}
			},

			footerSwitch :  function(){
				var sw = $('#f-switch');
				if(sw.length){
					var to_show = footer.find('div.foot-widgets'),
						header_indicator = $('#footer-msg-indicator');

					//needed for ajax call
					sw.off(click_event);

					sw.add(header_indicator).on( click_event, function(ev){
						ev.stopPropagation();
						ev.preventDefault();

						if(footer.is('.open')){
							$body.removeClass('footer-open');
							if(to_show.length){
								$body.trigger('a13FootWidgetsClosing');
								to_show.slideUp(500, function(){
									$body.trigger('a13FootWidgetsClosed');
									footer.removeClass('open');
								});
							}
						}
						else if(!footer.is('.open')){
							//class of body
							$body.addClass('footer-open');

							if(to_show.length) {
								$body.trigger('a13FootWidgetsOpening');
								footer.addClass('open');
								to_show.children().css('transform', 'scale(1)'); // repaint for webkit
								to_show.slideDown(500, function () {
									//refresh scroll sizes
									to_show.getNiceScroll().resize();
									$body.trigger('a13FootWidgetsOpened');
								});
							}

							//set cookie that message was read
							A13.cookieExpire('a13_footer_msg_'+G.msg_cookie_string+'=closed', 30*24);//30 days
							//remove flash animation
							if(header_indicator.length){
								header_indicator.removeClass('new');
							}
						}
					});

					to_show.niceScroll("div.foot-content",{
						//zindex: 1111,
						//styler: 'fb',
						bouncescroll: false,
						background : "#000",
						autohidemode : true,
						cursorcolor : "#fff",
						cursoropacitymin: 1,
						cursorwidth : 3,
						cursorborder : "none",
						cursorborderradius : "0"
					});
				}
			},

			blogMasonry : function(){
				var $container = $('#only-posts-here');
				if($container.length){
					$container.isotope({
						// main isotope options
						itemSelector: '.archive-item',
						layoutMode: 'packery',
						// options for masonry layout mode
						packery: {
							columnWidth: '.grid-master',
							gutter: parseInt(G.posts_brick_margin,10)
						}
					});

					// layout Isotope again after all images have loaded
					$container.imagesLoaded( function() {
						$container.isotope('layout');
					});

					// and again when web fonts are loaded
					$body.on('webfontsloaded', function(){
						if($container.data('isotope')){
							$container.isotope('layout');
						}
					});

					$(window).on('a13AjaxBeforeRequestNewSite', function() {
						$container.isotope('destroy');
						//unbind isotope loading more mechanism
						$window.off('.lazyload a13AjaxBeforeRequestNewSite');
					});
				}

			},

			albumsListMasonry : function(){
				var $container = $('#only-albums-here');

				if($container.length){
					var pagination		= mid.find('.navigation.pagination'),
						loading_space 	= $('#loadingSpace'),
						filter 			= mid.find('ul.genre-filter'),
						lli_id 			= 'lazyload-indicator',

						addLoader = function(elem){
							if($('#'+lli_id).length){ return; }//there is some loader

							var _appendTo = (typeof elem !== 'undefined') ? elem : $body;
							$('<div id="'+lli_id+'"><div class="ll-animation"></div>'+ G.loading_items+'</div>').
								appendTo(_appendTo).hide().fadeIn();
						},

						removeLoader = function(){
							var l = $body.find('#'+lli_id);
							if(l.length){
								l.fadeOut().promise().done(function(){l.remove();});
							}
						},

						bindLoadMore = function () {
							var _throttle = is_touch ? 'debounce' : 'throttle', //on IOS throttle for scroll causes some JavaScript issues
								action = function () {
									$window.off('.lazyload');
									loadTillViewIsFull();
								},

								cb = function () {
									var scroll_pos = $window.scrollTop() + $window.height();

									if ($container.height() - scroll_pos < 250) {
										action();
									}
								};

							$window.on('scroll.lazyload resize.lazyload', window[_throttle](cb, 150));
						},

						loadTillViewIsFull = function () {
							//we have more then one page of items
							if(pagination.length){
								var next_link = pagination.find('a.next');

								if( !next_link.length ){
									//unbind loading more
									removeLoader();

									return;
								}
								else if ( !(($container.height() < (2 * $window.height() + $window.scrollTop())) && next_link.length) ){
									bindLoadMore();
									removeLoader();

									return; //nothing to do here
								}

								//lets load more items
								addLoader();

								//get new items
								loading_space.load(next_link.attr('href'), 'a13-ajax-get', function(){
									//pagination replace
									var new_pagination = loading_space.find('.navigation');
									pagination.replaceWith(new_pagination);
									pagination = new_pagination;

									loading_space.imagesLoaded( function() {
										//get elements from loading space
										var elems = loading_space.find('.archive-item').appendTo($container);

										// add and lay out newly appended elements
										$container.isotope( 'appended', elems );

										//finished loading
										//but try to load more items
										loadTillViewIsFull();
									});

								});
							}
						};


					/****** STARTUP CONFIGURATION *****/

					if(!loading_space.length){
						loading_space = $('<div id="loadingSpace"></div>').appendTo($body);
					}

					//start isotope
					$container.isotope({
						// main isotope options
						itemSelector: '.archive-item',
						transitionDuration: '0.6s',

						layoutMode: 'packery',
						// options for masonry layout mode
						packery: {
							columnWidth: '.grid-master',
							gutter: parseInt(G.albums_list_brick_margin,10)
						}
					});

					// layout Isotope again after all images have loaded
					$container.imagesLoaded( function() {
						$container.isotope('layout');
						//and add more items
						loadTillViewIsFull();
					});

					// and again when web fonts are loaded
					$body.on('webfontsloaded', function(){
						if($container.data('isotope')){
							$container.isotope('layout');
						}
					});

					$(window).on('a13AjaxBeforeRequestNewSite', function() {
						$container.isotope('destroy');
						//unbind isotope loading more mechanism
						$window.off('.lazyload a13AjaxBeforeRequestNewSite');
					});

					//filter bind
					if(filter.length){
						var filters = filter.find('li');

						filters.on( click_event, function(ev){
							ev.stopPropagation();
							ev.preventDefault();

							filters.removeClass('selected');

							var f = $(this).addClass('selected'),
								genre = f.data('filter');

							if(genre === '__all'){ //__all so users will not overwrite this
								genre = '*'
							}
							else{
								genre = '[data-genre-'+genre+']';
							}

							$container.isotope({ filter: genre });


							//trigger scroll to load more elements if there is place
							$window.trigger('scroll.lazyload');
						});
					}
				}
			},

			singleAlbumMasonry : function(){
				var $container = $('#only-album-items-here');

				if($container.length){
					var $items_list		= $('#album-media-collection'),
						$items			= $items_list.children(),
						loading_space 	= $('<div id="loadingSpace"></div>').appendTo($body),
						lli_id 			= 'lazyload-indicator',
						hover_effect	= $container.data('hover'),
						title_color		= $container.data('title-color'),
						show_desc		= parseInt($container.data('desc')),
						limit_per_load	= 10,
						pointer			= 0,//how many elements are loaded
						number_of_items = $items.length,
						thumbs_video	= parseInt(G.album_bricks_thumb_video,10),

						addLoader = function(elem){
							if($('#'+lli_id).length){ return; }//there is some loader

							var _appendTo = (typeof elem !== 'undefined') ? elem : $body;
							$('<div id="'+lli_id+'"><div class="ll-animation"></div>'+ G.loading_items+'</div>').
								appendTo(_appendTo).hide().fadeIn();
						},

						removeLoader = function(){
							var l = $body.find('#'+lli_id);
							if(l.length){
								l.fadeOut().promise().done(function(){l.remove();});
							}
						},

						bindLoadMore = function() {
							var _throttle = is_touch ? 'debounce' : 'throttle', //on IOS throttle for scroll causes some JavaScript issues
								action = function () {
									$window.off('.lazyload');
									loadTillViewIsFull();
								},

								cb = function () {
									var scroll_pos = $window.scrollTop() + $window.height();

									if ($container.height() - scroll_pos < 250) {
										action();
									}
								};

							$window.on('scroll.lazyload resize.lazyload', window[_throttle](cb, 150));
						},

						makeBrick = function(itemNumber){
							var $el = $items.eq(itemNumber),
								html = '',
								description = $el.find('div.album-desc').find('.description'),
								add_to_cart = $el.find('p.add_to_cart_inline'),
								link = $el.children('a'),
								title = link.text();

							add_to_cart = add_to_cart.length ? $('<div />').append(add_to_cart.clone()).html() : '';

							//video
							if( !thumbs_video && $el.hasClass('type-video') ){
								//external video
								if( $el.hasClass('subtype-videolink') ){
									html += '<figure class="archive-item w'+$el.data('ratio_x')+'">';
									html += '<iframe src="'+$el.data('video_player')+'" allowfullscreen />';
									html += '</figure>';
								}
								//internal video
								else{
									html += '<figure class="archive-item w'+$el.data('ratio_x')+'">';
									html += $($el.data('html')).html();
									html += '</figure>';
								}
							}

							//images
							else{
								html += '<figure class="archive-item '+hover_effect+' w'+$el.data('ratio_x')+'">';
								html += '<img src="'+$el.data('brick_image')+'" alt="'+$el.data('alt_attr')+'" title="'+title+'" />';
								html += '<figcaption>';
								if(show_desc){
									html += '<div class="center_group">';
									if(title.length){
										html += '<h2 class="post-title">';
										html += title_color.length? '<span style="background-color:'+title_color+'">'+title+'</span>' : title;
										html += '</h2>';
									}
									html += description.length ? ('<div class="excerpt">'+description.html()+add_to_cart+'</div>') : '';
									html += '</div>';
								}
								if($el.hasClass('link')){
									html += '<a href="'+link.attr('href')+'"></a>';
								}
								//html += $el.find('.a2a_kit').wrap('<div></div>').parent().html();
								html += '</figcaption>';
								html += '</figure>';
							}

							return html;
						},

						openBrick = function(ev){
							var index = $container.find('figure.archive-item').index($(this)),
								$item = $items.eq(index),
								target = $(ev.target);

							//check if we didn't click some link in description
							if(!target.is('a') && target.parents('a').length === 0){
								//no click stealing if video or link
								if(thumbs_video || !$item.hasClass('type-video')){
									$item.click();
									return;
								}
								//no click on link
								else if($item.hasClass('link')){
									return;
								}

								ev.stopPropagation();
								ev.preventDefault();
							}
						},

						startupConfiguration = function(){
							//start isotope
							$container.isotope({
								// main isotope options
								itemSelector: '.archive-item',
								transitionDuration: '0.6s',
								'stamp' : 'div.album-content',

								layoutMode: 'packery',
								// options for masonry layout mode
								packery: {
									columnWidth: '.grid-master',
									gutter: parseInt($container.data('margin'),10)
								}
							});

							// layout Isotope again when web fonts are loaded
							$body.on('webfontsloaded', function(){
								if($container.data('isotope')){
									$container.isotope('layout');
								}
							});

							$(window).on('a13AjaxBeforeRequestNewSite', function() {
								$container.isotope('destroy');
								//unbind isotope loading more mechanism
								$window.off('.lazyload a13AjaxBeforeRequestNewSite');
							});

							//click on bricks make click on list element to open lightbox
							$container
								.on( 'click', 'figure.archive-item', openBrick);

							//faster touch event, buggy for now as it blocks lightbox
								//.on( 'touchstart', 'figure.archive-item', function(startEvent){
								//	var threshold = 10,
								//		timeThreshold = 500,
								//		self = this,
								//		$self = $(self),
								//		target = startEvent.target,
								//		touchStart = startEvent.originalEvent.touches[0],
								//		startX = touchStart.pageX,
								//		startY = touchStart.pageY,
								//		timeout,
								//
								//		removeTapHandler = function() {
								//			clearTimeout(timeout);
								//			$self.off('touchmove', moveHandler).off('touchend', tapHandler);
								//		},
								//
								//		tapHandler = function (endEvent) {
								//			removeTapHandler();
								//
								//			// When the touch end event fires, check if the target of the
								//			// touch end is the same as the target of the start, and if
								//			// so, fire a click.
								//			if (target == endEvent.target) {
								//				//$.event.simulate('tap', self, endEvent);
								//				openBrick(startEvent);
								//			}
								//		},
								//
								//		// Remove tap and move handlers if the touch moves too far
								//		moveHandler = function(moveEvent) {
								//			var touchMove = moveEvent.originalEvent.touches[0],
								//				moveX = touchMove.pageX,
								//				moveY = touchMove.pageY;
								//
								//			if (Math.abs(moveX - startX) > threshold ||
								//				Math.abs(moveY - startY) > threshold) {
								//				removeTapHandler();
								//			}
								//		};
								//
								//	// Remove the tap and move handlers if the timeout expires
								//	timeout = setTimeout(removeTapHandler, timeThreshold);
								//
								//	// When a touch starts, bind a touch end and touch move handler
								//	$self.on('touchmove', moveHandler).on('touchend', tapHandler);
								//
								//});
						},

						loadTillViewIsFull = function () {
							//we have any items
							if(number_of_items){
								if( pointer >= number_of_items ){
									//unbind loading more
									removeLoader();

									return;
								}

								//our formula to decide if we have enough items loaded
								if ( !( $container.height() < ( 2 * $window.height() + $window.scrollTop() ) ) ){
									bindLoadMore();
									removeLoader();

									return; //nothing to do here
								}

								addLoader();

								//get new items
								var new_items_html = '',
									saved_pointer = pointer,
									load_till 	 = pointer + limit_per_load;
								//check if we are not beyond number of items
								load_till = load_till > number_of_items ? number_of_items : load_till;

								for(; pointer < load_till; pointer++){
									new_items_html += makeBrick(pointer);
								}

								//start loading items
								loading_space.append(new_items_html)
									//make video look proper
									.fitVids();

								//add social icons for sharing
								if(typeof a2a_config !== 'undefined'){
									pointer = saved_pointer;
									loading_space.find('.archive-item').each(function(){
										//will work only when item has thumb
										$(this).find('figcaption').append($items.eq(pointer).find('.a2a_kit'));
										pointer++;
									});
								}


								loading_space.find('.wp-video video').mediaelementplayer(mejs.MediaElementDefaults);

								//after items are ready to display send them to their container
								loading_space.imagesLoaded( function() {
									//get elements from loading space
									var elems = loading_space.find('.archive-item')/*.css('opacity',0).addClass('not-revealed')*/.appendTo($container);

									//first run
									if( saved_pointer === 0 ){
										startupConfiguration();
									}
									// add and lay out newly appended elements
									else{
										$container.isotope( 'appended', elems );
									}

									//try to load more items
									loadTillViewIsFull();
								});

								//});
							}
						};


					loadTillViewIsFull();

					//check if it isn't "share image" link
					var share_it = getParameterByName('gallery_item'),
						links, i;

					if(share_it.length){
						links = $items.children('a');
						for(i = 0; i < links.length; i++){
							if(links.eq(i).attr('href').indexOf(share_it) > -1){
								//alert(share_it + ' index '+i);
								links.eq(i).click();
								break;
							}
						}
					}
				}
			},

			singleAlbumSlider : function() {
				var $container = $('#album-slider'),
					$gallery = $("#album-media-collection");

				if ($container.length && $gallery.length) {
					var $gallery_items 	= $gallery.children(),
						items = [],
						share_it = getParameterByName('gallery_item'),
						links,
						start_slide = 0,
						i,item, type, description, link,
						add_to_cart,
						html5_video, video_type;

					//collect data from items
					for(i = 0; i < $gallery_items.length; i++){
						item 		= $gallery_items.eq(i);
						type 		= item.hasClass('type-video')? 'video' : 'image';
						description = item.find('div.album-desc').find('.description');
						//if lightbox is disabled
						description = description.length ? description.html() : item.find('div.album-desc').html();

						add_to_cart = item.find('p.add_to_cart_inline');
						link 		= item.children('a');
						video_type  = item.data('video_type');
						html5_video = type==='video' && video_type === 'html5';

						add_to_cart = add_to_cart.length ? $('<div />').append(add_to_cart.clone()).html() : '';

						items.push({
							type:       type,
							image:      item.data('main-image'),
							thumb:		item.data('thumb'),
							title:      link.text(),
							alt_attr:   item.data('alt_attr'),
							desc:       description+add_to_cart,
							autoplay:   item.data('autoplay'),
							video_type: video_type,
							video_url:  html5_video? item.data('html') : item.data('video_player'),//id reference for internal video
							bg_color:   item.data('bg_color'),
							url:        type==='image' && item.hasClass('link')? link.attr('href') : false
						});
					}
					//resize #mid
					$html.css('height', '100%' );

					//check if it isn't "share image" link
					if(share_it.length){
						links = $gallery_items.children('a');
						for(i = 0; i < links.length; i++){
							if(links.eq(i).attr('href').indexOf(share_it) > -1){
								start_slide = i;
								break;
							}
						}
					}

					//call script
					$.a13slider({
						parent                  :   $container.parent(),                				// where will be embeded slider
						autoplay				:	parseInt($container.data('autoplay'), 10),
						slide_interval          :   parseInt($container.data('slide_interval'), 10),
						transition              :   parseInt($container.data('transition'), 10),
						transition_speed		:	parseInt($container.data('transition_time'), 10),
						ken_burns_scale			:   parseInt($container.data('ken_burns_scale'), 10),
						fit_variant				:	parseInt($container.data('fit_variant'), 10),
						pattern					:	parseInt($container.data('pattern'), 10),
						gradient				:	parseInt($container.data('gradient'), 10),
						texts					:	parseInt($container.data('texts'), 10),
						title_color				:	$container.data('title_color'),
						thumb_links				:	$container.data('thumbs') === 'on' ? 1 : 0,
						show_thumbs_on_start	:	$container.data('thumbs_on_load') === 'on' ? 1 : 0,
						start_slide				: 	start_slide,
						original_items			: 	$gallery_items,
						slides                  :   items                              					// Slideshow Images
					});


					//we don't need any more
					$container.remove();
				}
			},

			widgetSlider: function(){
				var sidebars = footer.add('#side-menu, #basket-menu, #secondary'),
					selectors = sidebars.find('div.widget_rss');
				if(selectors.length){
					selectors.each(function(){
						var selector = $(this),
							html = '<div class="widget-slider-ctrls"><span class="prev-slide icon-rewind"></span><span class="next-slide icon-fast-forward"></span>',
							slides = selector.find('li').eq(0).show().end(),
							left,right,

							move = function(ev){
								ev.stopPropagation();
								ev.preventDefault();

								var direction = ev.data.dir,
									current = slides.filter(':visible'),
									toShow;

								if(direction === 'next'){
									toShow = current.next();
									if(!toShow.length){
										toShow = slides.eq(0);
									}
								}
								else{
									toShow = current.prev();
									if(!toShow.length){
										toShow = slides.eq(slides.length-1);
									}
								}

								//animate
								current.fadeOut(200, function(){ toShow.fadeIn(200); })
							};

						if(selector.hasClass('slider-ctrls')){
							//there are controls already
							return;
						}

						if(slides.length > 1){ //more then one slide
							selector.addClass('slider-ctrls').append(html);
							left = selector.find('span.prev-slide');
							right = selector.find('span.next-slide');

							//bind clicks
							left.on(click_event,null,{dir: 'prev'}, move);
							right.on(click_event,null,{dir: 'next'}, move);
						}
					});
				}
			},

			lightbox : function(){
				//if no lightbox script do nothing
				//Using lightGallery ?
				if(typeof $.fn.lightGallery !== 'undefined'){

					var $gallery = $("#album-media-collection");
					if( $gallery.length ){
						var $gallery_items 	= $gallery.children();

						//rewrite HTML to match lightbox syntax
						$gallery_items.each(function(){
							var $el = $(this),
								$link = $el.children('a'),
								$desc = $el.find('div.album-desc'),
								$video = $el.find('div.album-video');

							if($desc.length){
								//mark where lightbox should search for description
								$el.attr('data-sub-html', '#'+$desc.attr('id'));
								//wrap real description so we can distinguish it
								$desc.wrapInner('<div class="description"></div>');
								//add title
								$desc.prepend('<h4>'+$link.text()+'</h4>');
								//wrap everything in special class, so it will look nice in lightbox
								$desc.wrapInner('<div class="customHtml"></div>');
							}

							//can't have data-html and data-src in one item
							if($video.length){
								$el.attr('data-html', '#'+$video.attr('id'));
							}
							else{
								$el.attr('data-src', $link.attr('href'));
							}
						});

						$gallery.lightGallery({
							selector          : $gallery_items.not('.link'),
							exThumbImage      : 'data-thumb',
							hash              : false,//not option
							controls          : !!parseInt(G.lg_lightbox_controls, 10),
							download          : !!parseInt(G.lg_lightbox_download, 10),
							counter           : !!parseInt(G.lg_lightbox_counter, 10),
							thumbnail         : !!parseInt(G.lg_lightbox_thumbnail, 10),
							showThumbByDefault: !!parseInt(G.lg_lightbox_show_thumbs, 10),
							autoplay          : !!parseInt(G.lg_lightbox_autoplay_open, 10),
							autoplayControls  : !!parseInt(G.lg_lightbox_autoplay, 10),
							fullScreen        : !!parseInt(G.lg_lightbox_full_screen, 10),
							zoom              : !!parseInt(G.lg_lightbox_zoom,10),
							mode              : G.lg_lightbox_mode,
							speed             : parseInt(G.lg_lightbox_speed,10)
						});
					}
				}

				//if no lightbox script do nothing

			}
		}
	};



	//start Theme
	A13 = window.A13;
	$(document).ready(A13.onReady);

})(jQuery, window, document);
