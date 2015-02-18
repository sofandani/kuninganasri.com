<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content-container and the #container div.
 * Also contains the footer widget area.
 *
 * @file      footer.php
 * @package   kuas-beta
 * @author    Sami Ch.
 * @link 	  http://gazpo.com
 */
 ?>
 
</div> <!-- /content-container -->

</div> <!-- /container -->

    <div id="footer" class="bgColorBaseKuas_y3">
        
        <div id="footer-container">

        	<div id="footer-widget-container">
		        <div class="footer-widgets">		            
					<?php if ( ! dynamic_sidebar( 'sidebar-3' ) ) : ?>				
						
					<div class="widget widget_text">
						<h3><?php _e( 'KuAs Theme', 'kuas-beta' ); ?></h3>			
						<div class="textwidget"><?php _e( 'Footer Widget', 'kuas-beta' ); ?></div>
					</div>
					
					<?php endif; // end footer widget area ?>	

					<?php 
					if(wp_is_mobile()==false){
						/* Ads spot 5 */ if(function_exists('wp125_single_ad')): ?>
						<div id="kuas_pariwara_widget-5" class="widget kuas_pariwara_widget">
						<?php /* Ads spot 5 */ wp125_single_ad(5); ?>
						</div>

						<div id="kuas_pariwara_widget-6" class="widget kuas_pariwara_widget">
						<?php /* Ads spot 6 */ wp125_single_ad(6); ?>
						</div>
					<?php
					endif;
					}
					?>
					
				</div>
	 		</div> <!-- Footer Widget -->

	 		<div id="footer-menu-container">
		        <?php wp_nav_menu( array( 'theme_location' => 'tertiary', 'container' => 'div', 'container_id' => 'footer-menu', 'menu_class' => 'foot-ul', 'fallback_cb' => 'kuas_beta_menu_fallback' ) ); ?>	
			</div> <!-- Footer Menu -->

			<div id="footer-info-container" class="bgColorBaseKuas_y1">
				<div class="footer-info bgColorBaseKuas_y1">	            
		            <p><?php bloginfo('description') ?></p>				
					<div class="credit">
						<p><?php _e('Copyright &copy; '.date('Y').' ', 'kuas-beta'); ?><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo ('name');?></a></p>
		            </div>
		        </div> 
	        </div> <!-- Footer Info -->

        </div> <!-- Footer Container -->

	</div> <!-- Footer -->

<?php wp_footer(); ?>

</div> <!-- Web Wrapper -->

<?php if(get_the_author_meta('twitter') && is_author() OR is_home()): ?>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script><!-- Twitter -->
<?php endif; ?>

<?php do_action('inner_body_script_bottom') ?>

</body>
</html>