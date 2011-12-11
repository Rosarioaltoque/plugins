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
$response = "";
//Process Data
switch(@$_GET["accion"]) {
	case "Ver":
		if ( current_user_can('manage_options') ) {
			$query = "select count(*) as cnt from wp_destacados ";
			$query .= "where wp_destacados.tabla = '".@$_GET["tabla"]."' ";
			$query .= "and wp_destacados.id_registro = '".@$_GET["id_registro"]."' ";
			$destacados = $wpdb->get_results($query,ARRAY_A);
			foreach($destacados as $d) {
				$cnt = @$d["cnt"];
			}
			if ($cnt > 0) {
				$response = '<a href="/wp-content/plugins/destacados-widget/requests.php?accion=Desdestacar&tabla='.@$_GET["tabla"].'&id_registro='.@$_GET["id_registro"].'"><img src="/wp-content/plugins/destacados-widget/images/icono-destacado.png"></a>';
			} else {
				$response = '<a href="/wp-content/plugins/destacados-widget/requests.php?accion=Destacar&tabla='.@$_GET["tabla"].'&id_registro='.@$_GET["id_registro"].'"><img src="/wp-content/plugins/destacados-widget/images/icono-no-destacado.png"></a>';
			}
		}
	break;
	case "Destacar":
		if ( current_user_can('manage_options') ) {
			$response = '<a href="/wp-content/plugins/destacados-widget/requests.php?accion=Desdestacar&tabla='.@$_GET["tabla"].'&id_registro='.@$_GET["id_registro"].'"><img src="/wp-content/plugins/destacados-widget/images/icono-destacado.png"></a>';
			$query = "insert into wp_destacados set ";
			$query .= "  tabla = '".@$_GET["tabla"]."'";
			$query .= ", id_registro = '".@$_GET["id_registro"]."'";
			$query .= ", fecha = now()";
			$wpdb->query($query);
		}
	break;
	case "Desdestacar":
		if ( current_user_can('manage_options') ) {
			$response = '<a href="/wp-content/plugins/destacados-widget/requests.php?accion=Destacar&tabla='.@$_GET["tabla"].'&id_registro='.@$_GET["id_registro"].'"><img src="/wp-content/plugins/destacados-widget/images/icono-no-destacado.png"></a>';
			$query = "delete from wp_destacados where ";
			$query .= "  tabla = '".@$_GET["tabla"]."'";
			$query .= "and id_registro = '".@$_GET["id_registro"]."'";
			$wpdb->query($query);
		}
	break;
}
die($response);
