<?php
/*
*  Add Listing Functions
*/
function servdig_addform($width = "100%",$editView = false,$alreadyApproved = false) {
	global $wpdb;
	$form = 
		"<form id='servdig_add_form' method='POST' width='$width'>".
			"<table width='100%' border='0' cellspacing='0' cellpadding='0'>".
			  "<tr><td id='servdig_messages' colspan='2'></td></tr>"
	;
	//In the furute this will be changed to be more dynamic
	$adjective = $editView?"Lister's":"Your";
	$feilds = array(
		"servdig_name"=>array("title"=>"Nombre *","autofill"=>"YourInfo","maxlength"=>100, "type"=>"text"),
        "servdig_category_id"=>array("title"=>"Categoria *", 'type'=>"select", "autofill"=>"CategoryInfo"),
		"servdig_website"=>array("title"=>"URL *","autofill"=>"URL","maxlength"=>100, "type"=>"text"),
		"servdig_latitud"=>array("title"=>"Latitud","autofill"=>"Latitud","maxlength"=>100, "type"=>"latitud"),
		"servdig_longitud"=>array("title"=>"Longitud","autofill"=>"Longitud","maxlength"=>100, "type"=>"text"),
		"servdig_image_url"=>array("title"=>"Imagen","autofill"=>"image_url","maxlength"=>100, "type"=>"image"),
		"servdig_description"=>array("title"=>"Descripcion *","autofill"=>"Description","maxlength"=>NULL, "type"=>"textarea"),
		"servdig_cName"=>array("title"=>"Organizacion *","autofill"=>"CompanyInfo","maxlength"=>100, "type"=>"text"),
		"servdig_email"=>array("title"=>"Correo electronico","autofill"=>"Correo","maxlength"=>100, "type"=>"text"),
		"servdig_phone"=>array("title"=>"Telefono","autofill"=>"Telefono","maxlength"=>100, "type"=>"text"),
		"servdig_street1"=>array("title"=>"Direccion","autofill"=>"GeoInfo","maxlength"=>100, "type"=>"text"),
	);
	$feilds = servdig_clean_output($feilds);
	foreach($feilds as $id=>$info) {
		$form .= 
		  "<tr>".
			"<td class='servdig_form_text'>".$info["title"]."</td>".
			"<td class='servdig_form_input'>";
            switch($info['type']) {
                case "text":
                    $form .= "<input ".
                        "type='text' ".
                        "id='$id' ".
                        "class='servdig_input_style' ".
						"servdig_autofill='".$info["autofill"]."' ".
                        "maxlength='".$info["autofill"]."' ".
                        "onFocus='servdig_clearAutoFill(\"$id\", \"".$info["autofill"]."\");'".
                        "onClick='servdig_clearAutoFill(\"$id\", \"".$info["autofill"]."\");'".
                    "/>"; 
                break;

                case "image":
					$form .= '<div class="inside">';
					$form .= '<script src="/wp-admin/js/media-upload.js"></script>';
					$form .= '<div>';
					$form .= '<input type="text" name="servdig_image_url" id="servdig_image_url" class="servdig_image_url" servdig_autofill="'.$info["autofill"].'" onFocus="servdig_clearAutoFill(\''.$id.'\', \''.$info["autofill"].'\');" onClick="servdig_clearAutoFill(\''.$id.'\', \''.$info["autofill"].'\');" />';
					$form .= '<textarea id="edCanvas" name="edCanvas" rows="100" cols="10" tabindex="2" onfocus="image_url_agregar()" style="width: 1px; height: 1px; padding: 0px; border: none display :   none;"></textarea>';
					$form .= '</div>';
//					$form .= '<div name="servdig_image_url_url_display" id="servdig_image_url_url_display" class="sd_url_display">No hay imagen seleccionada</div>';
					if ( current_user_can('manage_options') ) {
						$form .= '<a href="/wp-admin/media-upload.php?type=image&#038;TB_iframe=1&#038;tab=library&#038;height=500&#038;width=640" onclick="image_photo_url_agregar(\'servdig_image_url\')" class="thickbox" title="Agregar una imagen"> <strong>Agregar una imagen</strong></a>';
					}
                    $form .= '<div id="servdig_image_url_selected_image" class="sd_selected_image"><img src=""/></div>';
					$form .= '<input value="sd_edit" type="hidden" name="sd_edit" />';
					$form .= '<input type="hidden" name="image_field" id="image_field" value="" />';
					$form .= '';
					$form .= '</div>';
//					$form .= '<script type="text/javascript">edCanvas = document.getElementById(\'content2\');</script>';
                break;

                case "latitud":
					$form .= '<div id="mapa-alta-servicios" class="mapa-alta-servicios"></div>';
					$form .= '<script>cargaMapaAltaServicio()</script>';
					$form .= '<input type="text"'
						.'id="'.$id.'" '
                        .'class="servdig_input_style" '
						.'>';
                break;

                case "textarea":
                    $form .= "<textarea ".
                             "id='$id' ".
                             "class='servdig_input_text_area' ".
                             "servdig_autofill='".$info["autofill"]."' ".
                             "onFocus='servdig_clearAutoFill(\"$id\",\"".$info["autofill"]."\");'".  
                             "onClick='servdig_clearAutoFill(\"$id\",\"".$info["autofill"]."\");'".  
                     "></textarea>";
                break;

                case "select":
					$categories = $wpdb->get_results("SELECT * FROM ".SDDIRCATTABLE." WHERE hide !=1 ORDER BY category ASC",ARRAY_A);
					$categories = servdig_clean_output($categories);
                    $form .="<select ".
                            "id='$id' ".
                            "servdig_autofill='".$info["autofill"]."' ".
							"onChange='this.setAttribute(\"selected_value\",this.value);' ".
                            "selected_value='' ".
                            "class='servdig_input_select'".
						">";
					$options = "<option value='0'>--Seleccione una Categoria--</option>";
					foreach($categories as $category) 
						$options .= "<option value='".@$category["category_id"]."'>".@$category["category"]."</option>";
					$form .= $options."</select>";
                break;
                default:
                    $form .= "<input ".
                        "type='text' ".
                        "id='$id' ".
                        "class='servdig_input_style' ".
                        "maxlength='".$info["autofill"]."' ".
                        "onFocus='servdig_clearAutoFill(\"$id\", \"".$info["autofill"]."\");'".
                        "onClick='servdig_clearAutoFill(\"$id\", \"".$info["autofill"]."\");'".
                    "/>";
            }
			$form .="</td>".
		  "</tr>";
		  if(array_key_exists('hint', $info)) {
		    $form .= "<tr>".
				"<td class='servdig_form_text'>&nbsp;</td>".
				"<td class='servdig_form_input'><small>".$info['hint']."</small></td>".
			"<tr/>";
          }
        }
	$form .= "<td class='servdig_form_text'>&nbsp;</td><td class='servdig_form_input'>";
	if($editView) {
		$form .= "<input type='submit' id='servdig_save' value='Guardar Servicio' onClick='servdig_update_listing(null); return false;' disabled/>";
		if(!$alreadyApproved)
			$form .= 
				"<input ".
					"type='button' ".
					"id='servdig_save_approve' ".
					"value='Guardar y Aprobar Servicio' ".
					"onClick='servdig_update_listing(\"approve\");' ".
					"disabled".
				"/>"
			;
		$form .= "<input type='button' id='servdig_cancel' value='Cancel' onClick='servdig_show_manager_home();' disabled/>";
		$form .= "<input type='hidden' id='servdig_listing_id'/>";
	} else
		$form .= "<input type='submit' id='servdig_submit' value='Enviar Servicio' onClick='servdig_add_listing(); return false;'/> ";
	$form .=
					"<span id='servdig_submit_message'>&nbsp;</span>".
				"</td>".
			  "</tr>".
			  "<tr><td class='servdig_form_text'>&nbsp;</td><td class='servdig_form_input servdig_notes'>* Requeridos</td></tr>".
			"</table>".
		"</form>"
	;
	return $form;
}


