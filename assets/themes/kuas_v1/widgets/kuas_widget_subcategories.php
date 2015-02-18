<?php
/*
 * Widget Name: Sub Categories Widget
 * Widget URI: http://kuas.com/
 * Description: A widget to display latest sub categories in the theme.
 * Version: 1.0
 * Author: Kuas.
 * Author URI: http://kuas.com
 */

add_shortcode('kuas_subcategories', 'kuas_subcategories_shortcode');
function kuas_subcategories_shortcode( $atts, $content = null ) {
	$merged = ( shortcode_atts( array(
		'cat_id' => 0,
		'mode' => 'wide',
		'display' => 0,
		'show_nopost' => 0
	), $atts ) );
	$output = '<div class="shortcode-function">';
	$output .= kuas_beta_subcategory_list($merged['cat_id'], $merged['mode'], $merged['display'], $merged['show_nopost']);
	$output .= '</div>';
	return $output;
}

add_action( 'widgets_init', 'kuas_beta_sub_categories_widgets' );

function kuas_beta_sub_categories_widgets() {
	register_widget( 'kuas_beta_sub_categories_widget' );
}

class kuas_beta_sub_categories_widget extends WP_Widget {

function kuas_beta_sub_categories_widget() {
	
		/* Widget settings */
		$widget_ops = array( 'classname' => 'widget_sub_categories', 'description' => __('Widget ini menampilkan jumlah tulisan dari daftar sub kategori.', 'kuas-beta') );

		/* Create the widget */
		$this->WP_Widget( 'kuas_beta_sub_categories_widget', __('KuAs: Sub Categories', 'kuas-beta'), $widget_ops );
	}
	
function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$categories_select = $instance['categories_select'];
		$categories_display = $instance['categories_display'];
		$mode_select = $instance['mode_select'];
		$condition_page = $instance['condition_page'];
		$show_nopost = $instance['show_nopost'];

		$condition_page_function = array(
		  'no_function' => 'kuas_beta_no_function', //0
		  'is_home' => 'is_home',					//1
		  'is_category' => 'is_category',			//2
		  'is_single' => 'is_single',				//3
		  'is_tag' => 'is_tag',						//4
		  'is_search' => 'is_search',				//5
		  'is_archive' => 'is_archive'				//6
		);

		if(empty($title)){ $data_title = 1; } else { $data_title = 0; }

		if($categories_select == 0){			
			echo '<div id="error_message">'._e('Salah Pengaturan','kuas-beta').'<br />'._e('Sub Categories Widget','kuas-beta').'</div>';
		}
		else{
			switch ($condition_page) {
				//1-5 is one condition
				case '1':
					$ifelse = function(){ return is_home(); };
				break;
				case '2':
					$ifelse = function(){ return is_category(); };
				break;
				case '3':
					$ifelse = function(){ return is_single(); };
				break;	
				case '4':
					$ifelse = function(){ return is_tag(); };
				break;	
				case '5':
					$ifelse = function(){ return is_search(); };
				break;	
				//6-11 is two condition	
				case '6':
					$ifelse = function(){ return is_tag(); };
					$ifelse1 = function(){ return is_home(); };
				break;		
				case '7':
					$ifelse = function(){ return is_tag(); };
					$ifelse1 = function(){ return is_category(); };
				break;		
				case '8':
					$ifelse = function(){ return is_tag(); };
					$ifelse1 = function(){ return is_single(); };
				break;		
				case '9':
					$ifelse = function(){ return is_category(); };
					$ifelse1 = function(){ return is_home(); };
				break;		
				case '10':
					$ifelse = function(){ return is_category(); };
					$ifelse1 = function(){ return is_single(); };
				break;	
				case '11':
					$ifelse = function(){ return is_home(); };
					$ifelse1 = function(){ return is_single(); };
				break;
				//12-15 is three condition
				case '12':
					$ifelse = function(){ return is_home(); };
					$ifelse1 = function(){ return is_single(); };
					$ifelse2 = function(){ return is_category(); };
				break;
				case '13':
					$ifelse = function(){ return is_tag(); };
					$ifelse1 = function(){ return is_single(); };
					$ifelse2 = function(){ return is_category(); };
				break;
				case '14':
					$ifelse = function(){ return is_tag(); };
					$ifelse1 = function(){ return is_home(); };
					$ifelse2 = function(){ return is_category(); };
				break;	
				case '15':
					$ifelse = function(){ return is_tag(); };
					$ifelse1 = function(){ return is_single(); };
					$ifelse2 = function(){ return is_home(); };
				break;				
				default:
					$ifelse = function(){ return is_404(); };
				break;
			}

			if($condition_page >= 1 && $condition_page <=5 ){
				if( $ifelse() ) {
					echo $before_widget;

					if ( $title ){ ?> <h4 class="title bgColorBaseKuas_y2 headingColorBaseKuas"><?php echo $title; ?></h4> <?php }
					if ( kuas_beta_get_option( 'show_subcategories' ) != 0) { echo kuas_beta_subcategory_list($categories_select, $mode_select, $categories_display, $show_nopost, $data_title ); }
					else { echo '<div class="error_message">'._e('Aktifkan Sub Categories di Theme Setting','kuas-beta').'</div>'; }

					echo $after_widget;
				}
			}
			elseif($condition_page >= 6 && $condition_page <=11 ){
				if( $ifelse() || $ifelse1() ) {
					echo $before_widget;

					if ( $title ){ ?> <h4 class="title bgColorBaseKuas_y2 headingColorBaseKuas"><?php echo $title; ?></h4> <?php }
					if ( kuas_beta_get_option( 'show_subcategories' ) != 0) { echo kuas_beta_subcategory_list($categories_select, $mode_select, $categories_display, $show_nopost, $data_title ); }
					else { echo '<div class="error_message">'._e('Aktifkan Sub Categories di Theme Setting','kuas-beta').'</div>'; }

					echo $after_widget;
				}
			}
			elseif($condition_page >= 12 && $condition_page <=15 ){
				if( $ifelse() || $ifelse1() || $ifelse2() ) {
					echo $before_widget;

					if ( $title ){ ?> <h4 class="title bgColorBaseKuas_y2 headingColorBaseKuas"><?php echo $title; ?></h4> <?php }
					if ( kuas_beta_get_option( 'show_subcategories' ) != 0) { echo kuas_beta_subcategory_list($categories_select, $mode_select, $categories_display, $show_nopost, $data_title ); }
					else { echo '<div class="error_message">'._e('Aktifkan Sub Categories di Theme Setting','kuas-beta').'</div>'; }

					echo $after_widget;
				}
			}
			else{
					echo $before_widget;

					if ( $title ){ ?> <h4 class="title bgColorBaseKuas_y2 headingColorBaseKuas"><?php echo $title; ?></h4> <?php }
					if ( kuas_beta_get_option( 'show_subcategories' ) != 0) { echo kuas_beta_subcategory_list($categories_select, $mode_select, $categories_display, $show_nopost, $data_title ); }
					else { echo '<div class="error_message">'._e('Aktifkan Sub Categories di Theme Setting','kuas-beta').'</div>'; }

					echo $after_widget;	
			}
		}

	}
	
