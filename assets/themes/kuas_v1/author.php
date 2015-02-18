<?php
/**
 * The template for displaying Author Archive pages.
 *
 * Used to display archive-type pages for posts by an author.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

<?php 
if ( have_posts() ) {
the_post();
?>

	<div id="content" >	

			<?php
			rewind_posts();
			$author_cover = get_the_author_meta( 'cover_profile' );
			$adding_bg_class_cover = $author_cover ? 'cover_active' : '';
			$styling_image_cover = $author_cover ? 'style="background-image:url('.get_the_author_meta( 'cover_profile' ).')"' : '';
			?>
			<div class="author-info <?php echo $adding_bg_class_cover ?>" data-cover="<?php echo get_the_author_meta( 'cover_profile' ); ?>" <?php echo $styling_image_cover ;?>>
				<div class="author-avatar">
					<?php _e(get_avatar( get_the_author_meta('email'), '130' ), 'kuas-beta'); ?>
				</div><!-- .author-avatar -->
				<div class="author-meta author-meta-page">
					<h2 class="author-title"><?php printf( __( 'Tentang %s', 'kuas-beta' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );  ?></h2>
					<div class="author-contact">
						<?php kuas_beta_author_contact_list(get_the_author_meta('ID'),true,'icon') ?>
					</div>
					<div class="clear"></div>
					<div class="author-description"><?php echo make_clickable(get_the_author_meta( 'description' )); ?></div>
				</div><!-- .author-description	-->
				<div class="author-statistic">
					<?php if(class_exists('BadgeOS')): ?>
					<span class="author-achievment child-author-statistic">
						<big><?php _e(thousandsCurrencyFormat(badgeos_get_users_points(get_the_author_meta('ID')),'in'),'kuas-beta') ?></big><br />
						<small>poin</small>
					</span>
					<span class="author-achievment child-author-statistic">
						<big><?php _e(thousandsCurrencyFormat(kuas_beta_badgeos_custom_count_earned(get_the_author_meta('ID')),'in'),'kuas-beta') ?></big><br />
						<small>lencana</small>						
					</span>
					<?php endif; ?>
					<span class="author-post child-author-statistic">
						<big><?php _e(thousandsCurrencyFormat(count_user_posts(get_the_author_meta('ID')),'in'),'kuas-beta') ?></big><br />
						<small><?php _e('tulisan', 'kuas-beta') ?></small>
					</span>
				</div>
			</div><!-- .author-info -->

			<div id="wrapper_post_list">
			<div id="posts-list">

				<h4 class="author-post-count" id="post-<?php echo get_the_author_meta('user_login') ?>">
				<?php printf( __( '%1$s tulisan %2$s', 'kuas-beta' ), count_user_posts(get_the_author_meta('ID')), esc_attr( get_the_author() ) ) ?>
				</h4>
				<div class="clear"></div>

				<?php /* Start the Loop */
				if ( function_exists('kuas_beta_list_post') ) {
					if(get_current_user_id() == get_the_author_meta('ID') OR is_user_logged_in()){
					kuas_beta_list_post('title',1);
					} else {
					kuas_beta_list_post('excerpt',1);
					}
				}
				?>

			</div><!-- #posts-list -->
			
			<?php if ( function_exists('kuas_beta_pagination') ) { kuas_beta_pagination(); } ?>
			</div>

	</div><!-- #content -->