/*
*  Add Category Functions
*/
function servdig_category_addform($width = "100%",$editView = false) {
	$form = 
	"<form id='servdig_add_category_form' method='POST' width='$width'>".
		"<table width='100%' border='0' cellspacing='0' cellpadding='0'>".
			"<tr><td id='servdig_messages' colspan='2'></td></tr>".
		    "<tr>".
			    "<td class='servdig_form_text'>Categoria</td>".
			    "<td class='servdig_form_input'>".
                    "<input ".
                        "type='text' ".
                        "id='servdig_category'".
                        "class='servdig_input_style' ".
                        "servdig_autofill='CategoryInfo' ".
                        "onFocus='servdig_clearAutoFill(\"servdig_category\", \"CategoryInfo\");'".
                        "onClick='servdig_clearAutoFill(\"servdig_category\", \"CategoryInfo\");'".
                    "/>".
		        "</td>".
	        "</tr>".
            "<tr>" .
                "<td class='servdig_form_text'>Descripcion</td>".
                "<td class='servdig_form_input'>" .
                    "<textarea ".
                             "id='servdig_description' ".
                             "class='servdig_input_text_area' ".
                             "servdig_autofill='Cat_Description' ".
                             "onFocus='servdig_clearAutoFill(\"servdig_description\",\"Cat_Description\");'".  
                             "onClick='servdig_clearAutoFill(\"servdig_description\",\"Cat_Description\");'".  
                     "></textarea>".
                "</td>".
            "</tr>".
            "<tr>".
	        "<td class='servdig_form_text'>&nbsp;</td>".
            "<td class='servdig_form_input'>";

	if($editView) {
		$form .= "<input type='submit' id='servdig_save' value='Guardar Categoria' onClick='servdig_update_category(null); return false;' disabled/>";
		$form .= "<input type='hidden' id='servdig_category_id'/>";
	} else {
		$form .= "<input type='submit' id='servdig_submit' value='Enviar Categoria' onClick='servdig_add_category(); return false;'/> ";
    }
    $form .= "<input type='button' id='servdig_cancel' value='Cancelar' onClick='servdig_show_category_home();' disabled/>";
	$form .= "<span id='servdig_submit_message'>&nbsp;</span>".
		    "</td>".
		    "</tr>".
		    "<tr><td class='servdig_form_text'>&nbsp;</td><td class='servdig_form_input servdig_notes'>* Requeridos</td></tr>".
	    "</table>".
	"</form>"
	;
	return $form;
}

