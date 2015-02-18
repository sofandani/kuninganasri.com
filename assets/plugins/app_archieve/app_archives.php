<?php
/*
Plugin Name: App Archives
Plugin URI: http://labs.alek.be/
Description: Display archives as a calendar.
Version: 0.3.0
Author: Aleksei Polechin (alek´)
Author URI: http://alek.be
License: GPLv3

//Original Name: archives-calendar-widget

/***** LICENSE *****

	Archives Calendar Widget for Wordpress
	Copyright (C) 2013  Aleksei Polechin (http://alek.be)

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
	
****/

// LOCALISATION
add_action('init', 'archivesCalendar_init');
function archivesCalendar_init() {
	load_plugin_textdomain('archives_calendar', false, dirname(plugin_basename(__FILE__)));
}

// ACTIVATION
function archivesCalendar_activation($network_wide){
	global $wpdb;
	if (isMU()){ //isMU verifyes id the site is in Multisite mode
		// check if it is a network activation - if so, run the activation function for each blog id
		if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids =  $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blogid) {
				$blog_id = $blogid->blog_id;
				switch_to_blog($blog_id);
				_archivesCalendar_activate();
			}
			switch_to_blog($old_blog);
			return;
		}   
	} 
	_archivesCalendar_activate();
}
function _archivesCalendar_activate(){
	global $wpdb;	
	if(!get_option( 'archivesCalendar' ))
		$options = array("css" => 1, "theme" => "default", "jquery" => 0, "js" => 1, "show_settings" => 1, "shortcode" => 0);
	else
		$options = get_option( 'archivesCalendar' );
		
	if(isMU()) {
		update_blog_option($wpdb -> blogid, 'archivesCalendar', $options);
		add_blog_option($wpdb -> blogid, 'archivesCalendar', $options);
	}
	else {
		update_option('archivesCalendar', $options);
		add_option('archivesCalendar', $options);
	}
}
register_activation_hook(__FILE__, 'archivesCalendar_activation');

function archivesCalendar_new_blog($blog_id) {
	global $wpdb;
	if (is_plugin_active_for_network('archives-calendar/archives-calendar.php')) {
		$old_blog = $wpdb->blogid;
		switch_to_blog($blog_id);
		_archivesCalendar_activate();
		switch_to_blog($old_blog);
	}
}
add_action( 'wpmu_new_blog', 'archivesCalendar_new_blog', 10, 6); // in case of creation of a new site in WPMU


// UNINSTALL 
function archivesCalendar_uninstall(){
	global $wpdb;
   if (isMU()) {
		$old_blog = $wpdb->blogid;
		$blogids =  $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blogids as $blogid) {
			$blog_id = $blogid->blog_id;
			switch_to_blog($blog_id);
			_archivesCalendar_uninstall();
		}
		switch_to_blog($old_blog);
		return;
	} 
	_archivesCalendar_uninstall();
}
function _archivesCalendar_uninstall(){
	global $wpdb;
	if (isMU()) delete_blog_option($wpdb->blogid, 'archivesCalendar');
	else delete_option('archivesCalendar');
}
register_uninstall_hook(__FILE__, 'archivesCalendar_uninstall');

// ADD Settings link in Plugins page when the plugin is activated
if(!function_exists('plugin_settings_link')){
	function plugin_settings_link($links) {
		$settings_link = '<a href="options-general.php?page=archives_calendar">'.__( 'Settings' ).'</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
	}
}
$acplugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$acplugin", 'plugin_settings_link' );


$archivesCalendar_options = get_option('archivesCalendar');

