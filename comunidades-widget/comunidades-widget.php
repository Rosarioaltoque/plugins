<?php
/*

Plugin Name: Comunidades Widget
Plugin URI: http://rosarioaltoque.com.ar
Description: Un widget para mostrar informacion sobre las &uacute;ltimas o mas populares comunidades.
Author: Pampacom CDT
Author URI: http://pampacomcdt.com.ar
Version: 0.1
*/
?>
<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_action( 'bp_register_widgets', 'groups_register_widgetss' );

/* Register widgets for groups component */
function groups_register_widgetss() {
	add_action('widgets_init', create_function('', 'return register_widget("BP_Groups_Grafic_Widget");') );
}

/*** GROUPS WIDGET *****************/

class BP_Groups_Grafic_Widget extends WP_Widget {
	function bp_groups_widget() {
		$this->_construct();
	}

	function __construct() {
		$widget_ops = array( 'description' => __( 'Una lista grafica de lo ultimo en comunidades', 'buddypress' ) );
		parent::__construct( false, __( 'Comunidades', 'buddypress' ), $widget_ops );

		if ( is_active_widget( false, false, $this->id_base ) ) {
      //wp_enqueue_script( 'groups_widget_groups_list-js', BP_PLUGIN_URL . '/bp-groups/js/widget-groups.dev.js', array( 'jquery' ) );
      wp_enqueue_script( 'jcarousel-for-widgets-js', get_template_directory_uri(). '/js/jquery.jcarousel.js', array( 'jquery' ) );
		}
	}

  function control(){
   	echo 'Soy el panel de control de las Comunidades';
	}

	function widget( $args, $instance ) {
		global $bp;

		$user_id = apply_filters( 'bp_group_widget_user_id', '0' );

		extract( $args );

		if ( empty( $instance['group_default'] ) )
			$instance['group_default'] = 'popular';

		if ( empty( $instance['title'] ) )
			$instance['title'] = __( 'Comunidades', 'buddypress' );

		echo $before_widget;
		echo $before_title
        .$instance['title']
        .$after_title;

    ?>
		<?php if ( bp_has_groups( 'user_id=' . $user_id . '&type=' . $instance['group_default'] . '&max=' . $instance['max_groups'] ) ) : ?>

      <script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery('#widget-comunidades').jcarousel({visible: 1,scroll: 1});
        });
      </script>
	  <div class="hide_jcarousel_to_be">
      <ul id="widget-comunidades" class="jcarousel-skin-tango">
      <?php while ( bp_groups() ) : bp_the_group(); ?>
        <li style="float: right;" >
          <div class="item-avatar">
            <a href="<?php bp_group_permalink() ?>" title="<?php bp_group_name() ?>">
              <?php bp_group_avatar('width=189&height=189&class="tutuca"') ?>
            </a>
          </div>
          <div class="contenedor">
            <div class="item-title"><a href="<?php bp_group_permalink() ?>" title="<?php bp_group_name() ?>"><?php bp_group_name() ?></a></div>
            <div class="item-excer">
              <?php bp_group_description_excerpt(); ?>
            </div>
            <div class="item-meta">
              <span class="usuarios_widget">
             <img src="/wp-content/themes/rcd/images/ico_user_18.png" width="9" height="18" align="absmiddle" />  <?php bp_group_member_count(); ?>
              </span>
              <span class="comuni_widget"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php bp_group_permalink() ?>" target="_blank"><img src="/wp-content/themes/rcd/images/social/16_bn_facebook.png" width="16" height="16" border="0" align="absmiddle" /></a>
	          <a href="https://twitter.com/share?original_referer=<?php bp_group_permalink() ?>" target="_blank"><img src="/wp-content/themes/rcd/images/social/16_bn_twitter.png" width="16" height="16" border="0" align="absmiddle" /></a></span>
            </div>
          </div>
        </li>
      <?php endwhile; ?>
			</ul>
            </div>
			<?php wp_nonce_field( 'groups_widget_groups_list', '_wpnonce-groups' ); ?>
			<input type="hidden" name="groups_widget_max" id="groups_widget_max" value="<?php echo esc_attr( $instance['max_groups'] ); ?>" />

		<?php else: ?>

			<div class="widget-error">
				<?php _e('There are no groups to display.', 'buddypress') ?>
			</div>

		<?php endif; ?>

		<?php echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['max_groups'] = strip_tags( $new_instance['max_groups'] );
		$instance['group_default'] = strip_tags( $new_instance['group_default'] );

		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title'         => __( 'Groups', 'buddypress' ),
			'max_groups'    => 5,
			'group_default' => 'active'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = strip_tags( $instance['title'] );
		$max_groups = strip_tags( $instance['max_groups'] );
		$group_default = strip_tags( $instance['group_default'] );
		?>

		<p><label for="bp-groups-widget-title"><?php _e('Title:', 'buddypress'); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 100%" /></label></p>

		<p><label for="bp-groups-widget-groups-max"><?php _e('Max groups to show:', 'buddypress'); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_groups' ); ?>" name="<?php echo $this->get_field_name( 'max_groups' ); ?>" type="text" value="<?php echo esc_attr( $max_groups ); ?>" style="width: 30%" /></label></p>

		<p>
			<label for="bp-groups-widget-groups-default"><?php _e('Default groups to show:', 'buddypress'); ?>
			<select name="<?php echo $this->get_field_name( 'group_default' ); ?>">
				<option value="newest" <?php if ( $group_default == 'newest' ) : ?>selected="selected"<?php endif; ?>><?php _e( 'Newest', 'buddypress' ) ?></option>
				<option value="active" <?php if ( $group_default == 'active' ) : ?>selected="selected"<?php endif; ?>><?php _e( 'Active', 'buddypress' ) ?></option>
				<option value="popular"  <?php if ( $group_default == 'popular' ) : ?>selected="selected"<?php endif; ?>><?php _e( 'Popular', 'buddypress' ) ?></option>
			</select>
			</label>
		</p>
	<?php
	}
}
?>