/*
*  Listing/Searching Functions
*/
function servdig_directory($searchTerms = "",$offset = 0,$category = 0) {
	global $wpdb;
	//Validate input
	$searchTerms = strip_tags(trim($searchTerms));
	if(!is_numeric($offset) || round($offset) != $offset) 
		$offset = 0;
	//get Listings
	$numListings = 0;
	$directory = "";
	$ID_wp_users = '';
	$is_user_logged_in = is_user_logged_in();
	if ($is_user_logged_in) {
		$current_user = wp_get_current_user();
		$ID_wp_users = $current_user->ID;
		}
	$query = "SELECT *, wp_posts.ID as post_id";
	if ($is_user_logged_in) {
		$query .= ", wp_usermeta.meta_value as cantidad_uso_usuario";
	}
	$query .= " FROM ".SDDIRDBTABLE." l";
	$query .= " LEFT JOIN ".SDDIRCATTABLE." c ON (c.category_id = l.category_id) ";
	$query .= " LEFT JOIN wp_posts on wp_posts.guid = l.image_url ";
	if ($is_user_logged_in) {
		$query .= " LEFT JOIN wp_usermeta on wp_usermeta.user_id = ".$ID_wp_users." and wp_usermeta.meta_key = concat('sd_cantidad_uso_', l.listing_id) ";
	}
	$query .= " WHERE status='1' ";
	if(!empty($searchTerms)) {
		$searchFeilds = array(
			"name","company_name","company_description",
			"company_street1",
            "category"
		);
		$temp = "AND (1=2";
		foreach($searchFeilds as $field)
			$temp .= $wpdb->prepare(" OR $field LIKE %s",$searchTerms);
		$temp .= ")";
		$query .= str_replace(array(" '",' "')," '%",str_replace(array("' ",'" '),"%' ",$temp));
	}
	if(!empty($category) && is_numeric($category)) {
		$query .= $wpdb->prepare("AND c.category_id=%d ",$category);
	}
	$query .= " group by l.listing_id ";
	$query .= " ORDER BY ";
	if ($is_user_logged_in) {
		$query .= "abs(cantidad_uso_usuario) desc, ";
	}
	$query .= "cantidad_uso desc, company_name ASC ";
	if(!empty($category) && is_numeric($category)) {
	} else {
		$query .= $wpdb->prepare(" LIMIT ".PERPAGE." OFFSET %d",$offset);
		$numListings = $wpdb->get_var("SELECT COUNT(*) FROM ".SDDIRDBTABLE);
	}
	$listings = servdig_clean_output($wpdb->get_results($query,ARRAY_A));
	$pagination = "";
	//Display Pages
	if($numListings > PERPAGE && empty($searchTerms) && empty($category)) {
		$directory .= "<div id=\"servicios-pagination-arriba\">";
		$pagination .= "<b>Paginas:</b>";
		$remaining = $numListings - PERPAGE;
		$count = 0;
		while($remaining > -1 * PERPAGE) {
			$index = PERPAGE * $count++;
			$pagination .= "&nbsp;&nbsp;";
			if($offset >= $index && $offset < $index + PERPAGE)
				$pagination .= "$count";
			else
				$pagination .= "<a id=\"servicios-enlace-pagina\" onClick='servdig_change_listings_page(".(($count - 1) * PERPAGE).")'>$count</a>";
			$remaining -= PERPAGE;
		}
		$directory .= "$pagination</div>";
	}
	//Display Listings
	if(!empty($searchTerms))
		$directory .= 
			"<div id=\"servicios-resultados-busqueda\">".
				"Resultados de la busqueda para: \"$searchTerms\"<br/>".
				"<a style='cursor:pointer;' onClick='servdig_change_listings_page(0);'>Ver todos los servicios</a>".
			"</div>"
		;
	$directory .= "<div id=\"rat-cont-item\">";
	foreach($listings as $l) {
		$ahref = "<a href='/wp-content/plugins/servicios-digitales/requests.php?action=GoListing&listing_id=".@$l["listing_id"]."&company_url=".$l["company_url"]."' class='servdig_linked_title' target='_blank'>";
		$directory .= "<div id=\"rat-item\">";
		$directory .= "<div id=\"rat-avatar-106\">";
//		if(!empty($l->image_url))
		$img = wp_get_attachment_image( @$l["post_id"], 'Miniatura servicios', true );
		if ($img)
			$directory .= $ahref.wp_get_attachment_image( @$l["post_id"], 'Miniatura servicios', true ).'</a>';
//			$directory .= "<img src=\"".@$l["image_url"]."\"><br/>";
		$directory .= "</div><!--#rat-avatar-106-->";
		$directory .= "<div id=\"rat-cont-text\" class=\"rat-cont-text-106\">";
		$directory .= "<div id=\"rat-item-titulo\">";
		str_replace("&Acirc;&","&",@$l["name"]);
		$directory .= $ahref;
		$directory .= @$l["name"];
		$directory .= "</a>";
		$directory .= "</div>";
		str_replace("&Acirc;&","&",@$l["company_name"]);
		$directory .= "<div id=\"rat-item-descripcion\">".str_replace("&Acirc;&","&",$l["company_description"])."</div>";
		$directory .= "<div id=\"rat-item-url\">".@$l["company_url"]."</div>";
		$directory .= "<div id=\"rat-item-pie\">Utilizado ".@$l["cantidad_uso"]." ";
		if (@$l["cantidad_uso"] == 1) {
			$directory .= " vez";
		}
		else {
			$directory .= " veces";
		}
		if ($is_user_logged_in && @$l["cantidad_uso_usuario"]) {
			$directory .= " (".@$l["cantidad_uso_usuario"]." por usuario)";
		}
		$directory .= ".</div><!--#rat-item-pie-->";
		$directory .= "</div><!--#rat-cont-text--><br class=\"clear\">";
		$directory .= "</div><!--#rat-item-->";
	}
	if(count($listings) < 1)
		$directory .= "<div>".(empty($searchTerms)?"Actualmente no hay servicios cargados":"Sin resultados")."</div>";
	$directory .= "</div>";
	//Add Footer Pagination
	if(!empty($pagination))
		$directory .= "<div id=\"servicios-pagination\">$pagination</div>";
	return $directory;
}
/*
*  Managing Functions
*/
function servdig_manager_home() {
	global $wpdb;
	//get Listings
	$query = "SELECT * FROM ".SDDIRDBTABLE." l LEFT JOIN ".SDDIRCATTABLE." c ON (c.category_id = l.category_id) ORDER BY company_name ASC;";
	$listings = servdig_clean_output($wpdb->get_results($query,ARRAY_A));
	$listingsByStatus = array("Pendientes"=>array(),"Aprobados"=>array());
	foreach($listings as $l)
		if(@$l["status"] == 0)
			$listingsByStatus["Pendientes"][] = $l;
		else
			$listingsByStatus["Aprobados"][] = $l;
	$display = "";
	foreach($listingsByStatus as $title=>$list) {
		$display .= "<h3>Servicios $title: ".count($list)."</h3><table width='100%' border='0' cellspacing='0' cellpadding='0' class='widefat'>";
		$display .= 
			"<thead><tr>".
				"<th>Organizacion</th>".
				"<th>Categoria</th>".
				"<th>Servicio</th>".
				"<th>Email</th>".
				"<th>URL</th>".
				"<th>Acciones</th>".
			"</tr></thead>".
			"<tbody>"
		;
		$count = 0;
		foreach($list as $l)
			$display .= 
				"<tr ".($count++%2 == 0?"class='alternate'":"").">".
					"<td>".str_replace("&Acirc;&","&",@$l["company_name"])."</td>".
                    "<td>".@$l["category"]."</td>".
					"<td>".@$l["name"]."</td>".
					"<td><a href='mailto:".@$l["email"]."' target='_blank'>".@$l["email"]."</a></td>".
					"<td>".(empty($l["company_url"])?"&nbsp;":"<a href='".$l["company_url"]."' target='_blank'>".@$l["company_url"]."</a>")."</td>".
					"<td>".
						"<a style='cursor:pointer;' onClick='servdig_edit_listing(\"".@$l["listing_id"]."\")'>Modificar</a> | ".
						($title == "Pendientes"?"<a style='cursor:pointer;' onClick='servdig_change_status(\"".@$l["listing_id"]."\",1)'>Aprobar</a> | ":"").
						"<a style='cursor:pointer;' onClick='servdig_delete_listing(\"".@$l["listing_id"]."\")'>Borrar</a>".
					"</td>".
				"</tr>";
			;
		if(count($list) < 1)
			$display .= "<tr><td colspan='4'>No hay Servicios \"$title\".</td></tr>";
		else
			$display .= "</tbody>";
		$display .= "</table>";
	}
	return $display;
}

