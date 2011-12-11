<?php
//Load WordPress
$wp_root = explode("wp-content",$_SERVER["SCRIPT_FILENAME"]);
$wp_root = $wp_root[0];
if($wp_root == $_SERVER["SCRIPT_FILENAME"]) {
	$wp_root = explode("index.php",$_SERVER["SCRIPT_FILENAME"]);
	$wp_root = $wp_root[0];
}
chdir($wp_root);
if(!function_exists("add_action")) require_once(file_exists("wp-load.php")?"wp-load.php":"wp-config.php");
require(dirname(__FILE__).'/config.php'); //Load Biz-Directory Config File
//Clean Input
$accepted_values = array(
	"action"=>"alpha",
	"category_id"=>"id",
	"name"=>"text","email"=>"email",
	"cName"=>"text","description"=>"text","website"=>"text","phone"=>"text",
	"street1"=>"text",
	"image_url"=>"text",
	"latitud"=>"text",
	"longitud"=>"text",
	"searchTerms"=>"text","category"=>"id",
	"offset"=>"numeric"
);
$v = servdig_clean_array($_POST,$accepted_values);
if (@$_GET["action"] == 'GoListing') {
	@$v["action"] = 'GoListing';
}
$response = "";
//Process Data
switch(@$v["action"]) {
	case "GoListing":

		//incrementar uso y actualizar fecha ultimo uso
		$query = "update ".SDDIRDBTABLE." set";
		$query .= " fecha_ultimo_uso = now()";
		$query .= ", cantidad_uso = cantidad_uso + 1";
		$query .= " where listing_id = ".@$_GET["listing_id"]." ";
		$wpdb->query($query);

		//si usuario logueado insertar metas de usuario: cantidad uso, fecha ultimo uso
		$ID_wp_users = null;
		if (is_user_logged_in()) {
			wp_get_current_user();
			$cantidad_uso = get_user_meta($current_user->ID, 'sd_cantidad_uso_'.@$_GET["listing_id"], true);
			$cantidad_uso++;
			update_user_meta( $current_user->ID, 'sd_cantidad_uso_'.@$_GET["listing_id"], $cantidad_uso, '' );
			update_user_meta( $current_user->ID, 'sd_fecha_ultimo_uso_'.@$_GET["listing_id"], date("Y-m-d G:i:s"), '' );
			$ID_wp_users = $current_user->ID;
		}

		//registrar uso
		if ($ID_wp_users) {
			$keys = "listing_id_wp_sd_listings, ID_wp_users, fecha, HTTP_USER_AGENT, REMOTE_ADDR";
			$query = $wpdb->prepare(
				"INSERT INTO ".SDDIRUSOTABLE." ($keys) VALUES (%d,%d,now(),%s,%s);",
				@$_GET["listing_id"],$ID_wp_users,@$_SERVER["HTTP_USER_AGENT"],@$_SERVER["REMOTE_ADDR"]
			);
		} else {
			$keys = "listing_id_wp_sd_listings, fecha, HTTP_USER_AGENT, REMOTE_ADDR";
			$query = $wpdb->prepare(
				"INSERT INTO ".SDDIRUSOTABLE." ($keys) VALUES (%d,now(),%s,%s);",
				@$_GET["listing_id"],@$_SERVER["HTTP_USER_AGENT"],@$_SERVER["REMOTE_ADDR"]
			);
		}
		$wpdb->query($query);

		$response = "Redireccionando ... ";
		$response .= "<script>";
		$response .= "location.replace(\"".@$_GET["company_url"]."\");";
		$response .= "</script>";
	break;
	case "AddListing":
		//validate Imput
		$errors = validateListing($v);
		//Process Input
		if(count($errors) < 1) {
			//Insert Listing into the database
			$keys = 
				"category_id,date_created,status,".
				"name,email,".
				"company_name,company_description,company_url,company_phone,".
				"company_street1,image_url,latitud,longitud"
			;
			$query = $wpdb->prepare(
				"INSERT INTO ".SDDIRDBTABLE." ($keys) VALUES (%d,%s,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s);",
				$v["category_id"],date("Y-m-d G:i:s"),0,
				$v["name"],$v["email"],
				$v["cName"],$v["description"],$v["website"],$v["phone"],
				$v["street1"],$v["image_url"]
				,$v["latitud"],$v["longitud"]
			);
			$wpdb->query($query);
			//create response
			$response = "
				var messages = document.getElementById('servdig_messages');
				messages.className = 'servdig_message';
				messages.innerHTML = 'Su servicio ha sido enviado y esta pendiente de aprobacion del administrador.';
				document.getElementById('servdig_submit').disabled = false;
				var submit_message = document.getElementById('servdig_submit_message');
				submit_message.className = '';
				submit_message.innerHTML = '&nbsp;';
				servdig_populateAutofill(true);
			";
		} else {
			$message = "";
			foreach($errors as $err)
				$message .= $err;
			$response = "
				var messages = document.getElementById('servdig_messages');
				messages.className = 'servdig_error_box';
				messages.innerHTML = '$message';
				document.getElementById('servdig_submit').disabled = false;
				var submit_message = document.getElementById('servdig_submit_message');
				submit_message.className = 'servdig_error';
				submit_message.innerHTML = 'Por favor corrija los errores indicados arriba  y envie otra vez.';
			";
			$keys = array("name","email","cName","website","phone","category_id");
            $response .= "servdig_populateAutofill();";
		}
	break;
	case "SearchListings":
		if((empty($v["searchTerms"]) || !is_string($v["searchTerms"])) && (empty($v["category"]) || !is_string($v["category"]))) {
			$response = "
				var messages = document.getElementById('servdig_search_message');
				messages.className = 'servdig_error';
				messages.innerHTML = 'Enter search was unsearchable. Please Try again.';
				document.getElementById('servdig_search').disabled = false;
			";
			break;
		}
		$listings = "";
		if(!empty($v["searchTerms"]) && is_string($v["searchTerms"]))
			$listings = str_replace("'","\'",stripslashes(servdig_directory($v["searchTerms"])));
		elseif(!empty($v["category"]) && is_numeric($v["category"]))
			$listings = str_replace("'","\'",stripslashes(servdig_directory("",0,$v["category"])));

/*		$listings = utf8_decode($listings);*/ // en el servidor de produccion no es necesario
		$response = "
			clearMessage();
			document.getElementById('servdig_directory').innerHTML = '$listings';
			document.getElementById('servdig_search').disabled = false;
		";
	break;
	case "ChangePage":
		$listings = str_replace("'","\'",servdig_directory("",@$v["offset"]));
		$response .= "
			clearMessage();
			document.getElementById('servdig_directory').innerHTML = '$listings';
			document.getElementById('servdig_search').disabled = false;
		";
	break;
}
die($response);