function archivesCalendar_jquery() {
    wp_enqueue_script('jquery');
}
function calendar_archives_js() {
	?>
	<script type="text/javascript">
		eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('12(R).Z(9($){r d=D($(\'.7-6 .10.4\').v(\'h\'));r f=$(\'.7-6 .4-8 > a.4\').11;i(f<=1)$(\'.7-6 .M-L\').H();J();y();$(\'.7-6 .B-4\').s(\'t\',9(e){e.E();i($(n).x(\'.m\'))I;u(d+1)});$(\'.7-6 .C-4\').s(\'t\',9(e){e.E();i($(n).x(\'.m\'))I;u(d-1)});$(\'.7-6 .M-L\').s(\'t\',9(e){$(n).S().T(\'.4-8\').U()});$(\'.7-6 .4-8\').X(9(e){$(n).H()});$(\'.7-6 .4-8 a.4\').s(\'t\',9(e){e.E();i($(n).x(\'.p\'))I;$(\'.7-6 .4-8 a\').A(\'p\');r a=D($(n).v(\'h\'));u(a);$(\'.7-6 .4-8\').H()});9 u(a){r b=d;i(a<b){$(\'.6-l .4\').g(\'j-k\',\'-F%\').g(\'q\',1);$(\'.6-l .4[h=\'+b+\']\').g({\'j-k\':0,\'z-o\':2}).w({\'q\':.5},K);$(\'.6-l .4[h=\'+a+\']\').g({\'z-o\':3}).w({\'j-k\':0})}G{$(\'.6-l .4:V(.W)\').g(\'j-k\',\'-F%\').g(\'q\',1);$(\'.6-l .4[h=\'+a+\']\').g({\'j-k\':0,\'q\':.3,\'z-o\':2}).w({\'q\':1},K);$(\'.6-l .4[h=\'+b+\']\').g({\'j-k\':0,\'z-o\':3}).w({\'j-k\':\'-F%\'})}d=a;r c=$(\'.7-6 .4-8 a.4[h=\'+d+\']\');$(\'.7-6 a.4-Y\').v(\'N\',c.v(\'N\')).O(c.O());y();J()}9 y(){i(d==f-1)$(\'.7-6 .B-4\').P(\'m\');G $(\'.7-6 .B-4\').A(\'m\');i(d==0)$(\'.7-6 .C-4\').P(\'m\');G $(\'.7-6 .C-4\').A(\'m\')}9 J(){$(\'.7-6 .4-8\').Q(\'a.p, a[h=\'+d+\']\').13(\'p\');$(\'.7-6 .4-8\').g(\'14\',-$(\'.7-6 .4-8\').Q(\'a.p\').o()*D($(\'.7-6 .4-15\').16()))}});',62,69,'||||year||archives|calendar|select|function|||||||css|rel|if|margin|left|years|disabled|this|index|selected|opacity|var|on|click|gotoYear|attr|animate|is|aCalCheckArrows||removeClass|prev|next|parseInt|preventDefault|100|else|hide|return|aCalSetYearSelect|300|down|arrow|href|html|addClass|find|document|parent|children|show|not|last|mouseleave|title|ready|current|length|jQuery|toggleClass|top|nav|height'.split('|'),0,{}));
	</script>
	<?php
}
function archives_calendar_styles(){
	$archivesCalendar_options = get_option('archivesCalendar');
	wp_register_style( 'archives-cal-css', plugins_url('themes/'.$archivesCalendar_options['theme'].'.css', __FILE__));
	wp_enqueue_style('archives-cal-css');
}

if($archivesCalendar_options['css'] == 1)	add_action('wp_enqueue_scripts', 'archives_calendar_styles');
if($archivesCalendar_options['js'] == 1) add_action('wp_head', 'calendar_archives_js');
if($archivesCalendar_options['jquery'] == 1) add_action( 'wp_enqueue_scripts', 'archivesCalendar_jquery' );