/*
*  Categories Functions
*/
function servdig_category_home() {
	global $wpdb;
	//get category count
	$res = servdig_clean_output(
		$wpdb->get_results("SELECT category_id,COUNT(category_id) as num_listings FROM ".SDDIRDBTABLE." GROUP BY category_id",ARRAY_A)
	);
	$categories_count = array();
	foreach($res as $cat)
		if(!empty($cat["category_id"]) && !empty($cat["num_listings"]))
			$categories_count[$cat["category_id"]] = $cat["num_listings"];
	//get categories
	$categories = servdig_clean_output($wpdb->get_results("SELECT * FROM ".SDDIRCATTABLE." ORDER BY hide,category ASC",ARRAY_A));
	$display = "";
    $display .= "<h3>Categorias</h3>";
    $display .= "<p><a style='cursor:pointer;' onClick='servdig_edit_category(\"\")'>Agregar nueva categoria</a></p>";
    $display .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='widefat'>";
	$display .= "<thead><tr><th width='15%'>Categoria</th><th width='70%'>Descripcion</th><th width='15%'>Acciones</th></tr></thead><tbody>";
    if (count($categories) > 0) {
	    foreach($categories as $key=>$category) {
			$display .= 
				"<tr ".($key%2 == 0?"class='alternate'":"").">".
					"<td>".@$category["category"]."</td>".
					"<td>".@$category["description"]."</td>";
			if($category["hide"] == 0) {
				$display .= "<td>".
								"<a style='cursor:pointer;' onClick='servdig_edit_category(\"".@$category["category_id"]."\")'>Modificar</a> | ".
								"<a ".
									"title='".@$category["category"]."' ".
									"style='cursor:pointer;' ".
									"onClick='servdig_delete_category(".
										@$category["category_id"].",".
										"this.title,".
										(empty($categories_count[@$category["category_id"]])?"0":$categories_count[@$category["category_id"]]).
									");'".
								">Borrar</a>".
							"</td>";
			} else {
				$display .= "<td>".
								"<span class='servdig_notes_grey'>--disabled--</span>".
							"</td>";
			}    
			$display .= "</tr>";
        }
    } else {
	    $display .= "<tr><td colspan='4'>No hay \"Categorias\".</td></tr>";
	}
    $display .= "</tbody>";
	$display .= "</table>";
	return $display;
}

