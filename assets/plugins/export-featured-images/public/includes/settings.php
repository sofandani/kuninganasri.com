<?php
/**
 * Get Settings
 *
 * Retrieves all plugin settings
 *
 * @since 1.0
 * @return array PREFIX settings
 */
function wpefi_get_settings() {

        $settings = get_option( 'wpefi_settings' );

        return apply_filters( 'wpefi_get_settings', $settings );
}