<div id="sidebar" class="sidebar_big">

	<?php 
	if(is_user_logged_in()){
		if(function_exists('kuas_UserFrontPress_Form') && get_current_user_id() == get_the_author_meta('ID')){ ?>
			<div id="kuas_beta_frontpress_markup_html" class="widget widget_frontpress_markup_html">
			<h4>Kirimkan Tulisan</h4>
			<?php
			//Only Admin always visible & other user will limited by total pending post
			if(kuas_valid_frontpress_display(get_current_user_id(),5) == 1):
				//kuas_UserFrontPress_Form($print,$exclude,$include,$hirarcical);
				kuas_UserFrontPress_Form(1,'','18,38,49,70,69,21,29,17,37',0);
			else: ?>
				<div id="attention_message">
				<?php _e('Beberapa tulisan kiriman anda ditangguhkan.') ?>
				<div class="clear"><br /></div>
				<?php _e('Untuk sementara formulir submisi di non-aktifkan sampai tulisan di setujui') ?>
				<div class="clear"><br /></div>
				<?php _e('Jika keberatan, silahkan ') ?>
				<?php printf(__('kontak ke %1$s, atau melalui formulir di %2$s'),'<a href="mailto:'.get_bloginfo('admin_email').'">'.__('Email').'</a>','<a href="'.site_url('kontak').'">'.__('Kontak').'</a>') ?>
				</div>
			<?php 
			endif;
			?>
			</div>
		<?php
		}
		//Else IF LOGIN AND IS NOT CURRENT USER PAGE
		else {

			if(get_the_author_meta('instagram')): ?>
			<div id="kuas_beta_instamini_markup_html" class="widget widget_instamini_markup_html">
				<h4>Instagram</h4>
				<?php kuas_instagram_mini(get_the_author_meta('instagram'),array('parent'=>'ul','child'=>'li'),1,9,1) ?>
			</div>		
			<?php endif;

			if(get_the_author_meta('twitter')): ?>
			<div id="kuas_beta_twitter_markup_html" class="widget widget_twitter_markup_html">
				<h4>Twitter</h4>
				<div style="padding:10px">
				<a class="twitter-timeline" style="display:inline-block;min-height:335px;clear:both" height="320" data-tweet-limit="5" data-widget-id="377057394171191296" data-chrome="noheader noborders transparent" data-show-replies="false" data-screen-name="<?php echo get_the_author_meta('twitter') ?>"></a>
				</div>
			</div>
			<?php endif; 

		}
		?>

		<div class="clear"></div>

	<?php			
		if(kuas_beta_badgeos_custom_count_earned(get_the_author_meta('ID')) > 0){
			kuas_beta_user_badgeos_widget_custom_markup(get_the_author_meta('ID'),array('parent'=>'div','child'=>'a','class'=>'widget-achievements-listing'),0,array(100,100),1,'');
		}
	}
	//ELSE IF LOGIN
	else {

		if(wp_is_mobile()==false){
			if(kuas_beta_badgeos_custom_count_earned(get_the_author_meta('ID')) > 0){
				kuas_beta_user_badgeos_widget_custom_markup(get_the_author_meta('ID'),array('parent'=>'div','child'=>'a','class'=>'widget-achievements-listing'),8,array(100,100),1,'');
			}		
			?>

			<?php if(get_the_author_meta('instagram')): ?>
			<div id="kuas_beta_instamini_markup_html" class="widget widget_instamini_markup_html">
				<h4>Instagram</h4>
				<?php kuas_instagram_mini(get_the_author_meta('instagram'),array('parent'=>'ul','child'=>'li'),1,9,1) ?>
			</div>		
			<?php endif; ?>

			<?php if(get_the_author_meta('twitter')): ?>
			<div id="kuas_beta_twitter_markup_html" class="widget widget_twitter_markup_html">
				<h4>Twitter</h4>
				<div style="padding:10px">
				<a class="twitter-timeline" style="display:inline-block;min-height:335px;clear:both" height="320" data-tweet-limit="5" data-widget-id="377057394171191296" data-chrome="noheader noborders transparent" data-show-replies="false" data-screen-name="<?php echo get_the_author_meta('twitter') ?>"></a>
				</div>
			</div>
			<?php endif; 
		}
		
	}
	?>

</div>

<?php
}
else {
	global $wp_query;
	$get_author_id = $wp_query->get_queried_object();
	if ( isset( $get_author_id->user_nicename ) ) {
	    $author_id = $get_author_id->ID;
	}	
	if(is_user_logged_in() && get_current_user_id() == $author_id){ 
	?>
		<div id="content" class="wide-content empat_kosong_empat">
		<?php
		if(function_exists('kuas_UserFrontPress_Form')){ ?>
				<div id="kuas_beta_frontpress_markup_html" class="widget widget_frontpress_markup_html">
				<h2 class="title-pages" align="center">Kirimkan Tulisan</h2>
				<?php
				//Only Admin always visible & other user will limited by total pending post
				if(kuas_valid_frontpress_display(get_current_user_id(),5) == 1):
					kuas_UserFrontPress_Form(1,'','18,38,49,70,69,21,29,17,37',0);
				else: ?>
					<div id="attention_message">
					<?php _e('Beberapa tulisan kiriman anda ditangguhkan.') ?>
					<div class="clear"><br /></div>
					<?php _e('Untuk sementara formulir submisi di non-aktifkan sampai tulisan di setujui') ?>
					<div class="clear"><br /></div>
					<?php _e('Jika keberatan, silahkan ') ?>
					<?php printf(__('kontak ke %1$s, atau melalui formulir di %2$s'),'<a href="mailto:'.get_bloginfo('admin_email').'">'.__('Email').'</a>','<a href="'.site_url('kontak').'">'.__('Kontak').'</a>') ?>
					</div>
				<?php 
				endif;
				?>
				</div>
			<?php
		}
		?>
		</div><!-- #content -->
	<?php
	}
	else {
		if(is_user_logged_in()){		
		?>
			<div id="content" class="wide-content empat_kosong_empat">
				<div id="post-0" class="post">
					<h2 align="center">
					<?php _e( 'Tidak Terdaftar :p', 'kuas-beta' ); ?>
					</h2>
					<p align="center"><?php _e('Hubungi kami jika halaman ini tidak ada perubahan setelah anda mengirimkan tulisan.', 'kuas-beta') ?></p>
					<p align="center"><?php printf(__('Melalui %1$s (khusus), atau melalui formulir di %2$s', 'kuas-beta'),'<a href="mailto:'.get_bloginfo('admin_email').'">'.__('Email').'</a>','<a href="'.site_url('kontak').'">'.__('Kontak', 'kuas-beta').'</a>') ?></p>
				</div>
			</div>

		</div><!-- #content -->

<?php
		}
		else {
			kuas_force_redirect();
		}
	}
}
?>

<?php get_footer(); ?>