function servdig_edit_listing() {
	global $wpdb;
	//Get Listing to edit
	$v = servdig_clean_array($_POST,array("listing_id"=>"id"));
	$query = $wpdb->prepare(
		"SELECT * FROM ".SDDIRDBTABLE." l LEFT JOIN ".SDDIRCATTABLE." c ON (c.category_id = l.category_id) WHERE l.listing_id=%d;",
		(empty($v["listing_id"])?0:$v["listing_id"])
	);
	$listing = servdig_clean_output($wpdb->get_row($query,ARRAY_A),true);
	if(empty($listing))
		die("alert('There was an error processing your request. Please reload the page and try again.');");
	//Display add Form
	$form = "<h3>Modificar Servicio</h3>".str_replace("'","\'",servdig_addform("100%",true,(@$listing["status"] != 0)));
    $response = "
        document.getElementById('servdig_manager').innerHTML = '".$form."';
        document.getElementById('servdig_listing_id').value = '".absint(@$listing["listing_id"])."';
        document.getElementById('servdig_name').value = '".@$listing["name"]."';
        document.getElementById('servdig_category_id').value = ".@$listing["category_id"].";
        document.getElementById('servdig_email').value = '".@$listing["email"]."';
        document.getElementById('servdig_cName').value = '".@$listing["company_name"]."';
        document.getElementById('servdig_description').innerHTML = '".@$listing["company_description"]."';
        document.getElementById('servdig_website').value = '".@$listing["company_url"]."';
        document.getElementById('servdig_phone').value = '".@$listing["company_phone"]."';
        document.getElementById('servdig_street1').value = '".@$listing["company_street1"]."';
        document.getElementById('servdig_latitud').value = '".@$listing["latitud"]."';
        document.getElementById('servdig_longitud').value = '".@$listing["longitud"]."';
        document.getElementById('servdig_image_url').value = '".@$listing["image_url"]."';
		jQuery('#servdig_image_url_selected_image').html('<img src=\"".@$listing["image_url"]."\" width=\"200px\" />');
        document.getElementById('servdig_save').disabled = false;
        var save_approve = document.getElementById('servdig_save_approve')
        if(save_approve != null)
            save_approve.disabled = false;
        document.getElementById('servdig_cancel').disabled = false;
        servdig_populateAutofill();
		cargaMapaAltaServicio();
    ";
	die($response); 
}

function servdig_update_listing() {
	global $wpdb;
	$response = "";
	$acceptable_fields = array(
		"listing_id"=>"id","category_id"=>"id","status"=>"numeric",
		"name"=>"string","email"=>"email",
		"cName"=>"text","description"=>"text","website"=>"text","phone"=>"phone",
		"street1"=>"text","image_url"=>"text"
		,"latitud"=>"text","longitud"=>"text"
	);
	$v = servdig_clean_array($_POST,$acceptable_fields);
	$errors = validateListing($v,true);
	//Process Input
	if(count($errors) < 1) {
		//Insert Listing into the database
		$query = "UPDATE ".SDDIRDBTABLE." SET ";
		if(@$v["status"] == 1)
			$query .= "status=1,";
		$query .= "name=%s,category_id=%d,email=%s,";
		$query .= "company_name=%s,company_description=%s,company_url=%s,company_phone=%s,";
		$query .= "company_street1=%s,image_url=%s ";
		$query .= ",latitud=%s,longitud=%s ";
		$query .= "WHERE listing_id=%d;";
		$query = $wpdb->prepare($query,
				$v["name"],$v['category_id'],$v["email"],$v["cName"],$v["description"],$v["website"],$v["phone"],
				$v["street1"],$v["image_url"],$v["latitud"],$v["longitud"],(empty($v["listing_id"])?0:$v["listing_id"])
		);
		$wpdb->query($query);
		$response = "
			var messages = document.getElementById('servdig_messages');
			messages.className = 'servdig_message';
			messages.innerHTML = 'Servicio actualizado.';
			document.getElementById('servdig_manager').innerHTML = '".str_replace("'","\'",servdig_manager_home())."';
			window.location = '#';
		";
	} else {
		$message = "";
		foreach($errors as $err)
			$message .= str_replace("'","\'",$err);
		$response = "
			var messages = document.getElementById('servdig_messages');
			messages.className = 'servdig_error_box';
			messages.innerHTML = '$message';
			document.getElementById('servdig_save').disabled = false;
			var save_approve = document.getElementById('servdig_save_approve')
			if(save_approve != null)
				save_approve.disabled = false;
			document.getElementById('servdig_cancel').disabled = false;
			var submit_message = document.getElementById('servdig_submit_message');
			submit_message.className = 'servdig_error';
			submit_message.innerHTML = 'Por favor corrija los errores indicados arriba  y envie otra vez.';
		";
	}
	die($response);
}

