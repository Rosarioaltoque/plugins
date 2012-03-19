<?php
/*
Plugin Name: Que hay al toque Widget
Plugin URI: http://rosarioaltoque.org.ar
Description: Un widget para mostrar que hay al toque
Author: Pampacom CDT
Author URI: http://pampacomcdt.com.ar
Version: 0.4
*/
add_action("widgets_init", array('Widget_Que_hay_al_toque', 'register'));
add_action('init', 'my_enqueue_js_function');
function my_enqueue_js_function(){
	wp_enqueue_script('OpenLayers', get_template_directory_uri().'/js/OpenLayers.js');
	wp_enqueue_script('RosarioAlToque', get_template_directory_uri().'/js/rosario-al-toque.js');
}
class Widget_Que_hay_al_toque extends WP_Widget {
	function Widget_Que_hay_al_toque() {
		$widget_ops = array( 'classname' => 'Widget_Que_hay_al_toque');
		$control_ops = array( 'width' => 850, 'height' => 350, 'id_base' => 'que-hay-al-toque-widget' );
		$this->WP_Widget( 'que-hay-al-toque-widget', __('Widget Que_hay_al_toque', 'a'), $widget_ops, $control_ops );
	}
	function control(){
    	echo 'I am a control panel';
	}
	function widget($args){
		extract( $args );
		$title = "Al toque";
		
    	echo $args['before_widget'];
	    echo $args['before_title'] . $title . $args['after_title'];
		echo	'
				<div id="mapaWidgetQueHayAlToque"></div>
				<div id="piemapaWidgetQueHayAlToque">
					<a href="/rosario-ciudad-digital/rosario-al-toque/servicios/que-hay-al-toque/">
						<img src="/wp-content/themes/rosarioaltoque/images/w_qhat_pie.png" align="absmiddle" />
					</a>
				</div>
				<script>
					var map = getMapaRosarioAlToque("mapaWidgetQueHayAlToque");
						
					var yelp = new OpenLayers.Icon("'.get_template_directory_uri().'/images/marker-mapa-rosario-al-toque.png", new OpenLayers.Size(22,22));
					var newl = new OpenLayers.Layer.GeoRSS( "Rosario al toque", "/feed/", {"icon":yelp});
					map.addLayer(newl);
						
					map.addControl(new OpenLayers.Control.LayerSwitcher());
				</script>
';

	    
	    echo $args['after_widget'];
	}
	function register(){
		register_sidebar_widget('Que_hay_al_toque', array('Widget_Que_hay_al_toque', 'widget'));
		register_widget_control('Que_hay_al_toque', array('Widget_Que_hay_al_toque', 'control'));
	}
}
?>