/***** WIDGET CLASS *****/
class Archives_Calendar extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'archives_calendar',
			'Archives Calendar',
			array( 'description' => __( 'Show archives as calendar', 'archives_calendar' ), )
		);
	}
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		$instance['function'] = 'no';
		echo archive_calendar($instance);
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['prev_text'] = $new_instance['prev_text'];
		$instance['next_text'] = $new_instance['next_text'];
		$instance['post_count'] = $new_instance['post_count'];
		return $instance;
	}

	public function form( $instance ) {
		$defaults = array(
			'title'      => __( 'Archives' ),
			'next_text' => '>',
			'prev_text' => '<',
			'post_count' => 1,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = $instance['title'];
		$prev = $instance['prev_text'];
		$next = $instance['next_text'];
		$count = $instance['post_count'];
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'prev_text' ); ?>"><?php _e( 'Previous button text:', 'archives_calendar' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'prev_text' ); ?>" name="<?php echo $this->get_field_name( 'prev_text' ); ?>" type="text" value="<?php echo esc_attr( $prev ); ?>" />
		<label for="<?php echo $this->get_field_id( 'next_text' ); ?>"><?php _e( 'Next button text:', 'archives_calendar' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'next_text' ); ?>" name="<?php echo $this->get_field_name( 'next_text' ); ?>" type="text" value="<?php echo esc_attr( $next ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e( 'Show number of posts:', 'archives_calendar' ); ?></label> 
		<select name="<?php echo $this->get_field_name( 'post_count' ); ?>" >
			<option <?php selected( 1, $count ); ?> value="1">
				<?php _e( 'Yes', 'archives_calendar' ); ?>
			</option>
			<option <?php selected( 0, $count ); ?> value="0">
				<?php _e( 'No', 'archives_calendar' ); ?>
			</option>
		</select>
		</p>
		<?php 
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "Archives_Calendar" );' ) );

/***** WIDGET CONSTRUCTION FUNCTION *****/
/* can be called directly archive_calendar($args) */
function archive_calendar($args = array()) {
	global $wpdb;
	$aloc = 'archives_calendar';
	$mnames = array( '', __('Jan', $aloc), __('Feb', $aloc), __('Mar', $aloc), __('Apr', $aloc), __('May', $aloc), __('Jun', $aloc), __('Jul', $aloc), __('Aug', $aloc), __('Sep', $aloc), __('Oct', $aloc), __('Nov', $aloc), __('Dec', $aloc) );

	$defaults = array(
		'next_text' => '>',
		'prev_text' => '<',
		'post_count' => true,
	);
	$args = wp_parse_args( (array) $args, $defaults );
	extract($args);
		
	$results = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month FROM $wpdb->posts WHERE post_type='post' AND post_status='publish' AND post_password='' ORDER BY year DESC, month DESC");
	
	$years = array();
	foreach ($results as $date) {
		if($post_count){
			$postcount = $wpdb->get_results("SELECT COUNT(ID) AS count FROM $wpdb->posts WHERE post_type='post' AND post_status='publish' AND post_password='' AND YEAR(post_date) = $date->year AND MONTH(post_date) = $date->month");
			$count = $postcount[0]->count;
		}
		else $count = 0;
		$years[$date->year][$date->month] = $count;
	}

	$totalyears = count($years);

	$yearNb = array();
	foreach ($years as $year => $months){
		$yearNb[] = $year;
	}
	
	if(is_archive()){
		global $post;
		$archiveYear = date('Y', strtotime($post->post_date)); // year to be visible
	}
	else $archiveYear = $yearNb[0]; // if no current year -> show the more recent

	$nextyear = ($totalyears > 1) ? '<a href="#" class="next-year"><span>'.$next_text.'</span></a>' : '';
	$prevyear = ($totalyears > 1) ? '<a href="#" class="prev-year"><span>'.$prev_text.'</span></a>' : '';
	
	$cal = "\n<!-- Archives Calendar Widget by Aleksei Polechin - alek´ - http://alek.be -->\n";
	$cal.= '<div class="calendar-archives">';
	$cal.= '<div class="cal-nav">'.$prevyear.'<div class="year-nav">';
		$cal .=  '<a href="'.get_year_link($archiveYear).'" class="year-title">'.$archiveYear.'</a>';
		$cal .= '<div class="year-select">';
		$i=0;
		foreach( $yearNb as $year ){
			$current = ($archiveYear == $year) ? " current" : "";
			$cal.= '<a href="'.get_year_link($year).'" class="year '.$year.$current.'" rel="'.$i.'" >'.$year.'</a>';
			$i++;
		}
		$cal.= '</div>';
		if ($totalyears > 1) $cal.= '<div class="arrow-down" title="'.__("Select archives year", $aloc).'">&#x25bc;</div>';
	$cal.= '</div>'.$nextyear.'</div>';
	$cal.= '<div class="archives-years">';

	$i=0;

	foreach ($years as $year => $months){
		$lastyear = ($i == $totalyears-1 ) ? " last" : "";
		$current = ($archiveYear == $year) ? " current" : "";

		$cal .= '<div class="year '.$year.$lastyear.$current.'" rel="'.$i.'">';
		for ( $month = 1; $month <= 12; $month++ ) {
			$last = ( $month%4 == 0 ) ? ' last' : '';
			if($post_count) {
				if(isset($months[$month])) $count = $months[$month];
				else $count = 0;
				$posts_text = ($count == 1) ? __('Post', 'archives_calendar') : __('Posts', 'archives_calendar');

				$postcount = '<span class="postcount"><span class="count-number">'.thousandsCurrencyFormat($count).'</span> <span class="count-text">'.$posts_text.'</span></span>';
			}
			else $postcount = "";
			if(isset($months[$month]))
				$cal .= '<div class="month'.$last.'"><a href="'.get_month_link($year, $month).'" title="Ada '.$count.' Tulisan"><span class="month-name">'.$mnames[$month].'</span>'.$postcount.'</a></div>';
			else
				$cal .= '<div class="month'.$last.' empty"><span class="month-name">'.$mnames[$month].'</span>'.$postcount.'</div>';
		}
		$cal .= "</div>\n";
		$i++;
	}
	$cal .= "</div></div>";

	if($function == "no") return $cal;
	else echo $cal;
}

/***** SHORTCODE *****/
if($archivesCalendar_options['shortcode']){
	add_filter( 'widget_text', 'shortcode_unautop');
	add_filter('widget_text', 'do_shortcode');
}

function archivesCalendar_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'next_text' => '>',
		'prev_text' => '<',
		'theme' => 'default',
		'post_count' => true,
	), $atts ) );

	$post_count = ($post_count == "true") ? true : false;
	$defaults = array(
		'next_text' => $next_text,
		'prev_text' => $prev_text,
		'theme' => $theme,
		'post_count' => $post_count,
		'function' => 'no',
	);
	
	return archive_calendar($defaults);
}
add_shortcode( 'arcalendar', 'archivesCalendar_shortcode' );