function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags( $new_instance['title'] );
	$instance['categories_select'] = $new_instance['categories_select'];
	$instance['categories_display'] = $new_instance['categories_display'];	
	$instance['mode_select'] = $new_instance['mode_select'];	
	$instance['condition_page'] = $new_instance['condition_page'];
	$instance['show_nopost'] = $new_instance['show_nopost'];		
	return $instance;
}

function form( $instance ) {	
		/* Set up some default widget settings. */
		$defaults = array(
		'title' => 'Sub Categories',
		'categories_select' => 0,
		'categories_display' => 10,
		'mode_select' => 0,
		'condition_page' => 'all',
		'show_nopost' => 0		
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'categories_select' ); ?>"><?php _e('Select Categories:', 'kuas-beta') ?></label>
			<?php 
			$categories = get_categories( array( 'hide_empty' => 1, 'hierarchical' => 0 ) );  ?>
			<select id="categories_select" name="<?php echo $this->get_field_name( 'categories_select' ); ?>">
				<option <?php selected( 0 == $instance['categories_select'] ); ?> value="0"><?php _e( '--none--', 'kuas-beta' ); ?></option>
				<?php foreach( $categories as $category ) : ?>
				<option <?php selected( $category->term_id == $instance['categories_select'] ); ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'condition_page' ); ?>"><?php _e('Select will show to:', 'kuas-beta') ?></label>
			<?php 
			$condition_page = array( "home","category","post","tag","search",
									 "tag+home","tag+category","tag+post","category+home","category+post","home+post",
									 "home+post+category","tag+post+category","tag+home+category","home+post+tag" );  
			?>
			<select id="condition_page" name="<?php echo $this->get_field_name( 'condition_page' ); ?>">
				<option <?php selected( 0 == $instance['condition_page'] ); ?> value="0"><?php _e( '--none--', 'kuas-beta' ); ?></option>
				<?php foreach( $condition_page as $num => $conditionpage ) : ?>
				<?php $num++ ?>
				<option <?php selected( $num == $instance['condition_page'] ); ?> value="<?php echo $num; ?>"><?php echo $conditionpage ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'mode_select' ); ?>"><?php _e('Pilih model tampilan:', 'kuas-beta') ?></label>
			<?php 
			$mode_select = array('wide','tail');  ?>
			<select id="mode_select" name="<?php echo $this->get_field_name( 'mode_select' ); ?>">
				<?php foreach( $mode_select as $mode ) : ?>
				<option <?php selected( $mode == $instance['mode_select'] ); ?> value="<?php echo $mode; ?>"><?php echo $mode; ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'categories_display' ); ?>"><?php _e('Berapa banyak ditampilkan:', 'kuas-beta') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'categories_display' ); ?>" name="<?php echo $this->get_field_name( 'categories_display' ); ?>" value="<?php echo $instance['categories_display']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_nopost' ); ?>"><?php _e('Tampilkan kategori jika isi tulisan 0:', 'kuas-beta') ?></label>
			<?php 
			$show_nopost = array('yes','no');  ?>
			<select id="mode_select" name="<?php echo $this->get_field_name( 'show_nopost' ); ?>">
				<?php foreach( $show_nopost as $k=>$shownopost ) : ?>
				<option <?php selected( $k == $instance['show_nopost'] ); ?> value="<?php echo $k; ?>"><?php echo $shownopost; ?></option>
				<?php endforeach; ?>
			</select>
		</p>

	<?php
	}
}

?>