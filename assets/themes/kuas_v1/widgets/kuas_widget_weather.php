<?php
/*
Widget Name: Wunderground Widget
Widget URI: http://kuas.com
Description: A Widget for displaying the current weather using the Wunderground API
Version: 1.0
Author: KuAs
Author URI: http://kuas.com
License: non-Commercial
*/

add_action( 'widgets_init', 'kuas_weather_widget_init' );

if ( ! function_exists('kuas_weather_widget_init') ) {
	function kuas_weather_widget_init(){
		register_widget( 'kuas_weather_widget' );
	}
}

class kuas_weather_widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'kuas_weather_widget',
			'KuAs: Weather Widget',
			array( 'description' => 'A Widget for displaying the current weather using the Wunderground API' ) 
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$api_key1 = $instance['api_key1']; // d99ad94c123332dc
		$api_key2 = $instance['api_key2']; // 884f5119fba8dcca
		$api_key3 = $instance['api_key3']; // ea3e5444b26c226d
		$api_key4 = $instance['api_key4']; // fc2b1beb23d8c176
		$api_key5 = $instance['api_key5']; // d4c777b679398c1f
		$city = $instance['city'];
		$country = $instance['country'];
		$timezone_q = $instance['timezone_q'];
		$location = $city .'_'. $country;

		if(empty($api_key1) OR empty($api_key2) OR empty($api_key3) OR empty($api_key4) OR empty($api_key5)) die('<div id="error_message">Weather API key empty</div>');

		// FIND AND CACHE CITY ID
		$city_name_slug	= sanitize_title( $location );
		$weather_transient_name	= 'wunderground-weather-' . $city_name_slug;

		// Set Local TIMEZONE
		if(empty($timezone_q)){
			$timezone_q = 'Asia/Beijing';
		}

		date_default_timezone_set(get_option('timezone_string'));
		date_default_timezone_set( $timezone_q );

	    // CLEAR THE TRANSIENT
	    if( isset($_GET['clear_wunderground']) ){
	    	delete_transient( $weather_transient_name );
	    }

		// GET WEATHER DATA
		if( get_transient( $weather_transient_name ) ){ //Set condition if transient (cache WP) for weather data is stored
			$wunderground_data = get_transient( $weather_transient_name );
	    	$mesage_data = 'transient_data';
	    	$random_keys = 'transient_api';
		}	
		else { //If	transient (cache WP) for weather data is not found, is will create new data from fetching API server wunderground.com
			//Set random 5 API Keys from wunderground for handling overlimit request per minutes by users
			$api_keys = array( $api_key1, $api_key2, $api_key3, $api_key4, $api_key5 );
			$random_keys = randomArrayVar($api_keys);

			//Define API winderground URL with parameyter
			$fix_language = explode('_',WPLANG);
			$api_url = 'http://api.wunderground.com/api/';
			$api_parameter = '/conditions/forecast/lang:'.$fix_language[1].'/q/'.$location.'.json';
			$url_fetch = $api_url . $random_keys . $api_parameter; 
			$response = wp_remote_get( $url_fetch );

			if( is_wp_error( $response ) ) { //Set condition become redirect header & delete transient if fetch data failed
				/*global $wp;
				 *$uri_web_kuas = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
				 */
				$mesage_data = 'is_error_fetch';
				$path_web_kuas = $_SERVER['REQUEST_URI'];
				$uri_web_kuas = $path_web_kuas.'?clear_wunderground';
				wp_redirect( home_url( $uri_web_kuas ), 302 );
				exit;
			}
			
			$wunderground_data = json_decode($response['body']);
			// SET THE TRANSIENT, CACHE FOR AN HOUR
			set_transient( $weather_transient_name, $wunderground_data, apply_filters( 'wunderground_cache', 60*60*05 ) ); 
			$mesage_data = 'json_data';
		}

		echo $before_widget;
		?>
		<div class='wunderground-widget <?php echo $mesage_data ?>' id="<?php echo $random_keys ?>">
			<div class="wunderground-box hover-effect">	
			
			<?php
			if($wunderground_data->{'current_observation'}){
				//Set the data fetch from API
				//From Current Observation root JSON
				$current = $wunderground_data->{'current_observation'};
				$location = $current->{'display_location'}->{'full'};
				$temp = $current->{'feelslike_c'};
				$temp_dew = $current->{'dewpoint_c'};
				$wind = $current->{'wind_kph'};
				$wind_string = $current->{'wind_string'};
				$humidity = $current->{'relative_humidity'};
				$summary = $current->{'weather'};
				$icon = $current->{'icon'};
				//$icon_url = $current->{'icon_url'};
				
				//From Forecast root JSON
				$forecast = $wunderground_data->{'forecast'};
				$txt_forecast = $forecast->{'txt_forecast'};
				$simpleforecast = $forecast->{'simpleforecast'}->{'forecastday'};

				//From Text Forecast root JSON Forecast Day
				$forecastday = $txt_forecast->{'forecastday'}[0];
				$temp_high = $simpleforecast[0]->{'high'}->{'celsius'};
				$temp_low = $simpleforecast[0]->{'low'}->{'celsius'};
				$text = $forecastday->{'fcttext_metric'};

				//Define default PHP time()
				$default_get_times = time();
				
				//Define hourly time by 12format
				$hourly_local = date("g",$default_get_times);

				//Set condition AM or PM times
				if( date( "A", $default_get_times ) == 'AM' ){
					
					if ( $hourly_local == 12 || $hourly_local > 5 ) { // Siang
						$icon_uri = content_url( 'images/'.$txt_forecast->{'forecastday'}[0]->{'icon'}.'.gif', __FILE__ );
						$temp = akurasiSuhuKuningan($temp,20,'PM',$hourly_local);
						$temp_low = akurasiSuhuKuningan($temp_low,20,'PM',$hourly_local);
						$temp_high = akurasiSuhuKuningan($temp_high,20,'PM',$hourly_local);
					}
					elseif ( $hourly_local <= 5 ) { // Malam
						$icon_uri = content_url( 'images/nt_'.$txt_forecast->{'forecastday'}[0]->{'icon'}.'.gif', __FILE__ );
						$temp = akurasiSuhuKuningan($temp,15,'AM',$hourly_local);
						$temp_low = akurasiSuhuKuningan($temp_low,15,'AM',$hourly_local);
						$temp_high = akurasiSuhuKuningan($temp_high,15,'AM',$hourly_local);
					}

				}
				elseif( date( "A", $default_get_times ) == 'PM' ){
					
					if ( $hourly_local == 12 || $hourly_local > 5 ) { // Malam
						$icon_uri = content_url( 'images/nt_'.$txt_forecast->{'forecastday'}[0]->{'icon'}.'.gif', __FILE__ );
						$temp = akurasiSuhuKuningan($temp,15,'AM',$hourly_local);
						$temp_low = akurasiSuhuKuningan($temp_low,15,'AM',$hourly_local);
						$temp_high = akurasiSuhuKuningan($temp_high,15,'AM',$hourly_local);
					}
					elseif ( $hourly_local <= 5 ) { // Siang
						$icon_uri = content_url( 'images/'.$txt_forecast->{'forecastday'}[0]->{'icon'}.'.gif', __FILE__ );
						$temp = akurasiSuhuKuningan($temp,20,'PM',$hourly_local);
						$temp_low = akurasiSuhuKuningan($temp_low,20,'PM',$hourly_local);
						$temp_high = akurasiSuhuKuningan($temp_high,20,'PM',$hourly_local);
					}

				}				
				
				//Build Default HTML parse for widget WP
				if(isset($title)){ ?>
					<div class="header-wunderground normalTip" title="<?php echo $text ?>">
						<h3 class='widget-title'><?php echo $title ?></h3>
						<div class="summary"><?php echo $summary ?></div>	
					</div>	
				<?php } ?>

					<div class="inner-wunderground">
						<div class="temp <?php echo date( "A", $default_get_times ) .' - '. $hourly_local ?>" style="background-image:url(<?php echo $icon_uri ?>)">
							<span class="value-temp"><?php echo $temp ?></span>
							<span class="matric-temp">&deg;C</span>
						</div>
						<div class="range">Berkisar: <?php echo $temp_low ?>&deg;C - <?php echo $temp_high ?>&deg;C</div>
						<div class="wind">Angin: <?php echo $wind ?>kph</div>
						<div class="humidity">Kelembaban: <?php echo $humidity ?></div>
						<div class="spacing-wunderground"></div>
						<?php
						//Looping array data from forecastday wunderground & remove first (mean --> array[0]) array to offset current day
						foreach (array_slice($simpleforecast,1,2) as $k => $s_f) {
							$k++;
							$pre_temp = $s_f->{'low'}->{'celsius'};
							$pre_icon = $s_f->{'icon'};
							$pre_icon_uri = content_url( 'images/'.$pre_icon.'.gif', __FILE__ );
							$pre_day = $s_f->{'date'}->{'weekday'};
							$pre_condition = $s_f->{'conditions'};
							?>
							<div class="prediction_wunderground normalTip" id="<?php echo $k ?>" title="<?php echo $pre_condition ?>">
								<img src="<?php echo $pre_icon_uri ?>" alt="<?php echo $icon ?>" align="absmiddle" width="50" height="50" />
								<br />
								<?php echo $pre_temp ?>&deg;C
								<h5><?php echo $pre_day ?></h5>
							</div>
							<?php
						}
						?>
					</div>
				<?php
				} 
				else {
					print('<div id="error_message">Data JSON/transient is failed, <a href="'.site_url('?clear_wunderground').'">click to fix</a></div>');
				}
				?>
			</div> <!-- div class = wunderground-box hover-effect -->
		</div> <!-- div class = wunderground-widget -->
		<?php	
		echo $after_widget;				
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['api_key1'] = strip_tags( $new_instance['api_key1'] );
		$instance['api_key2'] = strip_tags( $new_instance['api_key2'] );
		$instance['api_key3'] = strip_tags( $new_instance['api_key3'] );
		$instance['api_key4'] = strip_tags( $new_instance['api_key4'] );
		$instance['api_key5'] = strip_tags( $new_instance['api_key5'] );
		$instance['city'] = strip_tags( $new_instance['city'] );
		$instance['country'] = strip_tags( $new_instance['country'] );
		$instance['timezone_q'] = strip_tags( $new_instance['timezone_q'] );
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];}
		else { $title = 'New title'; }
		
		if ( isset( $instance[ 'api_key1' ] ) ) {
		$api_key1 = $instance[ 'api_key1' ];
		} else { $api_key1 = ''; }
		
		if ( isset( $instance[ 'api_key2' ] ) ) {
		$api_key2 = $instance[ 'api_key2' ];
		} else { $api_key2 = ''; }
		
		if ( isset( $instance[ 'api_key3' ] ) ) {
		$api_key3 = $instance[ 'api_key3' ];
		} else { $api_key3 = ''; }
		
		if ( isset( $instance[ 'api_key4' ] ) ) {
		$api_key4 = $instance[ 'api_key4' ];
		} else { $api_key4 = ''; }
		
		if ( isset( $instance[ 'api_key5' ] ) ) {
		$api_key5 = $instance[ 'api_key5' ];
		} else { $api_key5 = ''; }
		
		if ( isset( $instance[ 'city' ] ) ) {
		$city = $instance[ 'city' ];
		} else { $city = 'Kuningan'; }

		if ( isset( $instance[ 'country' ] ) ) {
		$country = $instance[ 'country' ];
		} else { $country = 'Indonesia'; }

		if ( isset( $instance[ 'timezone_q' ] ) ) {
		$timezone_q = $instance[ 'timezone_q' ];
		} else { $timezone_q = 'Asia/Beijing'; }	

		?>
		<p>		
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		
		<label for="<?php echo $this->get_field_id( 'api_key1' ); ?>"><?php _e( 'API Key 1:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'api_key1' ); ?>" name="<?php echo $this->get_field_name( 'api_key1' ); ?>" type="text" value="<?php echo esc_attr( $api_key1 ); ?>" />		
		
		<label for="<?php echo $this->get_field_id( 'api_key2' ); ?>"><?php _e( 'API Key 2:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'api_key2' ); ?>" name="<?php echo $this->get_field_name( 'api_key2' ); ?>" type="text" value="<?php echo esc_attr( $api_key2 ); ?>" />		
		
		<label for="<?php echo $this->get_field_id( 'api_key3' ); ?>"><?php _e( 'API Key 3:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'api_key3' ); ?>" name="<?php echo $this->get_field_name( 'api_key3' ); ?>" type="text" value="<?php echo esc_attr( $api_key3 ); ?>" />		
		
		<label for="<?php echo $this->get_field_id( 'api_key4' ); ?>"><?php _e( 'API Key 4:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'api_key4' ); ?>" name="<?php echo $this->get_field_name( 'api_key4' ); ?>" type="text" value="<?php echo esc_attr( $api_key4 ); ?>" />		

		<label for="<?php echo $this->get_field_id( 'api_key5' ); ?>"><?php _e( 'API Key 5:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'api_key5' ); ?>" name="<?php echo $this->get_field_name( 'api_key5' ); ?>" type="text" value="<?php echo esc_attr( $api_key5 ); ?>" />		

		<label for="<?php echo $this->get_field_id( 'city' ); ?>"><?php _e( 'City:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'city' ); ?>" name="<?php echo $this->get_field_name( 'city' ); ?>" type="text" value="<?php echo esc_attr( $city ); ?>" />

		<label for="<?php echo $this->get_field_id( 'country' ); ?>"><?php _e( 'Country:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'country' ); ?>" name="<?php echo $this->get_field_name( 'country' ); ?>" type="text" value="<?php echo esc_attr( $country ); ?>" />

		<label for="<?php echo $this->get_field_id( 'timezone_q' ); ?>"><?php _e( 'Time Zone:' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'timezone_q' ); ?>" name="<?php echo $this->get_field_name( 'timezone_q' ); ?>" type="text" value="<?php echo esc_attr( $timezone_q ); ?>" />				
		</p>
		<?php 
	}

}

if ( ! function_exists('akurasiSuhuKuningan') ) {
	function akurasiSuhuKuningan($suhu,$percent,$AMPM,$hourly_local){
		$akurasi = $suhu - ( ($suhu*$percent)/100 ); //Membuat persentasi dari nilai yg keluar
		$akurasi = ceil($akurasi); //Membulatkan bilangan desimal
		if($AMPM == 'PM' && $hourly_local > 8 && $hourly_local < 10 ){ $akurasi = (int) $akurasi-1; }
		elseif($AMPM == 'PM' && $hourly_local > 10 && $hourly_local < 12 ){ $akurasi = (int) $akurasi-2; }
		return $akurasi;
	}
}