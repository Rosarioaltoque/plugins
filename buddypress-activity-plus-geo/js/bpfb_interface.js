var _bpfbActiveHandler = false;

(function($){
$(function() {

var $form;
var $text;
var $textContainer;


/**
 * Video insertion/preview handler.
 */
var BpfbVideoHandler = function () {
	$container = $(".bpfb_controls_container");
	
	var createMarkup = function () {
		var html = '<input type="text" id="bpfb_video_url" name="bpfb_video_url" value="' + l10nBpfb.paste_video_url + '" />' +
			'<input type="button" id="bpfb_video_url_preview" value="' + l10nBpfb.preview + '" />';
		$container.empty().append(html);
		
		$('#bpfb_video_url').width($container.width());
		$('#bpfb_video_url').focus(function () { 
			$(this)
				.select()
				.addClass('changed')
			;
		});
		
		$('#bpfb_video_url').change(createVideoPreview);
		$('#bpfb_video_url_preview').click(createVideoPreview);
	};
	
	var createVideoPreview = function () {
		var url = $('#bpfb_video_url').val();
		if (!url) return false;
		$.post(ajaxurl, {"action":"bpfb_preview_video", "data":url}, function (data) {
			$('.bpfb_preview_container').empty().html(data);
			$('.bpfb_action_container').html(
				'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_video + '" /> ' +
				'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
			);
			$("#bpfb_cancel_action").hide();
		});
	};
	
	var processForSave = function () {
		return {
			"bpfb_video_url": $("#bpfb_video_url").val()
		};
	};
	
	var init = function () {
		$('#aw-whats-new-submit').hide();
		createMarkup();
	};
	
	var destroy = function () {
		$container.empty();
		$('.bpfb_preview_container').empty();
		$('.bpfb_action_container').empty();
		$('#aw-whats-new-submit').show();
	};
	
	init ();
	
	return {"destroy": destroy, "get": processForSave};
};

/**
 * Geo insertion/preview handler.
 */
var BpfbGeoHandler = function () {
	$container = $(".bpfb_controls_container");
	
	var createMarkup = function () {
/*		var html = '<input type="text" id="bpfb_geo_url" name="bpfb_geo_url" value="' + l10nBpfb.paste_geo_url + '" />' +
			'<input type="button" id="bpfb_geo_url_preview" value="' + l10nBpfb.preview + '" />';*/
		var html = '<div id="mapa-alta-actividad" class="mapa-alta-actividad"></div>'
		+ '<script>cargaMapaAltaActividad();</script>'
		+ '<input type=hidden name="alta_actividad_latitud" id="alta_actividad_latitud">'
		+ '<input type=hidden name="alta_actividad_latitud" id="alta_actividad_longitud">'
		+ '<input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_geo + '" /> '
		;
		$container.empty().append(html);
		
		$('#bpfb_geo_url').width($container.width());
		$('#bpfb_geo_url').focus(function () { 
			$(this)
				.select()
				.addClass('changed')
			;
		});
		
		$('#bpfb_geo_url').change(createGeoPreview);
		$('#bpfb_geo_url_preview').click(createGeoPreview);
	};
	
	var createGeoPreview = function () {
		var url = $('#bpfb_geo_url').val();
		if (!url) return false;
		$.post(ajaxurl, {"action":"bpfb_preview_geo", "data":url}, function (data) {
			$('.bpfb_preview_container').empty().html(data);
			$('.bpfb_action_container').html(
				'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_geo + '" /> ' +
				'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
			);
			$("#bpfb_cancel_action").hide();
		});
	};
	
	var processForSave = function () {
		return {
			"alta_actividad_latitud": $("#alta_actividad_latitud").val(),
			"alta_actividad_longitud": $("#alta_actividad_longitud").val()
		};
	};
	
	var init = function () {
		$('#aw-whats-new-submit').hide();
		createMarkup();
	};
	
	var destroy = function () {
		$container.empty();
		$('.bpfb_preview_container').empty();
		$('.bpfb_action_container').empty();
		$('#aw-whats-new-submit').show();
	};
	
	init ();
	
	return {"destroy": destroy, "get": processForSave};
};


/**
 * Link insertion/preview handler.
 */
var BpfbLinkHandler = function () {
	$container = $(".bpfb_controls_container");
	
	var createMarkup = function () {
		var html = '<input type="text" id="bpfb_link_preview_url" name="bpfb_link_preview_url" value="' + l10nBpfb.paste_link_url + '" />' +
			'<input type="button" id="bpfb_link_url_preview" value="' + l10nBpfb.preview + '" />';
		$container.empty().append(html);
		
		$('#bpfb_link_preview_url').width($container.width());
		$('#bpfb_link_preview_url').focus(function () { 
			$(this)
				.select()
				.addClass('changed')
			;
		});
		
		$('#bpfb_link_preview_url').change(createLinkPreview);
		$('#bpfb_link_url_preview').click(createLinkPreview);
	};
	
	var createPreviewMarkup = function (data) {
		if (!data.url) {
			$('.bpfb_preview_container').empty().html(data.title);
			return false;
		}
		var imgs = '';
		$.each(data.images, function(idx, img) {
			var url = img.match(/^http/) ? img : data.url + '/' + img;
			imgs += '<img class="bpfb_link_preview_image" src="' + url + '" />';
		});
		var html = '<table border="0">' +
			'<tr>' +
				'<td>' +
					'<div class="bpfb_link_preview_container">' +
						imgs +
						'<input type="hidden" name="bpfb_link_img" value="" />' +
					'</div>' +
				'</td>' +
				'<td>' +
					'<div class="bpfb_link_preview_title">' + data.title + '</div>' +
					'<input type="hidden" name="bpfb_link_title" value="' + data.title + '" />' +
					'<div class="bpfb_link_preview_url">' + data.url + '</div>' +
					'<input type="hidden" name="bpfb_link_url" value="' + data.url + '" />' +
					'<div class="bpfb_link_preview_body">' + data.text + '</div>' +
					'<input type="hidden" name="bpfb_link_body" value="' + data.text + '" />' +
					'<div class="bpfb_thumbnail_chooser">' +
						'<img class="bpfb_thumbnail_chooser_left" src="' + _bpfbRootUrl + '/img/system/left.gif" />' +
						'&nbsp;' +
						'<img class="bpfb_thumbnail_chooser_right" src="' + _bpfbRootUrl + '/img/system/right.gif" />' +
						'&nbsp;' +
						l10nBpfb.choose_thumbnail + '<br />' +
						'<input type="checkbox" id="bpfb_link_no_thumbnail" /> <label for="bpfb_link_no_thumbnail">' + l10nBpfb.no_thumbnail + '</label>' +
					'</div>' +
				'</td>' +
			'</tr>' +
		'</table>';
		$('.bpfb_preview_container').empty().html(html);
		$('.bpfb_action_container').html(
			'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_link + '" /> ' +
			'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
		);
		$("#bpfb_cancel_action").hide();
		
		$('img.bpfb_link_preview_image').hide();
		$('img.bpfb_link_preview_image').first().show();
		$('input[name="bpfb_link_img"]').val($('img.bpfb_link_preview_image').first().attr('src'));
		
		$('.bpfb_thumbnail_chooser_left').click(function () {
			var $cur = $('img.bpfb_link_preview_image:visible');
			var $prev = $cur.prev('.bpfb_link_preview_image');
			if ($prev.length) {
				$cur.hide();
				$prev
					.width($('.bpfb_link_preview_container').width())
					.show();
				$('input[name="bpfb_link_img"]').val($prev.attr('src'));
			}
			return false;
		});
		$('.bpfb_thumbnail_chooser_right').click(function () {
			var $cur = $('img.bpfb_link_preview_image:visible');
			var $next = $cur.next('.bpfb_link_preview_image');
			if ($next.length) {
				$cur.hide();
				$next
					.width($('.bpfb_link_preview_container').width())
					.show();
				$('input[name="bpfb_link_img"]').val($next.attr('src'));
			}
			return false;
		});
		$("#bpfb_link_no_thumbnail").click(function () {
			if ($("#bpfb_link_no_thumbnail").is(":checked")) {
				$('img.bpfb_link_preview_image:visible').hide();
				$('input[name="bpfb_link_img"]').val('');
			} else {
				var $img = $('img.bpfb_link_preview_image:first');
				$img.show();
				$('input[name="bpfb_link_img"]').val($img.attr('src'));
			}
				
		});
	};
	
	var createLinkPreview = function () {
		var url = $('#bpfb_link_preview_url').val();
		if (!url) return false;
		$.post(ajaxurl, {"action":"bpfb_preview_link", "data":url}, function (data) {
			createPreviewMarkup(data);
		});
	};
	
	var processForSave = function () {
		return {
			"bpfb_link_url": $('input[name="bpfb_link_url"]').val(),
			"bpfb_link_image": $('input[name="bpfb_link_img"]').val(),
			"bpfb_link_title": $('input[name="bpfb_link_title"]').val(),
			"bpfb_link_body": $('input[name="bpfb_link_body"]').val()
		};
	};
	
	var init = function () {
		$('#aw-whats-new-submit').hide();
		createMarkup();
	};
	
	var destroy = function () {
		$container.empty();
		$('.bpfb_preview_container').empty();
		$('.bpfb_action_container').empty();
		$('#aw-whats-new-submit').show();
	};
	
	init ();
	
	return {"destroy": destroy, "get": processForSave};
};


/**
 * Photos insertion/preview handler.
 */
var BpfbPhotoHandler = function () {
	$container = $(".bpfb_controls_container");
	
	var createMarkup = function () {
		var html = '<div id="bpfb_tmp_photo"> </div>' +
			'<ul id="bpfb_tmp_photo_list"></ul>' + 
			'<input type="button" id="bpfb_add_remote_image" value="' + l10nBpfb.add_remote_image + '" /><div id="bpfb_remote_image_container"></div>' +
			'<input type="button" id="bpfb_remote_image_preview" value="' + l10nBpfb.preview + '" />';
		$container.append(html);
		
		var uploader = new qq.FileUploader({
			"element": $('#bpfb_tmp_photo')[0],
			"listElement": $('#bpfb_tmp_photo_list')[0],
			"allowedExtensions": ['jpg', 'jpeg', 'png', 'gif'],
			"action": ajaxurl,
			"params": {
				"action": "bpfb_preview_photo"
			},
			"onComplete": createPhotoPreview
		});
		
		$("#bpfb_remote_image_preview").hide();
		$("#bpfb_tmp_photo").click(function () {
			if ($("#bpfb_add_remote_image").is(":visible")) $("#bpfb_add_remote_image").hide();
		});
		$("#bpfb_add_remote_image").click(function () {
			if (!$("#bpfb_remote_image_preview").is(":visible")) $("#bpfb_remote_image_preview").show();
			if ($("#bpfb_tmp_photo").is(":visible")) $("#bpfb_tmp_photo").hide();
			$("#bpfb_add_remote_image").val(l10nBpfb.add_another_remote_image);
			$("#bpfb_remote_image_container").append(
				'<input type="text" class="bpfb_remote_image" size="64" value="" /><br />'
			);
			$("#bpfb_remote_image_container .bpfb_remote_image").width($container.width());
		});
		$("#bpfb_remote_image_container .bpfb_remote_image").live('change', createRemoteImagePreview);
		$("#bpfb_remote_image_preview").click(createRemoteImagePreview);
	};
	
	var createRemoteImagePreview = function () {
		var imgs = [];
		$("#bpfb_remote_image_container .bpfb_remote_image").each(function () {
			imgs[imgs.length] = $(this).val();
		});
		$.post(ajaxurl, {"action":"bpfb_preview_remote_image", "data":imgs}, function (data) {
			var html = '';
			$.each(data, function() {
				html += '<img class="bpfb_preview_photo_item" src="' + this + '" width="80px" />' +
				'<input type="hidden" class="bpfb_photos_to_add" name="bpfb_photos[]" value="' + this + '" />';;
			});
			$('.bpfb_preview_container').html(html);
		});
		$('.bpfb_action_container').html(
			'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_photos + '" /> ' +
			'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
		);
		$("#bpfb_cancel_action").hide();
	};
	
	var createPhotoPreview = function (id, fileName, resp) {
		if ("error" in resp) return false;
		var html = '<img class="bpfb_preview_photo_item" src="' + _bpfbTempImageUrl + resp.file + '" width="80px" />' +
			'<input type="hidden" class="bpfb_photos_to_add" name="bpfb_photos[]" value="' + resp.file + '" />';
		$('.bpfb_preview_container').append(html);
		$('.bpfb_action_container').html(
			'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_photos + '" /> ' +
			'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
		);
		$("#bpfb_cancel_action").hide();
	};
	
	var removeTempImages = function (rti_callback) {
		var $imgs = $('input.bpfb_photos_to_add');
		if (!$imgs.length) return rti_callback();
		$.post(ajaxurl, {"action":"bpfb_remove_temp_images", "data": $imgs.serialize().replace(/%5B%5D/g, '[]')}, function (data) {
			rti_callback();
		});
	};
	
	var processForSave = function () {
		var $imgs = $('input.bpfb_photos_to_add');
		var imgArr = [];
		$imgs.each(function () {
			imgArr[imgArr.length] = $(this).val();
		});
		return {
			"bpfb_photos": imgArr//$imgs.serialize().replace(/%5B%5D/g, '[]')
		};
	};
	
	var init = function () {
		$container.empty();
		$('.bpfb_preview_container').empty();
		$('.bpfb_action_container').empty();
		$('#aw-whats-new-submit').hide();
		createMarkup();
	};
	
	var destroy = function () {
		removeTempImages(function() {
			$container.empty(); 
			$('.bpfb_preview_container').empty(); 	
			$('.bpfb_action_container').empty();
			$('#aw-whats-new-submit').show();
		});
	};
	
	removeTempImages(init);
	
	return {"destroy": destroy, "get": processForSave};
};


/* === End handlers  === */


/**
 * Main interface markup creation.
 */
function createMarkup () {
	var html = '<div class="bpfb_actions_container">' +
		'<div class="bpfb_toolbar_container">' +
			'<a href="#photos" title="' + l10nBpfb.add_photos + '" id="bpfb_addPhotos"><img src="' + _bpfbRootUrl + '/img/system/camera.png" border="0" /></a>' +
			'&nbsp;' +
			'<a href="#videos" title="' + l10nBpfb.add_videos + '" id="bpfb_addVideos"><img src="' + _bpfbRootUrl + '/img/system/film.png" border="0" /></a>' +
			'&nbsp;' +
			'<a href="#links" title="' + l10nBpfb.add_links + '" id="bpfb_addLinks"><img src="' + _bpfbRootUrl + '/img/system/link.png" border="0" /></a>' +
			'&nbsp;' +
			'<a href="#geo" title="' + l10nBpfb.add_geo + '" id="bpfb_addGeo"><img src="' + _bpfbRootUrl + '/img/system/geo-location.png" border="0" /></a>' +
		'</div>' +
		'<div class="bpfb_controls_container">' +
		'</div>' +
		'<div class="bpfb_preview_container">' +
		'</div>' +
		'<div class="bpfb_action_container">' +
		'</div>' +
		'<input type="button" id="bpfb_cancel_action" value="' + l10nBpfb.cancel + '" style="display:none" />' +
	'</div>';
	$form.wrap('<div class="bpfb_form_container" />');
	$textContainer.after(html);
}


/**
 * Initializes the main interface.
 */
function init () {
	$form = $("#whats-new-form");
	$text = $form.find('textarea[name="whats-new"]');
	$textContainer = $form.find('#whats-new-textarea');
	createMarkup();
	$('#bpfb_addPhotos').click(function () {
		if (_bpfbActiveHandler) _bpfbActiveHandler.destroy();
		_bpfbActiveHandler = new BpfbPhotoHandler();
		$("#bpfb_cancel_action").show();
		return false;
	});
	$('#bpfb_addLinks').click(function () {
		if (_bpfbActiveHandler) _bpfbActiveHandler.destroy();
		_bpfbActiveHandler = new BpfbLinkHandler();
		$("#bpfb_cancel_action").show();
		return false;
	});
	$('#bpfb_addVideos').click(function () {
		if (_bpfbActiveHandler) _bpfbActiveHandler.destroy();
		_bpfbActiveHandler = new BpfbVideoHandler();
		$("#bpfb_cancel_action").show();
		return false;
	});
	$('#bpfb_addGeo').click(function () {
		if (_bpfbActiveHandler) _bpfbActiveHandler.destroy();
		_bpfbActiveHandler = new BpfbGeoHandler();
		$("#bpfb_cancel_action").show();
		return false;
	});
	$('#bpfb_cancel_action').click(function () {
		_bpfbActiveHandler.destroy();
		$("#bpfb_cancel_action").hide();
		return false;
	});
	$('#bpfb_submit').live('click', function () {
		var params = _bpfbActiveHandler.get();
		var group_id = $('#whats-new-post-in').length ? $('#whats-new-post-in').val() : 0;
		$.post(ajaxurl, {
			"action": "bpfb_update_activity_contents", 
			"data": params, 
			"content": $text.val(), 
			"group_id": group_id
		}, function (data) {
			_bpfbActiveHandler.destroy();
			$text.val('');
			$('#activity-stream').prepend(data.activity);
			/**
			 * Handle image scaling in previews.
			 */
			$(".bpfb_final_link img").each(function () {
				$(this).width($(this).parents('div').width());
			});
		});
	});
	$('#bpfb_cancel').live('click', function () {
		_bpfbActiveHandler.destroy();
	});
}


// Only initialize if we're supposed to.
if ($("#whats-new-form").is(":visible")) init();


/**
 * Handle image scaling in previews.
 */
$(".bpfb_final_link img").each(function () {
	$(this).width($(this).parents('div').width());
});

});
})(jQuery);