/***** SETTINGS ******/
function archivesCalendar_admin_init(){
	register_setting( 'archivesCalendar_options', 'archivesCalendar', 'archivesCalendar_options_validate' );
	add_settings_section('archivesCalendar_main', '', 'archivesCalendar_options', 'archivesCalendar_plugin');
}
add_action('admin_init', 'archivesCalendar_admin_init');

function ArchivesCalandarSettingsMenu() {
	global $archivesCalendar_options;
	if($archivesCalendar_options['show_settings'] == 1) $menu_name = 'Archives Calendar';
	else $menu_name = '';
	add_options_page('Archives Calendar Settings', $menu_name, 'manage_options', 'archives_calendar', 'archives_calendar_settings');
}

function archivesCalendar_options_validate($args) {
	if(!isset($args['show_settings'])) $args['show_settings'] = 0;
	else $args['show_settings'] = 1;

	if(!isset($args['css'])) $args['css'] = 0;
	else $args['css'] = 1;

	if(!isset($args['theme'])) $args['theme'] = "default";

	if(!isset($args['jquery'])) $args['jquery'] = 0;
	else $args['jquery'] = 1;

	if(!isset($args['js'])) $args['js'] = 0;
	else $args['js'] = 1;

	if(!isset($args['shortcode'])) $args['shortcode'] = 0;
	else $args['shortcode'] = 1;

	return $args;
}

function archives_calendar_settings(){?>
	<div class="wrap">
	<div class="icon32"><img src="<?php echo plugins_url('icon32.png', __FILE__);?>" /></div>
	<h2><?php _e('Archives Calendar Settings', 'archives_calendar');?></h2>
	<form method="post" action="options.php">
		<?php
		settings_fields('archivesCalendar_options');
		do_settings_sections('archivesCalendar_plugin');	
		?>			
	</form>
	</div>
	<?php 
}
add_action('admin_menu', 'ArchivesCalandarSettingsMenu');

