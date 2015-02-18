<?php
/*
 * Widget Name: Social Links Widget
 * Widget URI: http://kuas.com/
 * Description: A widget to display the social links in footer or sidebar.
 * Version: 1.0
 * Author: KuAs.
 * Author URI: http://kuas.com/
 */

add_action( 'widgets_init', 'kuas_beta_social_widgets' );

function kuas_beta_social_widgets() {
	register_widget( 'kuas_beta_social_widget' );
}

class kuas_beta_social_widget extends WP_Widget {

function kuas_beta_social_widget() {
	/* Widget settings */
	$widget_ops = array( 'classname' => 'widget_social', 'description' => __('A widget to display the social links in footer or sidebar.', 'kuas-beta') );

	/* Create the widget */
	$this->WP_Widget( 'kuas_beta_social_widget', __('KuAs: Social Links', 'kuas-beta'), $widget_ops );
}
	
function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$modes = $instance['mode'];
		$twitter_url = $instance['twitter_url'];
		$facebook_url = $instance['facebook_url'];
		$gplus_url = $instance['gplus_url'];
		$youtube_url = $instance['youtube_url'];
		$path = $instance['path'];
		$pinterest_url = $instance['pinterest_url'];
		$vimeo_url = $instance['vimeo_url'];
		$instagram_url = $instance['instagram_url'];
		$rss_url = $instance['rss_url'];
		$contact = $instance['contact'];
		
		echo $before_widget;

		echo '<div id="social-widget" class="container_socmed_'.$modes.'">';

		if ( $title )
			echo $before_title . $title . $after_title;	

			echo '<ul id="ul_socmed_'.$modes.'">';
			$socmed_arr = array( 'twitter'=>$twitter_url,'facebook'=>$facebook_url,'gplus'=>$gplus_url,
								 'youtube'=>$youtube_url,'path'=>$path,'pinterest'=>$pinterest_url,
								 'vimeo'=>$vimeo_url,'instagram'=>$instagram_url,'rss'=>$rss_url,'contact'=>$contact, );	
			foreach ($socmed_arr as $name => $url) {
			    if(!empty($url)){
					echo '<li class="'.$name.'">';
					echo '<a href="'.$url.'" target="_blank" title="'.get_bloginfo('name').' '.ucfirst($name).'" class="normalTip">';
					if( $modes == 'tail' ) { echo '<i>&nbsp;</i><span>'; _e( ucfirst($name),'kuas-beta' ); echo '</span>'; } else { _e( '&nbsp;','kuas-beta' ); }
					echo '</a></li>';
				}
			}
        	echo '</ul></div>';
		echo $after_widget;
	}
	
function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['mode'] 			= $new_instance['mode'];
		$instance['twitter_url'] 	= $new_instance['twitter_url'];
		$instance['facebook_url'] 	= $new_instance['facebook_url'];
		$instance['gplus_url'] 		= $new_instance['gplus_url'];
		$instance['youtube_url'] 	= $new_instance['youtube_url'];
		$instance['path'] 			= $new_instance['path'];
		$instance['pinterest_url'] 	= $new_instance['pinterest_url'];
		$instance['vimeo_url'] 		= $new_instance['vimeo_url'];
		$instance['instagram_url'] 	= $new_instance['instagram_url'];
		$instance['rss_url'] 		= $new_instance['rss_url'];
		$instance['contact']		= $new_instance['contact'];
		return $instance;
	}

	function form( $instance ) {
	
		/* Set up some default widget settings. */
		$defaults = array(
		'title' => 'Interact',
		'mode' => 0,
		'twitter_url' => 'http://twitter.com/gazpodotcom',
		'facebook_url' => 'http://www.facebook.com/pages/Gazpocom/182192741803165',
		'gplus_url' => 'https://plus.google.com/112871708280864003956/',
		'youtube_url' => 'http://www.youtube.com/user/',
		'path' => '',
		'pinterest_url' => 'http://www.pinterest.com/',
		'vimeo_url' => 'http://www.vimeo.com/',
		'instagram_url' => 'http://www.instagram.com/',
		'rss_url' => 'http://feeds.feedburner.com/gazpo',
		'contact' => 'http://gazpo.com/contact/',
		
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<!-- Set Mode Style -->
		<p>
			<label for="<?php echo $this->get_field_id( 'mode' ); ?>"><?php _e('Mode:', 'kuas-beta') ?></label>
			<?php 
			$modes_arr = array( 'wide', 'tail' );
			?>
			<select id="mode" name="<?php echo $this->get_field_name( 'mode' ); ?>">
				<?php foreach( $modes_arr as $num => $tipe ) : ?>
				<option <?php selected( $tipe == $instance['mode'] ); ?> value="<?php echo $tipe; ?>"><?php echo $tipe ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<!-- Ad image url: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_url' ); ?>"><?php _e('Twitter URL:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_url' ); ?>" name="<?php echo $this->get_field_name( 'twitter_url' ); ?>" value="<?php echo $instance['twitter_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook_url' ); ?>"><?php _e('Facebook URL:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'facebook_url' ); ?>" name="<?php echo $this->get_field_name( 'facebook_url' ); ?>" value="<?php echo $instance['facebook_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'gplus_url' ); ?>"><?php _e('Google Plus URL:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'gplus_url' ); ?>" name="<?php echo $this->get_field_name( 'gplus_url' ); ?>" value="<?php echo $instance['gplus_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'youtube_url' ); ?>"><?php _e('Youtube URL:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'youtube_url' ); ?>" name="<?php echo $this->get_field_name( 'youtube_url' ); ?>" value="<?php echo $instance['youtube_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'path' ); ?>"><?php _e('Path Profile ID:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'path' ); ?>" name="<?php echo $this->get_field_name( 'path' ); ?>" value="<?php echo $instance['path']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'pinterest_url' ); ?>"><?php _e('Pinterest URL:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'pinterest_url' ); ?>" name="<?php echo $this->get_field_name( 'pinterest_url' ); ?>" value="<?php echo $instance['pinterest_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'vimeo_url' ); ?>"><?php _e('Vimeo URL:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'vimeo_url' ); ?>" name="<?php echo $this->get_field_name( 'vimeo_url' ); ?>" value="<?php echo $instance['vimeo_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'instagram_url' ); ?>"><?php _e('Instagram URL:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'instagram_url' ); ?>" name="<?php echo $this->get_field_name( 'instagram_url' ); ?>" value="<?php echo $instance['instagram_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'rss_url' ); ?>"><?php _e('RSS URL:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'rss_url' ); ?>" name="<?php echo $this->get_field_name( 'rss_url' ); ?>" value="<?php echo $instance['rss_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'contact' ); ?>"><?php _e('Contact Page URL:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'contact' ); ?>" name="<?php echo $this->get_field_name( 'contact' ); ?>" value="<?php echo $instance['contact']; ?>" />
		</p>
		
	<?php
	}
}

?>