function cargaMapaAltaActividad() {
				OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {
					defaultHandlerOptions: {
						'single': true,
						'double': false,
						'pixelTolerance': 0,
						'stopSingle': false,
						'stopDouble': false
					},
					initialize: function(options) {
						this.handlerOptions = OpenLayers.Util.extend(
							{}, this.defaultHandlerOptions
						);
						OpenLayers.Control.prototype.initialize.apply(
							this, arguments
						);
						this.handler = new OpenLayers.Handler.Click(
							this, {
								'click': this.trigger
							}, this.handlerOptions
						);
					},
					trigger: function(e) {
						var lonlat = mapaAltaActividad.getLonLatFromViewPortPx(e.xy);
						document.getElementById('alta_actividad_latitud').value = lonlat.lat;
						document.getElementById('alta_actividad_longitud').value = lonlat.lon;
						var layerABorrar = mapaAltaActividad.getLayersByName("Actividad");
						if(layerABorrar.length > 0){
							mapaAltaActividad.removeLayer(layerABorrar[0]);
						}
						var markerActividad = new OpenLayers.Layer.Markers( "Actividad" );
						var icon = new OpenLayers.Icon("/wp-content/themes/rcd/images/marker-mapa-rosario-al-toque.png");
						markerActividad.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(lonlat.lon,lonlat.lat),icon));
						mapaAltaActividad.addLayer(markerActividad);
					}
				});
				var options = {
					numZoomLevels: 25
				};
				var mapaAltaActividad = new OpenLayers.Map("mapa-alta-actividad", options);
				var wms_rosario = new OpenLayers.Layer.WMS(
					"WMS-Rosario",
					"http://www.rosario.gov.ar/wms/planobase",
					{layers: "WMS-Rosario"}
				);
				mapaAltaActividad.addLayer(wms_rosario);
				var osm = new OpenLayers.Layer.OSM();
				var gmap = new OpenLayers.Layer.Google("Google Streets", {visibility: true});
