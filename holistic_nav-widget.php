<?php

/**
 * Plugin Name: Holistic Navegation Widget
 * Description: A widget that controls navegation (de-)activating tags
 * Version: 0.1
 * Author: Andreas
 * Author URI: http://inf.udec.cl/~apolymer
 */

/* Add our function to the widgets_init hook. */
add_action( 'widgets_init', 'holistic_nav_widget' );

/* Function that registers our widget. */
function holistic_nav_widget() {
	register_widget( 'Holistic_Nav_Widget' );
}

class Holistic_Nav_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __( "controls navegation using tags") );
		parent::__construct('holistic_navegation_controls', __('Holistic Navegation Controls'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		if ( !empty($instance['title']) ) {
			$title = $instance['title'];
		} else { $title = __('Holistic Navegation');
		}
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div class="holistic_nav">';
//		echo '<div class="tagcloud">';

		$state = array();
		$state = wp_holistic_nav( '' );

?>
                        <div id="holistic_nav" style="color: red;"> 

			<?php if($state["activated"]) { ?>
                        <br> ETIQUETAS ACTIVADAS: <br> <?php echo $state["activated"]; ?>
			 <?php } ?>

                        <br> < lista de <?php echo $state["cardinality"]; ?> posteos filtrados
                        __________________________
                        </div>

			<?php if($state["implied"]) { ?>
                        <div id="holistic_nav" style="color: orange;"> 
                        <br> ETIQUETAS IMPLICADAS: <br> <?php echo $state["implied"]; ?>
                        __________________________
                        </div>
			 <?php } ?>

			<?php if($state["cutting"]) { ?>
                        <div id="holistic_nav" style="color: green;">
                        <br> ETIQUETAS HABILITADAS: <br> <?php echo $state["cutting"]; ?>
                        __________________________
                        </div>
			 <?php } ?>
<?php

		echo "</div>\n";
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
		return $instance;
	}

	function form( $instance ) {
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:') ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
	<?php foreach ( get_object_taxonomies('post') as $taxonomy ) :
				$tax = get_taxonomy($taxonomy);
				if ( !$tax->show_tagcloud || empty($tax->labels->name) )
					continue;
	?>
		<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, 'post_tag') ?>><?php echo $tax->labels->name; ?></option>
	<?php endforeach; ?>
	</select></p><?php
	}
}
