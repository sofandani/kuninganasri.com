<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @file      header.php
 * @package   kuas-beta
 * @author    Ofan Ebob.
 * @link 	  http://kuas.com
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]> <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]> <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" <?php language_attributes(); ?> id="kuas-html" xmlns="http://www.w3.org/1999/xhtml" <?php if(wp_is_mobile()==false): ?>xmlns:fb="http://ogp.me/ns/fb#"<?php endif; ?> itemscope itemtype="http://schema.org/<?php if(is_single() OR is_page()): echo 'Article'; else: echo 'Blog'; endif; ?>"><!--<![endif]-->
<?php do_action('kuas_meta_html'); ?>
<head>
<title><?php if( is_home() ){
bloginfo('name'); echo ' | '; bloginfo('description');
}
else { wp_title('', true); } ?></title>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<?php do_action('kuas_meta_head');
wp_head();
if ( is_singular() && get_option( 'thread_comments' ) ){
	wp_enqueue_script( 'comment-reply' );
}

do_action('inner_header_bottom') ?>

</head>
<?php do_action('kuas_between_head_body') ?>
<body id="body" <?php body_class(); ?>>
<?php do_action('inner_body_script_top') ?>
<div id="web_wrapper">

<?php if(is_user_logged_in()): ?><div id="navigation-top" class="is-login bgColorBaseKuas_y2 shadowBottom">
<?php else: ?><div id="navigation-top" class="is-public bgColorBaseKuas_y2 shadowBottom"><?php endif; ?>

	<div id="container-navigation-top">
		<a href="<?php echo home_url() ?>"  title="<?php bloginfo('name'); ?>" class="achor-top-nav nav-home">
			<div class="home-icon-url bgElement"></div>
		</a>
		
		<?php echo kuas_user_front_menu() ?>

		<?php wp_nav_menu(array('theme_location' => 'secondary', 
								'container' => 'div', 
								'container_id' => 'top-navigation-menu', 
								'container_class' => 'menu_navigation_site', 
								'menu_class' => 'ul-top first-ul-menu', 
								'fallback_cb' => 'kuas_beta_menu_fallback',
								'before' => '<span class="bgElement display-inline-block styling-top-achor">',
								'after' => '</span>'
							) ); ?>	
	
	</div>		
</div>

<div class="clear" id="space_login_navigation"></div>
<div id="container" class="is-logged hfeed shadowLeftRight6">

<?php 
if( is_home() && $paged < 2 && wp_is_mobile()==false){
?>
	<div id="header">
		<div class="header-wrap">
		<?php kuas_header_content(1) ?>
		</div>
	</div>
<?php
}
?>

<?php 
if(wp_is_mobile()==false){
kuas_beta_breadcrumbs();
}
?>

<?php do_action('before_content_container') ?>

<div id="navigasi-center">
		<?php if (kuas_beta_get_option( 'logo_url' )) { 
			if(wp_is_mobile()){ ?>
			<div class="favicon">
				<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
					<img src="<?php echo site_url('assets/images'); ?>/KuAs_Logo_250.png" alt="<?php bloginfo( 'name' ); ?>" />
				</a>
			<?php } else { ?>

			<div class="logo">
			<h1>
				<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
					<img src="<?php echo kuas_beta_get_option( 'logo_url' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
				</a>
			</h1>	
		<?php } } ?>	
		</div>

	<?php wp_nav_menu(array('theme_location' => 'primary',
							'container' => 'div', 
							'container_id' => 'nav', 
							'container_class' => 'menu_navigation_site', 
							'menu_class' => 'ul-main first-ul-menu', 
							'fallback_cb' => 'kuas_beta_menu_fallback' 
						) ); ?>

	<div class="nav-search">
		<?php get_template_part('searchform') ?>
	</div>

</div>

<div class="clear"></div>

<div id="content-container">