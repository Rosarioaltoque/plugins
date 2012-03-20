<?php
/**
 * Plugin Name: Destacados Widget
 * Plugin URI:  http://pampacomcdt.com.ar
 * Description: Muestra lo mas destacado de RosarioAlToque 
 * Author:      PampacomCDT
 * Version:     0.1
 * Author URI:  http://www.pampacomcdt.com.ar
 * Network:     true
 */
 
add_action( 'plugins_loaded', 'destacados_register_widgets' );
/*add_action( 'wp_print_styles', 'enqueue_estilos' );*/
wp_register_style('destacados-widget', '/wp-content/plugins/destacados-widget/destacados-widget.css');
wp_enqueue_style('destacados-widget');

function destacados_register_widgets() {
	add_action('widgets_init', create_function('', 'return register_widget("Destacados_Widget");') );
}

function enqueue_estilos() {
	add_action('widgets_init', create_function('', 'return register_widget("Destacados_Widget");') );
}

class Destacados_Widget extends WP_Widget {
	function Destacados_Widget() {
		$this->WP_Widget( false, 'Destacados Widget', array( 'description' => 'Elementos destacados de RosarioAlToque'));
		$this->default_num_to_show = 10;
		$this->default_destacados = "blogs";
	}

	function __construct() {
		$widget_ops = array( 'description' => 'Conjuntos de elementos destacados del sitio.');
		parent::__construct( false, 'Destacados', $widget_ops );
		
    if ( is_active_widget( false, false, $this->id_base ) ) {
      wp_enqueue_script( 'jcarousel-for-widgets-js', get_template_directory_uri(). '/js/jquery.jcarousel.js', array( 'jquery' ) );
      wp_enqueue_script( 'jTabs-for-widgets-js', get_template_directory_uri(). '/js/jquery.idTabs.min.js', array( 'jquery' ) );
		}
	}

	function form( $instance ) {
		$title = ( isset( $instance['title'] ) ) ? esc_attr( $instance['title'] ) : '';
		$title_id = $this->get_field_id( 'title' );
		$title_name = $this->get_field_name( 'title' );
		
    $num_to_show = ( isset( $instance['num_to_show'] ) ) ? esc_attr( $instance['num_to_show'] ) : $this->default_num_to_show;
		$num_to_show_id = $this->get_field_id( 'num_to_show' );
		$num_to_show_name = $this->get_field_name( 'num_to_show' );
    
    $destacados = ( isset( $instance['destacados']))? $instance['destacados'] : $this->default_destacados; //esc_attr( $instance['destacados'])
    $destacados_id = $this->get_field_id('destacados');
		$destacados_name = $this->get_field_name( 'destacados');

?>
 <p>
	<label for="<?php echo $title_id ?>"><?php _e( 'Title:', 'p2' ); ?>
		<input type="text" class="widefat" id="<?php echo $title_id ?>" name="<?php echo $title_name ?>"
			value="<?php echo $title; ?>" />
	</label>
</p>
<p>
	<label for="<?php echo $destacados_id ?>">
		Destacados:
		<table class="widefat">
			<thead><tr><td>&nbsp;</td><td>Tabla</td><td>Registro</td><td>Titulo</td></tr></thead>
			<tbody>
				<?php
				$query = "select * from wp_destacados ";
				$query .= "group by wp_destacados.tabla, wp_destacados.id_registro";
				global $wpdb;
				$destacados = $wpdb->get_results($query,ARRAY_A);
				foreach($destacados as $d) { 
					$post_destacado = get_post(@$d["id_registro"]); 
					echo '<tr class="alternate"><td><a href="/wp-content/plugins/destacados-widget/requests.php?accion=Desdestacar&tabla='.$d["tabla"].'&id_registro='.$d["id_registro"].'">X</a></td><td>'.@$d["tabla"].'</td><td>'.@$d["id_registro"].'</td><td>'.$post_destacado->post_title.'</td></tr>';
				}
				?>
			</tbody>
		</table>
	</label>
</p>
<p>
	<label for="<?php echo $num_to_show_id ?>"><?php _e( 'Number of tags to show:', 'p2' ); ?>
		<input type="text" class="widefat" id="<?php echo $num_to_show_id ?>" name="<?php echo $num_to_show_name ?>"
			value="<?php echo $num_to_show; ?>" />
	</label>
</p>
<?php
}
function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
    $instance['num_to_show'] = (int)$new_instance['num_to_show']? (int)$new_instance['num_to_show'] : $this->default_num_to_show;
    
    error_log(var_dump($old_instance));
    error_log(var_dump($new_instance));
    
    $instance['destacados'] = $new_instance['destacados'];

	return $instance;
}  

