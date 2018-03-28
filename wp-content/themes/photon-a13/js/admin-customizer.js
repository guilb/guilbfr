/*global A13_CUSTOMIZER_DEPENDENCIES, wp, console */
( function( api ) {
( function( $ ){
	"use strict";

		//swaping elemnts in array
	var a13_array_swap = function (x,y) {
			var b = this[x];
			this[x] = this[y];
			this[y] = b;
			return this;
		},

		//moving element in Array from one place to another
		a13_array_move = function (old_index, new_index) {
			if (new_index >= this.length) {
				var k = new_index - this.length;
				while ((k--) + 1) {
					this.push(undefined);
				}
			}
			this.splice(new_index, 0, this.splice(old_index, 1)[0]);
			return this; // for testing purposes
		},

		a13_check_customizer_controls = function(){
		if(typeof A13_CUSTOMIZER_DEPENDENCIES !== 'undefined'){
			var $t = $(this),
				id = $t.data('customize-setting-link'),
				ACD = A13_CUSTOMIZER_DEPENDENCIES,
				deps = ACD.dependencies,
				switches = ACD.switches,
				visible,
				requirements, requirements_keys, field;

			//if true we need to check controls that depend on changed switch
			if(typeof switches[id] !== 'undefined'){

				//for each control that depends on this switch...
				for(var i = 0, size = switches[id].length; i < size; i++){
					visible = true; //reset
					requirements = deps[ switches[id][i] ];
					requirements_keys = Object.keys(requirements);

					//... check values of all switches it depends on
					for(var j = 0, size_rk = requirements_keys.length; j < size_rk; j++){
						field = $( '[data-customize-setting-link="'+requirements_keys[j]+'"]');
						//if radio then get selected one
						if(field.is(':radio')){
							field = field.filter(':checked');
						}

						if(requirements[requirements_keys[j]] !== field.val() ){
							visible = false;
							break;
						}
					}
					api.control(switches[id][i]).toggle(visible);
				}
			}
		}
		},
		a13_slider_control = function(){
			var sliders = $('div.slider-place');
			if(sliders.length){
				//setup sliders
				sliders.each(function(index){
					var min,max,unit,$s;
					//collect settings
					$s = sliders.eq(index);
					min = $s.data('min');
					min = (min === '')? 10 : min; //0 is allowed now
					max = $s.data('max');
					max = (max === '')? 30 : max; //0 is allowed now
					unit = $s.data('unit');

					$s.slider({
						range: "min",
						animate: true,
						min: min,
						max: max,
						slide: function( event, ui ) {
							$( this ).prev('input.slider-dump').val( ui.value + unit )
								.trigger('keyup');//fire customizer update
						}
					});
				});

				//set values of sliders
				$( "input.slider-dump" ).bind('blur', function(){
					var _this = $(this),
						value = parseInt(_this.val(), 10),
						slider = _this.next('div.slider-place'),
						unit = slider.data('unit');

					if( !isNaN(value) && (value + '').length){ //don't work on empty && compare as string
						slider.slider( "option", "value", value );
						_this.val(value + unit);
					}
				}).trigger('blur');
			}
		},
		a13_reset_cookie = function(){
			var button = $('button.a13_reset_cookie'),
				input  = button.next('input');
			if(button.length){
				button.click(function(e){
					e.preventDefault();
					input.val(Math.random().toString(36).slice(2)).trigger('keyup');
				})
			}
		},
		a13_fonts = function(){
			var init = function(){
				var s = $('select.fonts-choose');

				if(s.length){
					//bind font change
					s.change(change);

					//bind sample text update
					$('input.sample-text')
						.on('blur input keyup', updateSampleText )
						.on('dblclick', editSampleText);
					$('span.sample-view').on('dblclick', editSampleText);

					//bind selecting font parameters
					$('div.font-info').on('change', 'input[type="checkbox"]',{}, makeFontWithParams);

					//run to load selected font after page is loaded
					s.change();
				}
			},

			change = function(){
				var _s = $(this),
					parent = _s.parent(),
					first_load = false;

				if(_s.hasClass('first-load')){
					_s.removeClass('first-load');
					first_load = true;
				}

				//if font is classic font don't make request
				if(_s.find('option').filter(':selected').hasClass('classic-font')){
					//set family for sample view
					parent.find('span.sample-view').css('font-family', _s.val());
					//clear font info
					parent.find('div.font-info').find('div.variants, div.subsets').empty();
					//fill hidden input
					makeFontWithParams(_s, true);
					return;
				}

				//google font details request
				$.post(ajaxurl, {
						action : 'a13_font_details', //called in backend
						font : _s.val()    //value of select
					},
					function(r) { //r = response
						//check if font was found
						if(r !== false){
							createHeadLink(r);
							//don't overwrite saved option in first 'change' event
							if(!first_load){
								parent.find('span.sample-view').css('font-family', r.family);
								fillInfo(r, _s);
								makeFontWithParams(_s);
							}
						}
					},
					'json'
				);
			},

			createHeadLink = function(r){
				var apiUrl = [],
					url;

				apiUrl.push('//fonts.googleapis.com/css?family=');
				apiUrl.push(r.family.replace(/ /g, '+')); //font name -> font+name

				if ($.inArray('regular', r.variants) !== -1) {
					apiUrl.push(':');
					apiUrl.push('regular,bold');
				}
				else{
					apiUrl.push(':');
					apiUrl.push(r.variants[0]);
					apiUrl.push(',bold');
				}
				apiUrl.push('&subset=');
				$.each(r.subsets, function(index, val){
					//add comma if more subsets
					if(index > 0){
						apiUrl.push(',');
					}
					apiUrl.push(val);

				});

				url = apiUrl.join('');
				// url: '//fonts.googleapis.com/css?family=Anonymous+Pro:bold&subset=greek'

				$('head').append('<link href="'+url+'" rel="stylesheet" type="text/css" />');
			},

			updateSampleText = function(){
				var inp = $(this);
				inp.parent().find('span.sample-view').html(inp.val());
			},

			editSampleText = function(){
				var elem = $(this);

				if(elem.is('span')){//enable edit
					elem.hide().prev().show().focus();
				}
				else{//disable edit
					elem.hide().next().show();
				}
			},

			fillInfo = function(r, select){
				var info = select.parent().find('div.font-info'),
					v = info.find('div.variants'),
					s = info.find('div.subsets'),
					html = '';

				$.each(r.subsets, function(){
					html += '<label><input type="checkbox" name="subset" value="'+this+'" />'+this+'</label>'+"\n";
				});
				s.empty().append(html);

				html = '';
				$.each(r.variants, function(){
					html += '<label><input type="checkbox" name="variant" value="'+this+'" />'+this+'</label>'+"\n";
				});
				v.empty().append(html);
			},

			makeFontWithParams = function(s, classic_font){
				//if called as event callback
				if(!(s instanceof jQuery)){
					s = $(this).parents('div.input-desc').eq(0).find('select');
				}
				if(typeof classic_font === 'undefined'){
					classic_font = false;
				}

				var name = s.val(),
					parent = s.parent(),
					font_input = parent.find('input.font-request'),
					variants = parent.find('.variants input').filter(':checked'),
					subsets = parent.find('.subsets input').filter(':checked');

				//it is not needed to strip colon and other stuff form classic fonts
				//but missing colon will be used to easily distinguish classic from google
				if(!classic_font){
					//variants
					//colon even if no variant
					name +=':';
					$.each(variants, function(index, val){
						//add comma if more subsets
						if(index !== 0){
							name +=',';
						}
						name += $(val).val();
					});

					//subsets
					$.each(subsets, function(index, val){
						//add comma if more subsets
						if(index === 0){
							name +=':';
						}
						else{
							name +=',';
						}
						name += $(val).val();
					});
				}

				//fill input
				font_input.val(name).trigger('keyup');
			};

			//work it
			init();
		},
		a13_socials = function(){
			//api.instance('my_theme_options[footer_image]').previewer.refresh();
			//input.data('customize-setting-link');

			var sortable_area 	= $('#a13_sortable-socials'),
				textarea 		= sortable_area.prev('textarea'),
				items_JSON      = $.parseJSON( textarea.val() ),
				item_selector   = 'div.service',
				sort_start_position,
				all_items,

				//refreshes all_items variable
				update_all_items = function(){
					all_items = sortable_area.find(item_selector);
				},

				//returns index of item in list
				index_of_item = function(item){
					//check if we have proper element to get index
					if(item.is(item_selector)){
						return all_items.index(item);
					}

					return false;
				},

				//updates JSON string in textarea
				update_textarea = function(){
					textarea.val(JSON.stringify(items_JSON)).trigger('keyup');
				},

				//action on sort start
				items_sort_start = function(event, ui){
					sort_start_position = index_of_item(ui.item);
				},

				//action after drop of sorted item
				items_sort_update = function(event, ui){
					update_all_items(); //for good indexes
					var sort_end_position = index_of_item(ui.item);
					//no change, do nothing
					if(sort_start_position === sort_end_position){ return; }
					//only swap
					else if(Math.abs( sort_start_position - sort_end_position ) === 1){
						//swap in object
						a13_array_swap.call(items_JSON, sort_end_position, sort_start_position );
					}
					//move element
					else{
						a13_array_move.call(items_JSON, sort_start_position, sort_end_position);
					}

					update_textarea();
				},

				update_item = function(event){
					var item = this; //input[type="text"]

					//if not yet a jQuery object then make it so
					if(!(item instanceof jQuery)){
						item = $(item);
					}

					var parent = item.parents(item_selector).eq(0);

					items_JSON[index_of_item(parent)].link = item.val();

					update_textarea();
				},

				check_for_new_services = function(){
					if(all_items.length > items_JSON.length){
						var current_length = items_JSON.length,
							add_length = all_items.length - current_length;

						//add new elements to end of list
						for(var i = 0; i < add_length; i++){
							items_JSON.push({
								'id' : all_items.eq(current_length+i).data('a13_ss_id'),
								'link' : ''
							});
						}
					}
				};

			//bind actions
			sortable_area
				.on('blur', 'input[type="text"]', {}, update_item)

				.sortable({
					axis: 'y',
					distance: 10,
					placeholder: "ui-state-highlight",
					items: item_selector,
					cursor: 'move',
					revert: true,
					forcePlaceholderSize: true,
					start: items_sort_start,
					update: items_sort_update
				});

			//prepare set ot items
			update_all_items();

			//check if there are some new social services - if there are they will be added at end of list
			check_for_new_services();
		},
		a13_font_icons_selector = function(){
			var selector = $('#a13-fa-icons');

			if(selector.length){
				var inputs_selector = 'input.a13-fa-icon, input.a13_fa_icon',
					$body = $(document.body),
					icons = selector.children(),
					current_input,

					show_selector = function(){
						current_input = $(this);
						// Reposition the popup window
						selector.css({
							top: (current_input.offset().top + current_input.outerHeight()) + 'px',
							left: current_input.offset().left + 'px'
						}).show();

						$body.off('click.iconSelector');
						$body.on('click.iconSelector', hide_check);
					},

					hide_selector = function(){
						current_input = null;
						selector.hide();
						$body.off('click.iconSelector');
					},

					hide_check = function(e){
						if(typeof e.target !== 'undefined'){
							var check = $(e.target);
							if(check.is(current_input) || check.is(selector) || check.parents('#a13-fa-icons').length){
	//                                    current_input.focus();
							}
							else{
								hide_selector();
							}
						}
					},

					fill_input = function(e){
						current_input.val($(this).attr('title')).trigger('keyup');
					};

				selector.prependTo('#customize-controls');

				$body
					.on('focus', inputs_selector, {}, show_selector);

				$('span.a13-font-icon').on('click', fill_input);
			}
		};

	//fire on DOM Ready
	$(document).ready(function(){
		a13_slider_control();
		a13_reset_cookie();
		a13_fonts();
		a13_socials();
		a13_font_icons_selector();
		$('#customize-controls')
			.on('change', 'input[type="radio"], select',{}, a13_check_customizer_controls);
	});
} )( jQuery );
} )( wp.customize );