/*global tb_show, tb_remove, alert, plupload, AdminParams, ajaxurl, wp, console */
(function($){
    "use strict";

    //swaping elemnts in array
    var a13ArraySwap = function (x,y) {
            var b = this[x];
            this[x] = this[y];
            this[y] = b;
            return this;
        },

        //moving element in Array from one place to another
        a13ArrayMove = function (old_index, new_index) {
            if (new_index >= this.length) {
                var k = new_index - this.length;
                while ((k--) + 1) {
                    this.push(undefined);
                }
            }
            this.splice(new_index, 0, this.splice(old_index, 1)[0]);
            return this; // for testing purposes
		},

		//delay events
		debounce=function(d,a,b){"use strict";var e;return function c(){var h=this,g=arguments,f=function(){if(!b){d.apply(h,g);}e=null};if(e){clearTimeout(e)}else{if(b){d.apply(h,g)}}e=setTimeout(f,a||100)}};

    //listing all properties of object
    Object.keys = Object.keys || (function () {
        var hasOwnProperty = Object.prototype.hasOwnProperty,
            hasDontEnumBug = !{toString:null}.propertyIsEnumerable("toString"),
            DontEnums = [
                'toString', 'toLocaleString', 'valueOf', 'hasOwnProperty',
                'isPrototypeOf', 'propertyIsEnumerable', 'constructor'
            ],
            DontEnumsLength = DontEnums.length;

        return function (o) {
            if (typeof o != "object" && typeof o != "function" || o === null)
                throw new TypeError("Object.keys called on a non-object");

            var result = [];
            for (var name in o) {
                if (hasOwnProperty.call(o, name))
                    result.push(name);
            }

            if (hasDontEnumBug) {
                for (var i = 0; i < DontEnumsLength; i++) {
                    if (hasOwnProperty.call(o, DontEnums[i]))
                        result.push(DontEnums[i]);
                }
            }

            return result;
        };
    })();

    window.A13_ADMIN = { //A13 = APOLLO 13
        settings : {},

        //run after DOM is loaded
        onReady : function(){
            A13_ADMIN.upload();
            A13_ADMIN.utils.init();
            A13_ADMIN.metaActions.init();
            A13_ADMIN.settingsAction();
			A13_ADMIN.demoDataImporter();
		},

		demoDataImporter : function(){
			var starter = $('#a13_import_demo_data');

			if(starter.length){
				var parent = starter.parent().parent(),
					status = $('#demo_data_import_progress'),
					log_div = $('#demo_data_import_log'),
					log_link = $('#a13_import_demo_data_log_link'),
					startImport = function(e){
						e.preventDefault();

						if (window.confirm($(this).data('confirm'))){
							parent.addClass('importing');
							//clear log
							log_div.html('');

							nextLevel('','');
						}
					},

					nextLevel = function(level, sublevel){
						var request = $.ajax({
							type: "POST",
							url: ajaxurl,
							data:  {
								action : 'a13_import_demo_data', //called in backend
								level : level,
								sublevel : sublevel
							},
							success: function(r) { //r = response
								if(r !== false){
									setupStatus(r);

									if(r.is_it_end === false){//end of importing
//                                        setTimeout(function(){nextLevel(r.level, r.sublevel);}, 900);
										nextLevel(r.level, r.sublevel);
									}
									else{
										parent.removeClass('importing');
									}
								}
							},
							dataType: 'json'
						});

						request.fail(function( jqXHR, textStatus ) {
							alert( "Request failed: " + textStatus );
						});
					},

					setupStatus = function(r){
						var content = r.level_name;
						if(r.sublevel_name.length){
							content += ' - '+r.sublevel_name;
						}

//                        status.append('<p>'+content+'</p>');
						status.html('<p>'+content+'</p>');
						log_div.html(log_div.html()+ r.log);
					},

					switchLogDiv = function(e){
						e.preventDefault();
						log_div.toggle();
					};

				starter.click(startImport);
				log_link.click(switchLogDiv);
			}
		},

        upload : function(){
            //uploading files variable
            var custom_file_frame,
                field_for_uploaded_file,
                $upload_input,
                upload_buttons_selector = 'input.upload-image-button',
                clear_buttons_selector = 'input.clear-image-button',

                //on start of selecting/uploading file
                a13UploadFile = function(event){
                    event.preventDefault();

                    var upload_button = $(this);

                    //makes 'Upload Files' tab default one
                    wp.media.controller.Library.prototype.defaults.contentUserSetting=false;

                    //find text input to write in
                    $upload_input = $('input[type=text]', $(this).parent());

                    //remember in which input we want to write
                    field_for_uploaded_file = $upload_input.attr('name');

                    //If the frame already exists, reopen it
                    if (typeof(custom_file_frame)!=="undefined") {
                        custom_file_frame.close();
                    }

                    //Create WP media frame.
                    custom_file_frame = wp.media.frames.customHeader = wp.media({
                        //Title of media manager frame
                        title: "WP Media Uploader",
//                        frame: 'post',
                        frame: 'select',
                        state: 'library',
//                        editing:    true,
                        multiple:   false,
                        library: {
                            type: upload_button.data('media-type') || 'image' //others: audio, video, document(?)
                        },
                        button: {
                            text: upload_button.data('media-button-name') || "Insert image"
                        },
                        states : [
                            new wp.media.controller.Library({
                                filterable : 'all'
                            })
                        ]
                    });

                    //callback for selected image
                    custom_file_frame.on('insert select change', a13SelectFile);

                    //Open modal
                    custom_file_frame.open();
                },

				a13ClearFileInput = function(event){
                    event.preventDefault();

                    var clear_button 		= $(this),
						main_input 			= $('input[type=text]',clear_button.parent()),
						attachment 			= main_input.data('attachment'),
						attachemnt_input 	= typeof attachment === 'undefined' ? false : $('#a13_'+attachment);

					main_input.val('');
					if( attachemnt_input !== false ){
						attachemnt_input.val('');
					}
                },

                //after of selecting/uploading file
                a13SelectFile = function(){
                    var whole_state     = custom_file_frame.state(),
                        attachment      = whole_state.get('selection').first().toJSON();

                    //do something with attachment variable, for example attachment.filename
                    //Object:
                    //attachment.alt - image alt
                    //attachment.author - author id
                    //attachment.caption
                    //attachment.dateFormatted - date of image uploaded
                    //attachment.description
                    //attachment.editLink - edit link of media
                    //attachment.filename
                    //attachment.height
                    //attachment.icon - don't know WTF?))
                    //attachment.id - id of attachment
                    //attachment.link - public link of attachment, for example ""http://site.com/?attachment_id=115""
                    //attachment.menuOrder
                    //attachment.mime - mime type, for example image/jpeg"
                    //attachment.name - name of attachment file, for example "my-image"
                    //attachment.status - usual is "inherit"
                    //attachment.subtype - "jpeg" if is "jpg"
                    //attachment.title
                    //attachment.type - "image"
                    //attachment.uploadedTo
                    //attachment.url - http url of image, for example "http://site.com/wp-content/uploads/2012/12/my-image.jpg"
                    //attachment.width


                    //if there is some field waiting for input
                    if (field_for_uploaded_file !== undefined) {

                        //if selected media is image
                        if(attachment.type === 'image'){
                            var file_url    = attachment.url,
								attachment_field = $upload_input.data('attachment'),
                                temp_size;

                            //insert its src to waiting field
                            $upload_input.val(file_url);

                            //for this field save also attachment id
							if(typeof attachment_field !== 'undefined'){
                            	$('#a13_'+attachment_field).val(attachment.id);
							}
                        }
                        //search for link and its href
                        else{
                            //insert its src to waiting field
                            $upload_input.val(attachment.url);
                        }

                        //clean waiting variable
                        field_for_uploaded_file = undefined;
                    }
                };

            $(document).on('click', upload_buttons_selector, a13UploadFile);
            $(document).on('click', clear_buttons_selector, a13ClearFileInput);
        },

        utils: {
            init : function(){
                var AU = A13_ADMIN.utils;

                AU.contactDropArea();
                AU.colorPicker();
                AU.sliderOption();
                AU.adminMenu();
                AU.customSidebars();
                AU.fontIconsSelector();
                AU.selectExport();
            },

            contactDropArea: function(){
                var da = $('#a13_contact_drop_area');
                if(da.length){
                    var ll          	= $('#a13_contact_ll'),
                        zoom        	= $('#a13_contact_zoom'),
                        type        	= $('#a13_contact_map_type'),
                        ll_reg_ex     	= /ll=([0-9\.,\-]+)&?/ig,
                        zoom_reg_ex   	= /&z=([0-9]+)&?/ig,
                        type_reg_ex   	= /&t=([a-z]+)&?/ig,
                        new_map_reg_ex 	= /\/@(\-?[0-9]+\.[0-9]+),(\-?[0-9]+\.[0-9]+),([0-9]+[a-z])+\/?/ig,
                        matches,

                        processField = function(){
                            var val     = da.val();

                            //if any value then please proceed
                            if(val.length){

                                //new map?
                                matches = new_map_reg_ex.exec(val);
                                if(matches !== null && matches.length === 4){
                                    ll.val(matches[1]+','+matches[2]);
                                    real_type = 'SATELLITE';

                                    var zoomLvl  = /([0-9]+)z/ig.exec(matches[3]);
                                    if(zoomLvl !== null && zoomLvl.length === 2){
                                        zoom.val(zoomLvl[1]).blur();
                                        real_type = 'HYBRID';
                                    }
                                    type.val(real_type);
                                }

                                //old map
                                else{
                                    //Latitude, Longitude
                                    matches = ll_reg_ex.exec(val);
                                    if(matches !== null && matches.length === 2){
                                        ll.val(matches[1]);
                                    }

                                    //Zoom
                                    matches = zoom_reg_ex.exec(val);
                                    if(matches !== null && matches.length === 2){
                                        zoom.val(matches[1]).blur();
                                    }

                                    //Map type
                                    matches = type_reg_ex.exec(val);
                                    if(matches !== null && matches.length === 2){
                                        var real_type;
                                        if(matches[1] === 'k'){
                                            real_type = 'SATELLITE';
                                        }
                                        else if(matches[1] === 'm'){
                                            real_type = 'ROADMAP';
                                        }
                                        else if(matches[1] === 'h'){
                                            real_type = 'HYBRID';
                                        }
                                        else if(matches[1] === 'p'){
                                            real_type = 'TERRAIN';
                                        }

                                        type.val(real_type);
                                    }
                                }
                            }
                        };

                    //bind drop area
                    da.on('input blur', processField);

                }
            },

            /*** color picker ***/
            colorPicker : function(){
                var input_color = $('input.with-color');
                if(input_color.length){
                    input_color.wheelColorPicker({
                        format: 'rgba',
                        preview: false, /* buggy */
                        validate: true,
                        autoConvert: true,
                        preserveWheel: true
                    });

                    //transparent value
                    $('body').on('click', 'button.transparent-value', function(){
                        $(this).prev('input.with-color').attr('style','').val('transparent');
                        return false;
                    });
                }
            },

            /**** SLIDER FOR SETTING NUMBER OPTIONS ****/
            sliderOption : function(){
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
                                $( this ).prev('input.slider-dump').val( ui.value + unit );
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

            adminMenu : function(){
                var root = $('#menu-to-edit'),
                    enabled_class = 'mega-menu-enabled';

                if(root.length){
                    var switchMegaMenuOptions = function(){
                        var menu_items = root.children(),
                            number = menu_items.length,
                            i = 0,
                            current, check,
                            mm_enabled = false,
                            classChange = function(current){
                                if(mm_enabled){
                                    current.addClass(enabled_class);
                                }
                                else{
                                    current.removeClass(enabled_class);
                                }
                            };

                        for(;i<number;i++){
                            current = menu_items.eq(i);

                            //level 0
                            if(current.is('.menu-item-depth-0')){
                                check = current.find('input.enable-mega-menu');
                                mm_enabled = check.is(':checked');//true || false
                                classChange(current);
                                continue;
                            }

                            //level 1
                            if(current.is('.menu-item-depth-1')){
                                classChange(current);
                            }
                        }
                    };

                    //bind events
                    root.on( 'change', 'input.enable-mega-menu', switchMegaMenuOptions);
                    root.on( 'sortstop', function(){ setTimeout( switchMegaMenuOptions, 100);} ); //delay so all DOM can be updated
                    root.on( 'click','p.field-move a', switchMegaMenuOptions ); //for manual moving
                }
            },

            customSidebars : function(){
                var sidebars = $('#a13-custom-sidebars-list');
                if(sidebars.length){
                    var remove_links = sidebars.find('a'),
                        removeSidebar = function(e){
                            //no going to hash
                            e.preventDefault();

                            var link = $(this);

                            $.post(ajaxurl, {
                                    action : 'a13_remove_custom_sidebar', //called in backend
                                    sidebar : link.attr('id')    //sidebar to remove
                                },
                                function(r) { //r = response
                                    //check if sidebar was deleted
                                    if(r === true){
                                        link.parent().fadeOut(300, function(){ $(this).remove(); })
                                    }
                                },
                                'json'
                            );
                        };
                    //bind event
                    remove_links.click(removeSidebar);
                }
            },

            fontIconsSelector :  function(){
                var selector = $('#a13-fa-icons');

                if(selector.length){
                    var inputs_selector = 'input.a13-fa-icon, input.a13_fa_icon',
                        $body = $(document.body),
                        icons = selector.children(),
                        current_input,

                        showSelector = function(){
                            current_input = $(this);
                            // Reposition the popup window
                            selector.css({
                                top: (current_input.offset().top /*+ current_input.outerHeight()*/) + 'px',
                                left: current_input.offset().left + 'px'
                            }).show();

                            $body.off('click.iconSelector');
                            $body.on('click.iconSelector', hideCheck);
                        },

                        hideSelector = function(){
                            current_input = null;
                            selector.hide();
                            $body.off('click.iconSelector');
                        },

                        hideCheck = function(e){
                            if(typeof e.target !== 'undefined'){
                                var check = $(e.target);
                                if(check.is(current_input) || check.is(selector) || check.parents('#a13-fa-icons').length){
//                                    current_input.focus();
                                }
                                else{
                                    hideSelector();
                                }
                            }
                        },

                        fillInput = function(e){
                            current_input.val($(this).attr('title'));
                        };

                    selector.prependTo('#wpcontent');

                    $body
                        .on('focus', inputs_selector, {}, showSelector);

                    $('span.a13-font-icon').on('click', fillInput);
                }
            },

            //auto select text in export textarea
            selectExport : function(){
                var ex = $('#a13_export_options_field');

                if(ex.length){
                    ex.focus(function(){ this.select(); });
                }
            }
        },

        metaActions : {
            init : function(){
                //if there are meta fields check for special elements
                var apollo_meta = $('div.apollo13-metas'),
                    AM = A13_ADMIN.metaActions;

                if (apollo_meta.length) {
                    //bind multi upload and some other things
                    AM.muManage(apollo_meta);

                    //bind switcher(hides unused options like image vs video)
//                    apollo_meta.find('div.switch').children('div.input-parent').find('input[type="radio"], select').change(AM.changeSwitch);
                    apollo_meta
                        .on('change','div.switch > div.input-parent input[type="radio"], div.switch > div.input-parent select',{}, AM.changeSwitch);
                }
            },

			muManage : function(apollo_meta){
                var prototype_selector = 'div.fieldset.prototype',
					_prototype = apollo_meta.find(prototype_selector);

                //there is prototype so we have work to do
                if(_prototype.length){
                    var textarea                = $('#a13_images_n_videos');
						//prevent changed "written"(changed) value of textarea if user hits f5(happens in firefox for sure)
						textarea.val(textarea.text());
                    var items_JSON              = $.parseJSON( textarea.val() ),
						our_apollo_meta			= textarea.parents('.apollo13-metas').eq(0),
                        item_selector           = 'li.mu-item',
						prototype_pre_id		= 'mu-prototype-',
                        mu_button               = $('#a13-multi-upload'),
						remove_button 			= $('#a13-multi-remove'),
						sort_area 				= $('#mu-media'),
						notice_area				= $('#a13-mu-notice'),
						single_item_html		= $('#mu-single-item').children(),
                        defaults				= [],//will hold default values for different item types
						ideal_column_width		= 150,
						edited_item,			//memory what is currently edited
                        custom_file_frame,      //for multi upload window
						columns,				//number of columns
                        sort_start_position,
                        all_items,

                        //refreshes all_items variable
                        updateAllItems = function(){
                            all_items = sort_area.find(item_selector);
                        },

						//prepares list of default values for each item type
						collectDefaults = function(){
							_prototype.each(function(){
								var _this = $(this),
									id = _this.attr('id').substring(prototype_pre_id.length);

								defaults[id] = collectValues(_this);
							});
						},

						//collects values from edit fieldset
                        collectValues = function(fields_part){
                            var values = {},
                                inputs = fields_part.find('input,textarea,select').not(':button'),
                                size = inputs.length,
                                temp, is_radio, i;

							for(i = 0; temp = inputs.eq(i), i < size; i++){
								is_radio = temp.is('[type="radio"]');
								if( !is_radio || ( is_radio && temp.is(':checked') ) ){
									values[temp.attr('name').slice(4)] = temp.val(); //slice(4) to avoid a13_ prefix
								}
							}

                            return values;
                        },

                        //returns index of item in list
                        indexOfItem = function(item){
                            //check if we have proper element to get index
                            if(!item.is(item_selector)){
								//what we are doing here?
								return -1;
                            }

                            return all_items.index(item);
                        },

                        //updates JSON string in textarea
                        updateTextarea = function(){
                            textarea.val(JSON.stringify(items_JSON));
                        },

						//check if such attachment exist in gallery already
						uniqueAttachment = function(id){
							for(var i = 0, end = items_JSON.length; i < end; i++){
								if(id === items_JSON[i].id){
									return false;
								}
							}
							return true;
						},

                        //fills inputs of currently edited item with data from JSON
                        fillItemDetails = function(index, fields_part){
                            var fields  = items_JSON[index],
                                keys    = Object.keys(fields),
                                size    = keys.length,
                                i, field, field_id;

                            //fill inputs
                            for(i = 0; field_id = keys[i],  i < size; i++){
								field = fields_part.find('[name="a13_'+field_id+'"]');
								//if such field exist(it doesn't have to!)
								if(field.length){
									//radio input? special work to do!
									if(field.is('[type="radio"]')){
										field.filter('[value="'+fields[field_id]+'"]')
											.prop('checked', true).change();
									}
									//classic...
									else{
                                		field.val(fields[field_id]);
									}
								}
                            }
                        },

						//show fieldset where details of item can be edited
						showFieldset = function(type){
							var lightbox 	= $('<div class="a13_mu_lighhtbox"></div>'),
								pop 		= $('<div class="a13_mu_white_content"></div>'),
								shadow 		= $('<div class="a13_mu_black_overlay"></div>'),
								fieldset 	= $('#'+prototype_pre_id+type).show(),
								input_color = fieldset.find('input.with-color'),
								controls 	= $(
									'<div class="controls">'+
										'<span class="title">Editing item of '+type+' type</span>'+
										'<input class="a13_mu_save button button-large button-primary" value="Save" type="button">'+
										'<input class="a13_mu_cancel button button-large" value="Cancel" type="button">'+
									'</div>'
								);


							//setup lightbox
							pop				.append(controls, fieldset);
							lightbox		.append(pop, shadow);
							our_apollo_meta	.append(lightbox);

							//color picker
							if(input_color.length){
								input_color.wheelColorPicker({
									format: 'css',
									preview: false, /* buggy */
									validate: true,
									autoConvert: true,
									preserveWheel: true
								});
							}

							//display
							shadow.fadeIn(100);
							pop.slideDown(300);

							return fieldset;
						},

						//hides fieldset after edit/add item
						closeFieldset = function(event){
							var lightbox 	= $(this).parents('.a13_mu_lighhtbox').eq(0).hide(),
								type 		= items_JSON[edited_item].type,
								fieldset 	= $('#'+prototype_pre_id+type).hide();

							//cleanup
							hideColorPicker(fieldset);
							our_apollo_meta.append(fieldset);
							lightbox.remove();
							edited_item = '';
						},

                        //hides open color pickers
                        hideColorPicker = function(fields_part){
							var inputs = fields_part.find('input.with-color');
							if(inputs.length){
                            	inputs.wheelColorPicker('hide');
							}
                        },

                        //action on sort start
                        itemsSortStart = function(event, ui){
                            sort_start_position = indexOfItem(ui.item);
							ui.placeholder.html('<div class="attachment-preview"></div>');
                        },

                        //action after drop of sorted item
                        itemsSortUpdate = function(event, ui){
                            updateAllItems(); //for good indexes
                            var sort_end_position = indexOfItem(ui.item);

                            //no change, do nothing
                            if(sort_start_position === sort_end_position){ return; }

                            //only swap
                            else if(Math.abs( sort_start_position - sort_end_position ) === 1){
                                //swap in object
                                a13ArraySwap.call(items_JSON, indexOfItem(ui.item), sort_start_position );
                            }

                            //move element
                            else{
                                a13ArrayMove.call(items_JSON, sort_start_position, indexOfItem(ui.item));
                            }

                            updateTextarea();
                        },

                        //on start of selecting/uploading images
                        muUploadFile = function(event){
                            event.preventDefault();

							var button = $(this);

                            //makes 'Upload Files' tab default one
                            //wp.media.controller.Library.prototype.defaults.contentUserSetting=false;

                            //If the frame already exists, reopen it
                            if (typeof(custom_file_frame)!=="undefined") {
                                custom_file_frame.close();
                            }

                            //Create WP media frame.
                            custom_file_frame = wp.media.frames.customHeader = wp.media({
                                //Title of media manager frame
                                title: "WP Media Uploader",
                                frame: 'select',
                                state: 'library',
                                multiple: true,
                                library: {
									//doesn't work with filterable enabled
									//type: button.data('media-type') || 'image' //others: audio, video, document(?)
                                },
                                button: {
                                    text: "Insert item(s)"
                                },
                                states : [
                                    new wp.media.controller.Library({
                                        filterable : 'all',
                                        multiple : true
                                    })
                                ]
                            });

                            //callback for selected items
                            custom_file_frame.on('select', muSelectFile);

                            //Open modal
                            custom_file_frame.open();
                        },

                        //after of selecting/uploading file
                        muSelectFile = function(){
                            var whole_state     = custom_file_frame.state(),
                                selection       = whole_state.get('selection').models,
                                items_num       = selection.length,
								is_prepend		= $('#mu-prepend').is(':checked'),
                                to_send_array 	= [],
                                new_index, elem, current_item,
                                attachment, item_type, id, temp;

							//are there any items?
                            if (items_num) {
                                for(elem = 0; elem < items_num; elem++){
                                    attachment      = selection[elem].toJSON();
									id  			= attachment.id;
									if(!uniqueAttachment(id)){
										continue;
									}
									item_type 		= attachment.type;

									//add items to elements array
									temp = $.extend({},defaults[item_type]);
									if(is_prepend){
										items_JSON.unshift(temp);
										new_index   = 0;
									}
									else{
										new_index   = items_JSON.push(temp)-1;
									}
									current_item    = items_JSON[new_index];

									//collect this item in JSON
									to_send_array.push(attachment);

									//update of item JSON
									current_item.type	= item_type;
									current_item.id     = id;
                                }

								//proceed only if we have new(unique) items
								if(to_send_array.length){
									$.ajax({
										type: "POST",
										url: ajaxurl,
										data: {
											action : 'a13_prepare_gallery_items_html', //called in backend
											items : to_send_array
										},
										success: function(new_html) {
											//insert HTML
											if(is_prepend){
												sort_area.prepend(new_html);
											}
											else{
												sort_area.append(new_html);
											}

											updateAllItems();

											temp = 'Added '+to_send_array.length+' new item(s)';
											if(items_num-to_send_array.length){
												temp += '<br />'+(items_num-to_send_array.length)+' item(s) was already in your gallery';
											}
											showNotice(temp)
										},
										error: function(jqXHR, textStatus, errorThrown ){
											showNotice('Error: '+textStatus+' \n '+errorThrown);
										},
										dataType: 'html'
									});

									updateTextarea();
								}
								else{
									showNotice('All elements that you choose exist already in your gallery.');
								}
                            }
                        },

						editAction = function(){
							editItem($(this));
						},

                        addItem = function(event){
							var is_prepend		= $('#mu-prepend').is(':checked'),
								type			= 'videolink',
								temp 			= $.extend({},defaults[type]),
								placeholder		= single_item_html.clone(),
								new_index;

							//fill defaults
							temp.id = 'external';
							temp.type = type;

							if(is_prepend){
								items_JSON.unshift(temp);
								new_index   = 0;
								sort_area.prepend(placeholder);
							}
							else{
								new_index   = items_JSON.push(temp)-1;
								sort_area.append(placeholder);
							}

                            //add to all_items list
                            updateAllItems();

                            updateTextarea();

							editItem(all_items.eq(new_index));
                        },

						editItem = function(item){
							//if clicked edit button
							if(!item.is(item_selector)){
								//item is link inside
								item = item.parents(item_selector)
							}

							var index           = indexOfItem(item),
								type			= items_JSON[index].type;

							edited_item =  index; //memory

							//show form in new place
							fillItemDetails(index, showFieldset(type));
						},

						removeItem = function(item, skip_update){
							//used for mass delete
							if(typeof skip_update === 'undefined'){
								skip_update = false;
							}

							var index = indexOfItem(item),
								type = items_JSON[index].type;

							if(index === -1){//this was deleted
								return;
							}

							//update all_items list
							all_items = all_items.not(all_items.eq(index));

							//update JSON
							items_JSON.splice( index ,1 );

							if(!skip_update){
								updateTextarea();
							}
						},

                        removeAction = function(){
                            var item = $(this).parents(item_selector),
                                type = items_JSON[indexOfItem(item)].type;

                            removeItem(item);

                            //remove HTML
                            item.fadeOut(250,function(){
                                item.remove();
                            });

							showNotice('Removed 1 item of type '+ type );
                        },

                        updateItem = function(event){
                            var $item 		= all_items.eq(edited_item),
								item 		= items_JSON[edited_item],
								type 		= item.type,
								fieldset 	= $('#'+prototype_pre_id+type),
								values 		= collectValues(fieldset);

                            item = $.extend(item, values);

							//link media type
							if(item.id === 'external'){
								//ask for new html
								$.ajax({
									type: "POST",
									url: ajaxurl,
									data: {
										action : 'a13_prepare_gallery_single_item_html', //called in backend
										item : item
									},
									success: function(new_html) {
										//insert HTML
										$item.replaceWith(new_html);
										updateAllItems(); //need to grab new DOM element instead of removed one
									},
									error: function(jqXHR, textStatus, errorThrown ){
										showNotice('Error: '+textStatus+' \n '+errorThrown);
									},
									dataType: 'html'
								});
							}

                            updateTextarea();
							closeFieldset.call(this);
							showNotice('Updated 1 item of type '+ type );
						},

						showNotice = function(text){
							hideNotice();
							$('<p>'+text+'</p>').appendTo(notice_area).slideUp(0).slideDown();
						},

						hideNotice = function(){
							var notes = notice_area.children();
							if(notes.length){
								notes.slideUp().promise().done(function(){notes.remove()});
							}
						},

						selectionHandler = function(event){
							event.preventDefault();
							var method = 'single',
								selected = all_items.filter('.selected'),
								$this = $(this),
								last, clicked;

							if ( event.shiftKey ) {
								method = 'between';
							} else if ( event.ctrlKey || event.metaKey ) {
								method = 'toggle';
							}

							//check if there is anything selected
							if(method === 'single'){
								all_items.not($this).removeClass('selected');
								$this.toggleClass('selected');
							}
							else{
								if(selected.length && method === 'between'){
									last = indexOfItem(selected.eq(selected.length - 1));
									clicked = indexOfItem($this);

									if(clicked !== last){
										if(clicked < last){
											$this.nextUntil(last).andSelf().addClass('selected');
										}
										else{
											selected.eq(selected.length - 1).nextUntil($this).add($this).addClass('selected');
										}
									}
								}
								else{
									$this.toggleClass('selected');
								}
							}

							//toggle delete button
							if(all_items.filter('.selected').length){
								remove_button.prop({
									disabled: false
								})
							}
							else{
								remove_button.prop({
									disabled: true
								});
							}
						},

						removeSelected = function(){
							var selected = all_items.filter('.selected'),
								number = selected.length;

							if(number){
								selected.each(function(){
									var t = $(this);
									removeItem(t, true);
								});

								//remove HTML
								selected.fadeOut(250,function(){
									selected.remove();
								});

								updateTextarea();

								showNotice('Removed '+number+' items');
							}

							//disable button
							remove_button.prop({
								disabled: true
							});
						},

						workingStatusOn = function(){

						},

						workingStatusOff = function(){

						},

						setColumns = function() {
							var prev = columns,
								width = sort_area.width();

							if ( width ) {
								columns = Math.min( Math.round( width / ideal_column_width ), 12 ) || 1;

								if ( ! prev || prev !== columns ) {
									sort_area.attr( 'data-columns', columns );
								}
							}
						};


                    collectDefaults();

					updateAllItems();

					setColumns();

                    //bind actions
					our_apollo_meta
                        .on('click', 'span.add-link-media', {}, addItem)
                        .on('click', 'input.a13_mu_save', {}, updateItem)
                        .on('click', 'input.a13_mu_cancel', {}, closeFieldset);

                    //actions on single gallery item
					sort_area
                        .on('click', 'span.mu-item-remove', {}, removeAction)
                        .on('click', 'span.mu-item-edit', {}, editAction)
						.on('click keydown', item_selector, selectionHandler)
						.sortable({
							handle: 'div.mu-item-drag',
							items: item_selector,
							placeholder : 'sort-placeholder attachment',
							start: itemsSortStart,
							update: itemsSortUpdate
                    	})
						.disableSelection();

					//remove many items
					remove_button.on('click', removeSelected);

					$(window).resize(debounce(setColumns, 250));

					//enable multi upload
                    mu_button.click(muUploadFile);

					//hide notice on click
					notice_area.click(hideNotice);

                }
            },

            changeSwitch : function(){
                var input   = $(this),
                    parent  = input.parents('div.switch').eq(0), /* first switch parent */
                    to_show = input.val();

                parent
                    .children('div.switch-group').hide()
                    .filter('[data-switch="'+to_show+'"]').show();
            }
        },

        settingsAction : function(){
            //sliding options fields sets
            var hideFieldset = function(){
                var bar = $(this),
                    block = bar.parent(),
                    input = bar.find('input[type="hidden"]');

                if(block.hasClass('closed')){
                    block.removeClass('closed');
                    bar.next('div.inside').slideDown(300);
                    input.val('1');
                }
                else{
                    input.val('0');
                    bar.next('div.inside').slideUp(300, function(){
                        block.addClass('closed');
                    });
                }
            };

            $('div.fieldset-name').click(hideFieldset);

            //bind switcher(hides unused options like image vs text in logo options)
            $('#apollo13-settings').find('div.switch').children('div.input-parent').find('input[type="radio"], select').change(A13_ADMIN.metaActions.changeSwitch);

            //save options button - back to current fieldset after reload
            $('input[name="theme_updated"]').click(function(){
                var I = $(this),
                    fieldset = I.parents('div.postbox').eq(0).attr('id'),
                    form = I.parents('form').eq(0);

                form.attr('action', '#'+fieldset); //insert anchor

            });
        }
    };

    var A13_ADMIN = window.A13_ADMIN;

    //start ADMIN
    $(document).ready(A13_ADMIN.onReady);

})(jQuery);