function widget( $args, $instance ) {
	extract( $args );
	
	$title = (isset( $instance['title'] ) && $instance['title'])? $instance['title'] : __( 'Recent tags', 'p2' );
	$num_to_show = (isset( $instance['num_to_show'] ) && (int)$instance['num_to_show'])? (int)$instance['num_to_show'] : $this->default_num_to_show;
    $destacados = $instance['destacados']; // Lista de destacados
    
    $arr_destacados = explode("\r", $destacados);
    
    echo $before_widget;
    echo $before_title
    . $instance['title']
    . $after_title;

?>
	<div id="marco" class="destacados">
		<?php
			$query = "select * from wp_destacados ";
			$query .= "group by wp_destacados.tabla, wp_destacados.id_registro";
       		$query .= " order by max(fecha) desc ";
			global $wpdb;
			$destacados = $wpdb->get_results($query,ARRAY_A);
			foreach($destacados as $d) {
				$id_imagen = get_post_meta(@$d["id_registro"], '_thumbnail_id', true);
				$image_attributes = wp_get_attachment_image_src( $id_imagen, 'Grande destacados' );
				$post_marco = get_post(@$d["id_registro"]); 
				echo '<div id="wp_posts_'.@$d["id_registro"].'" class="detalle">';
				echo '<div class="foto">';
				echo '<a href="'.$post_marco->post_name.'"><img border="0" src="'.$image_attributes[0].'" ></a>';
				echo '</div><!--#foto-->';
				echo '<div class="descripcion">';
				echo '<div class="titulo"><h3><a href="'.$post_marco->post_name.'">'.$post_marco->post_title.'</a></h3></div>';
				echo '<div class="texto" style="height:100px;overflow:hidden">'.$post_marco->post_excerpt .'</div>';
				echo '</div><!--#descripcion-->';
				echo '</div><!--detalle-->';
			}
			echo '<script>'."\n";
			echo 'var t;'."\n";
			echo 'var timer_destacados_is_on=1;'."\n";
			echo 'var destacado_actual = "";'."\n";
			echo 'var arreglo_destacados = new Array(""';
			$destacados = $wpdb->get_results($query,ARRAY_A);
			foreach($destacados as $d) {
				echo ', "wp_posts_'.@$d["id_registro"].'"';
			}
			$destacados = $wpdb->get_results($query,ARRAY_A);
			foreach($destacados as $d) {
				echo ', "wp_posts_'.@$d["id_registro"].'"';
			}
			echo ');'."\n";
			echo 'function mover_a_proximo_destacado(){'."\n";
			echo 'var encontrado = 0;'."\n";
			echo 'for (i = 0; i < arreglo_destacados.length; i++) { '."\n";
			echo 'if(arreglo_destacados[i] == destacado_actual){'."\n";
			echo 'for (h = 0; h < arreglo_destacados.length; h++) { '."\n";
			echo 'if(i > 0){';
			echo 'jQuery("#" + arreglo_destacados[h]).css("display", "none");'."\n";
			echo '}';
			echo '}'."\n";
			echo 'jQuery("#" + arreglo_destacados[i]).css("display", "inline-block");'."\n";
			echo 'destacado_actual = arreglo_destacados[i+1]'."\n";
			echo 't = setTimeout("mover_a_proximo_destacado()",4000);'."\n";
			echo 'return;'."\n";
			echo '}'."\n";
			echo '}'."\n";
			echo '}'."\n";
			echo '</script>';
		?>
		</div><!--#marco-->

        <div id="tira" class="destacados-tira">
			<div class="hide_jcarousel_to_be_destacados">
			<ul id="widget-destacados" class="idTabs jcarousel-skin-destacados">
          	<?php
          		$query = "select * from wp_destacados ";
          		$query .= "group by wp_destacados.tabla, wp_destacados.id_registro";
          		$query .= " order by max(fecha) desc ";
          		global $wpdb;
          		$destacados = $wpdb->get_results($query,ARRAY_A);
          		foreach($destacados as $d) {
          			$id_imagen = get_post_meta(@$d["id_registro"], '_thumbnail_id', true);
          			$image_attributes = wp_get_attachment_image_src( $id_imagen, 'Miniatura destacados' );
          			echo '<li><div class="item-avatar"><a href="#wp_posts_'.@$d["id_registro"].'" id="wp_posts_'.@$d["id_registro"].'">';
          			echo '<img width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" src="'.$image_attributes[0].'"/>';
          			echo '</a></div></li><!--#item-avatar-->';
          		}
          	?>
          </ul><!--#carusel-destacados-->
         </div><!--#hide_jcarousel_to_be-->
        </div><!--#tira-->
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#widget-destacados').jcarousel({scroll: 1});
				mover_a_proximo_destacado();
			});
		</script>
<?php
    echo $after_widget;
    wp_reset_postdata();
	}
}
?>