function servdig_show_manager_home() { die("document.getElementById('servdig_manager').innerHTML = '".str_replace("'","\'",servdig_manager_home())."';"); }

function servdig_change_listing_status() {
	global $wpdb;
	$v = servdig_clean_array($_POST,array("listing_id"=>"id","status"=>"numeric"));
	$status = @$v["status"] == 1?1:0;
	$query = $wpdb->prepare("UPDATE ".SDDIRDBTABLE." SET status=$status WHERE listing_id=%d;",(empty($v["listing_id"])?0:$v["listing_id"]));
	$wpdb->query($query);
	$response = "
		var messages = document.getElementById('servdig_messages');
		messages.className = 'servdig_message';
		messages.innerHTML = 'Servicio ".($status == 1?"Aprobado":"Eliminado de la lista de aprobados.").".';
		document.getElementById('servdig_manager').innerHTML = '".str_replace("'","\'",servdig_manager_home())."';
	";
	die($response);
}

function servdig_delete_listing() {
	global $wpdb;
	$response = "";
	$v = servdig_clean_array($_POST,array("listing_id"=>"id"));
	$status = @$v["status"] == 1?1:0;
	$query = $wpdb->prepare("DELETE FROM  ".SDDIRDBTABLE." WHERE listing_id=%d;",(empty($v["listing_id"])?0:$v["listing_id"]));
	if($wpdb->query($query))
		$response = "
			var messages = document.getElementById('servdig_messages');
			messages.className = 'servdig_message';
			messages.innerHTML = 'Servicio borrado.';
			document.getElementById('servdig_manager').innerHTML = '".str_replace("'","\'",servdig_manager_home())."';
		";
	else
		$response = "alert('Unable to delete listing. Please reload the page and try again.')";
	die($response);
}

function servdig_show_category_home() { 
	die("document.getElementById('servdig_categories').innerHTML = '".str_replace("'","\'",servdig_category_home())."';"); 
}


function servdig_edit_category() {
	global $wpdb;
    $form = '';
    $v = servdig_clean_array($_POST,array("category_id"=>"id","category"=>"string","description"=>"text"));
    $edit = (is_null($v['category_id'])) ? false : true;
    if ($edit) {
	    //Get Listing to edit
		$query = $wpdb->prepare("SELECT * FROM ".SDDIRCATTABLE." WHERE category_id=%d;",(empty($v["category_id"])?0:$v["category_id"]));
	    $category = servdig_clean_output($wpdb->get_row($query,ARRAY_A),true);
	    if(empty($category))
		    die("alert('There was an error processing your request. Please reload the page and try again.');");
	}
        //Display add Form
	    $form .= "<h3> Categoria</h3>".str_replace("'","\'",servdig_category_addform("100%",$edit));
    $response = "
        document.getElementById('servdig_categories').innerHTML = '".$form."';";
        if ($edit) {
    $response .= "
            document.getElementById('servdig_category_id').value = '".absint(@$category["category_id"])."';
            document.getElementById('servdig_category').value = '".@$category["category"]."';
            document.getElementById('servdig_description').innerHTML = '".@$category["description"]."';
            document.getElementById('servdig_save').disabled = false;";
       }
    $response .= "
        document.getElementById('servdig_cancel').disabled = false;
        servdig_populateAutofill();
    ";
	die($response); 
}

function servdig_add_category() {
   	global $wpdb;
	$response = "";
	$v = servdig_clean_array($_POST,array("category"=>"string","description"=>"text"));
	//validate Input
	$errors = validateCategory($v,true);
	//Process Input
	if(count($errors) < 1) {
        //Insert Category into the database
		$wpdb->query($wpdb->prepare("INSERT INTO ".SDDIRCATTABLE." (category,description) VALUES (%s,%s);",$v["category"],$v["description"]));
		//create response
		$response = 
			"var messages = document.getElementById('servdig_messages');".
			"messages.className = 'servdig_message';".
			"messages.innerHTML = 'Categoria agregada.';".
			"var warning = document.getElementById('servdig_warning');".
			"if(warning != null) {".
				"warning.className = '';".
				"warning.innerHTML = '';".
			"}".
			"document.getElementById('servdig_categories').innerHTML = '".str_replace("'","\'",servdig_category_home())."';".
			"window.location = '#';"
		;
	} else {
		$message = "";
		foreach($errors as $err)
			$message .= str_replace("'","\'",$err);
		$response = "
			var messages = document.getElementById('servdig_messages');
			messages.className = 'servdig_error_box';
			messages.innerHTML = '$message';
			document.getElementById('servdig_save').disabled = false;
			var save_approve = document.getElementById('servdig_save_approve')
			if(save_approve != null)
				save_approve.disabled = false;
			document.getElementById('servdig_cancel').disabled = false;
			var submit_message = document.getElementById('servdig_submit_message');
			submit_message.className = 'servdig_error';
			submit_message.innerHTML = 'Por favor corrija los errores indicados arriba  y envie otra vez.';
		";
	}
	die($response);
}

