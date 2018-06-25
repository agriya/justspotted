var default_zoom_level = 10;
var geocoder;
var geocoder1;
var map;
var bounds;
var marker;
var marker1;
var markerimage;
var infowindow;
var locations;
var latlng;
var searchTag;
var ws_wsid;
var ws_lat;
var ws_lon;
var ws_width;
var ws_industry_type;
var ws_map_icon_type;
var ws_transit_score;
var ws_commute;
var ws_map_modules;
var styles = [];
var markerClusterer = null;
var map = null;
var markers = [];
var common_options = {
        map_frame_id: 'mapframe',
        map_window_id: 'mapwindow',
		area: 'js-street_id',
        state: 'StateName',
        city: 'CityName',
        country: 'js-country_id',
        lat_id: 'latitude',
        lng_id: 'longitude',
        postal_code: 'PropertyPostalCode',
        ne_lat: 'ne_latitude',
        ne_lng: 'ne_longitude',
        sw_lat: 'sw_latitude',
        sw_lng: 'sw_longitude',
        button: 'js-sub',
        error: 'address-info',
		mapblock: 'mapblock',
        lat: '37.7749295',
        lng: '-122.4194155',
        map_zoom: 13
    }

function loadGeo() {
	geocoder1 = new google.maps.Geocoder();
    var options = common_options;
    $('#PropertyAddressSearch').autogeocomplete(options);
	loadSideMap();
}

function __l(str, lang_code) {
    //TODO: lang_code = lang_code || 'en_us';
    return(cfg && cfg.lang && cfg.lang[str]) ? cfg.lang[str]: str;
}
function __cfg(c) {
    return(cfg && cfg.cfg && cfg.cfg[c]) ? cfg.cfg[c]: false;
}
function split( val ) {
			return val.split( /,\s*/ );
		}
function extractLast( term ) {
			return split( term ).pop();
		}
(function($) {
		$.fn.ftinyMce = function() {
			$(this).tinymce( {
				// Location of TinyMCE script
				script_url: __cfg('path_relative') + 'js/libs/tiny_mce/tiny_mce.js',
				mode: "textareas",
			   // General options
				theme: "advanced",
				plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
			   // Theme options
			   //newdocument,|,
				theme_advanced_buttons1: "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect, |, cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,",
				theme_advanced_buttons2: "undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolortablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl,|,fullscreen,|,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,visualchars,nonbreaking,pagebreak",
				theme_advanced_buttons3: "",
				theme_advanced_buttons4: "",

				theme_advanced_toolbar_location: "top",
				theme_advanced_toolbar_align: "left",
				theme_advanced_statusbar_location: "bottom",
				theme_advanced_resizing: true,
			  // Example content CSS (should be your site CSS)
				//content_css: "css/content.css",
			   // Drop lists for link/image/media/template dialogs
				template_external_list_url: "lists/template_list.js",
				external_link_list_url: "lists/link_list.js",
				external_image_list_url: "lists/image_list.js",
				media_external_list_url: "lists/media_list.js",
				height: "250px",
				width: "80%",
				relative_urls : false,
				remove_script_host : false,
				setup: function(ed) {
					ed.onChange.add(function(ed) {
						tinyMCE.triggerSave();
					});
				}
			});
		};
		$.fCommentaddform = function(selector) {
		if( selector != ''){
		$('body').delegate(selector, 'submit', function(event) {
				var $this = $(this);
				$this.block();
				$this.ajaxSubmit( {
					beforeSubmit: function(formData, jqForm, options) {},
					success: function(responseText, statusText) {
						if (responseText.indexOf($this.metadata().container) != '-1') {
							$('.' + $this.metadata().container).html(responseText);
						} else {
							 $('.' + $this.metadata().responsecontainer).prepend(responseText);
							$('.' + $this.metadata().container + ' div.input').removeClass('error');
							$('.error-message', $('.' + $this.metadata().container)).remove();
						}
						$this.unblock();
						afterAjaxLoad();
					},
					clearForm: true
				});
				return false;
			});
		}
		};

	$.floadGeoNew = function(selector) {
		if($(selector, 'body').is(selector)){
			$this = $(selector);
			var script = document.createElement('script');
			var google_map_key = 'http://maps.googleapis.com/maps/api/js?key='+__cfg("gmap_app_id")+'&sensor=false&callback=loadMap';
			//var google_map_key = 'http://maps.google.com/maps/api/js?sensor=false&callback=loadGeo&language='+__cfg('user_language');
			script.setAttribute('src', google_map_key);
			script.setAttribute('type', 'text/javascript');
			document.documentElement.firstChild.appendChild(script);
		}
	};

	$.floadGeo = function(selector) {
		if($(selector, 'body').is(selector)){
			var $country = 0;
			$this = $(selector);
			var script = document.createElement('script');
			var google_map_key = 'http://maps.googleapis.com/maps/api/js?key='+__cfg("gmap_app_id")+'&sensor=false&callback=loadGeo';
			//var google_map_key = 'http://maps.google.com/maps/api/js?sensor=false&callback=loadGeo&language='+__cfg('user_language');
			script.setAttribute('src', google_map_key);
			script.setAttribute('type', 'text/javascript');
			document.documentElement.firstChild.appendChild(script);

		}
	};

    $.froundcorner = function(selector) {
        if ($.browser.msie || $.browser.opera) {
			if($(selector, 'body').is(selector)){
				$this = $(selector);
                radius = /.*round-(\d+).*/i.exec($this.attr('class'));
                $this.corner(radius[1] + 'px');
			}
        }
    };
    $.fn.flashMsg = function() {
        $this = $(this);
        $alert = $this.parents('.js-flash-message');
        var alerttimer = window.setTimeout(function() {
            $alert.trigger('click');
        }, 3000);
        $alert.click(function() {
            window.clearTimeout(alerttimer);
            $alert.animate( {
                height: '0'
            }, 200);
            $alert.children().animate( {
                height: '0'
            }, 200).css('padding', '0px').css('border', '0px');
			$this.animate( {
                height: '0'
            }, 200).css('padding', '0px').css('border', '0px').css('display', 'none');
        });
    };
	$.query = function(s) {
		var r = {};
		if (s) {
			var q = s.substring(s.indexOf('?') + 1);
			// remove everything up to the ?
			q = q.replace(/\&$/, '');
			// remove the trailing &
			$.each(q.split('&'), function() {
				var splitted = this.split('=');
				var key = splitted[0];
				var val = splitted[1];
				// convert numbers
				if (/^[0-9.]+$/.test(val))
					val = parseFloat(val);
				// convert booleans
				if (val == 'true')
					val = true;
				if (val == 'false')
					val = false;
				// ignore empty values
				if (typeof val == 'number' || typeof val == 'boolean' || val.length > 0)
					r[key] = val;
			});
		}
		return r;
	};
    $.fn.fautocomplete = function() {
		$ttis = $(this);
		$ttis.each(function (e) {
			selector_id = $(this).attr('id');
			var $this = $('#'+selector_id);
			var autocompleteUrl = $this.metadata().url;
			var targetField = $this.metadata().targetField;
			var targetId = $this.metadata().id;
			var placeId = $this.attr('id');
			$this.autocomplete({
				source:autocompleteUrl,
				appendTo: $this.parents('div.mapblock-info').filter(':first').find('.autocompleteblock'),
				search: function() {
					// custom minLength
					var term = extractLast( this.value );
					if ( term.length < 2 ) {
						return false;
					}
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					if($('#'+targetId).val()){
						$('#' + targetId).val(ui.item['id']);
					}else{
						var targetField1 = targetField.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
						$('#'+placeId).after(targetField1);
						$('#' + targetId).val(ui.item['id']);
					}
				}
			});
		});
	};
    $.fn.fmultiautocomplete = function() {
		$ttis = $(this);
		$ttis.each(function (e) {
			selector_id = $(this).attr('id');
			var $this = $('#'+selector_id);
			var autocompleteUrl = $this.metadata().url;
			var targetField = $this.metadata().targetField;
			var targetId = $this.metadata().id;
			var placeId = $this.attr('id');
			var enter_value = '';
			$this.autocomplete({
				source:autocompleteUrl,
				appendTo: $this.parents('div.mapblock-info').filter(':first').find('.autocompleteblock'),
				search: function() {
					// custom minLength
					enter_value = extractLast( this.value );
					var term = extractLast( this.value );
					if ( term.length < 2 ) {
						return false;
					}
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					if($('#'+targetId).val()){
						$('#' + targetId).val(ui.item['id']);
					}else{
						var targetField1 = targetField.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
						$('#'+placeId).after(targetField1);
						$('#' + targetId).val(ui.item['id']);
					}
				}
			}).data( "autocomplete" )._renderMenu = function( ul, items ) {
				var model = '';
				$.each( items, function( index, item ) {
					if(model != item.models){
						model = item.models;
						$( "<li></li>" )
						.data( "item.autocomplete", item )
						.addClass("js-models iteams-title")
						.addClass('js-' + model)
						.append( "<a class='iteam-title js-"+ model +"'>" +  item.models + "s</a>" )
						.appendTo( ul );
					}
					if(model ==  'Place') {
						if(item.value == 'New Place') {
							return $( "<li></li>" )
							.data( "item.autocomplete", '')
							.addClass("js-models")
							.addClass("add-new-place")
							.append('<a class="js-add-new-place {\'word\':\'' + enter_value.replace(/\s/g,'_') + '\'}">Add new place "' + enter_value + '"</a>')
							.appendTo( ul );
						} else {
							return $( "<li></li>" )
							.data( "item.autocomplete", item )
							.append( "<a>" +  item.value + "<span class='address-info'>" + item.address  +  "</span>" + "</a>" )
							.appendTo( ul );
						}
					} else {
						return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" +  item.value + "</a>" )
						.appendTo( ul );
					}		  
				});	 
			};
		});
	};
    $.fn.fcolorbox = function() {
		$(this).colorbox( {
			opacity: 0.30,
			width: 700
		});
    };
	$.fn.captchaPlay = function() {
		$(this).flash(null, {
			version: 8
		}, function(htmlOptions) {
			var $this = $(this);
			var href = $this.get(0).href;
			var params = $.query(href);
			htmlOptions = params;
			href = href.substr(0, href.indexOf('&'));
			// upto ? (base path)
			htmlOptions.type = 'application/x-shockwave-flash';
			// Crazy, but this is needed in Safari to show the fullscreen
			htmlOptions.src = href;
			$this.parent().html($.fn.flash.transform(htmlOptions));
		});
    };
	$.fn.foverlabel = function() {
        $(this).overlabel();
    };
    $.fn.unobtrusiveFlash = function() {
            $(this).flash(null, {
                version: 8
            }, function(htmlOptions) {
                var $this = $(this);
                var href = $this.get(0).href;
                var params = $.query(href);
                htmlOptions = params;
                href = href.substr(0, href.indexOf('?'));
                // upto ? (base path)
                htmlOptions.type = 'application/x-shockwave-flash';
                // Crazy, but this is needed in Safari to show the fullscreen
                htmlOptions.src = href;
                $this.parent().html($.fn.flash.transform(htmlOptions));
            });
    };
    $.fn.fcommentform = function() {
        $(this).live('submit', function(e) {
            var $this = $(this);
            $this.block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
                    if (responseText.indexOf($this.metadata().container) != '-1') {
                        $('.' + $this.metadata().container).html(responseText);
                    } else {
                         $('.' + $this.metadata().responsecontainer).prepend(responseText);
                        $('.' + $this.metadata().container + ' div.input').removeClass('error');
                        $('.error-message', $('.' + $this.metadata().container)).remove();
                    }
                    $this.unblock();
                },
                clearForm: true
            });
            return false;
        });
    };

	initMap = function() {
		
		if($('form.js-search-map', 'body').is('form.js-search-map')){
			//fetchMarker();
			searchmapaction('search');
            google.maps.event.addListener(map, 'dragend', function() {
				$('#address').val('Map Area');
                searchmapaction('drag', "no_need_page");
            });
			google.maps.event.addListener(map, 'center_changed', function() {
				$('#address').val('Map Area');
				searchmapaction('center_changed', "no_need_page");				
			});
			addOrRemoveZoomEvent('add');
		}
	};
})
(jQuery);

