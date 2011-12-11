<?php
/*
Plugin Name: Servicios Digitales
Plugin URI: http://pampacomcdt.com.ar
Description: Plugin diseñado especificamente para el mantenimiento y mostrado de Servicios Digitales en el sitio Rosario al toque.
Version: 0.8.6.1 Beta
Author: Pampacom CDT
Author URI: http://pampacomcdt.com.ar
*/

require_once(dirname(__FILE__)."/config.php"); //Load Biz-Directory Config File
/*
* Add functions
*/
//Add Actions
add_action('wp_head','servdig_js_header'); //Add Listing Form Header Ajax Call
add_action('admin_menu','servdig_navigation'); //Add Directory Tab in the menu
add_action('admin_print_scripts','servdig_js_admin_header'); //Add Ajax to the admin side
add_action('wp_ajax_servdig_edit_listing','servdig_edit_listing' );
add_action('wp_ajax_servdig_update_listing','servdig_update_listing' );
add_action('wp_ajax_servdig_show_manager_home','servdig_show_manager_home' );
add_action('wp_ajax_servdig_change_listing_status','servdig_change_listing_status' );
add_action('wp_ajax_servdig_delete_listing','servdig_delete_listing' );
add_action('wp_ajax_servdig_add_category', 'servdig_add_category' );
add_action('wp_ajax_servdig_edit_category', 'servdig_edit_category');
add_action('wp_ajax_servdig_show_category_home', 'servdig_show_category_home');
add_action('wp_ajax_servdig_update_category', 'servdig_update_category');
add_action('wp_ajax_servdig_delete_category', 'servdig_delete_category');
//Add Short Code
add_shortcode("servdig_addform","servdig_addform_shortcode"); //Add ShortCode for "Add Form"
add_shortcode("servdig_directory","servdig_directory_shortcode"); //Add ShortCode for "Directory"
//Register Hooks
register_activation_hook(__FILE__,'servdig_install');
//Add Javascript
wp_enqueue_script('jquery');
wp_register_style('thickbox-css', '/wp-includes/js/thickbox/thickbox.css');
wp_enqueue_style('thickbox-css');
wp_enqueue_script('thickbox');
wp_enqueue_script('media-upload');
wp_enqueue_script('quicktags');
wp_register_script('sd_dir_main_js',SDDIRCALLBACK.'/main.js');
wp_enqueue_script("sd_dir_main_js"); // ???
wp_enqueue_script('OpenLayers', get_template_directory_uri().'/js/OpenLayers.js');

/*
*  Set admin Messages
*/
$servdig_categories = $wpdb->get_results("SELECT * FROM ".SDDIRCATTABLE.";");
if(empty($servdig_categories) || !is_array($servdig_categories) || count($servdig_categories) < 1)
	add_action('admin_notices','servdig_warning_nocat');
elseif(count($servdig_categories) == 1 && $servdig_categories[0]->category == "General" && $servdig_categories[0]->description == "")
	add_action('admin_notices','servdig_warning_defaultcat');
