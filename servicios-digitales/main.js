/*
*  Autofill Function
*/
var servdig_yourInfo_autofill = "Nombre del servicio ...";
var servdig_URL_autofill = "URL del servicio ...";
var servdig_Latitud_autofill = "Latitud para geolocacion ...";
var servdig_Longitud_autofill = "Longitud para geolocacion ...";
var servdig_image_url_autofill = "URL de la imagen a mostrar ...";
var servdig_Correo_autofill = "Direccion de correo electronico de contacto ...";
var servdig_Telefono_autofill = "Telefono de contacto ...";
var servdig_description_autofill = "Breve descripcion (800 caracteres o menos) ...";
var servdig_cat_description_autofill = "Por favor ingrese una breve descripcion de la categoria (800 caracteres o menos)...";
var servdig_companyInfo_autofill = "Nombre de la organizacion...";
var servdig_geoInfo_autofill = "Direccion del servicio o de la organizacion...";
var servdig_categoryInfo_autofill = "El nombre de la categoria...";

function servdig_clearAutoFill(id,type) {
	if(id == null || id == "" || type == null || type == "")
		return;
	var input = document.getElementById(id);
	if(input != null && type != null)
		switch(type) {
			case "YourInfo": if(input.value == servdig_yourInfo_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "URL": if(input.value == servdig_URL_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "Latitud": if(input.value == servdig_Latitud_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "Longitud": if(input.value == servdig_Longitud_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "image_url": if(input.value == servdig_image_url_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "Correo": if(input.value == servdig_Correo_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "Telefono": if(input.value == servdig_Telefono_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "Description": if(input.value == servdig_description_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "Cat_Description": if(input.value == servdig_cat_description_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "CompanyInfo": if(input.value == servdig_companyInfo_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "GeoInfo": if(input.value == servdig_geoInfo_autofill) input.value = ""; input.style.color = "#000000"; break;
			case "CategoryInfo": if(input.value == servdig_categoryInfo_autofill) input.value = ""; input.style.color = "#000000"; break;
		}
}

function servdig_clearAllAutoFill() {
	//Clear text fields
	var all_inputs = document.getElementsByTagName('input');
	for(var i=0;i<all_inputs.length;i++) {
		var input = all_inputs[i];
		switch(input.getAttribute('servdig_autofill')) {
			case 'YourInfo': 
				if(input.value == servdig_yourInfo_autofill || input.value == "" || input.value == null) {
					input.value = "";
					input.style.color = "#000000"; 
				}
			break;
			case 'URL': 
				if(input.value == servdig_URL_autofill || input.value == "" || input.value == null) {
					input.value = "";
					input.style.color = "#000000"; 
				}
			break;
			case 'Latitud': 
				if(input.value == servdig_Latitud_autofill || input.value == "" || input.value == null) {
					input.value = "";
					input.style.color = "#000000"; 
				}
			break;
			case 'Longitud': 
				if(input.value == servdig_Longitud_autofill || input.value == "" || input.value == null) {
					input.value = "";
					input.style.color = "#000000"; 
				}
			break;
			case 'image_url': 
				if(input.value == servdig_image_url_autofill || input.value == "" || input.value == null) {
					input.value = "";
					input.style.color = "#000000"; 
				}
			break;
			case 'Correo': 
				if(input.value == servdig_Correo_autofill || input.value == "" || input.value == null) {
					input.value = "";
					input.style.color = "#000000"; 
				}
			break;
			case 'Telefono': 
				if(input.value == servdig_Telefono_autofill || input.value == "" || input.value == null) {
					input.value = "";
					input.style.color = "#000000"; 
				}
			break;
			case 'CompanyInfo':
				if(input.value == servdig_companyInfo_autofill || input.value == "" || input.value == null) {
					input.value = "";
					input.style.color = "#000000";
				}
			break;
			case 'GeoInfo':
				if(input.value == servdig_geoInfo_autofill || input.value == "" || input.value == null) {
					input.value = "";
					input.style.color = "#000000";
				}
			break;
            case 'CategoryInfo':
                if(input.value == servdig_categoryInfo_autofill || input.value == "" || input.value == null) {
					input.value = "";
					input.style.color = "#000000";
				}
            break;
		}
	}
	var all_textareas = document.getElementsByTagName('textarea');
	for(var i=0;i<all_textareas.length;i++) {
		var input = all_textareas[i];
		if(
		   	input.getAttribute('servdig_autofill') == 'Description' && 
			(input.value == "" || input.value == null || input.value == servdig_description_autofill)
		) {
			input.style.color = '#000000';
			input.value = "";
		} else if(
		   	input.getAttribute('servdig_autofill') == 'Cat_Description' && 
			(input.value == "" || input.value == null || input.value == servdig_cat_description_autofill)
		) {
			input.style.color = '#000000';
			input.value = "";
		}
	}
}

function servdig_populateAutofill() {
	var override = arguments[0] == true;
	var all_inputs = document.getElementsByTagName('input');
	for(var i=0;i<all_inputs.length;i++) {
		var input = all_inputs[i];
		switch(input.getAttribute('servdig_autofill')) {
			case 'YourInfo': 
				if(override || input.value == servdig_yourInfo_autofill || input.value == "" || input.value == null) {
					input.value = servdig_yourInfo_autofill;
					input.style.color = "#999999"; 
				}
			break;
			case 'URL': 
				if(override || input.value == servdig_URL_autofill || input.value == "" || input.value == null) {
					input.value = servdig_URL_autofill;
					input.style.color = "#999999"; 
				}
			break;
			case 'Latitud': 
				if(override || input.value == servdig_Latitud_autofill || input.value == "" || input.value == null) {
					input.value = servdig_Latitud_autofill;
					input.style.color = "#999999"; 
				}
			break;
			case 'Longitud': 
				if(override || input.value == servdig_Longitud_autofill || input.value == "" || input.value == null) {
					input.value = servdig_Longitud_autofill;
					input.style.color = "#999999"; 
				}
			break;
			case 'image_url': 
				if(override || input.value == servdig_image_url_autofill || input.value == "" || input.value == null) {
					input.value = servdig_image_url_autofill;
					input.style.color = "#999999"; 
				}
			break;
			case 'Correo': 
				if(override || input.value == servdig_Correo_autofill || input.value == "" || input.value == null) {
					input.value = servdig_Correo_autofill;
					input.style.color = "#999999"; 
				}
			break;
			case 'Telefono': 
				if(override || input.value == servdig_Telefono_autofill || input.value == "" || input.value == null) {
					input.value = servdig_Telefono_autofill;
					input.style.color = "#999999"; 
				}
			break;
			case 'Keywords': 
				if(override || input.value == servdig_keywords_autofill || input.value == "" || input.value == null) {
					input.value = servdig_keywords_autofill;
					input.style.color = "#999999"; 
				}
			break;
			case 'CompanyInfo':
				if(override || input.value == servdig_companyInfo_autofill || input.value == "" || input.value == null) {
					input.value = servdig_companyInfo_autofill;
					input.style.color = "#999999";
				}
			break;
			case 'GeoInfo':
				if(override || input.value == servdig_geoInfo_autofill || input.value == "" || input.value == null) {
					input.value = servdig_geoInfo_autofill;
					input.style.color = "#999999";
				}
			break;
            case 'CategoryInfo':
                if(override || input.value == servdig_categoryInfo_autofill || input.value == "" || input.value == null) {
					input.value = servdig_categoryInfo_autofill;
					input.style.color = "#999999";
				}
            break;
		}
	}
	var all_textareas = document.getElementsByTagName('textarea');
	for(var i=0;i<all_textareas.length;i++) {
		var input = all_textareas[i];
		if(
		   	input.getAttribute('servdig_autofill') == 'Description' && 
			(input.value == "" || input.value == null || input.value == servdig_description_autofill || override)
		) {
			input.style.color = '#999999';
			input.value = servdig_description_autofill;
		} else if(
		   	input.getAttribute('servdig_autofill') == 'Cat_Description' && 
			(input.value == "" || input.value == null || input.value == servdig_cat_description_autofill || override)
		) {
			input.style.color = '#999999';
			input.value = servdig_cat_description_autofill;
		}
	}
}
/*
* ON LOAD
*/
function servdig_onload() {
	servdig_populateAutofill();
	var servdig_submit = document.getElementById("servdig_submit")
	if(servdig_submit != null)
		servdig_submit.disabled = false;
}

window.onload = servdig_onload;
/*
*  HELPER FUNCTIONS
*/
function clearMessage() {
	var messages = document.getElementById('servdig_messages');
	if(messages != null) {
		messages.className = '';
		messages.innerHTML = '';
	}
}

function image_url_sinc(){
    add_image_url = '';
    add_image_url = image_url_collection;
    view_image_url = "<img src=\"" + add_image_url + "\" width=\"200px\" />";
       
    if (add_image_url == '') add_image_url = 'No hay una imagen seleccionada';
    field = '';
    field = jQuery("#image_field").val();
    
    url_display_id = '#' + field + '_url_display';
    image_display_id = '#' + field + '_selected_image';
    
    jQuery(url_display_id).html(add_image_url);
    jQuery('#' + field).val(add_image_url);
    jQuery(image_display_id).html(view_image_url);
    jQuery("#image_field").val('');
    
}
function image_url_agregar(){
    image_url = edCanvas.value.match(/img src=\"(.*?)\"/g)[0].split(/img src=\"(.*?)\"/g)[1];
    image_url = image_url.replace(/-[0-9][0-9][0-9]x[0-9][0-9][0-9]\./i,'.');
    image_url_collection = image_url;
    edCanvas.value = '';
    image_url_sinc();
}
function image_photo_url_agregar($field){
    jQuery("#image_field").val($field);
}
function cargaMapaAltaServicio() {
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
						var lonlat = mapaAltaServicios.getLonLatFromViewPortPx(e.xy);
						document.getElementById('servdig_latitud').value = lonlat.lat;
						document.getElementById('servdig_longitud').value = lonlat.lon;
						var layerABorrar = mapaAltaServicios.getLayersByName("Servicio");
						if(layerABorrar.length > 0){
							mapaAltaServicios.removeLayer(layerABorrar[0]);
						}
						var markerServicio = new OpenLayers.Layer.Markers( "Servicio" );
						var icon = new OpenLayers.Icon("/wp-content/themes/rcd/images/marker-mapa-rosario-al-toque.png");
						markerServicio.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(lonlat.lon,lonlat.lat),icon));
						mapaAltaServicios.addLayer(markerServicio);
					}
				});
				var options = {
					numZoomLevels: 25
				};
/*				var mapaAltaServicios = new OpenLayers.Map("mapa-alta-servicios", options);
				var wms_rosario = new OpenLayers.Layer.WMS(
					"WMS-Rosario",
					"http://www.rosario.gov.ar/wms/planobase",
					{layers: "WMS-Rosario"}
				);
				mapaAltaServicios.addLayer(wms_rosario);
				var osm = new OpenLayers.Layer.OSM();
				var gmap = new OpenLayers.Layer.Google("Google Streets", {visibility: true});*/
//				mapaAltaServicios.addLayers([osm, gmap]);
				var mapaAltaServicios = getMapaRosarioAlToque("mapa-alta-servicios");
				var click = new OpenLayers.Control.Click();
				mapaAltaServicios.addControl(click);
				click.activate();
				if (document.getElementById('servdig_latitud') && document.getElementById('servdig_longitud') && document.getElementById('servdig_latitud').value * 1 != 0 && document.getElementById('servdig_latitud').value != '' && document.getElementById('servdig_longitud').value * 1 != 0 && document.getElementById('servdig_longitud').value != '') {
					var markerServicio = new OpenLayers.Layer.Markers( "Servicio" );
					var icon = new OpenLayers.Icon("/wp-content/themes/rcd/images/marker-mapa-rosario-al-toque.png");
					markerServicio.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(document.getElementById('servdig_longitud').value,document.getElementById('servdig_latitud').value),icon));
					mapaAltaServicios.addLayer(markerServicio);
					mapaAltaServicios.setCenter(new OpenLayers.LonLat(document.getElementById('servdig_longitud').value, document.getElementById('servdig_latitud').value), 17);
				} else {
					mapaAltaServicios.setCenter(new OpenLayers.LonLat(-60.665817, -32.949911), 14);
				}
//				mapaAltaServicios.addControl(new OpenLayers.Control.LayerSwitcher());
}