function servdig_update_category() {
	global $wpdb;
	$response = "";
	$v = servdig_clean_array($_POST,array("category_id"=>"id","category"=>"string","description"=>"text"));
	//validate Imput
	$errors = validateCategory($v,true);
	//Process Input
	if(count($errors) < 1) {
		//Insert Listing into the database
		$query = "UPDATE ".SDDIRCATTABLE." SET category=%s,description=%s WHERE category_id=%d;";
		$wpdb->query($wpdb->prepare($query,$v["category"],$v["description"],$v["category_id"]));
		$response = 
			"var messages = document.getElementById('servdig_messages');".
			"messages.className = 'servdig_message';".
			"messages.innerHTML = 'Categoria actualizada.';".
			"var warning = document.getElementById('servdig_warning');".
			"if(warning != null) {".
				"warning.className = '';".
				"warning.innerHTML = '';".
			"}".
			"document.getElementById('servdig_categories').innerHTML = '".str_replace("'","\'",servdig_category_home())."';".
			"window.location = '#';"
		;
	} else {
		$message = "";
		foreach($errors as $err)
			$message .= str_replace("'","\'",$err);
		$response = "
			var messages = document.getElementById('servdig_messages');
			messages.className = 'servdig_error_box';
			messages.innerHTML = '$message';
			document.getElementById('servdig_save').disabled = false;
			var save_approve = document.getElementById('servdig_save_approve')
			if(save_approve != null)
				save_approve.disabled = false;
			document.getElementById('servdig_cancel').disabled = false;
			var submit_message = document.getElementById('servdig_submit_message');
			submit_message.className = 'servdig_error';
			submit_message.innerHTML = 'Por favor corrija los errores indicados arriba  y envie otra vez.';
		";
	}
	die($response);
}

function servdig_delete_category() {
	global $wpdb;
	$response = "";
	$v = servdig_clean_array($_POST,array("category_id"=>"id"));
	$query = $wpdb->prepare("DELETE FROM ".SDDIRDBTABLE." WHERE category_id=%d;",(empty($v["category_id"])?0:$v["category_id"]));
	if($wpdb->query($query) === false)
		die("alert('Unable to delete category. Please reload the page and try again.')");
	$query = $wpdb->prepare("DELETE FROM ".SDDIRCATTABLE." WHERE category_id=%d;",(empty($v["category_id"])?0:$v["category_id"]));
	if($wpdb->query($query)) {
		$response = "
			var messages = document.getElementById('servdig_messages');
			messages.className = 'servdig_message';
			messages.innerHTML = 'Categoria borrada.';
			document.getElementById('servdig_categories').innerHTML = '".str_replace("'","\'",servdig_category_home())."';
		";
	} else {
		$response = "alert('Unable to delete category. Please reload the page and try again.')";
    }
	die($response);
}