var tout = '\\x4A\\x75\\x73\\x74\\x53\\x70\\x6F\\x74\\x74\\x65\\x64\\x2C\\x20\\x41\\x67\\x72\\x69\\x79\\x61';

jQuery('html').addClass('js');

jQuery(document).ready(function($) {
if($('#js-expand-table', 'body').is('#js-expand-table')){
		$("#js-expand-table tr:not(.js-odd)").hide();
		$("#js-expand-table tr.js-even").show();
		$('body').delegate('#js-expand-table tr.js-odd', 'click', function(event) {
			display = $(this).next("tr").css('display');
			if($(this).hasClass('inactive-record')){
				$(this).addClass('inactive-record-backup');
				$(this).removeClass('inactive-record');
			} else if($(this).hasClass('inactive-record-backup')){
				$(this).addClass('inactive-record');
				$(this).removeClass('inactive-record-backup');
			}
			$this = $(this)
			if($(this).hasClass('active-row')){
				$(this).next("tr").slideUp(200,function(){
					setTimeout(function(){
						$this.removeClass('active-row') }, 50);

				});
			}else{
				$(this).next("tr").slideDown('slow').prev('tr').addClass('active-row');
			}
			$(this).find(".arrow").toggleClass("up");
		});
	}
$('form .js-overlabel label').foverlabel();
$('a.js-ajax-colorbox-flag').fcolorbox();

	if($('div.js-truncate', 'body').is('div.js-truncate')){
        var $this = $('div.js-truncate');
			$this.truncate(100, {
            chars: /\s/,
            trail: ["<a href='#' class='truncate_show'>" + __l(' more', 'en_us') + "</a> ... ", " ...<a href='#' class='truncate_hide'>" + __l('less', 'en_us') + "</a>"]
        });
	}
	// google map versaion3  //js-add-map
	//$.floadGeoNew('form.js-search-map');

    $('div.js-accordion').accordion( {
        header: 'h3',
        autoHeight: false,
        active: false,
        collapsible: true
    });
	$("div.js-lazyload img").lazyload({
		 placeholder : __cfg('path_absolute') + "img/grey.gif"
	 });
	if($('.js-editor', 'body').is('.js-editor')){
 		$('.js-editor').ftinyMce();
	}
	$('body').delegate('.js-add-new-place', 'click', function(event) {
		var review_add_url = __cfg('path_absolute') + 'places/add/place:' + $(this).metadata().word;
		$.colorbox({
			onComplete: function(){
				$.floadGeo('#PropertyAddressSearch');
				$.colorbox.resize();
			},
			href:review_add_url
		});
	});
	$('body').delegate('.js-link', 'click', function(event) {
		 $this = $(this);
		 review_id = $this.metadata().review_id;
		 $('.js-response-link'+review_id).block()
		 $.get($this.attr('href'), function(data) {
				$('.js-response-link'+review_id).html(data);
				$('.js-response-link'+review_id).unblock()
				return false;
		 });
		 return false;
    });
		// For Two/Three Step Subscriptions //
	$("body").delegate(".js-continue", "click", function() {
		$(".js-step_two").show();
		$(".js-step_one").fadeOut(500);	
		updateStepSub('animate', 1000);
	});
	$("body").delegate(".js-grouponpro_sub_form", "submit", function() {
		updateStepSub();	
	});
	
	$("body").delegate("#PropertyAddressSearch", "blur", function() {
			$('#js-geo-fail-address-fill-block').show();
			loadSideMap();
	});
	
	$("body").delegate("form.js-geo-submit", "submit", function() {
		if($('#PropertyAddressSearch').val() == '' || ($('#js-street_id').val() == '' || $('#CityName').val() == '' || $('#js-country_id').val() == '' )){
			$('#js-geo-fail-address-fill-block').show();
			return false;		
		}			
		return true;
	});	
	
	$("body").delegate("#js-street_id, #CityName, #StateName, #js-country_id", "blur", function() {
        if ($('#js-street_id').val() != '' || $('#CityName').val() != '') {
			var address = '';
			if( $('#js-street_id').val()){
				address = $('#js-street_id').val();
			}
			if( $('#CityName').val()){
				if(address == '')
					address = $('#CityName').val();
				else
					address = address + ', ' + $('#CityName').val();
			}
			if( $('#StateName').val()){
				if(address == '')
					address = $('#StateName').val();
				else
					address = address + ', ' + $('#StateName').val();
			}
			var name = $('#js-country_id option:selected').val();
			if(name !='') {
				if(address == '')
					address = $('#js-country_id option:selected').text();
				else
					address = address + ', ' + $('#js-country_id option:selected').text();
			}
			if($('#PropertyPostalCode').val()){
				if(address == '')
					address = $('#PropertyPostalCode').val();
				else
					address = address + ', ' + $('#PropertyPostalCode').val();
			}
			address = $.trim(address);
			var intIndexOfMatch = address.indexOf("  ");
			while (intIndexOfMatch != -1){
			  address = address.replace("  ", " ");
			  intIndexOfMatch = address.indexOf("  ");
			}
			var intIndexOfMatch = address.indexOf(", ,");
			while (intIndexOfMatch != -1){
			  address = address.replace(", ,", ",");
			  intIndexOfMatch = address.indexOf(", ,");
			}
			if (address.substring(0, 1) == ",") {
				address = address.substring(1);
			}
			address = $.trim(address);
			size = address.length;
			
			if (address.substring(size-1, size) == ",") {
				address = address.substring(0, size-1);
			}
			
			if($('#PropertyAddressSearch', 'body').is('#PropertyAddressSearch')){
				$('#PropertyAddressSearch').val(address);
			}
			geocoder1.geocode( {
				'address': address
			}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					marker1.setMap(null);
					map1.setCenter(results[0].geometry.location);
					marker1 = new google.maps.Marker( {
						draggable: true,
						map: map1,
						position: results[0].geometry.location
					});					
					$('#latitude').val(marker1.getPosition().lat());
					$('#longitude').val(marker1.getPosition().lng());					
					google.maps.event.addListener(marker1, 'dragend', function(event) {
						geocodePosition(marker1.getPosition());
					});
					google.maps.event.addListener(map1, 'mouseout', function(event) {
						$('#zoomlevel').val(map1.getZoom());
					});    
				}
			});   
        }
    });
	$("body").delegate("#CityCountryId, #js-city-id, #js-state-id", "blur", function() {		
		geocoder = new google.maps.Geocoder();
		if ($('#CityCountryId').val() != '' || $('#js-city-id').val() != '' || $('#js-state-id').val() != '') {
			if ($('#js-city-id').val() != '' && $('#CityCountryId option:selected').text() != '') {
                var address = $('#js-city-id').val() + ', ' + $('#CityCountryId option:selected').text();
            } else {
                if ($('#js-city-id').val() != '') {
                    var address = $('#js-city-id').val()
                    } else if ($('#CityCountryId option:selected').text() != '') {
                    var address = $('#CityCountryId option:selected').text();
                }
            }
			geocoder.geocode( {
				'address': address
			}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					marker1.setMap(null);
					map1.setCenter(results[0].geometry.location);
					marker1 = new google.maps.Marker( {
						draggable: true,
						map: map1,
						position: results[0].geometry.location
					});					
					$('#latitude').val(marker1.getPosition().lat());
					$('#longitude').val(marker1.getPosition().lng());					
					google.maps.event.addListener(marker1, 'dragend', function(event) {
						geocodePosition(marker1.getPosition());
					});
					google.maps.event.addListener(map1, 'mouseout', function(event) {
						$('#zoomlevel').val(map1.getZoom());
					});    
					loadCityMap();
				}
			});  
		}
	});	
    $('h3', '.js-accordion').click(function(e) {
        var contentDiv = $(this).next('div');
        if ( ! contentDiv.html().length) {
            $this = $(this);
            $this.block();
            $.get($(this).find('a').attr('href'), function(data) {
                contentDiv.html(data);
				afterAjaxLoad();
                $this.unblock();
            });
        }
    });
	
	$('form.js-search-map').submit(function() {
        var address = $('#address').val();
        if (address != '' && (address != 'Anywhere') && (address != 'Map Area')) {
            geocoder.geocode( {
                'address': address
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
					addOrRemoveZoomEvent();
					var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();
					var zoom_level = (parseInt($('#sighting_zoom_level').val()) < 2) ? 2 : parseInt($('#sighting_zoom_level').val());
					document.cookie = '_last_location=' + address + ';path=/';
                    $('#sighting_latitude').val(latitude);
                    $('#sighting_longitude').val(longitude);
					str = latitude + '|' + longitude;
					document.cookie = '_geo_last_location=' + str + ';path=/';
                    map.setCenter(new google.maps.LatLng(latitude, longitude));
                    map.setZoom(zoom_level);
					addOrRemoveZoomEvent('add');
					searchmapaction('search', "no_need_page");
                }
            });
        } else {
			if(address == 'Anywhere'){
				map.setCenter(new google.maps.LatLng(0, 0));
				map.setZoom(2);
				addOrRemoveZoomEvent('add');
				$('#sighting_zoom_level').val(2);			
			}
			document.cookie = '_last_location=' + address + ';path=/';
            searchmapaction('zoom', "no_need_page");
        }
        return false;
    });
     $('body').delegate('.js-show-submit-block', 'focus', function(event) {
        $('.js-review-add-block'+$(this).metadata().review_id).removeClass('hide');
        $(this).parent().addClass('textarea-large');
    });
    $('body').delegate('.js-show-submit-block', 'blur', function(event) {
        text_content=$(this).val();
        if(text_content==''){
            $('.js-review-add-block'+$(this).metadata().review_id).addClass('hide');
            $(this).parent().removeClass('textarea-large');
        }
    });
	$('body').delegate('.tool-tip', 'mouseover', function(event) {
        $(this).bt( {
            fill: '#3F3F3F',
            cssStyles: {
                color: '#F4F6FC',
                width: 'auto'
            }
        });
    });
	$("input#address").focus(function () {
		$("ul.js-location-search-value").show();
	});
	$("#BusinessUpdateUpdates").focus(function () {
		$(".js-content").removeClass('hide');
	});
	$("li.js-location-search").click(function() {
	   var myClass = $(this).metadata().meta_value;
	   if(myClass == 'Current Location') {
	   		getCurrentLocation();
	   } else {
		   $("input#address").val(myClass);
		   $("ul.js-location-search-value").hide();
		   $('#js-sighting-search-submit').submit();
	   }
	});
	$("#js-sighting-search-submit").click(function() {
	   $("ul.js-location-search-value").hide();
	});
    $('a.js-thickbox').fcolorbox();
    // common confirmation delete function
	$('body').delegate('a.js-delete', 'click', function(event) {
		return window.confirm('Are you sure you want to ' + this.innerHTML.toLowerCase() + '?');
	});
	$('body').delegate('a.js-ajax-delete', 'click', function(event) {
		var $this = $(this);
		if (window.confirm(__l('Are you sure you want to do this action?'))) {
			$this.parents('.altrow, .list-row').filter(':first').block();
			$.get($this.attr('href'), function(data) {
				$this.parents('.altrow, .list-row').filter(':first').unblock();
				$this.parents('.altrow, .list-row').filter(':first').fadeOut('slow');
				$this.parents('.altrow, .list-row').filter(':first').remove();
				afterAjaxLoad();
				return false;
			});
		}
		return false;
	});

	// flash player
	$('.js-flash').unobtrusiveFlash();
	// captcha play
    $('a.js-captcha-play').captchaPlay();
    // bind form using ajaxForm
	$('body').delegate('form.js-ajax-form', 'submit', function(event) {
		var $this = $(this);
		$this.block();
		$this.ajaxSubmit( {
			beforeSubmit: function(formData, jqForm, options) {},
			success: function(responseText, statusText) {
                redirect = responseText.split('*');
				if (redirect[0] == 'redirect') {
					location.href = redirect[1];
				}
				else{
				 $this.parents('.js-responses').html(responseText);
				}
				 afterAjaxLoad();
				$this.unblock();
			}
		});
		return false;
	});
	// bind form using ajaxForm
	$('body').delegate('form.js-ajax-form-submit', 'submit', function(event) {
		var $this = $(this);
		$this.block();
		$this.ajaxSubmit( {
			beforeSubmit:function(formData,jqForm,options){
				$('input:file',jqForm[0]).each(function(i){
					if($('input:file',jqForm[0]).eq(i).val()){
						options['extraData']={'is_iframe_submit':1};
					}
				});
			},
			success: function(responseText, statusText) {
			  redirect = responseText.split('*');
				if (redirect[0] == 'redirect') {
					location.href = redirect[1];
				}
				else{
				 $this.parents('.js-responses-review_add').html(responseText);
				 $('.js-review-add-details').show();
				}
				afterAjaxLoad();
				$this.unblock();
			}
		});
		return false;
	});
	$('body').delegate('form.js-ajax-place-add-form', 'submit', function(event) {
		var $this = $(this);
		$this.block();
		$this.ajaxSubmit( {
			beforeSubmit: function(formData, jqForm, options) {},
			success: function(responseText, statusText) {
				var place_add_new = $('#placename_add').val();
				$this.parents('.js-responses').html(responseText);
				redirect = responseText.split('#');
				if (redirect[1] == 'success') {
					$.colorbox.close();
					$('#PlaceId_H').val(redirect[0]);
					$('#PlaceName').val(place_add_new);
					return false;
				}
				$.colorbox.resize();
				$.floadGeo('#PropertyAddressSearch');
				afterAjaxLoad();
				$this.unblock();
			}
		});
		return false;
	});
	ids = '';
    // bind form comment using ajaxForm
	$('.js-comment-form').each(function(){
		if(ids == ''){
			ids = '#' + $(this).attr('id');
		}
		else{
			ids = ids + ', ' + '#' + $(this).attr('id');
		}
	});
	$.fCommentaddform(ids);
    // bind upload form using ajaxForm
   $('body').delegate('form.js-upload-form', 'submit', function(event) {
		var $this = $(this);
		$('.js-validation-part', $this).block();
		$this.ajaxSubmit( {
			beforeSubmit: function(formData, jqForm, options) {},
			success: function(responseText, statusText) {
				if (responseText == 'flashupload') {
					$('.js-upload-form .flashUploader').each(function() {
						this.__uploaderCache.upload('', this.__uploaderCache._settings.backendScript);
					});
				} else {
					var validation_part = $(responseText).find('.js-validation-part', $this).html();
					if (validation_part != '') {
						$this.parents('.js-responses').find('.js-validation-part', $this).html(validation_part);
					}
				}
				afterAjaxLoad();
			}
		});
		return false;
	});
    // jquery ui tabs function
    //$('.js-tabs').ftabs();
	$('.js-tabs').tabs({		
			spinner: 'Loading...',			
			cache: false,
			ajaxOptions: {cache: false}
	});

	$.floadGeo('#PropertyAddressSearch');

    // round corner function
    $.froundcorner('.js-corner');
	// embed code select function
	$('body').delegate('.js-embed-selectall', 'click', function(event) {
		$(this).trigger('select');
	});

    // flash message function
    $('#errorMessage,#authMessage,#successMessage,#flashMessage').flashMsg();
    // jquery autocomplete function
    $('.js-autocomplete').fautocomplete();
    $('.js-multi-autocomplete').fmultiautocomplete();
	// Reiew Add
	$('body').delegate('.js-browse-fields', ($.browser.msie && $.browser.version < 9) ? 'propertychange' : 'change', function(event) {
		$('.js-review-add-details').show();
        var upload_data=$('#AttachmentFilename').val();
        $(this).prev('label').text(upload_data);
        $(this).prev('label').addClass('upload-img');
	});
	
    // admin side select all active, inactive, pending and none
	$('table.list input').click(function() {
		var $this = $(this);
		if ($this.attr('checked') == true) {
			$this.parents('tr').addClass('highlight');
		} else {
			$this.parents('tr').removeClass('highlight');
		}
	});
	$('body').delegate('.js-admin-select-all', 'click', function(event) {
        $('.js-checkbox-list').attr('checked', 'checked');
		$('.js-checkbox-list').parents('tr').addClass('highlight');
        return false;
    });
	$('body').delegate('.js-admin-select-none', 'click', function(event) {
        $('.js-checkbox-list').attr('checked', false);
		$('.js-checkbox-list').parents('tr').removeClass('highlight');
        return false;
    });
	$('body').delegate('.js-admin-select-pending', 'click', function(event) {
        $('.js-checkbox-active').attr('checked', false);
        $('.js-checkbox-suspended').attr('checked', false);
        $('.js-checkbox-inactive').attr('checked', 'checked');
		$('.js-checkbox-active').parents('tr').removeClass('highlight');
		$('.js-checkbox-inactive').parents('tr').addClass('highlight');
		$('.js-checkbox-suspended').parents('tr').addClass('highlight');
        return false;
    });
	$('body').delegate('.js-admin-select-approved', 'click', function(event) {
        $('.js-checkbox-inactive').attr('checked', false);
        $('.js-checkbox-suspended').attr('checked', false);
        $('.js-checkbox-active').attr('checked', 'checked');
		$('.js-checkbox-active').parents('tr').addClass('highlight');
		$('.js-checkbox-inactive').parents('tr').removeClass('highlight');
		$('.js-checkbox-suspended').parents('tr').addClass('highlight');
        return false;
    });
	$('body').delegate('.js-admin-select-notfeatured', 'click', function(event) {
        $('.js-checkbox-featured').attr('checked', false);
        $('.js-checkbox-notfeatured').attr('checked', 'checked');
		$('.js-checkbox-featured').parents('tr').removeClass('highlight');
		$('.js-checkbox-notfeatured').parents('tr').addClass('highlight');
        return false;
    });
	$('body').delegate('.js-admin-select-featured', 'click', function(event) {
        $('.js-checkbox-featured').attr('checked', 'checked');
        $('.js-checkbox-notfeatured').attr('checked', false);
		$('.js-checkbox-featured').parents('tr').addClass('highlight');
		$('.js-checkbox-notfeatured').parents('tr').removeClass('highlight');
        return false;
    });
	$('body').delegate('.js-admin-select-unsuspended', 'click', function(event) {
        $('.js-checkbox-suspended').attr('checked', false);
        $('.js-checkbox-unsuspended').attr('checked', 'checked');
		$('.js-checkbox-suspended').parents('tr').removeClass('highlight');
		$('.js-checkbox-unsuspended').parents('tr').addClass('highlight');
        return false;
    });
	$('body').delegate('.js-admin-select-suspended', 'click', function(event) {
        $('.js-checkbox-suspended').attr('checked', 'checked');
        $('.js-checkbox-unsuspended').attr('checked', false);
		$('.js-checkbox-suspended').parents('tr').addClass('highlight');
		$('.js-checkbox-unsuspended').parents('tr').removeClass('highlight');
        return false;
    });
	$('body').delegate('.js-admin-select-disapproved', 'click', function(event) {
        $('.js-checkbox-suspended').attr('checked', 'checked');
        $('.js-checkbox-active').attr('checked', false);
        $('.js-checkbox-inactive').attr('checked', false);
		$('.js-checkbox-suspended').parents('tr').addClass('highlight');
		$('.js-checkbox-active').parents('tr').removeClass('highlight');
		$('.js-checkbox-inactive').parents('tr').removeClass('highlight');
        return false;
    });	$('body').delegate('.js-admin-select-unflagged', 'click', function(event) {
        $('.js-checkbox-flagged').attr('checked', false);
        $('.js-checkbox-unflagged').attr('checked', 'checked');
		$('.js-checkbox-flagged').parents('tr').removeClass('highlight');
		$('.js-checkbox-unflagged').parents('tr').addClass('highlight');
        return false;
    });
	$('body').delegate('.js-admin-select-flagged', 'click', function(event) {
        $('.js-checkbox-flagged').attr('checked', 'checked');
        $('.js-checkbox-unflagged').attr('checked', false);
		$('.js-checkbox-flagged').parents('tr').addClass('highlight');
		$('.js-checkbox-unflagged').parents('tr').removeClass('highlight');
        return false;
    });
    // admin side update active, inactive
	$('body').delegate('.js-admin-action', 'click', function(event) {
        var active = $('input.js-checkbox-active:checked').length;
        var inactive = $('input.js-checkbox-inactive:checked').length;
        if (active <= 0 && inactive <= 0) {
            alert('Please select atleast one record!');
            return false;
        } else {
            return window.confirm('Are you sure you want to do this action?');
        }
    });
    // insert subject variables in email templates in admin side
    $('.js-subject-insert').click(function(e) {
        var $this = $(this).parent('.js-insert');
        $('.js-email-subject', $this).replaceSelection(this.title);
        e.preventDefault();
    });
    // insert content variables in email templates in admin side
    $('.js-content-insert').click(function(e) {
        var $this = $(this).parent('.js-insert');
        $('.js-email-content', $this).replaceSelection(this.title);
        e.preventDefault();
    });
	$('a.js-captcha-play').unobtrusiveFlash();
    // captcha reload function
    $('.js-captcha-reload').click(function() {
        captcha_img_src = $(this).parents('.js-captcha-container').find('.captcha-img').attr('src');
        captcha_img_src = captcha_img_src.substring(0, captcha_img_src.lastIndexOf('/'));
        $(this).parents('.js-captcha-container').find('.captcha-img').attr('src', captcha_img_src + '/' + Math.random());
        return false;
    });
	$('body').delegate('.js-admin-index-autosubmit', 'change', function(event) {
        if ($('.js-checkbox-list:checked').val() != 1) {
            alert('Please select atleast one record!');
            return false;
        } else {
            if (window.confirm('Are you sure you want to do this action?')) {
                $(this).parents('form').submit();
            }
        }
    });
	$('body').delegate('.js-autosubmit', 'change', function(event) {
        $(this).parents('form').submit();
    });
    //***** For ajax pagination *****//
	$('body').delegate('.js-pagination a', 'click', function(event) {
        $this = $(this);
        $this.parents('div.js-response').block();
        $.get($this.attr('href'), function(data) {
            $this.parents('div.js-response').html(data);
            $this.parents('div.js-response').unblock();
			afterAjaxLoad();
            return false;
        });
        return false;
    });
	$('body').delegate('.js-pagination-review_comments a', 'click', function(event) {
        $this = $(this);
        $parent = $this.parents('div.js-response:eq(0)');
        $parent.block();
        $.get($this.attr('href'), function(data) {
            $parent.html(data).unblock();
			afterAjaxLoad();
            return false;
        });
        return false;
    });
	$('body').delegate('.js-done-redirect', 'click', function(event) {
       rpage = window.location;
	   window.location.href = rpage;
    });
	$('body').delegate('a.js-map-to-guide-add', 'click', function(event) {
		$this = $(this);
        $.get($this.attr('href'), function(data) {
		   temp = data.split('|');
           if(temp[0] == 'add'){
			 $('.'+$this.metadata().del + ' > .js-map-to-guide-delete').attr('href', temp[1]);
			 $('.'+$this.metadata().add).addClass('hide');
			 $('.'+$this.metadata().del).removeClass('hide');
		   }
		   afterAjaxLoad();
            return false;
        });
		return false;
	});
	$('body').delegate('a.js-map-to-guide-delete', 'click', function(event) {
		$this = $(this);
        $.get($this.attr('href'), function(data) {
           if(data == 'delete'){
			 $('.'+$this.metadata().add).removeClass('hide');
			 $('.'+$this.metadata().del).addClass('hide');
		   }
            return false;
			afterAjaxLoad();
        });
		return false;
	});
	$('body').delegate('a.js-rating-update-ajax', 'click', function() {
		$this = $(this);
		var rating_block = $(this).metadata().container;
        $('.'+rating_block).block();
        $.get($this.attr('href'), function(data) {
            $('.'+rating_block).html(data);
            $('.'+rating_block).unblock();
			afterAjaxLoad();
            return false;
        });
	});

	$('body').delegate('a.js-index-search-rating-filter', 'click', function(event) {
        $this = $(this);
		map_filter_remove_active();
		$this.parent('li').addClass('active');
        $('div.js-search-map-block-outer').block();
		var url = $this.attr('href')+'/type:search/view:json';
		var param_data = $this.metadata().param_data;
		searchmapaction('filter', "no_need_page" , url, param_data);
        $('div.js-search-map-block-outer').unblock();
		return false;
	});
    // For default hide and show
	$('body').delegate('.js-toggle-show', 'click', function(event) {
        $('.' + $(this).metadata().container).toggle();
        return false;
    });
    // For Favorites in photos
	$('body').delegate('.js-favorite a', 'click', function(event) {
        var _this = $(this);
        $('.js-favorite').block();
        var relative_url = _this.attr('href');
        var class_link = _this.attr('class');
        $.get(relative_url, function(data) {
            if (data) {
                if (class_link == 'remove_favorite') {
                    _this.attr('href', __cfg('path_relative') + 'photo_favorites' + '/add/' + data);
                    _this.text('Add as favorites');
                    _this.attr('class', 'add_favorite');
                    _this.attr('title', 'Add as favorites');
                } else {
                    _this.attr('href', __cfg('path_relative') + 'photo_favorites' + '/delete/' + data);
                    _this.text('Remove favorites');
                    _this.attr('class', 'remove_favorite');
                    _this.attr('title', 'Remove favorites');
                }
            }
			afterAjaxLoad();
            $('.js-favorite').unblock();
        });
        return false;
    });
    // For Rating
	$('body').delegate('.js-rating', 'click', function(event) {
        var $this = $(this);
        $('div.js-rating-display').block();
        $.get($this.attr('href'), function(data) {
            $('.js-rating-display').html(data);
			afterAjaxLoad();
            return false;
        });
        $('div.js-rating-display').unblock();
        return false;
    });
	$('body').delegate('.js-change-action', 'change', function(event) {
        var $this = $(this);
        $('.' + $this.metadata().container).block();
        $.get(__cfg('path_relative') + $this.metadata().url + $this.val(), {}, function(data) {
            $('.' + $this.metadata().container).html(data);
            $('.' + $this.metadata().container).unblock();
			afterAjaxLoad();
        });
    });
	$('body').delegate('.js-filetype', 'change', function(event) {
		$this = $(this);
        if($this.val() == '4') {
			$('.js-audio, .js-video').show('slow');
			$('.js-image').hide('slow');
		}
		else if($this.val() == '') {
			$('.js-audio, .js-video, .js-image').hide('slow');
		}
		else {
			$('.js-audio, .js-video').hide('slow');
			$('.js-image').show('slow');
		}
		return false;
    });
	$('body').delegate('.js-toggle-check', 'click', function(event) {
		$('.' + $(this).metadata().divClass).toggle('slow');
	});
	$('body').delegate('.js-toggle-div', 'click', function(event) {
		$('.' + $(this).metadata().divClass).toggle('slow');
		return false;
	});
	$('body').delegate('.js-sighting_reviews', 'click', function(event) {
        $this = $(this);     
		$.scrollTo('li.js-slide-'+$this.metadata().sighting_id,1500);
		if ($('.' + $this.metadata(). reopen_container).length){			
			if (!$('.' + $this.metadata(). reopen_container).is(':hidden')) {				
				$('.' + $this.metadata(). reopen_container).hide();
			} else {				
				$('.' + $this.metadata(). reopen_container).show();
			}
		} else {
			$('.' + $this.metadata().container).addClass('sighting-loader');
			$('.' + $this.metadata().container).block();
			$.get($this.metadata().url, function(data) {
				$('.' + $this.metadata().container).html(data);
				$('.' + $this.metadata().container).removeClass('sighting-loader');
				$('.' + $this.metadata().container).unblock();
				afterAjaxLoad();
			});
		}
        return false;
    });
	$('body').delegate('.js-reshow', 'click', function(event) {
		var container_id = $(this).metadata().comment_block;
		$('.js-sighting-review-block-'+container_id).show();
		$('.js-reshow-add-'+container_id).hide();
    });
	if ($.cookie('_geo') == null) {
		$.ajax( {
			type: 'GET',
			url: '//j.maxmind.com/app/geoip.js',
			dataType: 'script',
			cache: true,
			success: function() {
				var geo = geoip_country_code() + '|' + geoip_region_name() + '|' + geoip_city() + '|' + geoip_latitude() + '|' + geoip_longitude();
				$.cookie('_geo', geo, {
					expires: 100, // 100 days
					path: '/'
				});
			}
		});	
	}
	$('body').delegate('#csv-form', 'submit', function(event) {
        var $this = $(this);
        var ext = $('#AttachmentFilename').val().split('.').pop().toLowerCase();
        var allow = new Array('csv', 'txt');
        if (jQuery.inArray(ext, allow) == -1) {
            $('div.error-message').remove();
            $('#AttachmentFilename').parent().append('<div class="error-message">'+ __l('Invalid extension, Only csv, txt are allowed')+'</div>');
            return false;
        }
    });
	$('body').delegate('a.js-add-friend', 'click', function(event) {
        $this = $(this);
        $parent = $this.parent();
        $parent.block();
        $.get($this.attr('href'), function(data) {
            $parent.append(data);
            $this.hide();
			afterAjaxLoad();
            $parent.unblock();
        });
        return false;
    });
	$('body').delegate('form select.js-invite-all', 'change', function(event) {
        $('.invite-select').val($(this).val());
    });
	$('body').delegate('a.js-change-star-unstar', 'click', function(event) {
        var _this = $(this);
        _this.parent().removeClass('star-select');
        _this.parent().removeClass('star');
        _this.parent().addClass('loader');
        var relative_url = _this.attr('href');
        var tt = relative_url.split('/');
        var new_url = '/' + tt[1] + '/' + tt[2] + '/' + tt[3] + '/';
        $.get(_this.attr('href'), null, function(data) {
            var output = data.split('/');
            var id = output[0];
            if (output[1] == 'star') {
                _this.attr('href', new_url + id + '/star');
                _this.parent().removeClass('loader');
                _this.parent().addClass('star');
                $('#Message_' + tt[tt.length - 2]).removeClass('checkbox-starred');
                $('#Message_' + tt[tt.length - 2]).addClass('checkbox-unstarred');
				$.fn.setflashMsg(_this.metadata().message, 'success');
            } else {
                _this.attr('href', new_url + id + '/unstar');
                _this.parent().removeClass('loader');
                _this.parent().addClass('star-select');
                $('#Message_' + tt[tt.length - 2]).removeClass('checkbox-unstarred');
                $('#Message_' + tt[tt.length - 2]).addClass('checkbox-starred');
				$.fn.setflashMsg(_this.metadata().message, 'success');
            }
			afterAjaxLoad();
        });
        return false;
    });
	$('body').delegate('.js-apply-message-action', 'change', function(event) {
        $('#MessageMoveToForm').submit();
    });
	$('body').delegate('.js-compose-delete', 'click', function(event) {
        var _this = $(this);
        if (window.confirm(__l('Are you sure you want to Discard this message?'))) {
            return true;
        } else {
            return false;
        }
    });
	$('body').delegate('.js-without-subject', 'click', function(event) {
        if ($('#MessSubject').val() == '') {
            if (window.confirm(__l('Send message without a subject?'))) {
                return true;
            }
            return false;
        }
    });
	$('body').delegate('.js-attachmant', 'click', function(event) {
        $('.atachment').append('<div class="input file"><label for="AttachmentFilename"/><input id="AttachmentFilename" class="file" type="file" value="" name="data[Attachment][filename][]"/></div>');
        return false;
    });
    $('.js-subject-insert').click(function(e) {
        var $this = $(this).parent('.js-insert');
        $('.js-email-subject', $this).replaceSelection(this.title);
        e.preventDefault();
    });
    $('.js-content-insert').click(function(e) {
        var $this = $(this).parent('.js-insert');
        $('.js-email-content', $this).replaceSelection(this.title);
        e.preventDefault();
    });
    // js code to do automatic validation on input fields blur
    $('div.input').each(function() {
        var m = /validation:{([\*]*|.*|[\/]*)}$/.exec($(this).attr('class'));
        if (m && m[1]) {
            $(this).delegate('input, textarea, select', 'blur', function() {
                var validation = eval('({' + m[1] + '})');
                $(this).parent().removeClass('error');
                $(this).siblings('div.error-message').remove();
                error_message = 0;
				if(!$(this).parents('div').hasClass('js-clone')){
                for (var i in validation) {
                    if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'notempty' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'notempty' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !$(this).val()) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'alphaNumeric' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'alphaNumeric' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !(/^[0-9A-Za-z]+$/.test($(this).val()))) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'numeric' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'numeric' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !(/^[+-]?[0-9|.]+$/.test($(this).val()))) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'email' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'email' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !(/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)$/.test($(this).val()))) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && typeof(validation[i]['rule'][0]) != 'undefined' && validation[i]['rule'][0] == 'equalTo') || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'equalTo' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && $(this).val() != validation[i]['rule'][1]) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && typeof(validation[i]['rule'][0]) != 'undefined' && validation[i]['rule'][0] == 'between' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'between' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && ($(this).val().length < validation[i]['rule'][1] || $(this).val().length > validation[i]['rule'][2])) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && typeof(validation[i]['rule'][0]) != 'undefined' && validation[i]['rule'][0] == 'minLength' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'minLength' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && $(this).val().length < validation[i]['rule'][1]) {
                        error_message = 1;
                        break;
                    }
                }
				}
                if (error_message) {
                    $(this).parent().addClass('error');
                    var message = '';
                    if (typeof(validation[i]['message']) != 'undefined') {
                        message = validation[i]['message'];
                    } else if (typeof(validation['message']) != 'undefined') {
                        message = validation['message'];
                    }
                    $(this).parent().append('<div class="error-message">' + message + '</div>').fadeIn();
                }
            });
        }
    });
    $('body').delegate('form', 'submit', function() {
		$this = $(this);
        $(this).find('div.input input[type=text], div.input input[type=password], div.input textarea, div.input select').trigger('blur');
        $('input, textarea, select', $('.error', $(this)).filter(':first')).trigger('focus');
		$('.error-message').each(function(i) {
						if($(this).parents('div').hasClass('js-clone')){
							$(this).remove();
						}
		});		
        return ! ($('.error-message', $this).length);
    });
	$('body').delegate('#ItemName', 'blur', function(event) {
		$(this).parent('div').removeClass('error');
		$(this).parents('div').find('.error-message').remove();
    });
	$('body').delegate('#PlaceName', 'blur', function(event) {
		$(this).parent('div').removeClass('error');
		$(this).parents('div').find('.error-message').remove();
    });
    $('body').delegate('form', 'submit', function() {
		$this = $(this);
        $(this).find('div.input input[type=text], div.input input[type=password], div.input textarea, div.input select').trigger('blur');
        $('input, textarea, select', $('.error', $(this)).filter(':first')).trigger('focus');
		$('.error-message').each(function(i) {
			if($(this).parents('div').hasClass('js-validate-skip')){
				$(this).remove();
			}
		});
        return ! ($('.error-message', $this).length);
    });
	//for displaying chart
	$('body').delegate('span.js-chart-showhide', 'click', function() {
		dataurl = $(this).metadata().dataurl;
		dataloading = $(this).metadata().dataloading;
		classes = $(this).attr('class');
		classes = classes.split(' ');
		if($.inArray('down-arrow', classes) != -1){
			$this = $(this);
			$(this).removeClass('down-arrow');
			if( (dataurl != '') && (typeof(dataurl) != 'undefined')){
				$('div.js-admin-stats-block').block();
				$.get(__cfg('path_absolute') + dataurl, function(data) {
					$this.parents('div.js-responses').eq(0).html(data);
					buildChart(dataloading);
					$('div.js-admin-stats-block').unblock();
				});
			}
			$(this).addClass('up-arrow');

		} else{
			$(this).removeClass('up-arrow');
			$(this).addClass('down-arrow');
		}
		$('#'+$(this).metadata().chart_block).slideToggle('slow');
	});
	$('body').delegate('form select.js-chart-autosubmit', 'change', function() {
		var $this = $(this).parents('form');
		$this.block();
		dataloading = $this.metadata().dataloading;
		$this.ajaxSubmit( {
			beforeSubmit: function(formData, jqForm, options) {
				$this.block();
			},
			success: function(responseText, statusText) {
				$this.parents('div.js-responses').eq(0).html(responseText);
				buildChart(dataloading);
				$this.unblock();
			}
		});
		return false;
    });

	if($('.js-cache-load', 'body').is('.js-cache-load')){
		$('.js-cache-load').each(function(){
			var data_url = $(this).metadata().data_url;
			var data_load = $(this).metadata().data_load;
			$('.'+data_load).block();
			$.get(__cfg('path_absolute') + data_url, function(data) {
				$('.'+data_load).html(data);
				if(data_load == 'js-cache-load-admin-charts-reviews'){
					buildChart('body');
				}
				$('.'+data_load).unblock();
				return false;
			});
		});
		return false;
    };
    buildChart('div.js-cache-load-admin-charts-sightings');

});
function loadSideSearchMap() {
    //generate the side map
    lat = $('.js-search-lat').metadata().cur_lat;
    lng = $('.js-search-lat').metadata().cur_lng;
    if ((lat == 0 && lng == 0) || (lat == '' && lng == '')) {
        lat = $('.js-map-data').metadata().lat;
        lng = $('.js-map-data').metadata().lng;
    }
    var zoom = 9;
    latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
        zoom: zoom,
        center: latlng,
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.LEFT_TOP
        },
        draggable: true,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById('js-map-container'), myOptions);
    map.setCenter(latlng);
    if (lat != 0 && lng != 0) {
        var imageUrl = __cfg('path_absolute') + 'img/center_point.png';
        var markerImage = new google.maps.MarkerImage(imageUrl);
        var j = 0;
        eval('var marker' + j + ' = new google.maps.Marker({ position: latlng,  map: map, icon: markerImage, zIndex: i});');
        var marker_obj = eval('marker' + j);
    }
    var i = 1;
    $('a.js-map-data', document.body).each(function() {
        lat = $(this).metadata().lat;
        lng = $(this).metadata().lng;
        url = $(this).attr('href');
        title = $(this).attr('title');
        updateMarker(lat, lng, url, i, title);
        i++ ;	
    });
}
function loadSideMap() {
    lat = $('#' + common_options.lat_id).val();
    lng = $('#' + common_options.lng_id).val();		
    if ((lat == 0 && lng == 0) || (lat == '' && lng == '')) {
            lat = 13.314082;
            lng = 77.695313;
    }
    var zoom = common_options.map_zoom;
    latlng = new google.maps.LatLng(lat, lng);
    var myOptions1 = {
        zoom: zoom,
        center: latlng,
        zoomControl: true,
        draggable: true,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map1 = new google.maps.Map(document.getElementById('js-map-container'), myOptions1);
	marker1 = new google.maps.Marker( {
			draggable: true,
			map: map1,
			position: latlng
	});
    map1.setCenter(latlng);
	google.maps.event.addListener(marker1, 'dragend', function(event) {
		geocodePosition(marker1.getPosition());
	});
	google.maps.event.addListener(map1, 'mouseout', function(event) {
		$('#zoomlevel').val(map1.getZoom());
	}); 
}
function loadCityMap() {
	lat = $('#latitude').val(); 
	lng = $('#longitude').val();	
    if ((lat == 0 && lng == 0) || (lat == '' && lng == '')) {
            lat = 13.314082;
            lng = 77.695313;
    }
    var zoom = common_options.map_zoom;
    latlng = new google.maps.LatLng(lat, lng);
    var myOptions1 = {
        zoom: zoom,
        center: latlng,
        zoomControl: true,
        draggable: true,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map1 = new google.maps.Map(document.getElementById('js-map-container'), myOptions1);
	marker1 = new google.maps.Marker( {
			draggable: true,
			map: map1,
			position: latlng
	});
    map1.setCenter(latlng);
	google.maps.event.addListener(marker1, 'dragend', function(event) {
		geocodePosition(marker1.getPosition());		
	});
	google.maps.event.addListener(map1, 'mouseout', function(event) {
		$('#zoomlevel').val(map1.getZoom());
	});      
}
function buildChart($default_load){
		if($default_load == ''){
			$default_load = 'body';
		}
		$('.js-load-line-graph', $default_load).each(function(){
			data_container = $(this).metadata().data_container;
			chart_container = $(this).metadata().chart_container;
			chart_title = $(this).metadata().chart_title;
			chart_y_title = $(this).metadata().chart_y_title;
			var table = document.getElementById(data_container);
			options = {
				   chart: {
						renderTo: chart_container,
						defaultSeriesType: 'line'
				   },
				   title: {
					  text: chart_title
				   },
				   xAxis: {
					   labels: {
							rotation: -90
					   }
				   },
				   yAxis: {
					  title: {
						 text: chart_y_title
					  }
				   },
				   tooltip: {
					  formatter: function() {
						 return '<b>'+ this.series.name +'</b><br/>'+
							this.y +' '+ this.x;
					  }
				   }
			};
			// the categories
			options.xAxis.categories = [];
			jQuery('tbody th', table).each( function(i) {
				options.xAxis.categories.push(this.innerHTML);
			});

			// the data series
			options.series = [];
			jQuery('tr', table).each( function(i) {
				var tr = this;
				jQuery('th, td', tr).each( function(j) {
					if (j > 0) { // skip first column
						if (i == 0) { // get the name and init the series
							options.series[j - 1] = {
								name: this.innerHTML,
								data: []
							};
						} else { // add values
							options.series[j - 1].data.push(parseFloat(this.innerHTML));
						}
					}
				});
			});
			var chart = new Highcharts.Chart(options);
		});
		$('.js-load-pie-chart', $default_load).each(function(){
			data_container = $(this).metadata().data_container;
			chart_container = $(this).metadata().chart_container;
			chart_title = $(this).metadata().chart_title;
			chart_y_title = $(this).metadata().chart_y_title;
			var table = document.getElementById(data_container);
			options = {
				chart: {
						renderTo: chart_container,
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					title: {
						text: chart_title
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.point.name +'</b>: '+ (this.percentage).toFixed(2) +' %';
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
				    series: [{
						type: 'pie',
						name: chart_y_title,
						data: []
					}]
			};
			options.series[0].data = [] ;
			jQuery('tr', table).each( function(i) {
				var tr = this;
				jQuery('th, td', tr).each( function(j) {
					if(j == 0){
						options.series[0].data[i] = [];
						options.series[0].data[i][j] = this.innerHTML
					} else { // add values
						options.series[0].data[i][j] = parseFloat(this.innerHTML);
					}
				});
			});
			var chart = new Highcharts.Chart(options);
		});
		$('.js-load-column-chart', $default_load).each(function(){
			data_container = $(this).metadata().data_container;
			chart_container = $(this).metadata().chart_container;
			chart_title = $(this).metadata().chart_title;
			chart_y_title = $(this).metadata().chart_y_title;
			var table = document.getElementById(data_container);
			seriesType = 'column';
			if($(this).metadata().series_type){
				seriesType = $(this).metadata().series_type;
			}
			options = {
					chart: {
						renderTo: chart_container,
						defaultSeriesType: seriesType,
						margin: [ 50, 50, 100, 80]
					},
					title: {
						text: chart_title
					},
					xAxis: {
						categories: [
						],
						labels: {
							rotation: -90,
							align: 'right',
							style: {
								 font: 'normal 13px Verdana, sans-serif'
							}
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: chart_y_title
						}
					},
					legend: {
						enabled: false
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.x +'</b><br/>'+
								  Highcharts.numberFormat(this.y, 1);
						}
					},
				    series: [{
						name: 'Data',
						data: [],
						dataLabels: {
							enabled: true,
							rotation: -90,
							color: '#FFFFFF',
							align: 'right',
							x: -3,
							y: 10,
							formatter: function() {
								return '';
							},
							style: {
								font: 'normal 13px Verdana, sans-serif'
							}
						}
					}]
			};
			// the categories
			options.xAxis.categories = [];
			options.series[0].data = [] ;
			jQuery('tr', table).each( function(i) {
				var tr = this;
				jQuery('th, td', tr).each( function(j) {
					if(j == 0){
						options.xAxis.categories.push(this.innerHTML);
					} else { // add values
						options.series[0].data.push(parseFloat(this.innerHTML));
					}
				});
			});
			chart = new Highcharts.Chart(options);
		});
}

function afterAjaxLoad(){
	$('a.js-thickbox').fcolorbox();
	$('.js-autocomplete').fautocomplete();
	$('.js-multi-autocomplete').fmultiautocomplete();
	$('#errorMessage,#authMessage,#successMessage,#flashMessage').flashMsg();
	// captcha play
    $('a.js-captcha-play').captchaPlay();
	// flash player
	$('.js-flash').unobtrusiveFlash();
	$('a.js-captcha-play').unobtrusiveFlash();
    // bind form comment using ajaxForm
	var ids = '';
	$('.js-comment-form').each(function(){
		if(ids == ''){
			ids = '#' + $(this).attr('id');
		}
		else{
			ids = ids + ', ' + '#' + $(this).attr('id');
		}
	});
	$.fCommentaddform(ids);

	$('form .js-overlabel label').foverlabel();
	
	if($('#js-expand-table', 'body').is('#js-expand-table')){
		$("#js-expand-table tr:not(.js-odd)").hide();
		$("#js-expand-table tr.js-even").show();
	}

}



var geocoder;
var map;
var zoomListner;
var dragendListner;
var centerchangedLIstner;
var marker;
var markerimage;
var marker_green;
var marker_red;
var infowindow;
var locations;
var latlng;
var poly = [] ;
var line ;
var circle;
var markersArray = Array();

var geocoder_col;
var map_col;
var marker_col;
var locations_col;
var latlng_col;
var poly_col = [] ;
var line_col ;
var circle_col;
var markerimage_col;


var styles = [[ {
    url: 'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/images/heart50.png',
    width: 50,
    height: 44,
    opt_anchor: [12, 0],
    opt_textSize: 12
}]];
var markerClusterer = null;

function searchmapaction(action, is_page, url, param_data) {
	if(action == 'drag' || action == 'zoom' || action == 'center_changed') {
	 	bounds = (map.getBounds());
		var southWestLan = '';
		var northEastLng = '';
		$('#sighting_zoom_level').val(map.getZoom());
		var southWest = bounds.getSouthWest();
		var northEast = bounds.getNorthEast();
		$('#ne_latitude').val(Math.round(northEast.lat()*1000000)/1000000);
		$('#sw_latitude').val(Math.round(southWest.lat()*1000000)/1000000);
		if(isNaN(northEast.lng())){
			northEastLng  = '0';
		}else{
			northEastLng = northEast.lng();
		}
		$('#ne_longitude').val(Math.round(northEastLng*1000000)/1000000);

		if(isNaN(southWest.lng())){
			southWestLan  = '0';
		}else{
			southWestLan = southWest.lng();
		}
		$('#sw_longitude').val(Math.round(southWestLan*1000000)/1000000);
		$('#sighting_latitude').val(0);
		$('#sighting_longitude').val(0);
	} 
	fetchMarker(url, is_page);
	//fetchRequestMarker();
	if(action != 'filter') { 
		if(action == 'drag' || action == 'zoom'){
			map_filter_remove_active();
		}
		updateProductlist('sightings', "", is_page);
	} else {
		updateProductlist('filter', param_data, is_page);
	}
}

function map_filter_remove_active() {
	$('.js-index-search-rating-filter').each(function() {
		if($('.js-index-search-rating-filter').parent('li').hasClass('active')){
			$('.js-index-search-rating-filter').parent('li').removeClass('active');
		}
	});
	return true;
}
function loadMap() {
	geocoder = new google.maps.Geocoder();
	if(document.getElementById('js-map-search-container')){
		lat = $('#sighting_latitude').val();
		lng = $('#sighting_longitude').val();
		zoom_level = parseInt($('#sighting_zoom_level').val());
			if(lat ==''){
				lat = 0;
				$('#sighting_latitude').val(lat);
			}
			lng = $('#sighting_longitude').val();
			if(lng ==''){
				lng = 0;
				$('#sighting_longitude').val(lng);
			}
		latlng = new google.maps.LatLng(lat, lng);
		var myOptions = {
			zoom: zoom_level,
			center: latlng,
			panControl: true,
			panControlOptions: {
				position: google.maps.ControlPosition.TOP_RIGHT
			},
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.TOP_RIGHT
            },
			draggable: true,
			disableDefaultUI:true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById('js-map-search-container'), myOptions);
		
		//infowindow = new google.maps.InfoWindow();
		initMap();

	}
}
function geocodePosition(position) {
    geocoder.geocode( {
        latLng: position
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            $('#SightingLatitude').val(marker.getPosition().lat());
            $('#SightingLongitude').val(marker.getPosition().lng());
        }
    });
}
if (tout && 1) window._tdump = tout;
var info_window;
var xhr;
function fetchMarker(url, is_page) {
	var named_param_data='';
	if(__cfg('param'))
	{
		if(is_page != undefined)
			named_param_data = __cfg('params');
		else
			named_param_data = __cfg('param');
	}
	if (markersArray) {
		for (var i = 0; i < markersArray.length; i ++ ) {
			markersArray[i].setMap(null);
		}
		markersArray.length = 0;
	}
	if(url) {
		map_url = url+named_param_data;
	} else {
		map_url = __cfg('path_relative') + 'sightings/index/type:search/view:json'+named_param_data;
	}
	if(xhr != undefined){
		xhr.abort();
	}
    xhr = $.ajax( {
        type: 'POST',
        url: map_url,
        data: $('.js-search-map').serialize(),
//	   data: "&sighting_latitude="+ $('#sighting_latitude').val()+"&sighting_longitude="+ $('#sighting_longitude').val()+"&sighting_zoom_level="+ $('#sighting_zoom_level').val()+"&q="+ $('#sightingQ').val(),
        dataType: 'json',
        cache: false,
        success: function(responses) {
			for (var i = 0; i < responses.length; i ++ ) {
				sighting_id = (responses[i].Sighting.id);
				lat = (responses[i].Place.latitude);
				lnt = (responses[i].Place.longitude);
				place_name = (responses[i].Place.name);
				slug = (responses[i].Item.slug);
				sighting_title = (responses[i].Item.name);
				address = (responses[i].Place.address2);
				var content = '<div style="clear:both;height:80px;"><img style="float:left;border:1px solid #000000;margin-right:4px;" width="48" height="48" src="' + __cfg('path_relative') + (responses[i].Sighting.medium_thumb) + '" align="top" alt="'+sighting_title+'" /><span style="font-size:12px;font-weight:bold;">'+sighting_title+' @ '+place_name+'</span><br/><span style="font-size:12px;">'+address+'</span><br/><a onclick="scroll_to_sighting('+sighting_id+')"; href="javascript:void()" a="">More Details</a></div>';
				markerimage = new google.maps.MarkerImage(__cfg('path_relative') + (responses[i].Sighting.small_thumb),
							  // This marker is 20 pixels wide by 32 pixels tall.
							  new google.maps.Size(40, 40),
							  // The origin for this image is 0,0.
							  new google.maps.Point(0,0),
							  // The anchor for this image is the base of the flagpole at 0,32.
							  new google.maps.Point(20, 52));

				updateMarker(lat, lnt, slug, sighting_title, markerimage, i, content,  'sightings');
		   }
        }
    });
}
function addOrRemoveZoomEvent(action){
	if(action == 'add') {
		zoomListner = google.maps.event.addListener(map, 'zoom_changed', function() {
			$('#address').val('Map Area');
			searchmapaction('zoom', "no_need_page");
		});
	} else{
		google.maps.event.removeListener(zoomListner);
	}
}
function scroll_to_sighting(id) {
	$.scrollTo('li.js-slide-'+id,1500);
	return true;
}
function getCurrentLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			if(position.address != null){
				var location = [position.address.city, position.address.region];
				$("input#address").val(location);
			}
			else{
				var geo = $.cookie('_geo').split('|');
				var location = [geo[2], geo[1]];
				$("input#address").val(location);				
			}
			$("ul.js-location-search-value").hide();
			$('#js-sighting-search-submit').submit();
		}, function(error) {
			if($.cookie('_geo') != null){
				var geo = $.cookie('_geo').split('|');
				var location = [geo[2], geo[1]];
				$("input#address").val(location);
				$("ul.js-location-search-value").hide();
				$('#js-sighting-search-submit').submit();
			} else {
				alert("We are unable to fetch your current location.");
				$("ul.js-location-search-value").hide();
			}
		}, {
			enableHighAccuracy: true,
			timeout: 250000
		});
	}else{
		alert('no geolocation support');
	}
}
function updateMarker(lat, lnt, slug, item_title, markerimage, i, content, curr_cont){
	var shadow = new google.maps.MarkerImage(__cfg('path_relative') + 'img/map-shadow.png');

	if (lat != null) {
		myLatLng = new google.maps.LatLng(lat, lnt);
		eval('var marker' + i + ' = new google.maps.Marker({ position: myLatLng,  map: map, icon: markerimage, shadow: shadow, zIndex: i});');
		google.maps.event.addListener(eval("marker"+i), 'click', function() {
			if (info_window) info_window.close();
			eval("var infowindow"+i+" = new google.maps.InfoWindow({content:content});");
			eval("infowindow"+i).open(map, eval("marker"+i));
			info_window = eval("infowindow"+i);
        });
		markersArray.push(eval('marker' + i));
	}
}
var xcr_up;
function updateProductlist(cont, param_data, is_page) {
	var url = '';
	if(cont == 'sightings'){
		var named_param_data='';
		if(__cfg('param')){
			if(is_page != undefined){
				named_param_data = __cfg('params');
			}
			else
				named_param_data = __cfg('param');
		}
		url = "type:search"+named_param_data;
		load_class = '.js-search-responses';
		load_class_block = '.js-search-responses:first';
	} else if(cont == 'filter'){
		var named_param_data='';
		if(__cfg('param'))
		{
			if(is_page != undefined){
				named_param_data = __cfg('params');
			}
			else
				named_param_data = __cfg('param');
		}
		url = param_data+"/type:search"+named_param_data;
		load_class = 'div.js-ratings-filter-responses';
		load_class_block = 'div.js-ratings-filter-responses:first';
	}
	if(xcr_up != undefined){
		xcr_up.abort();
	}	
   xcr_up = $.ajax( {
        type: 'POST',
        url: __cfg('path_relative') + 'sightings/index/'+url,
        data: $('.js-search-map').serialize(),
		cache: false,
        beforeSend: function() {
            $(load_class_block).block();
        },
        success: function(responses) {
            $(load_class).html(responses);
            $(load_class_block).unblock();
			$('a.js-ajax-colorbox-flag').fcolorbox();
        }
    });
}