//Warning functions
function servdig_warning_nocat() {
	echo 
		"<div id='servdig_warning' class='updated fade'>".
			"Debe añadir al menos una categoria antes que los usuarios den de alta Servicios.".
		"</div>"
	;
}
function servdig_warning_defaultcat() {
	echo 
		"<div id='servdig_warning' class='updated fade'>".
			"No se han configurado las categorias para los Servicios Digitales.".
		"</div>"
	;
}
/*
*  Insatllation Script
*/
function servdig_install() {
	global $wpdb;
	global $servdig_version_var;
	$sql = "";
	$cur_version = get_option("servdig_version");
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	//The Listings table is where all the user imputed data is stored
	if($wpdb->get_var("show tables like '".SDDIRDBTABLE."'") != SDDIRDBTABLE) {
		$wpdb->query( 
			"CREATE TABLE ".SDDIRDBTABLE." (".
				"listing_id int(11) NOT NULL AUTO_INCREMENT,".
				"category_id int(11) NOT NULL DEFAULT '1',".
				"date_created datetime NULL DEFAULT NULL,".
				"status tinyint(1) DEFAULT '0' NOT NULL,".
				"name varchar(100) NULL DEFAULT NULL,".
				"email varchar(100) NULL DEFAULT NULL,".
				"company_name varchar(100) NULL DEFAULT NULL,".
				"company_description text NULL DEFAULT NULL,".
				"company_url varchar(100) NULL DEFAULT NULL,".
				"company_phone varchar(100) NULL DEFAULT NULL,".
				"company_street1 varchar(100) NULL DEFAULT NULL,".
				"image_url varchar(255) NOT NULL,".
				"latitud decimal(16, 12) NOT NULL,".
				"longitud decimal(16, 12) NOT NULL,".
				"cantidad_uso int unsigned NOT NULL,".
				"fecha_ultimo_uso datetime NULL,".
				"PRIMARY KEY (listing_id)".
			");"
		);
	} elseif(empty($cur_version) || $cur_version < "0.8.2 Beta") //If we are working with a previous install, we need to alter the existing table
		$wpdb->query("ALTER TABLE ".SDDIRDBTABLE." ADD COLUMN category_id int(11) NOT NULL DEFAULT '1' AFTER listing_id;");
	//The Categories table stores the categories
	if($wpdb->get_var("show tables like '".SDDIRCATTABLE."'") != SDDIRCATTABLE) {
		$wpdb->query( 
			"CREATE TABLE ".SDDIRCATTABLE." (".
				"category_id int(11) NOT NULL AUTO_INCREMENT,".
				"category varchar(100) NOT NULL,".
				"description text NULL DEFAULT NULL,".
				"hide tinyint(1) NOT NULL DEFAULT '0',".
				"PRIMARY KEY (category_id)".
			");"
		);
		$wpdb->query("INSERT INTO ".SDDIRCATTABLE." (category,hide) VALUES ('General',0);");
	}
	//Update Version
	if(!add_option("servdig_version",$servdig_version_var));
		update_option("servdig_version",$servdig_version_var); 
}
/*
*  Set Header for Ajax calls
*/
function servdig_js_header() {
	wp_print_scripts(array('sack'));//Include Ajax SACK library  
	?>
		<script>
			function servdig_add_listing(name,email,cName,description,website,phone) { //Add Form Ajax Call
				//Deactivate submit button and display processing message
				document.getElementById('servdig_submit').disabled = true;
				var submit_message = document.getElementById('servdig_submit_message');
				submit_message.className = "servdig_message";
				submit_message.innerHTML = "Enviando datos, por favor espere...";
				//Clear inputs with Auto Text
				servdig_clearAllAutoFill();
				//Build SACK Call
				var mysack = new sack("<?php echo SDDIRCALLBACK; ?>requests.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action","AddListing");
				mysack.setVar("category_id", document.getElementById("servdig_category_id").value);
				mysack.setVar("name",document.getElementById("servdig_name").value);
				mysack.setVar("email",document.getElementById("servdig_email").value);
				mysack.setVar("cName",document.getElementById("servdig_cName").value);
				mysack.setVar("description",document.getElementById("servdig_description").value);
				mysack.setVar("website",document.getElementById("servdig_website").value);
				mysack.setVar("phone",document.getElementById("servdig_phone").value);
				mysack.setVar("street1",document.getElementById("servdig_street1").value);
//				mysack.setVar("image_url",document.getElementById("servdig_image_url_url_display").innerHTML);
				mysack.setVar("image_url",document.getElementById("servdig_image_url").value);
				mysack.setVar("latitud",document.getElementById("servdig_latitud").value);
				mysack.setVar("longitud",document.getElementById("servdig_longitud").value);
				mysack.onError = function() { alert('An ajax error occured while adding your listing. Please reload the page and try again.') };
				mysack.runAJAX();//excecute
				return true;
			}
			
			function servdig_search_listings() { //Search Ajax Call
				var search_term = document.getElementById('servdig_search_term');
				if(search_term.value == "" || search_term.value == null)
					return;
				//Deactivate submit button and display processing message
				document.getElementById('servdig_search').disabled = true;
				var submit_message = document.getElementById('servdig_messages');
				submit_message.className = "servdig_message";
				submit_message.innerHTML = "Buscando servicios, por favor espere...";
				//Build SACK Call
				var mysack = new sack("<?php echo SDDIRCALLBACK; ?>requests.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action","SearchListings");
				mysack.setVar("searchTerms",search_term.value);
				mysack.onError = function() { alert('An ajax error occured while searching. Please reload the page and try again.') };
				mysack.runAJAX();//excecute
				return true;
			}
			
			function servdig_change_listings_page(offset) { //Jump to the appropriate page in the directory Ajax Call
				//Build SACK Call
				var mysack = new sack("<?php echo SDDIRCALLBACK; ?>requests.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action","ChangePage");
				mysack.setVar("offset",offset);
				mysack.onError = function() { alert('An ajax error occured. Please reload the page and try again.') };
				mysack.runAJAX();//excecute
				return true;
			}

            function servdig_sort_categories(category) { //Jump to the appropriate page in the directory Ajax Call
				//Build SACK Call
                var category = category.value;
                var mysack = new sack("<?php echo SDDIRCALLBACK; ?>requests.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action","SearchListings");
                mysack.setVar('category',category);
				mysack.onError = function() { alert('An ajax error occured. Please reload the page and try again.') };
				mysack.runAJAX();//excecute
				return true;
			}
		</script>
	<?php 
}
/*
*  Navigation
*/
function servdig_navigation() { 
	add_menu_page(
		"Administracion de Servicios",
		"Servicios",
		"manage_options",
		__FILE__,
		"servdig_show_manager",
		"/wp-content/plugins/servicios-digitales/servicios-digitales.png"
	); 
    add_submenu_page(__FILE__, 'Categorias de Servicios Digitales' , 'Categorias', "manage_options",'categorias-servicios-digitales', 'servdig_show_category' );


}
/*
*  Add Form Script
*/
function servdig_addform_shortcode($atts) { 
	extract(shortcode_atts(array('width'=>'100%'),$atts));
	return 
		"<link rel='stylesheet' href='".SDDIRCALLBACK."main.css' type='text/css' media='screen'/>".servdig_addform($width);
}
/*
*  Directory Script
*/
function servdig_directory_shortcode($atts) {
	global $wpdb;
	//get attributes
	extract(shortcode_atts(array('width'=>'100%','name'=>' the Servicios Digitales'),$atts));
	//Get Category options
	$categories = $wpdb->get_results("SELECT * FROM ".SDDIRCATTABLE." WHERE hide !=1 ORDER BY category ASC");
	$options = "<option value='0'>--Seleccione una Categoria--</option>";
	foreach($categories as $category) 
		$options .= "<option value='".wp_specialchars($category->category_id)."'>".wp_specialchars($category->category)."</option>";
	//display Listings
	return
		"<link rel='stylesheet' href='".SDDIRCALLBACK."main.css' type='text/css' media='screen'/>". 
		"<div id=\"servicios-control-busqueda\">".
		"<div id=\"servicios-form-busqueda-texto\">".
		"<form name='search' onSubmit='servdig_search_listings(); return false;'>".
			"<div id=\"servicios-etiqueta-busqueda\">Buscar Servicio: </div>".
			"<div id=\"servicios-input-busqueda\"><input type='text' id='servdig_search_term'/></div>".
			"<div id=\"servicios-submit-busqueda\"><input type='submit' id='servdig_search' value='Buscar'/></div>".
			"<div id='servdig_messages'></div>".
		"</form>".
		"</div>".
		"<div id=\"servicios-form-busqueda-categoria\">".
        "<form name='sort'>".
        	"<div id=\"servicios-etiqueta-busqueda\">Buscar por categoria:</div> ".
        	"<div id=\"servicios-input-busqueda\"><select id='categories' onChange='servdig_sort_categories(this.options[this.selectedIndex]); return false;' servdig_autofill='CategoryInfo'>".
				$options.
			"</select></div>".
        "</form>".
		"</div>".
		"</div>".
		
		"<div id='servdig_directory'>".servdig_directory()."</div>"
	;
}
/*
*  Listing Manager
*/
function servdig_show_manager() { 
	echo 
		"<link rel='stylesheet' href='".SDDIRCALLBACK."main.css' type='text/css' media='screen'/>".
		"<div class='wrap wpcf7'>".
			"<div id='icon-tools' class='icon32'><br></div>".
			"<h2>Servicios Digitales</h2>".
			"<hr/>".
			"<div id='servdig_messages'></div>".
			"<div id='servdig_manager'>".servdig_manager_home()."</div>".
		"<div>".
		"<br/><br/><br/>"
	;
} //Display manager