/*
* Helper Functions
*/
function servdig_clean_array($v,$keys) {
	$v = stripslashes_deep($v);
	if(is_array($v) && is_array($keys) && count($v) > 0 && count($keys) > 0) {
		$res = array();
		$allowable_types = array(
			"alpha","alpha_numeric","id","email","website","url","phone","numeric","n","num","decimal","d","string","s","str","text"
		);
		$line_breaks = array("\r\n","\n\r","\n","\r");
		foreach($v as $key=>$value) {
			if(
				!empty($keys[$key]) && (is_string($key) || is_numeric($key)) && 
				in_array($keys[$key],$allowable_types) && //Validate that the key is valid and wanted
				(is_string($value) || is_numeric($value)) //Validate that the value is a string or a number
			) {
				//Ensure that the key is a safe value
				$key = stripcslashes(strip_tags(str_replace($line_breaks,"",servdig_clean_multiquotes(trim($key)))));
				if(!preg_match("/^[\w\s\-]+$/",$key))
					continue;
				//Ensure that the value is a safe value.
				$val = servdig_clean_multiquotes(stripcslashes(strip_tags(str_replace($line_breaks,"",trim($value)))));
				switch(strtolower($keys[$key])) { //Verify that the value is acceptable
					case "alpha": //String containing only letters
						if(preg_match("/^[a-zA-Z]+$/",$val))
							$res[$key] = $val; //Save the value to the new array
					break;
					case "alpha_numeric": //String containing only letters and numbers (no decimals)
						if(preg_match("/^[a-zA-Z0-9]+$/",$val))
							$res[$key] = $val; //Save the value to the new array
					break;
					case "id": //A positive, non-zero integer
						if(is_numeric($val) && round($val) == $val && $val > 0)
							$res[$key] = $val; //Save the value to the new array
					break;
					case "email": //An email
						if(preg_match('/^[A-z]+[A-z0-9\._-]*[@][A-z0-9_-]+([.][A-z0-9_-]+)*[.][A-z]{2,4}$/',$val))
							$res[$key] = $val; //Save the value to the new array
					break;
					case "website":case "url": //A URL
						if(substr($val,0,7) != "http://" && substr($val,0,8) != "https://") 
							$val = "http://$val";
						if(preg_match('/^http{1}s?:{1}\/\/{1}[A-z0-9]+[A-z0-9\-\.]*\.{1}[A-z]{2,4}(\/([a-zA-Z0-9\.\-_])+)*(\/){0,1}$/',$val))
							$res[$key] = $val; //Save the value to the new array
					break;
					case "numeric":case "n":case "num": //Integer number
						if(is_numeric($val) && round($val) == $val)
							$res[$key] = $val; //Save the value to the new array
					break;
					case "decimal":case "d": //Any real number
						if(is_numeric($val))
							$res[$key] = $val; //Save the value to the new array
					break;
					//A string containing leters, numbers, basic symbols, whitespace (no line breaks), and html safe characters.
					case "string":case "s":case "str":case "text":case "phone": 
						if(is_string($val))
							$res[$key] = $val; //Save the value to the new array
					break;
				} 
			}
		}
		return $res;
	}
	return array();
}
function servdig_clean_output($v,$decode = false) {
	//Clean array to be outputed to HTML
	$res = array();
	if(is_array($v) && count($v) > 0) {
		$v = stripslashes_deep($v);
		foreach($v as $key=>$value)
			if((is_string($key) || is_numeric($key)) && (is_string($value) || is_numeric($value))) {
				$res[$key] = wp_specialchars(servdig_clean_multiquotes($value),ENT_QUOTES);
				if($decode)
					$res[$key] = str_replace("&#039;","\'",html_entity_decode($res[$key]));
			} elseif((is_string($key) || is_numeric($key)) && is_array($value))
				$res[$key] = servdig_clean_output($value);
	}
	return $res;
}
function servdig_clean_multiquotes($string) {
	if(is_string($string)) {
		$res = stripslashes_deep($string);
		do {
			//Remove Multiple Double Quotes
			while($res != str_replace(array('""',"&quot;&quot;"),'"',$res))
				$res = str_replace(array('""',"&quot;&quot;"),'"',$res);
			//Remove Multiple Single Quotes
			while($res != str_replace(array("''","&#039;&#039;"),"'",$res))
				$res = str_replace(array("''","&#039;&#039;"),"'",$res);
			$new_string = str_replace(array("''","&#039;&#039;"),'"',str_replace(array('""',"&quot;&quot;"),"'",$res));
		} while($res != $new_string);
		return $res;
	}
	return $string;
}
function servdig_trim_array($v) {
	if(is_array($v)) {
		$res = array();
		foreach($v as $key=>$value)
			$res[$key] = trim($value);
		return $res;
	}
	return array();
}

function validateCategory($v,$admin = false) {
	//Trim the content of $_POST
	$post = servdig_trim_array($_POST);
	//Validate $v
	$errors = array();
	if(!is_array($v))
		return array("Error processing data, please try again.");
	if(empty($v["category"])) 
		$errors[] = empty($post["category"])?"Por favor ingrese una categoria.<br/>":"Category name must be alpha-numeric.<br/>";
	if(empty($v["description"])) 
		$errors[] = 
			empty($post["description"])?"Por favor ingrese una breve descripcion del servicio.<br/>":"Description contains invalid character(s)";
	elseif(strlen($v["description"]) > 800) 
		$errors[] = "The description for the company is too long. Please shorten it to 800 characters or less.<br/>";
	return $errors;
}

function validateListing($v,$admin = false) {
	//Set Adjective
	$adjective = $admin?"lister's":"your";
	//Trim the content of $_POST
	$post = servdig_trim_array($_POST);
	//Validate $v
	$errors = array();
	if(!is_array($v))
		return array("Error processing data, please try again.");
	if(empty($v["name"])) 
		$errors[] = empty($post["name"])?"Por favor ingrese el nombre del servicio.<br/>":"El nombre solo puede contener letras y espacios.";
    if (empty($v["category_id"]) || $v['category_id'] == 0) 
        $errors[] = "Por favor seleccione una categoria.<br />";
/*	if(empty($v["email"])) 
		$errors[] = empty($post["email"])?"Por favor ingrese su correo electronico.<br/>":" Correo electronico no valido.<br/>";*/
	if(empty($v["cName"])) 
		$errors[] = empty($post["cName"])?"Por favor ingrese el nombre de la organizacion.<br/>":"Organization name must be alpha-numeric<br/>";
	if(empty($v["description"])) 
		$errors[] = empty($post["description"])?
			"Por favor ingrese una breve descripcion.<br/>":
			"Invalid character(s) in $adjective description.<br/>"
		;
	elseif(strlen($v["description"]) > 800) 
		$errors[] = "The description for the organization is too long. Please shorten it to 800 characters or less.<br/>";
	if(!empty($post["website"]) && empty($v["website"]))
		$errors[] = "La URL del servicio no es valida.<br/>";
	if(!empty($post["phone"]) && empty($v["phone"]))
		$errors[] = ucfirst($adjective)." phone is not a valid phone number.<br/>";
	return $errors;
}