function archivesCalendar_options(){
	$options = get_option('archivesCalendar');
	$theme = $options['theme'];
	add_thickbox();
	?>
	<style type="text/css">
		pre{font-size:11px; padding:10px; border:#CCC 1px solid; background:#f1f1f1; overflow:auto;}
		label{font-weight:bold;}
	</style>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<p>
				
						
				<div id="ac_preview" style="display:none;">
					<p>
					<strong><?php _e('Theme');?>:</strong> <select id="themepreview">
						<option <?php selected( 'default', $theme ); ?> value="default"><?php _e('Default', 'archives_calendar'); ?></option>
						<!--<option <?php selected( 'dark', $theme ); ?> value="dark">Light</option>-->
						<option <?php selected( 'twentytwelve', $theme ); ?> value="twentytwelve">Twenty Twelve</option>
						<option <?php selected( 'twentythirteen', $theme ); ?> value="twentythirteen">Twenty Thirteen</option>
						<!--<option <?php selected( 'custom', $theme ); ?> value="custom"><?php _e('Custom', 'archives_calendar');?></option>-->
					</select> <button class="button-primary ok_theme">OK</button> <button class="button cancel_theme"><?php _e('Cancel', 'archives_calendar');?></button>
						
						<script type="application/javascript">
							jQuery(document).ready(function($) {
								$('.calendar-archives.preview a').on('click', function(e) {
									e.preventDefault();
								});
								$('#themepreview').change(function(){
									css = $(this).val() + '.css';
									$("#ac_preview_css").remove();
									$("head").append('<link id="ac_preview_css" href="<?php echo plugins_url('', __FILE__); ?>/themes/' + css + '" type="text/css" rel="stylesheet" />');
								});

								$('.button.preview_theme').on('click', function(){
									$('#themepreview option[value='+$('select.theme_select').val()+']').attr('selected', true);
									$("#ac_preview_css").remove();
									$("head").append('<link id="ac_preview_css" href="<?php echo plugins_url('', __FILE__); ?>/themes/' + $('select.theme_select').val() + '.css" type="text/css" rel="stylesheet" />');
								});
								$('.ok_theme').on('click', function(){
									tb_remove();
									$('select.theme_select option[value='+$('#themepreview').val()+']').attr('selected', true);
								});
								$('.cancel_theme').on('click', function(){
									tb_remove();
								});
							});
						</script>
					<?php
						calendar_archives_js();
					?>
					</p>
					<br><br>
					<div style="width:280px; margin:auto;">
						<div class="calendar-archives preview">
							<div class="cal-nav">
								<a href="#" class="prev-year"><span>&lt;</span></a>
								<div class="year-nav">
									<a href="#" class="year-title">2013</a>
									<div class="year-select" style="top: 0px;">
										<a href="#" class="year 2013 current selected" rel="0">2013</a>
										<a href="#" class="year 2012" rel="1">2012</a>
									</div>
									<div class="arrow-down" title="<?php _e( 'Select archives year', 'archives_calendar') ;?>">
										▼
									</div>
								</div>
								<a href="#" class="next-year disabled"><span>&gt;</span></a>
							</div>
							<?php 
							$aloc = 'archives_calendar';
							$mnames = array( '', __('Jan', $aloc), __('Feb', $aloc), __('Mar', $aloc), __('Apr', $aloc), __('May', $aloc), __('Jun', $aloc), __('Jul', $aloc), __('Aug', $aloc), __('Sep', $aloc), __('Oct', $aloc), __('Nov', $aloc), __('Dec', $aloc) );
								
							$years = array(2013 => array( 3 => 4, 6 => 3, 1 => 2 ), 2012 => array( 2 => 4, 7 => 3, 8 => 2 ));

							$cal= '<div class="archives-years">';
							$i = 0;
							
							
							foreach ($years as $year => $months){
								$current = ($i == 0) ? " current" : "";
								$lastyear = ($i == 1) ? " last" : "";
								$cal .= '<div class="year '.$year.$current.$lastyear.'" rel="'.$i.'">';
								for ( $month = 1; $month <= 12; $month++ ) {
									$last = ( $month%4 == 0 ) ? ' last' : '';
										if(isset($months[$month])) $count = $months[$month];
										else $count = '0';
										$posts_text = ($count == 1) ? __('Post', 'archives_calendar') : __('Posts', 'archives_calendar');

										$postcount = '<span class="postcount"><span class="count-number">'.$count.'</span> <span class="count-text">'.$posts_text.'</span></span>';
									
									if(isset($months[$month]))
										$cal .= '<div class="month'.$last.'"><a href="#"><span class="month-name">'.$mnames[$month].'</span>'.$postcount.'</a></div>';
									else
										$cal .= '<div class="month'.$last.' empty"><span class="month-name">'.$mnames[$month].'</span>'.$postcount.'</div>';
								}
								$cal .= "</div>\n";
								$i++;
							}

							$cal .= "</div>";
							echo $cal;
							?>
						</div>
					</div>
					
					
					<div style="position: absolute; bottom:10px; text-align:center;">
						<span class="description"><?php _e("The theme's CSS file is not included in administration, this preview may be different from the website rendering.", 'archives_calendar'); ?></span>
					</div>
				</div>
					<input type="checkbox" id="css" name="archivesCalendar[css]" <?php ac_checked('css');?> /> <label for="css"><?php _e('Include CSS file', 'archives_calendar'); ?></label><br />
					<span class="description"><?php _e( 'Include CSS file from the plugin.<br /><strong>It\'s recommended to copy the CSS code to your theme´s <strong>style.css</strong> and uncheck this option.', 'archives_calendar' ); ?></strong></span>
					<p><strong><?php _e('Theme');?>:</strong> <select name="archivesCalendar[theme]" class="theme_select">
						<option <?php selected( 'default', $theme ); ?> value="default"><?php _e('Default', 'archives_calendar'); ?></option>
						<!--<option <?php selected( 'dark', $theme ); ?> value="dark">Dark</option>-->
						<option <?php selected( 'twentytwelve', $theme ); ?> value="twentytwelve">Twenty Twelve</option>
						<option <?php selected( 'twentythirteen', $theme ); ?> value="twentythirteen">Twenty Thirteen</option>
						<!--<option <?php selected( 'custom', $theme ); ?> value="custom"><?php _e('Custom', 'archives_calendar');?></option>-->
					</select> <a href="#TB_inline?width=350&height=400&inlineId=ac_preview" class="thickbox button preview_theme"><?php _e('Preview');?></a><br />
					<?php _e( "<strong>NOTE:</strong> if you have modified any plugin's CSS file it will be restored on next plugin update.", 'archives_calendar' ); ?></span>
					</p>
				</p>
				<p>
					<input type="checkbox" id="jquery" name="archivesCalendar[jquery]" <?php ac_checked('jquery');?> /> <label for="jquery"><?php _e('Include jQuery library', 'archives_calendar');?></label><br />
					<span class="description"><?php _e('Include jQuery library into your theme. Uncheck if your theme already includes jQuery library.<br /><strong>jQuery library is required.</strong>', 'archives_calendar');?></span>
				</p>
				<p>
					<input type="checkbox" id="js" name="archivesCalendar[js]" <?php ac_checked('js');?> /> <label for="js"><?php _e('Insert JavaScript code into <head>', 'archives_calendar');?></label><br />
					<span class="description"><?php _e('Insert javascript code into your themes <head> Uncheck only if you copy this code into your default .js file.', 'archives_calendar'); ?><br />
					<strong><?php _e('This code is required.', 'archives_calendar');?></strong></span>
					<p>
						<a href="#TB_inline?width=350&height=400&inlineId=ac_default_code" class="thickbox button preview_theme"><?php _e('Show default script', 'archives_calendar');?></a>
					</p>

					<div id="ac_default_code" style="display:none;">
						<h2 class="title">Default javascript:</h2>
						<textarea style="width:100%; height:80%;">jQuery(document).ready(function($) {
	//init
	var $wearein = parseInt($('.calendar-archives .current.year').attr('rel'));
	var totalyears = $('.calendar-archives .year-select > a.year').length;
	if(totalyears <= 1) $('.calendar-archives .arrow-down').hide();
	aCalSetYearSelect();
	aCalCheckArrows();

	$('.calendar-archives .prev-year').on('click', function(e){
		e.preventDefault();
		if( $(this).is('.disabled') ) return;
		gotoYear($wearein + 1);
	});
	$('.calendar-archives .next-year').on('click', function(e){
		e.preventDefault();
		if( $(this).is('.disabled') ) return;
		gotoYear($wearein - 1);
	});

	$('.calendar-archives .arrow-down').on('click', function(e){
		$(this).parent().children('.year-select').show();
	});
	$('.calendar-archives .year-select').mouseleave(function(e) {
		$(this).hide();
	});

	$('.calendar-archives .year-select a.year').on('click', function(e){
		e.preventDefault();
		if( $(this).is('.selected') ) return;

		$('.calendar-archives .year-select a').removeClass('selected');

		var rel = parseInt($(this).attr('rel'));
		gotoYear(rel);
		$('.calendar-archives .year-select').hide();		
	});

	function gotoYear(goto){
		var wearein = $wearein;
		
		if(goto < wearein){// go next (more recent)
			$('.archives-years .year').css('margin-left', '-100%').css('opacity', 1);
			$('.archives-years .year[rel='+wearein+']').css({'margin-left': 0, 'z-index': 2}).animate({'opacity': .5}, 300);
			$('.archives-years .year[rel='+goto+']').css({'z-index': 3}).animate({'margin-left': 0});
		}
		else{// go prev (older)
			$('.archives-years .year:not(.last)').css('margin-left', '-100%').css('opacity', 1);
			$('.archives-years .year[rel='+goto+']').css({'margin-left': 0, 'opacity': .3, 'z-index': 2}).animate({'opacity': 1}, 300);
			$('.archives-years .year[rel='+wearein+']').css({'margin-left': 0, 'z-index': 3}).animate({'margin-left': '-100%'});
		}
		$wearein = goto;

		var $year = $('.calendar-archives .year-select a.year[rel='+$wearein+']');

		$('.calendar-archives a.year-title').attr( 'href', $year.attr('href') ).html( $year.html() );
		aCalCheckArrows();
		aCalSetYearSelect();
	}

	function aCalCheckArrows(){
		if($wearein == totalyears-1) $('.calendar-archives .prev-year').addClass('disabled');
		else $('.calendar-archives .prev-year').removeClass('disabled');
		if($wearein == 0) $('.calendar-archives .next-year').addClass('disabled');
		else $('.calendar-archives .next-year').removeClass('disabled');
	}
	function aCalSetYearSelect(){
		$('.calendar-archives .year-select').find('a.selected, a[rel='+$wearein+']').toggleClass('selected');
		$('.calendar-archives .year-select').css('top', - $('.calendar-archives .year-select').find('a.selected').index() * parseInt($('.calendar-archives .year-nav').height()) );
	}
});</textarea>
					</div>
				</p>
				<hr />
				<p>
					<input type="checkbox" id="shortcode" name="archivesCalendar[shortcode]" <?php ac_checked('shortcode');?> /> <label for="shortcode">
						<?php _e('Enable Shortcode support in text widget', 'archives_calendar');?></label><br />
					<span class="description"><?php _e('Use the shortcode in a text widget to display Archives Calendar.', 'archives_calendar');?></span>
					<pre>[arcalendar next_text=">" prev_text="<" post_count="true" theme="default"]</pre>
				</p>
				<hr />
				<p>
					<input type="checkbox" id="soptions" name="archivesCalendar[show_settings]" <?php ac_checked('show_settings');?> /> <label for="soptions">
						<?php _e('Show link to Settings in admin menu', 'archives_calendar');?></label><br />
					<span class="description"><?php _e('Show link "Archives Calendar" in admin "Settings" menu. If unchecked you can enter settings from "Settings" link in "Plugins" page.', 'archives_calendar');?></span>
				</p>
				<hr />
				<p>
					<input name="Submit" type="submit" style="margin:20px 0;" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</div>

		</div>
	</div>
	<?php
}

function ac_checked($option, $value = 1){
	$options = get_option('archivesCalendar');
	if($options[$option] == $value) echo 'checked="checked"';
}

if (!function_exists('isMU')){
	function isMU(){
		if (function_exists('is_multisite') && is_multisite()) return true;
		else return false;
	}
}
?>