/*
 * Categories Manager
 */
function servdig_show_category() {
    echo 
        "<link rel='stylesheet' href='".SDDIRCALLBACK."main.css' type='text/css' media='screen'/>".
		"<div class='wrap wpcf7'>".
			"<div id='icon-tools' class='icon32'><br></div>".
			"<h2>Servicios Digitales</h2>".
			"<hr />".
			"<div id='servdig_messages'></div>".
			"<div id='servdig_categories'>".servdig_category_home()."</div>".
		"</div>"
    ;
}


function servdig_js_admin_header() { //Set Ajax Calls for manager
	wp_print_scripts(array('sack')); //use JavaScript SACK library for Ajax
	?>
		<script type="text/javascript">
			function servdig_edit_listing(id) {
				clearMessage();
				//Build SACK Call
				var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action","servdig_edit_listing");
				mysack.setVar("listing_id",id);
				mysack.onError = function() { alert('Ocurrio un error de Ajax mientras se procesaba su solicitud. Por favor recargue la pagina e intente de nuevo.') };
				mysack.runAJAX();//excecute
				return true;
			}

            function servdig_add_category() {
                clearMessage();
				//Clear AutoFill Text
				var description = document.getElementById("servdig_description");
				servdig_clearAutoFill(description.id,description.getAttribute('servdig_autofill'));
                var mysack = new sack("<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php");
                mysack.execute = 1;
                mysack.method = 'POST';
                mysack.setVar("action", "servdig_add_category");
                mysack.setVar("category",document.getElementById("servdig_category").value);
				mysack.setVar("description",document.getElementById("servdig_description").value);
                mysack.onError = function () { alert('Ocurrio un error de Ajax mientras se procesaba su solicitud. Por favor recargue la pagina e intente de nuevo.') };
                mysack.runAJAX();
                return true;
            }


            function servdig_edit_category(id) {
                clearMessage();
                var mysack = new sack("<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php");
                mysack.execute = 1;
                mysack.method = 'POST';
                mysack.setVar("action", "servdig_edit_category");
                mysack.setVar("category_id", id);
                mysack.onError = function () { alert('Ocurrio un error de Ajax mientras se procesaba su solicitud. Por favor recargue la pagina e intente de nuevo.') };
                mysack.runAJAX();
                return true;
            }

            function servdig_update_category() {
				clearMessage();
				//Disable buttons and display message
				document.getElementById('servdig_save').disabled = true;
				document.getElementById('servdig_cancel').disabled = true;
				var submit_message = document.getElementById('servdig_submit_message')
				submit_message.className = "servdig_message";
				submit_message.innerHTML = "Enviando...";
				//Build SACK Call
				servdig_clearAllAutoFill();
				var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action","servdig_update_category");
				mysack.setVar("category_id",document.getElementById("servdig_category_id").value);
				mysack.setVar("category",document.getElementById("servdig_category").value);
				mysack.setVar("description",document.getElementById("servdig_description").value);
				mysack.onError = function() { alert('Ocurrio un error de Ajax mientras se actualizaba el listado. Por favor recargue la pagina e intente de nuevo.') };
				mysack.runAJAX();//excecute
				return true;
			}
			
			function servdig_update_listing(status) {
				clearMessage();
				//Disable buttons and display message
				document.getElementById('servdig_save').disabled = true;
				var save_approve = document.getElementById('servdig_save_approve')
				if(save_approve != null)
					save_approve.disabled = true;
				document.getElementById('servdig_cancel').disabled = true;
				var submit_message = document.getElementById('servdig_submit_message')
				submit_message.className = "servdig_message";
				submit_message.innerHTML = "Enviando...";
				//Clear AutoFill
				servdig_clearAllAutoFill();
				//Build SACK Call
				var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action","servdig_update_listing");
				if(status == "approve")
					mysack.setVar("status",1);
				mysack.setVar("listing_id",document.getElementById("servdig_listing_id").value);
				mysack.setVar("category_id", document.getElementById("servdig_category_id").value);
				mysack.setVar("name",document.getElementById("servdig_name").value);
				mysack.setVar("email",document.getElementById("servdig_email").value);
				mysack.setVar("cName",document.getElementById("servdig_cName").value);
				mysack.setVar("description",document.getElementById("servdig_description").value);
				mysack.setVar("website",document.getElementById("servdig_website").value);
				mysack.setVar("phone",document.getElementById("servdig_phone").value);
				mysack.setVar("street1",document.getElementById("servdig_street1").value);
				mysack.setVar("image_url",document.getElementById("servdig_image_url").value);
				mysack.setVar("latitud",document.getElementById("servdig_latitud").value);
				mysack.setVar("longitud",document.getElementById("servdig_longitud").value);
				mysack.onError = function() { alert('Ocurrio un error de Ajax mientras se actualizaba el listado. Por favor recargue la pagina e intente de nuevo.') };
				mysack.runAJAX();//excecute
				return true;
			}
			
			function servdig_show_manager_home() {
				clearMessage();
				//Build SACK Call
				var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action","servdig_show_manager_home");
				mysack.onError = function() { alert('Ocurrio un error de Ajax mientras se procesaba su solicitud. Por favor recargue la pagina e intente de nuevo.') };
				mysack.runAJAX();//excecute
				return true;
			}

            function servdig_show_category_home() {
				clearMessage();
				//Build SACK Call
				var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action","servdig_show_category_home");
				mysack.onError = function() { alert('Ocurrio un error de Ajax mientras se procesaba su solicitud. Por favor recargue la pagina e intente de nuevo.') };
				mysack.runAJAX();//excecute
				return true;
			}
			
			function servdig_change_status(id,status) {
				clearMessage();
				//Build SACK Call
				var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action","servdig_change_listing_status");
				mysack.setVar("listing_id",id);
				mysack.setVar("status",status);
				mysack.onError = function() { alert('Ocurrio un error de Ajax mientras se procesaba su solicitud. Por favor recargue la pagina e intente de nuevo.') };
				mysack.runAJAX();//excecute
				return true;
			}
			
			function servdig_delete_listing(id) {
				clearMessage();
				if(confirm('Esta seguro de borrar este Servicio?')) {
					//Build SACK Call
					var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php");
					mysack.execute = 1;
					mysack.method = 'POST';
					mysack.setVar("action","servdig_delete_listing");
					mysack.setVar("listing_id",id);
					mysack.onError = function() { alert('Ocurrio un error de Ajax mientras se procesaba su solicitud. Por favor recargue la pagina e intente de nuevo.') };
					mysack.runAJAX();//excecute
				}
				return true;
			}
            
            function servdig_delete_category(id,name,count) {
				clearMessage();
				var confirmMessage = "Esta seguro de borrar la categoria \""+name+"\"? "; 
				confirmMessage += "La categoria \""+name+"\" y todos sus servicios asociados seran borrados permanentemente. ";
				confirmMessage += "(Actualmente hay "+(count == 1?"is":"are")+" "+count+" servicio"+(count == 1?"":"s")+" en la categoria \""+name+"\" ). ";
				confirmMessage += "Quiza quiera respaldar la base de datos antes de borrar la categoria \""+name+"\".";
				if(confirm(confirmMessage)) {
					//Build SACK Call
					var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php");
					mysack.execute = 1;
					mysack.method = 'POST';
					mysack.setVar("action","servdig_delete_category");
					mysack.setVar("category_id",id);
					mysack.onError = function() { alert('Ocurrio un error de Ajax mientras se procesaba su solicitud. Por favor recargue la pagina e intente de nuevo.') };
					mysack.runAJAX();//excecute
				}
				return true;
			}
		</script>
	<?php
}