//				mapaAltaActividad.addLayers([osm, gmap]);
				if (document.getElementById('alta_actividad_latitud') && document.getElementById('alta_actividad_longitud') && document.getElementById('alta_actividad_latitud').value * 1 != 0 && document.getElementById('alta_actividad_latitud').value != '' && document.getElementById('alta_actividad_longitud').value * 1 != 0 && document.getElementById('alta_actividad_longitud').value != '') {
					mapaAltaActividad.setCenter(new OpenLayers.LonLat(document.getElementById('alta_actividad_longitud').value, document.getElementById('alta_actividad_latitud').value), 17);
					var markerActividad = new OpenLayers.Layer.Markers( "Actividad" );
					var icon = new OpenLayers.Icon("/wp-content/themes/rcd/images/puntero-mapa-rosario-al-toque.jpg");
					markerActividad.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(document.getElementById('alta_actividad_longitud').value,document.getElementById('alta_actividad_latitud').value),icon));
					mapaAltaActividad.addLayer(markerActividad);
				} else {
					mapaAltaActividad.setCenter(new OpenLayers.LonLat(-60.665817, -32.949911), 14);
				}
				mapaAltaActividad.addControl(new OpenLayers.Control.LayerSwitcher());
				var click = new OpenLayers.Control.Click();
				mapaAltaActividad.addControl(click);
				click.activate();
}