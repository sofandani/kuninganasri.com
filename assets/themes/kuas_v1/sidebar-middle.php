<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @file      index.php
 * @package   kuas-beta
 * @author    Kuningan_Asri.
 * @link 	  http://kuas.com
 */
 ?> 
<div id="sidebar" class="sidebar_middle">

		<?php
			if(is_single()){
				//Define get Venue_ID metapost (single post)
				$venue_meta_post = get_post_meta( get_the_ID(), 'Venue_ID', 'single');
				//Set condition if post have Venue_ID metapost
				if($venue_meta_post){
					//Fetch JSON data Foursquare from define Venue_ID metapost
					$venueSidebarData = getVenueData($venue_meta_post,'venues',15);
					//Set condition if fetch data JSON Foursquare successfull and will identificatiob with meta->code 200
					if ($venueSidebarData->meta->code==200){
						//Set first global data for venue name
						$venueSidebarName = $venueSidebarData->response->venue->name;			
						//Set condition if function renderVenuesMaps is already exist
						if(function_exists('renderVenueMaps')){
							//If function exist, lets build HTML widget
							if($venue_meta_post){
							?>
								<div id="widget_foursquare" class="widget widget_map">
								<h4 class="headingColorBaseKuas">Peta Lokasi</h4>
								<?php
								//Call renderVenueMaps with arrgs variable
								$GeoLocSidebar = $venue_meta_post->response->venue->location;
								renderVenueMaps($venue_meta_post,$venueSidebarName,array('size'=>'250x300','limit_title'=>20),true);
								?>
								</div>
							<?php
							}
						}

						//Set condition if function renderVenueSpecials is already exist
						if(function_exists('renderVenueSpecials')){
							//If function exist, lets build HTML widget
							$venueTips = $venueSidebarData->response->venue->tips;
							if($venueTips->count > 0 && count($venueTips->groups[0]) > 0){
								?>
								<div id="widget_foursquare" class="widget widget_tips">
								<h4 class="headingColorBaseKuas">Tips <?php echo $venueSidebarName; ?></h4>
								<?php
								//Call renderVenueTips with arrgs variable
								renderVenueTips($venueTips,$VenueName,3,true);
								?>
								</div>
								<?php
							}
						}

						//Set condition if function renderVenueSpecials is already exist
						if(function_exists('renderVenueSpecials')){
							//If function exist, lets build HTML widget
							$venueSpecials = $venueSidebarData->response->venue->specials;
							if($venueSpecials->count > 0 && count($venueSpecials) > 0){
								?>
								<div id="widget_foursquare" class="widget widget_specials">
								<h4 class="headingColorBaseKuas">Penawaran <?php echo $venueSidebarName; ?></h4>
								<?php
								//Call renderVenueSpecials with arrgs variable
								renderVenueSpecials($venueSpecials,'text',false,true);
								?>
								</div>
								<?php
							}
						}

					}
					else{
						return renderErrorVenueData($venue->meta->errorDetail);
					}
				}

					$count_post_content = kuas_beta_extract_categories_from_post( get_the_ID() );
					$count_total_text_content = get_the_content();
					$count_total_text_content = str_word_count($count_total_text_content, 2);
					$c_p_c_kuas = count($count_post_content);
					$c_tt_c_kuas = count($count_total_text_content);
					//echo count($count_total_text_content);

					if($c_tt_c_kuas > 450 && $c_p_c_kuas > 2 || $c_tt_c_kuas < 450 && $c_p_c_kuas <= 2){ $limit_loop_cat = 5; }
					elseif($c_tt_c_kuas < 450 && $c_p_c_kuas > 2){ $limit_loop_cat = 3; }
					elseif($c_tt_c_kuas > 450 && $c_p_c_kuas < 2){ $limit_loop_cat = 7; }
					elseif($c_tt_c_kuas > 450 && $c_p_c_kuas == 0 || $c_tt_c_kuas < 450 && $c_p_c_kuas < 2){ $limit_loop_cat = 10; }
					else { $limit_loop_cat = 5; }

					$fix_limit_loop = $limit_loop_cat;

					if(count($venueSpecials) > 0 && $c_tt_c_kuas > 450 && $c_p_c_kuas > 2){$limit_loop_cat = $limit_loop_cat-2;}

				//Show Related Post from extract category by post categories one by one (per Category)
				kuas_beta_extract_post_same_categories(get_the_ID(),$limit_loop_cat,false,true);

				if($c_tt_c_kuas < 450 && $c_p_c_kuas == 1 OR $c_tt_c_kuas > 450 && $c_p_c_kuas <= 2 && $fix_limit_loop < 7 OR $c_tt_c_kuas > 450 && $c_p_c_kuas == 3 && $fix_limit_loop > 3){
					if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>			
							
					<div class="widget widget_text">
					<h3><?php _e( 'KuAs Theme', 'kuas-beta' ); ?></h3>			
					<div class="textwidget"><?php _e( 'Sidebar Middle Widget', 'kuas-beta' ); ?></div>
					</div>	
						
							
					<?php endif; // end sidebar widget area 
				}
			} 
			else {
				if(!is_page()):
				echo '<div id="widget_category_selection">';
				echo '<h3 align="center" class="headingColorBaseKuas bgColorBaseKuas_y2 shadowBottom">'.__('Pilih Saluran Lain','kuas-beta').'</h3>';
				kuas_beta_render_all_categories('21,39,40,41',1);
				echo '</div>';
				endif;
				//$arr_cat_sidebar = explode(",",kuas_beta_get_parent_category(true));
				//kuas_beta_extract_post_same_categories($arr_cat_sidebar,3,true);
			}
		?>

		<?php
		// Ads spot 3
		if(is_category() OR is_tag() OR is_archive()):
		wp125_single_ad(1);
		endif;
		?>
</div><!-- /sidebar -->
		