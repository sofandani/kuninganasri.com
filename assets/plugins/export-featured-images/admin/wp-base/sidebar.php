	</div><!-- postbox-->
<div id="right-sidebar">	
<div id="sticky-anchor"></div>
<div class="postbox" id="sticky"> 
	<h3 style="cursor: default;" class="nowrap"><?php echo sprintf(__('Support %s', $this->plugin_slug), $this->WPB_PLUGIN_NAME);?> </h3>
		<div class="inside">
			


				<div class="intro"><p><strong><?php _e('If you enjoyed, please support this plugin:', $this->plugin_slug);?></strong></p></div>
				
				<p>
						<a href="http://wordpress.org/extend/plugins/<?php echo $this->WPB_SLUG;?>/"><?php _e('Rate the plugin 5★ on WordPress.org', $this->plugin_slug);?></a>
				</p>
				<p>
						<a href="<?php echo WPEFI_PLUGIN_URL.$this->plugin_slug.'.po';?>"><?php _e('Translate the plugin to your language', $this->plugin_slug);?></a>
				</p>
				
				<p>
						<a href="http://www.timersys.com/en/contact-us/"><?php _e('Hire me!', $this->plugin_slug);?></a>
				</p>
				
				<p><?php _e('Invite me a coffee :', $this->plugin_slug);?></p>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="K4T6L69EV9G2Q">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>


		
				
			
		
				<!--	<input type="submit" value="<?php _e('Save Settings','wsi');?>" class="button-large button-primary" name="save">-->
		</div>
</div>
 

</div><!--wsl_admin_tab_content-->

</div><!--wlsdiv-->