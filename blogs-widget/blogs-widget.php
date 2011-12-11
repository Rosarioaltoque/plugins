<?php
/**
 * Plugin Name: Blogs plugins
 * Plugin URI:  http://pampacomcdt.com.ar
 * Description: Muestra los &uacute;ltimos blogs
 * Author:      PampacomCDT
 * Version:     0.1
 * Author URI:  http://www.pampacomcdt.com.ar
 * Network:     true
 */

add_action( 'plugins_loaded', 'blogs_register_widgets' );

function blogs_register_widgets() {
	add_action('widgets_init', create_function('', 'return register_widget("Ultimos_Blogs_Widget");') );
}

class Ultimos_Blogs_Widget extends WP_Widget {
	function Ultimos_Blogs_Widget() {
		$this->WP_Widget( false, __( 'P2 Recent Tags', 'p2' ), array( 'description' => __( 'The tags from the latest posts.', 'p2' )));
		$this->default_num_to_show = 35;
	}

	function __construct() {
		$widget_ops = array( 'description' => __( 'Tira de blogs registrados en el sitio.', 'buddypress' ) );
		parent::__construct( false, __( 'Blogs', 'buddypress' ), $widget_ops );
		if ( is_active_widget( false, false, $this->id_base ) ) {
      wp_enqueue_script( 'jcarousel-for-widgets-js', get_template_directory_uri(). '/js/jquery.jcarousel.js', array( 'jquery' ) );
		}
	}

	function form( $instance ) {
		$title = ( isset( $instance['title'] ) ) ? esc_attr( $instance['title'] ) : '';
		$title_id = $this->get_field_id( 'title' );
		$title_name = $this->get_field_name( 'title' );
		$num_to_show = ( isset( $instance['num_to_show'] ) ) ? esc_attr( $instance['num_to_show'] ) : $this->default_num_to_show;
		$num_to_show_id = $this->get_field_id( 'num_to_show' );
		$num_to_show_name = $this->get_field_name( 'num_to_show' );

?>
	<p>
		<label for="<?php echo $title_id ?>"><?php _e( 'Title:', 'p2' ); ?>
			<input type="text" class="widefat" id="<?php echo $title_id ?>" name="<?php echo $title_name ?>"
				value="<?php echo $title; ?>" />
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
		$new_instance['num_to_show'] = (int)$new_instance['num_to_show']? (int)$new_instance['num_to_show'] : $this->default_num_to_show;
		return $new_instance;
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = (isset( $instance['title'] ) && $instance['title'])? $instance['title'] : __( 'Recent tags', 'p2' );
		$num_to_show = (isset( $instance['num_to_show'] ) && (int)$instance['num_to_show'])? (int)$instance['num_to_show'] : $this->default_num_to_show;

    echo $before_widget;
    echo $before_title
      . $instance['title']
      . $after_title;


    $args = array(
      'child_of'=> get_cat_ID('blogs'),
      'orderby' => 'name',
      'order'   => 'ASC'
    );
    $categories = get_categories($args);
    ?>
      <script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery('#widget-blogs').jcarousel({ visible: 1,scroll: 1});
        });
      
      </script>
      

      <div class="hide_jcarousel_to_be">
      <ul id="widget-blogs" class="jcarousel-skin-tango" style="width: 1000px;">
<?php
    foreach ($categories as $category) {
      ?> <li style="float: right;"> <?php
      if (function_exists('get_terms_meta')) {
        $metaValue = get_terms_meta($category->term_id, 'imagen');

        echo '<div class="item-avatar" >'.
          '<a href="'.get_category_link($category->term_id).'" title="' . sprintf( __( "View all posts in %s" ), $category->name).'" '.'>'
        .'<img height="189" title="" alt="" src="'.$metaValue[0].'">'
        .'</a></div>';
      }
      else {
        error_log("NO EXISTE LA FUNCION ! ! ! ! ! ! !");
      }
      echo '<div class="contenedor">'.'<div class="item-title"><a href="'.get_category_link($category->term_id).'" title="' . sprintf( __( "View all posts in %s" ), $category->name).'" '.'>'.$category->name. '</a></div>';
      echo '<div class="item-excer"><p>'.implode(' ', array_slice(explode(' ', $category->description), 0, 25)) .' [...]'   . '</p></div>';
      echo '<div class="item-meta"><span class="usuarios_widget"><img src="/wp-content/themes/rcd/images/ico_entr_18.png" width="18" height="18" align="absmiddle" />  '. $category->count . ' entradas. </span>';
	echo ' <span class="comuni_widget"><a href="https://www.facebook.com/sharer/sharer.php?u='.get_category_link($category->term_id).'" target="_blank"><img src="/wp-content/themes/rcd/images/social/16_bn_facebook.png" width="16" height="16" align="absmiddle" border="0" /></a> ';
	echo ' <a href="https://twitter.com/share?original_referer='.get_category_link($category->term_id).'" target="_blank"><img src="/wp-content/themes/rcd/images/social/16_bn_twitter.png" width="16" height="16" align="absmiddle" border="0" /></a></span>';
      echo '</div>';
      echo '</li>';
    }
?>
    </ul>
    </div>
<?php
    echo $after_widget;
    wp_reset_postdata();
	}
}

function get_ID_by_slug($page_slug) {
  $page = get_page_by_path($page_slug);
  error_log(print_r($page, true));
  if ($page) {
      return $page->ID;
  } else {
      return null;
  }
}

?>