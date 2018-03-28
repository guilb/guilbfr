/*
	Inspired by	: Supersized www.buildinternet.com/project/supersized
*/
var A13_slider_debug = false;

/*global Modernizr, A13, addTouchEvent, debounce, YT, $f, _V_ */
(function($){
    "use strict";

    $.a13slider = function(SliderOptions){
        // Default Options
        var body = document.body,
			$body = $(body),
			defaultOptions = {

            // Functionality
            parent                  :   body, 			//where will be embeded main element
            autoplay                :   1,             	// Slideshow starts playing automatically
            slide_interval          :   5000,          	// Length between transitions
            transition              :   2,             	// 0-None, 1-Fade, 2-Carousel
            transition_speed        :   750,           	// Speed of transition
            fit_variant             :   0,             	// 0-always, 1-landscape, 2-portrait, 3-when_needed, 4-cover
            pattern             	:   0,             	// shows above images
            gradient             	:   0,             	// shows above images
			start_slide				:	0,				// slide that should be shown at start

            // Components
            texts            		:   1,             	// Will titles and descriptions be shown
			title_color            	:   '',             // bg color under slide title
            progress_bar            :   1,             	// Timer for each slide
			thumb_links				:	1,			   	// Individual thumb links for each slide
			show_thumbs_on_start    :   1,          	//hide or show thumbs on load
			original_items			:   {},				//list from which options.slides where generated
            slides                  :   {}             	//here we will send slides with JSON format
        };


        /* Global Variables
         ----------------------------*/
        var album = this,
			launched = false,
            options     = $.extend({},defaultOptions, SliderOptions), //options of slide show
            $parent     = $(options.parent),
            slides      = options.slides,        //params of each slide
            slides_num  = slides.length,    //number of slides
            all_slides  = {},               //$el.find('li')
            $el         = {},               //our main element
            fit_variant = options.fit_variant,
			pattern 	= options.pattern,
			gradient 	= options.gradient,

			thumbs      		= {},               //thumb_list.children(),
			thumb_width 		= 0,                //thumbs.eq(0).outerWidth(true),
			tray_width  		= 0,                //width of visible tray
			thumb_list_w      	= 0,          		//width of all thumbnails
			maxThumbsCssLeft  	= 0,          		//maximum value of thumbs left postion

            slide_id_pre    	= 'ss-slide-',
            p_bar_enabled   	= options.progress_bar,
            slider_interval_time= options.slide_interval,
			transition_speed 	= options.transition_speed,
			thumb_links     	= options.thumb_links,
			title_color			= options.title_color,
			fancyThumbsHiding 	= true, //for future reference to make option to switch effect off easily


			//Minor animation times
            minorShowTime = 300,
            minorHideTime = 200,

            // Elements
			thumb_list_id       =   'thumb-list',          // Thumbnail list
			thumb_list          =   {},                     // Thumbnail list
			tray,       		// Thumbnail tray
			tray_i,        		// div.inner keeps all together for hidding
			tray_button,		// Thumbnail tray button

            play_button,    // Play/Pause button
            next_slide,     // Next slide button
            prev_slide,     // Prev slide button
            slide_list,     // Slide link list($)
            slide_list_li,  // Slide link children(<li>)
			texts_toggle, 	//open/hide caption/description texts

            // Internal variables
            current_slide           =   options.start_slide,          // Current slide number
            in_slide_transition     =   false,      // Prevents animations from stacking
            is_slider_playing       =   false,      // Tracks paused on/off
            is_video_playing        =   false,      // Tracks paused on/off
            is_fullscreen_for_video =   false,
            slideshow_interval_id   =   0,      	// Stores slideshow timer
            progress_delay          =   false,      // Delay after resize before resuming slideshow
			thumb_interval          =   0,          // Thumbnail interval
            clean_after_goTo_function =   false,      // Trigger to update images after slide jump
            loadYouTubeAPI          =   false,      // Bool if YT API should load
            loadVimeoAPI            =   false,      // Bool if Vimeo API should load
            loadNativeVideoAPI      =   false,      // Bool if Native Video API should load
            videos                  =   {} ,        // videos from options

            isTouch 		= 'ontouchstart' in window || !!(navigator.msMaxTouchPoints),
			clickEvent  	= isTouch ? 'click touchstart' : 'click',
			mouseOverEvent 	= isTouch ? 'mouseover touchmove' : 'mouseover',
			mouseOutEvent 	= isTouch ? 'mouseout touchend' : 'mouseout',
			it_was_drag		= false,

			//css for hidden elements
			hidden  = {
                opacity : 1,
                visibility : 'hidden',
                left: '-100%'
            },

            /***** small helpers functions *****/
			animate_thumbs = function(left){        // move thumbs
				var is_animated = thumb_list.is(':animated');

				thumb_list
					.stop()
					.animate({
						'left' : left
					}, 500, (is_animated? 'linear' : 'swing'));
			},

            clean_prev_slide = function(slide){
                slide.css(hidden);
            },
            
            getField = function(field){
                return (typeof slides[current_slide][field] === 'undefined')? "" : slides[current_slide][field];
            };


        /* Prepares Vars and HTML
		----------------------------*/
        album.prepareEnv = function(){
            // Add in slide markers
            var sliderIterator = 0,
                slideSet = '',
				p_bar_html = '',
				thumbMarkers = '',
				slider_classes = '',
				is_video = false,
				temp,
                ts; //this slide from array

            //collect slides
			while(sliderIterator <= slides_num-1){
				ts = slides[sliderIterator];
				is_video = ts.type === 'video';

				//prepare slide HTML
				slideSet = slideSet+'<li id="'+slide_id_pre+sliderIterator+'" class="slide-'+sliderIterator+(is_video ? ' video' : '')+'"></li>';

                //collect video info
                if(is_video){
                    //check which API is needed
                    if(ts.video_type === 'youtube' && loadYouTubeAPI !== 'loaded'){
                        loadYouTubeAPI = true;
                    }
                    else if(ts.video_type === 'vimeo' && loadVimeoAPI !== 'loaded'){
                        loadVimeoAPI = true;
                    }
                    else if(ts.video_type === 'html5' && loadNativeVideoAPI !== 'loaded'){
                        loadNativeVideoAPI = true;
                    }

                    //copy video details
                    videos[slide_id_pre+sliderIterator] = ts;
                }

				// Slide Thumbnail Links
				if (thumb_links){

					thumbMarkers += '<li class="thumb'+sliderIterator +
					(sliderIterator === 0 ? ' current-thumb' : '') +
					(is_video ? ' video' : '') +
					'"><div><img width="150" height="150" src="'+ts.thumb+'" alt="" /></div></li>';
				}

				//increase iterator
				sliderIterator++;
			}

			//we load marked video APIs
            album.loadVideoApi();

			if(p_bar_enabled){
				p_bar_html = '<em class="p_bar_1"></em><em class="p_bar_2"></em><em class="p_bar_3"></em><em class="p_bar_4"></em>';
			}

			if(pattern > 0){
				slider_classes += ' pattern pattern-'+pattern;
			}
			if(gradient > 0){
				slider_classes += ' gradient';
			}
			if(thumb_links){
				slider_classes += ' with-thumbs';
			}



            //Place slider HTML
            $parent.append('' +
                '<ul id="a13-slider" class="'+slider_classes+'"></ul>' +
				'<div id="slider-controls" class="show-with-slider'+(thumb_links? ' with-thumbs' : '')+'">' +
					((slides_num > 1)?
						'<span id="prev-slide" class="slider-arrow icon-chevron-thin-left" />'+
						'<span id="play-button" class="icon-controller-play">'+p_bar_html+'</span>' +
						'<span id="next-slide" class="slider-arrow icon-chevron-thin-right" />'
					: '') +
					(thumb_links? '<span id="tray-button" class="icon-arrow-up" />' : '') +
				'</div>' +
				(thumb_links? '<div id="thumb-tray"><div class="inner"></div></div>' : '')+
                '');

			//root element
            $el = $('#a13-slider');

            //append ready html
			$el.append(slideSet);

			if (thumb_links){
				tray            = $('#thumb-tray');
				tray_i          = tray.children();
				tray_button		= $('#tray-button');

				tray_i.append('<ul id="'+thumb_list_id+'">'+thumbMarkers+'</ul>');

				//fill vars
				thumb_list          = $('#'+thumb_list_id);
				thumbs              = thumb_list.children();
				thumb_width         = thumbs.eq(0).outerWidth(true);
				tray_width          = tray.width();
				thumb_list_w        = slides_num * thumb_width;
				maxThumbsCssLeft    = tray_width - thumb_list_w;

				// Show thumbnails on load or not
				if(!options.show_thumbs_on_start){
					// Hide tray off screen
					if(fancyThumbsHiding){

					}
					else{
						tray_i.css('top' , tray.innerHeight());
					}
					tray.hide();
					$body.addClass('slider-thumbs-hidden');
				}
				else{
					tray_button.addClass('active');
				}

				// Make thumb tray proper size
				thumb_list.width(thumb_list_w);	//Adjust to true width of thumb markers

				//less images then width
				if(thumb_list_w < tray_width){
					thumb_list.css('left', (tray_width - thumb_list_w)/2);
					tray.addClass('nomove');
				}
				else{
					tray.removeClass('nomove');
				}

				//Thumbnail Tray Navigation
				thumb_interval = Math.floor(tray_width / thumb_width) * thumb_width;
			}

            //save other slider elements
            all_slides      = $el.find('li').css(hidden); //hide all slides also
            play_button     = $('#play-button');
            next_slide		= $('#next-slide');
            prev_slide		= $('#prev-slide');

			//prepare slides
			// Set current slide
            album.fillSlide(current_slide, 'activeslide', 1);

            //load previous slide
			if (slides_num > 2){
				temp = current_slide === 0 ? slides_num - 1 : current_slide - 1;
				album.fillSlide(temp, 'prevslide');
			}

            //load next slide
            if (slides_num > 1){
				temp = current_slide === slides_num - 1 ? 0 : current_slide + 1;
				album.fillSlide(temp);
            }
        };



        /* Launch Slider
         ----------------------------*/
        album.launch = function(){
			if(launched === true ){
				return;
			}
			launched = true;

			//show slider
            $el.addClass('show');
			$parent.find('.show-with-slider').addClass('show');

            // Call function for before slide transition
            album.beforeAnimation('next', true);
            album.events();

            // Start slide show if auto-play enabled
            if(options.autoplay && slides_num > 1){
				album.playToggle();
            }
            else{
                album.indicatePlayerState('pause');
            }
        };


        /* Bind events
         ----------------------------*/
        album.events = function(){
            // Keyboard Navigation
            $(document.documentElement).keyup(function (event) {
                if(in_slide_transition){ return false;	}	// Abort if currently animating

                var key = event.keyCode;

                // Left Arrow
                if ((key === 37)){
                    album.prevSlide();

                }
				// Right Arrow
				else if ((key === 39)) {
                    album.nextSlide();
                }
				// Spacebar
				else if (key === 32) {
					album.kenBurnsEffectPause();
                    album.playToggle();
                }

				if(thumb_links) {
					// Down Arrow
					if( key === 40 && tray_button.hasClass('active') ){
						album.toggleThumbsTray(event);
					}
					// Up Arrow
					else if( key === 38 && !tray_button.hasClass('active') ){
						album.toggleThumbsTray(event);
					}
				}

                return true;
            });

			//controls
            next_slide.on( clickEvent, album.nextSlide);
            prev_slide.on( clickEvent, album.prevSlide);
            play_button.on( clickEvent, album.playToggle);

			//small screens only
			$el.on(clickEvent, 'div.texts-opener', album.textsToggle);


			//KNOWN ISSUE
			//when dragging HTML5 VIdeo it receives click and starts to play
			//not easy to fix

			//click on slider
            $el.on('click', 'li',{}, function(e){ //click cause we don't want to respond to touchStart
				//no clicking when drag
				if(it_was_drag){
					e.preventDefault();
					return false;
				}

				//check if this is video
				var index = all_slides.index($(this)),
					target = $(e.target);

				if(slides[index].type === 'video'){
					//click on video cover
					if(target.is('div.video-poster')){
						if( Modernizr.autoplay ){
							album.playVideo();
						}
						else{
							$(target).fadeOut(minorHideTime);
						}
					}
					return;
				}


				//check if we didn't click some link in description
				if(target.is('a.slide')){
					//continue execution
				}
				else if(target.is('a') && !target.is('.slide') || target.parents('a').length > 0){
					return;
				}

                album.playToggle();
            });


            //Touch event(changing slides) & drag
            if(slides_num > 1){
				if(isTouch){
					addTouchEvent($el[0], {
						right: album.prevSlide,
						left: album.nextSlide
					});
				}

				var slider_drag_start = 0;
				$el
					.on('mousedown', function(e) {
						slider_drag_start = e.clientX;
						$el.addClass('grab');
					})
					.on('mouseup', function(e) {
						var drag_end = e.clientX,
							diff = slider_drag_start - drag_end;

						if(diff > 30){
							album.nextSlide();
							it_was_drag = true;
							e.preventDefault();
						}
						else if(diff < -30){
							album.prevSlide();
							it_was_drag = true;
							e.preventDefault();
						}
						//else it is normal click so proceed
						else{
							it_was_drag = false;
						}

						$el.removeClass('grab');
					})
					.on('dragstart', function() { return false });

			}

			//thumbs actions
			if(thumb_links){
				tray_button.on( clickEvent, album.toggleThumbsTray );
				album.mouse_scrub();
				thumbs
					.on( 'click', function(e){//click cause we don't want to respond to touchStart
						e.preventDefault();
						album.goTo(thumbs.index(this));
					});
				tray
					.on( mouseOverEvent, function(){
						tray_i.stop().animate({top : '20px'}, minorShowTime );
					})
					.on( mouseOutEvent, function(){
						tray_i.stop().animate({top : '55px'}, minorShowTime );
					});

			}


			//respond to opening footer
			var $footer = $('#footer'),
				footWidgetOpened = function(){
					var fh = parseInt($footer.height(), 10);
					if(thumb_links) {
						tray.css('padding-bottom', fh);
					}
					$('#slider-controls').css('margin-bottom', fh-40);
				},

				footWidgetsClosing = function(){
					if(thumb_links) {
						tray.css('padding-bottom', '');
					}
					$('#slider-controls').css('margin-bottom', '');
				},

				destroy = function(){
					$body
						.off('a13FootWidgetsOpened', footWidgetOpened)
						.off('a13FootWidgetsClosing', footWidgetsClosing);

					$(window).off('a13AjaxBeforeRequestNewSite', destroy);
				};

			//react to footer
			$body
				.on('a13FootWidgetsOpened', footWidgetOpened)
				.on('a13FootWidgetsClosing', footWidgetsClosing);


			// Adjust image when browser is resized
			$(window).resize(debounce(function(){
				//resize all images
				album.resizeNow();

				// Delay progress bar on resize

				//if slider is currently not moving and video is not playing
				if (!in_slide_transition && !is_video_playing){
					if (is_slider_playing){
						album.stopMovingToNextSlide();
						album.kenBurnsEffectStop();
					}

					if (!progress_delay){
						// Delay slideshow from resuming for various resize reasons
						progress_delay = setTimeout(function() {
							if (is_slider_playing){
								album.startMovingToNextSlide();
								album.kenBurnsEffect();
							}
							progress_delay = false;
						}, 1000);
					}

				}

				if (thumb_links){
					thumb_list_w = thumb_list.width();
					tray_width = tray.width();

					// Update Thumb Interval & Page
					thumb_interval = Math.floor(tray_width / thumb_width) * thumb_width;

					// Adjust thumbnail markers
					if (thumb_list_w > tray_width){
						var currentPos = parseInt(thumb_list.css('left'), 10);
						thumb_list.stop();
						tray.removeClass('nomove');
						maxThumbsCssLeft = tray_width - thumb_list_w;
						//fix right side edge
						if(currentPos < maxThumbsCssLeft){
							thumb_list.css('left', maxThumbsCssLeft);
						}
						//fix left side edge
						else if(currentPos > 0){
							thumb_list.css('left', 0);
						}
					}
					//less images then width
					else{
						thumb_list.css('left', (tray_width - thumb_list_w)/2);
						tray.addClass('nomove');
					}
				}

			}, 250));

			$(window).on('a13AjaxBeforeRequestNewSite', destroy);
        };

		album.toggleThumbsTray = function(ev){
			if(typeof ev !== 'undefined'){
				ev.stopPropagation();
				ev.preventDefault();
			}

			var offset,
				first_visible, last_visible,
				i, last, iterations, animNo,
				animation, afterAnimation, callback,
				base_time = 300;

			//if hiding tray
			if(tray_button.hasClass('active')){
				$body.addClass('slider-thumbs-hidden');
				if(fancyThumbsHiding){
					tray_button.removeClass('active');
					// If thumb can be out of view
					if (thumb_list_w > tray_width ) {
						offset = thumb_list.offset().left;
						first_visible = Math.floor(-offset / thumb_width);
						last_visible = Math.floor((-offset + tray_width) / thumb_width);

						//check if we arent too far in our math
						last_visible = last_visible >= slides_num ? slides_num - 1 : last_visible;

					}
					//if all thumbs fit width
					else{
						first_visible = 0;
						last_visible = slides_num - 1;
					}
					iterations = Math.ceil((last_visible - first_visible + 1) / 2);

					animation = function(id,i,last){
						callback = last ? afterAnimation : null;

						thumbs.eq(id).stop().animate(
							{
								marginTop : 100,
								opacity: 0
							},
							base_time+i*100, // each thumb 100ms longer animation
							'easeInBack', callback);
					};

					afterAnimation = function(){
						tray.hide();
						//clean animation css
						thumbs.css(
							{
								marginTop : '',
								opacity: ''
							}
						);
					};

					//hide items from edges to center
					for(i = 0; i < iterations; i++){
						last = i === (iterations-1);

						if(first_visible+i === last_visible-i){
							animation(first_visible+i,i,last);
						}
						else{
							animation(first_visible+i,i,last);
							animation(last_visible-i,i,last);
						}
					}
					//}
				}
				//normal hiding
				else{
					tray_button.removeClass('active');
					tray_i.stop().animate({top : tray.innerHeight()+20}, //+20 is selected element that is sticking out
						minorHideTime, function(){tray.hide();} );
				}
			}

			//if opening tray
			else{
				$body.removeClass('slider-thumbs-hidden');
				if(fancyThumbsHiding){
					tray_button.addClass('active');

					//make tray measurable
					tray.css({
						visibility: 'hidden',
						display: 'block'
					});

					// If thumb can be out of view
					if (thumb_list_w > tray_width ) {
						offset = thumb_list.offset().left;
						first_visible = Math.floor(-offset / thumb_width);
						last_visible = Math.floor((-offset + tray_width) / thumb_width);

						//check if we arent too far in our math
						last_visible = last_visible >= slides_num ? slides_num - 1 : last_visible;
					}
					//if all thumbs fit width
					else{
						first_visible = 0;
						last_visible = slides_num - 1;
					}
					iterations = Math.ceil( (last_visible - first_visible + 1) / 2);

					animation = function(id,i,last){
						var callback = last ? afterAnimation : null;

						thumbs.eq(id).stop().animate(
							{
								marginTop : 0,
								opacity: 1
							},
							base_time+i*100, // each thumb 100ms longer animation
							'easeOutBack', callback);
					};

					afterAnimation = function(){
						tray_button.addClass('active');
						//clean animation css
						thumbs.css(
							{
								marginTop : '',
								opacity: ''
							}
						);
					};


					//initially hide items that we want to show
					for(i = 0; i <= last_visible; i++){
						thumbs.eq(first_visible+i).css(
							{
								marginTop : 100,
								opacity: 0
							}
						);
					}

					//make tray visible
					tray.css('visibility', '');

					//show items from center  to edges
					for(i = iterations-1; i >= 0; i--){
						last = i === 0;
						animNo = iterations - 1 - i;
						if(first_visible+i === last_visible-i){
							animation(first_visible+i,animNo,last);
						}
						else{
							animation(first_visible+i,animNo,last);
							animation(last_visible-i,animNo,last);
						}
					}
					//}
				}
				else{
					tray.show();
					tray_button.addClass('active');
					tray_i.stop().animate({top : '55px'}, minorShowTime );
				}
			}
		};


		album.mouse_scrub = function(){
			var
				//for checking if current move want get out of tray scope
				checkEdges = function(distance){
					if(distance > 0){
						return 0;
					}
					else if(distance < maxThumbsCssLeft){
						return maxThumbsCssLeft;
					}
					return distance;
				},

				mouseScroll = function(event,delta){
					event.preventDefault();
					//do nothing
					if(tray.hasClass('nomove')){
						return;
					}

					delta =  parseInt(delta, 10);
					var offset = thumb_list.offset().left,
						first_visible = thumbs.eq(Math.ceil(-offset / thumb_width)),
						to_end = first_visible.nextAll().andSelf().length * thumb_width,
						to_move = delta * thumb_width,
						move;

					//move forward
					if(to_move < 0){
						if(tray_width > (to_end + to_move) ){
							//forward edge');
							move = -thumb_list_w + tray_width; //right edge
						}
						else{
							//forward normal
							move = -first_visible.position().left + to_move; //normal move
						}
					}
					//move backward
					else{
						//if less then 0.9 thumb to scroll, jump to another(improves backward scrolling)
						if( -offset + (-first_visible.position().left + to_move) < (0.9)*thumb_width ){
							to_move += thumb_width;
						}
						if((offset + to_move) > 0 ){
							//backward edge
							move = 0; //left edge
						}
						else{
							//backward normal
							move = -first_visible.position().left + to_move; //normal move
						}
					}

					animate_thumbs(move);
				};

			//bind events
			if(isTouch){
				var element = tray[0],  //DOM element
					currentPosition,    //position of tray on start
					currentX,           //current position of finger
					startX,             //where was finger at move start
					lastX,              //last finger saved for comparison
					preLastDistance = 0,//pre last distance, for edge cases
					lastT,              //last time we checked distance
					threshold = 120,    //maximum time of no-move
					maxTapDistance = 7,
					multiplier = 5,
					now,


					onTouchStart = function(e){
						//do nothing
						if(tray.hasClass('nomove') === true){
							return;
						}
						if (e.touches.length === 1) {
							//collect init data
							lastT = Number(new Date());
							lastX = startX = currentX = e.touches[0].pageX;
							thumb_list.stop(); //stop any animation
							currentPosition = parseInt(thumb_list.css('left'), 10);

							//bind events for other work
							element.addEventListener('touchmove', onTouchMove, false);
							element.addEventListener('touchend', onTouchEnd, false);
						}
						//more fingers - we don't react
						else{ e.preventDefault(); }
					},

					onTouchMove = function(e){
						e.preventDefault();

						currentX = e.touches[0].pageX;
						now = Number(new Date());
						//if it is time for new measure new distance
						if(now - lastT > threshold){
							preLastDistance = lastX - currentX;
							lastT = now;
							lastX = currentX;
						}

						//update position of tray
						thumb_list.css('left', checkEdges(parseInt(currentPosition - (startX - currentX), 10)) );
					},

					onTouchEnd = function(e){
						var now = Number(new Date()),
							time = now - lastT,
						//calculate distance in full time cycle
							lastDistance = parseInt(threshold/time * (lastX - currentX), 10),
							ldAbs = Math.abs(lastDistance),
							animationDistance = 0;

						if(ldAbs > maxTapDistance * multiplier){
							animationDistance = lastDistance;
						}
						else if(Math.abs(preLastDistance) > maxTapDistance && time < threshold){
							animationDistance = preLastDistance;
						}

						//micro move we treat like tap
						if(!(preLastDistance === 0 && ldAbs <= maxTapDistance)){
							//it was NOT tap
							//it was slide
							e.preventDefault();

							if(animationDistance !== 0){

								thumb_list.stop()
									.animate({
										left : checkEdges(parseInt(thumb_list.css('left'), 10) - animationDistance * multiplier)
									}, 1000, 'easeOutSine');
							}
						}

						// finish the touch by undoing the touch session
						element.removeEventListener('touchmove', onTouchMove, false);
						element.removeEventListener('touchend', onTouchEnd, false);
						//clean after work
						preLastDistance = currentX = startX = 0;
					};

				element.addEventListener('touchstart', onTouchStart, false);
			}

			//scrolling with mouse
			tray
				.mouseenter(function() {
					//do nothing
					if(tray.hasClass('nomove')){
						return;
					}

					tray.data('mouseover', true);
				})
				.mouseleave(function() {
					tray.data('mouseover', false);
				})
				.mousewheel(throttle(mouseScroll, 100));
        };



        /* Loads APIs for Video types
         ----------------------------*/
        album.loadVideoApi = function(){
            //load Youtube API
            if(loadYouTubeAPI === true){
                //this function will run when YT API will load
                window.onYouTubeIframeAPIReady = function() {
                    if(A13_slider_debug){ console.log('Youtube Api ready!'); }
                    album.YT_ready(true);
                };

                //load YT API
                (function(){
                    var tag = document.createElement('script');
                    tag.src = "//www.youtube.com/iframe_api";
                    var firstScriptTag = document.getElementsByTagName('script')[0];
                    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                })();

            }

            //load Vimeo API
            if(loadVimeoAPI === true){
                //load VIMEO API
                (function(){
                    var tag = document.createElement('script');
                    tag.src = "http://a.vimeocdn.com/js/froogaloop2.min.js";
                    var firstScriptTag = document.getElementsByTagName('script')[0];
                    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                })();
            }

            //load native video API
            if(loadNativeVideoAPI === true){
                //load mediaElement API
                //loaded already
            }
        };

        /* Define YT_ready function.
         ----------------------------*/
        album.YT_ready = (function(){
            var onReady_funcs = [], api_isReady = false;
            /* @param func function     Function to execute on ready
             * @param func Boolean      If true, all queued functions are executed
             * @param b_before Boolean  If true, the func will added to the first
             position in the queue*/
            return function(func, b_before){
                if (func === true) {
                    api_isReady = true;
                    for (var i=0; i<onReady_funcs.length; i++){
                        // Removes the first func from the array, and execute func
                        onReady_funcs.shift()();
                    }
                }
                else if(typeof func === "function") {
                    if (api_isReady){ func(); }
                    else { onReady_funcs[b_before?"unshift":"push"](func); }
                }
            };
        })();

        /* Init player so it can be manipulated by API
         ----------------------------*/
        album.initPlayer = function(playerId, onReady){
            var current = videos[playerId],
                frame = $el.find('#'+playerId).find('iframe');

			//html5 not uses frame
			if(frame.length){
				frame = frame.get(0);// DOM element
			}

            //if player is initialized already
            if(typeof current.player !== 'undefined'){
                return;
            }

            if(typeof onReady !== 'function'){
                //empty function
                onReady = function(){};
            }

            if(A13_slider_debug){ console.log('init player', playerId, onReady.toString(), current.video_type); }

            if(current.video_type === 'youtube'){
                //cause youTube iframe API breaks on firefox when using iframes
                //we will grab parameters and then switch iframe with div with same id
                var elem    = $el.find('#'+playerId).find('div[data-vid_id]'),
                    vid_id  = elem.data('vid_id'),
                    width   = elem.data('width'),
                    height  = elem.data('height');

                current.player = new YT.Player(elem.get(0), {
                    height: height,
                    width: width,
                    videoId: vid_id,
                    playerVars : {wmode: 'transparent', rel: 0, vq: 'HD1080' },
                    events: {
                        'onReady'       : onReady,
                        'onStateChange' : album.videoStateChange
                    }
                });

            }

            else if(current.video_type === 'vimeo'){
                if(typeof $f !== 'undefined'){
                    current.player = $f(frame);
                    current.player.addEvent('ready', function() {
                        current.player.addEvent('pause', function(){ album.videoStateChange(2); });
                        current.player.addEvent('play', function(){ album.videoStateChange(1, playerId); });
                        current.player.addEvent('finish', function(){ album.videoStateChange(0); });

                        onReady();
                    });
                }
                else{
                    if(A13_slider_debug){ console.log('Vimeo API NOT loaded!'); }
                    //try again after 0.5s
                    setTimeout(function(){ album.initPlayer(playerId, onReady); }, 500);
                }
            }

            else if(current.video_type === 'html5'){
                if(typeof MediaElement !== 'undefined'){
                    var vid = $el.find('#'+playerId).find('video');

					//resize video to full size
					vid
						.attr('width', parseInt($el.width(), 10))
						.attr('height',  parseInt($el.height(), 10))
						.css({
							height : '100%',
							width  : '100%'
						});

                    current.player = new MediaElementPlayer(vid.get(0),{
						success : function(mediaElement, domObject){
							mediaElement.addEventListener('pause', function(){ album.videoStateChange(2); });
							mediaElement.addEventListener('play', function(){ album.videoStateChange(1); });
							mediaElement.addEventListener('ended', function(){ album.videoStateChange(0); });

						}
					});

					//fire this play or pause
					onReady();
                }
                else{
                    if(A13_slider_debug){ console.log('HTML5 Video API NOT loaded!'); }
                    //try again after 0.5s
                    setTimeout(function(){ album.initPlayer(playerId, onReady); }, 500);
                }
            }
        };

        /* Plays Video
         ----------------------------*/
        album.playVideo = function(){
            var playerId = slide_id_pre+current_slide,
                current = videos[playerId],
                type;
            if(A13_slider_debug){ console.log('play video', playerId, 'no type yet'); }

            //if no such player
            if(typeof current === 'undefined'){
                return;
            }
            type = current.video_type;
            if(A13_slider_debug){ console.log('play video', playerId, type); }

            //helper function
            var play = function(){
                if(type === 'youtube'){ current.player.playVideo(); }
                else if(type === 'vimeo'){ current.player.api('play'); }
                else if(type === 'html5'){ current.player.play(); }
            };

            //player not initialized yet
            if(typeof current.player === 'undefined'){
                //helper function
                var init = function(){
                    album.initPlayer(playerId, function(){ play(); } );
                };

                if(type === 'youtube'){ album.YT_ready( function(){ init(); }); }
                else if(type === 'vimeo'){ init(); }
                else if(type === 'html5'){ init(); }
            }
            else{
                play();
            }

			//show video, so user can see it is loading
			all_slides.eq(current_slide).find('.video-poster').fadeOut(minorHideTime);
        };

        /* Stops playing video
         ----------------------------*/
        album.pauseVideo = function(playerId){
            if(typeof playerId === 'undefined'){
                playerId = slide_id_pre+current_slide;
            }
            var current = videos[playerId],
//                player = '',
                type;

            //if no such player
            if(typeof current === 'undefined'){
                return;
            }

            type = current.video_type;

            if(A13_slider_debug){ console.log('pause video', playerId, type); }

            //helper function
            var pause = function(){
				if(type === 'youtube' && typeof current.player !== 'undefined' && typeof current.player.pauseVideo !== 'undefined'){
					//pause only when video was playing, cause pausing when video is not started breaks mobile players
					if(current.player.getPlayerState() === 1){
						current.player.pauseVideo();
					}
				}
                else if(type === 'vimeo' && typeof current.player !== 'undefined' && typeof current.player.api !== 'undefined'){ current.player.api('pause'); }
                else if(type === 'html5' && typeof current.player !== 'undefined' && typeof current.player.pause !== 'undefined'){ current.player.pause(); }
            };

            //player not initialized yet
            if(typeof current.player === 'undefined'){
                //helper function
                var init = function(){
                    album.initPlayer(playerId, function(){ pause(); } );
                };

                if(type === 'youtube'){ album.YT_ready( function(){ init(); }); }
                else if(type === 'vimeo'){ init(); }
                else if(type === 'html5'){ init(); }
            }
            else{
                pause();
            }
        };

        /* Video events handling
         ----------------------------*/
        album.videoStateChange = function(event, playerId){
            /*
            * VIMEO & HTML5 VIDEO change returns number
            * Youtube change returns event object
            * */
            var state = event;

            if(typeof state === 'object'){
                state = event.data;
            }

            if(A13_slider_debug){ console.log('player state: ' + state, typeof event, playerId); }

			var actual_slide = all_slides.eq(current_slide),
				slide_caption = actual_slide.find('div.slide-caption');

            //if playing
            if(state === 1){
                //stops slide show things on video playback
                album.stopMovingToNextSlide();
                is_video_playing = true;
				//take care of full screen
				album.turn_fullscreen(1);

				//take care of thumbnails
				if(thumb_links && tray_button.hasClass('active')){
					album.toggleThumbsTray();
				}

				//slide-caption
				if(slide_caption.length){
					slide_caption.fadeOut();
				}

				//show video
				actual_slide.find('.video-poster').fadeOut(minorHideTime);

				//fire event that we are about to start video
				$body.trigger('a13SliderVideoStarts');

                //protection for auto playing vimeo video after YT player initialization
                //it may play when it is not visible
                //only vimeo video return playerId on state change
                if(typeof playerId !== 'undefined' && playerId !== slide_id_pre+current_slide){
                    videos[playerId].player.api('pause');
                    return;
                }
            }
			//if paused or ended
            else if(state === 0 || state === 2){
                is_video_playing = false;
				album.turn_fullscreen(0);

            	//if video ended and slide show is not paused
                if(state === 0 && is_slider_playing){
                    album.nextSlide();
                }

				//slide-caption
				if(slide_caption.length){
					slide_caption.fadeIn();
				}
            }
        };


        /* Resize Images
          ----------------------------*/
		album.resizeNow = function(image){
			//all images or only one?
            var elem = (typeof image === 'undefined')? all_slides.children('a').children('img') : $(image);

            //  Resize each image
            elem.each(function(){
                var thisSlide = $(this),
                    image_height  	= thisSlide.data('origHeight'),
                    image_width  	= thisSlide.data('origWidth'),
                    space_width    	= $el.width(),
                    space_height   	= $el.height(),
                    image_ratio 	= (image_height/image_width).toFixed(2),
                    space_ratio 	= (space_height/space_width).toFixed(2),

                    fit_always      = fit_variant === 0,
                    fit_landscape   = fit_variant === 1,
                    fit_portrait    = fit_variant === 2,
                    fit_when_needed = fit_variant === 3,
                    fit_cover 		= fit_variant === 4,
					// Size & Position
					//Cover: Image will always cover all available area
					//Always: Image will never exceed browser width or height (Ignores min. dimensions)
					//Landscape: Landscape images will not exceed browser width
					//Portrait: Portrait images will not exceed browser height
					//When Needed: Best for small images that shouldn't be stretched

                    resizeWidth = function(){
                        thisSlide.width(space_width);
                        thisSlide.height(space_width * image_ratio);
                    },

                    resizeHeight = function(){
                        thisSlide.height(space_height);
                        thisSlide.width(space_height / image_ratio);
                    };

                /*-----Resize Image-----*/
                if (fit_when_needed){
                    //reset
                    thisSlide.css({
                        width: image_width,
                        height: image_height
                    });

                    if( image_height > space_height || image_width > space_width){
                        if (space_ratio > image_ratio){
                            resizeWidth();
                        } else {
                            resizeHeight();
                        }
                    }
                }
                else if (fit_always){
                    if (space_ratio > image_ratio){
                        resizeWidth();
                    } else {
                        resizeHeight();
                    }
                }
                else if (fit_cover){
                    if (space_ratio > image_ratio){
                        resizeHeight();
                    } else {
                        resizeWidth();
                    }
                }
                else{	// Normal Resize
                    if (space_ratio > image_ratio){
                        // If landscapes are set to fit
                        if(fit_landscape && image_ratio < 1){
                            resizeWidth();
                        }
                        else{
                            resizeHeight();
                        }
                    } else {
                        // If portraits are set to fit
                        if(fit_portrait && image_ratio >= 1){
                            resizeHeight();
                        }else{
                            resizeWidth();
                        }
                    }
                }
                /*-----End Image Resize-----*/

                // Horizontally Center
				thisSlide.css('left', (space_width - thisSlide.width())/2);

                // Vertically Center
				thisSlide.css('top', (space_height - thisSlide.height())/2);

            });
		};


        /* Filling empty slides when need
         ----------------------------*/
        album.fillSlide = function(loadSlide, bonusClass, firstSlide){
            var targetSlide = all_slides.eq(loadSlide),
				slide_options = slides[loadSlide],
                slide_type  = slide_options.type,
                addClass    = (typeof bonusClass !== 'undefined'),
                first       = (typeof firstSlide !== 'undefined'),
                imageLink, item;

            //if slide is empty
            if (!targetSlide.html()){
                if(slide_type === 'image') {
					imageLink = (slide_options.url) ? "href='" + slide_options.url + "'" : "";	// If link exists, build it
					item = $('<img src="" />');

					//add classes to li
					targetSlide.addClass('image-loading' + (addClass ? ' ' + bonusClass : '')).css(hidden);

					item
						.appendTo(targetSlide).wrap('<a class="slide"' + imageLink + '></a>')
						.load(function () {
							album._origDim($(this));
							album.resizeNow(this);
							targetSlide.removeClass('image-loading');
							item.hide().fadeIn(minorShowTime);
							//start slider if we have first slide prepared
							if (first) {
								album.launch();
							}
							//check if there shouldn be called ken burns effect for this slide
							//useful when changing slides with goTo
							else if (loadSlide === current_slide) {
								album.kenBurnsEffect();
							}
						})
						.attr('src', slide_options.image).attr('alt', slide_options.alt_attr);

					//photo bg color
					targetSlide.css('background-color', slide_options.bg_color);
				}
                else if(slide_type === 'video'){
					//cover for video
					$('<div class="video-poster" style="background-image: url('+slide_options.image+');">').appendTo(targetSlide);
					//when image is loaded hide loading animation
					$('<img src="" />')
						.load(function(){
							//var cover = $('<div class="video-poster" style="background-image0: url('+$(this).attr('src')+');">').appendTo(targetSlide);
							targetSlide.removeClass('image-loading');
							//cover.hide().fadeOut(minorShowTime);
						})
						.attr('src', slide_options.image);

                    targetSlide
                        .addClass('image-loading' + (addClass? ' '+bonusClass : ''))
                        .css(hidden);

					if(slide_options.video_type === 'html5'){
						targetSlide.append($(slide_options.video_url).html());
					}
					else{
						targetSlide.append('<iframe src="'+slide_options.video_url+'" allowfullscreen />');
					}

                    if(slide_options.video_type === 'youtube'){
                        //cause youTube iframe API breaks on firefox when using iframes
                        //we will grab parameters and then switch iframe with div with same id
                        var frame = targetSlide.find('iframe'),
                            vid_id = frame.attr('src'),
                            width = frame.width(),
                            height = frame.height(),
                            temp;

                        //search for video id
                        temp = /embed\/([a-zA-Z0-9\-_]+)\??/ig.exec(vid_id);
                        if(temp !== null && temp.length === 2){
                            vid_id = temp[1];
                        }

                        //insert empty div & remove old iframe
                        $('<div/>',{
                            'data-vid_id': vid_id,
                            'data-width': width,
                            'data-height': height
                        }).insertBefore(frame);
                        frame.remove();
                    }

                    if(first){
                        album.launch();

                        //if first slide is video with autoplay enabled
                        if(slide_type === 'video'){
                            if(getField('autoplay')){
                                album.stopMovingToNextSlide();
								album.playVideo();
                            }
                            else{
                                album.pauseVideo();//need for YT video if it is first slide
                            }
                        }

                    }
                }

				var socials	= options.original_items.eq(loadSlide).find('.a2a_kit');

				//add texts to slide?
				if(options.texts){
					var text_html,
						title       = $.trim(slide_options.title),
						desc        = $.trim(slide_options.desc);

					//title color
					if(title.length && title_color.length){
						title = '<span style="background-color:'+title_color+'">'+title+'</span>';
					}

					//add caption
					if (title.length || desc.length || socials.length){
						text_html =
							'<div class="slide-caption">' +
								(title.length ? '<h2 class="title">'+title+'</h2>' : '') +
								((desc.length || socials.length) ? '<div class="description">'+desc+'</div>' : '') +
								'<div class="texts-opener">+</div>' +
							'</div>';

						targetSlide.append(text_html);
						targetSlide.find('div.description').append( socials );
					}
				}
				else{
					if(socials.length){
						targetSlide
							//prepare description HTML
							.append('<div class="slide-caption"><div class="description"></div><div class="texts-opener">+</div></div>')
							//add socials
							.find('div.description').append( socials );
					}
				}

                if(first){ targetSlide.attr('style',''); } //clear init hide for first slide
            }
        };


        /* Change Slide
		----------------------------*/
		album.changeSlide = function(isPrev){
            if(typeof isPrev === 'undefined'){ isPrev = false; }

			// Abort if currently animating
			if(in_slide_transition){ return false; }
            // Otherwise set animation marker
            else{ in_slide_transition = true; }

			if(is_slider_playing){
            	album.stopMovingToNextSlide();
			}

            // Find active slide
			var	oldSlide = all_slides.filter('.activeslide');

            if(oldSlide){
                //pause playing video
                album.pauseVideo(oldSlide.attr('id'));
            }

			// Get the slide number of new slide
            if(isPrev){
                current_slide = current_slide <= 0 ?  slides_num - 1 : current_slide-1 ;
            }
            else{
                current_slide = current_slide + 1 === slides_num ? 0 : current_slide+1;
            }

            //clean old prev slide
            all_slides.filter('.prevslide').removeClass('prevslide');
            // Remove active class & update previous slide
            oldSlide.removeClass('activeslide').addClass('prevslide');

            var afterCB = function(){ clean_prev_slide(oldSlide); },
                nowSlide = all_slides.eq(current_slide),
                loadNextSlide = (current_slide === slides_num - 1) ? 0 : current_slide + 1,	// Determine next slide for preload
                loadPrevSlide = (current_slide === 0) ? slides_num - 1 : current_slide - 1;	// Determine previous slide for preload

            //if slide was not filled yet
            album.fillSlide(loadNextSlide);
            album.fillSlide(loadPrevSlide);


			// Call function for before slide transition
			album.beforeAnimation( isPrev? 'prev' : 'next' );


           nowSlide.css({visibility:'hidden'}).addClass('activeslide');	// Update active slide

            switch(options.transition){
                case 0:	// No transition
                    nowSlide.css({visibility:'visible', left: ''}); in_slide_transition = false; afterCB(); album.afterAnimation();
                    break;
                case 1:	// Fade
				case 3:
                    nowSlide.css({ visibility : 'visible', opacity : 0, left: ''}).animate({opacity : 1}, transition_speed, album.afterAnimation);
                    oldSlide.animate({opacity : 0}, transition_speed, afterCB);
                    break;
                case 2:	// Carousel
                    nowSlide.css({ visibility : 'visible', left : (isPrev? -$el.width() : $el.width())}).animate({left:0}, transition_speed, album.afterAnimation);
                    if(isPrev){
                        oldSlide.css({left : 0}).animate({left: $el.width()}, transition_speed, afterCB );
                    }
                    else{
                        oldSlide.animate({left: -$el.width()}, transition_speed, afterCB );
                    }
                    break;
            }

            return false;
		};

        album.prevSlide = function(ev){
			if(typeof ev !== 'undefined'){
				ev.stopPropagation();
				ev.preventDefault();
			}
            if (slides_num > 1){
                album.changeSlide(true);
            }
        };

        album.nextSlide = function(ev){
			if(typeof ev !== 'undefined'){
				ev.stopPropagation();
				ev.preventDefault();
			}
            if (slides_num > 1){
                album.changeSlide();
            }
        };


        album.playToggle = function(ev){
			if(typeof ev !== 'undefined'){
				ev.stopPropagation();
				ev.preventDefault();
			}
            if (in_slide_transition || slides_num < 2){ return; }		// Abort if currently animating
			//pause it
            if (is_slider_playing){
                is_slider_playing = false;
                album.indicatePlayerState('pause');
				album.stopMovingToNextSlide();
				album.kenBurnsEffectPause();
            }
			//play it
            else{
                is_slider_playing = true;
                album.indicatePlayerState('play');
                album.startMovingToNextSlide();
				album.kenBurnsEffect();
            }
        };


        album.indicatePlayerState = function(state){
            var big_play = $('#big-play'),
                current = all_slides.eq(current_slide);

			//create element if it doesn't exist yet
            if(!big_play.length){
                big_play = $('<div id="big-play" />');
            }

            if (state === 'play'){
                play_button.addClass(state).removeClass('pause');
                big_play.addClass(state).removeClass('pause');
            }
            else if (state === 'pause'){
                play_button.addClass(state).removeClass('play');
                big_play.addClass(state).removeClass('play');
            }

            //no big play above videos to not confuse anyone
            if(getField('type') === 'video'){
                return;
            }

			big_play
				.stop()
				.appendTo(current)
				.attr('style','')
				.animate({
					height 		: big_play.height() * 1.5,
					width 		: big_play.width() * 1.5,
					marginLeft	: parseInt(big_play.css('margin-left'), 10) * 1.5,
					marginTop	: parseInt(big_play.css('margin-top'), 10) * 1.5,
					opacity		: 0
				}, 400, function(){ big_play.hide(); }
			);
        };


        album.stopMovingToNextSlide = function(){
			album.stopProgressBar();
            clearTimeout(slideshow_interval_id);
        };

        album.startMovingToNextSlide = function(){
			slideshow_interval_id = setTimeout(album.nextSlide, slider_interval_time);
			album.progressBar();
        };


        /* Go to specific slide
		----------------------------*/
        album.goTo = function(targetSlide){
			if (in_slide_transition){return;}		// Abort if currently animating

			// If target outside range
			if(targetSlide < 0){
				targetSlide = 0;
			}
            else if(targetSlide > slides_num-1){
				targetSlide = slides_num - 1;
			}

			if (current_slide === targetSlide){
				//is_pa
				return;
			}

            album.fillSlide(targetSlide);
            clean_after_goTo_function = 1;
			// If ahead of current position
			if(current_slide < targetSlide){
				// Adjust for new next slide
				current_slide = targetSlide-1; //need to go step back
                album.nextSlide();
			}
			//Otherwise it's before current position
            else if(current_slide > targetSlide){
				// Adjust for new prev slide
				current_slide = targetSlide+1; //need to go step forward
                album.prevSlide();
			}

			if (thumb_links){
				thumbs.filter('.current-slide').removeClass('current-thumb');
				thumbs.eq(targetSlide).addClass('current-thumb');
			}
		};


		/* Save Original Dimensions of images
		----------------------------*/
		album._origDim = function(targetSlide){
			targetSlide.data('origWidth', targetSlide.width()).data('origHeight', targetSlide.height());
		};

		album.kenBurnsEffect = function(){
			//Ken burns aka Zooming effect?
			if(options.transition === 3){
				if(getField('type') === 'image'){

					var this_img = all_slides.eq(current_slide).find('a.slide').children();//img

					//if we have animation already then just restart it
					if(typeof this_img[0].animation !== 'undefined'){
						this_img[0].animation.restart();
						return;
					}

					var	getRange = function(from,to){
							if(from > to ){
								//switch to correct order
								var temp = to;
								to = from;
								from = temp;
							}
							return Math.floor(Math.random()*(to-from+1)) + from;
						},

						rand = Math.random(),
						time = (2*transition_speed + slider_interval_time)/1000,
						scale = parseInt(options.ken_burns_scale, 10)/100,
						w = parseInt(this_img.width(), 10),
						h = parseInt(this_img.height(), 10),
						top = parseInt(this_img.css('top'), 10),
						left = parseInt(this_img.css('left'), 10),
						zoom_w = w * scale,
						zoom_h = h * scale,
						zoom_top = (top - (zoom_h - h)/ 2),
						zoom_left = (left - (zoom_w - w)/ 2),
						start_vars, animation_vars,
						start_shift_left = getRange(-left,left),
						start_shift_top = getRange(-top,top),
						end_shift_left = getRange(-zoom_left, zoom_left),
						end_shift_top = getRange(-zoom_top, zoom_top);

					start_vars = {
						scale: 1,
						x: start_shift_left,
						y: start_shift_top
					};

					animation_vars = {
						scale: scale,
						x: end_shift_left,
						y: end_shift_top,
						ease: Linear.easeNone
					};

					//zoom in
					if(rand > 0.5 ){
						this_img[0].animation = TweenMax.fromTo(this_img, time, start_vars, animation_vars);
					}
					//zoom out
					else{
						//swap some values
						start_vars.ease = Linear.easeNone;

						this_img[0].animation = TweenMax.fromTo(this_img, time, animation_vars, start_vars);
					}
				}
			}

		};

		album.kenBurnsEffectPause = function(){
			//Ken burns aka Zooming effect?
			if(options.transition === 3){
				var this_img = all_slides.eq(current_slide).find('a.slide').children();//img

				if(this_img[0].animation.isActive()) {
					if(typeof this_img[0].animation !== 'undefined'){
						this_img[0].animation.pause().reverse();
					}
					//this_img.stop(false, false); //don't call callback
				}
			}
		};


		album.kenBurnsEffectStop = function(){
			if(options.transition === 3 && getField('type') === 'image') {
				var this_img = all_slides.eq(current_slide).find('img');

				if(typeof this_img[0].animation !== 'undefined'){
					this_img[0].animation.pause(0, true);
				}

				//reset all animations
				all_slides.each(function(index){
					if(slides[index].type === 'image'){
						var img = all_slides.eq(index).find('img');
						if(img.length){
							img[0].animation = undefined;
						}
					}
				});
			}
		};


		album.afterAnimation = function(){
			// Update previous slide
			if (clean_after_goTo_function){
                var setPrev = (current_slide - 1 < 0) ? slides_num - 1 : current_slide-1;
				clean_after_goTo_function = false;
				all_slides.filter('.prevslide').removeClass('prevslide');
				all_slides.eq(setPrev).addClass('prevslide');
			}

			in_slide_transition = false;

            if(getField('type') === 'video'){
            	//if current slide is video with auto-play option
				if( getField('autoplay') ){
					//play video
					album.playVideo();
					//nothing to do more
					return;
				}
                //or just initialize API
				else{
                    album.initPlayer(slide_id_pre+current_slide);

				}
            }

            if (is_slider_playing){
				album.startMovingToNextSlide();
            }
		};

        album.beforeAnimation = function(direction, firstRun){
			if(getField('type') === 'image' && is_slider_playing){
				album.kenBurnsEffect();
		}

		// Highlight current thumbnail and adjust row position
		if (thumb_links){
			var thumb_list_w = thumb_list.width(),
				position     = 0,
				current_thumb, temp, slidePx;

			//change current thumb class
			thumbs.filter('.current-thumb').removeClass('current-thumb');
			current_thumb = thumbs.eq(current_slide).addClass('current-thumb');

			if(tray.data('mouseover') !== true){
				// If thumb can be out of view
				if (thumb_list_w > tray_width ){
					position = current_thumb.offset().left - tray.offset().left;

					if (current_slide === 0){
						animate_thumbs(0);
					}
					//thumb out off view on the right
					else if (position >= thumb_interval){
						temp = current_thumb.nextAll().andSelf().length * thumb_width;
						//if there is less slides than width of tray
						if(temp <= thumb_interval ){
							slidePx = -(current_thumb.position().left  - (tray_width - temp));
						}
						else{
							slidePx = -current_thumb.position().left;
						}

						animate_thumbs(slidePx);
					}
					//thumb out off view on the left
					else if(position < 0){
						animate_thumbs(-current_thumb.position().left);
					}
				}
			}
		}
	};

		album.progressBar = function(stop){
			//don't do anything if progress bar is disabled
			if (!p_bar_enabled){
				return;
			}

			stop = (typeof stop === 'undefined')? false : stop;
            var parent = play_button,
				size = parent.width(),
				parts = parent.find('em'),
				part_time = slider_interval_time/4,
				i = 0;
			
			//stop all animations
			parts.eq(0).stop().css( 'width' , 0);
			parts.eq(2).stop().css( 'width' , 0);
			parts.eq(1).stop().css( 'height' , 0);
			parts.eq(3).stop().css( 'height' , 0);

			if(!stop){
				//animate all progress bar parts one by one
				parts.eq(0)
					.animate({
						width : size
					}, part_time, 'linear', function(){
						parts.eq(1)
							.animate({
								height : size
							}, part_time, 'linear', function(){
								parts.eq(2)
									.animate({
										width : size
									}, part_time, 'linear', function(){
										parts.eq(3)
											.animate({
												height : size
											}, part_time, 'linear');
									});
							});
					});
			}
        };

		album.stopProgressBar = function(){
			album.progressBar(1);
		};

		album.turn_fullscreen = function(turn_on){
			var fs_switch = $('#fs-switch'),
				in_fs = $body.hasClass('fullscreen');

			if((in_fs && turn_on)) {
				//do nothing
			}

			else if(!in_fs && !turn_on){
				is_fullscreen_for_video = false;
			}

			else if(!turn_on && in_fs){
				if(is_fullscreen_for_video){
					is_fullscreen_for_video = false;
					fs_switch.click();
				}
			}

			else if(turn_on && !in_fs){
				is_fullscreen_for_video = true;
				fs_switch.click();
			}
		};

		album.textsToggle = function(ev){
			var toggle = $(this),
				elements = toggle.parent().children().not(toggle);
			if(toggle.hasClass('open')){
				toggle.removeClass('open').text('+');
				elements.fadeOut(400, function () {
					elements.attr('style', '' );//clean inline style
				});
			}
			else{
				toggle.addClass('open').text('-');
				elements.fadeIn();
			}

			if(typeof ev !== 'undefined'){
				ev.stopPropagation();
				ev.preventDefault();
			}
		};

        // Make it go!
        album.prepareEnv();
	};

    $.fn.a13slider = function(options){
        return this.each(function(){
            $.a13slider(options);
        });
    };
})(jQuery);