<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// Return if PMS is not active
if( ! defined( 'PMS_VERSION' ) ) return;

add_action( 'admin_post_pms_stripe_connect_platform_authorization_return', 'pms_in_stripe_connect_handle_authorization_return' );
add_action( 'admin_post_nopriv_pms_stripe_connect_platform_authorization_return', 'pms_in_stripe_connect_handle_authorization_return' );
function pms_in_stripe_connect_handle_authorization_return(){

    if( !isset( $_POST['environment'] ) )
        return;

    $environment = sanitize_text_field( $_POST['environment'] );

    if( !empty( $_POST['account_id'] ) )
        update_option( 'pms_stripe_connect_'. $environment .'_account_id', sanitize_text_field( $_POST['account_id'] ) );

    if( !empty( $_POST['stripe_publishable_key'] ) )
        update_option( 'pms_stripe_connect_'. $environment .'_publishable_key', sanitize_text_field( $_POST['stripe_publishable_key'] ) );
    
    if( !empty( $_POST['stripe_secret_key'] ) )
        update_option( 'pms_stripe_connect_'. $environment .'_secret_key', sanitize_text_field( $_POST['stripe_secret_key'] ) );

    $redirect_url = add_query_arg( array(
            'page'                       => 'pms-settings-page',
            'tab'                        => 'payments',
            'pms_stripe_connect_success' => 1,
        ),
        admin_url( 'admin.php#pms-stripe__gateway-settings' ) 
    );

    // set account country
    $gateway = new PMS_IN_Payment_Gateway_Stripe_Connect();
    $gateway->init();

    $gateway->set_account_country();

    wp_redirect( $redirect_url );
    die();

}

add_action( 'admin_init', 'pms_in_stripe_connect_platform_disconnect' );
function pms_in_stripe_connect_platform_disconnect(){

    if( !isset( $_GET['pms_stripe_connect_platform_disconnect'] ) || $_GET['pms_stripe_connect_platform_disconnect'] != 1 || !isset( $_GET['environment' ] ) )
        return;

    $environment = sanitize_text_field( $_GET['environment'] );

    delete_option( 'pms_stripe_connect_'. $environment .'_account_id' );
    delete_option( 'pms_stripe_connect_'. $environment .'_publishable_key' );
    delete_option( 'pms_stripe_connect_'. $environment .'_secret_key' );

}

/**
 * Register domain with Apple Pay when the Payment Request functionality is enabled
 */
function pms_in_stripe_connect_process_payment_request_setting( $settings ){

    if( !isset( $settings['stripe_connect_payment_request'] ) || empty( $settings['active_pay_gates'] ) )
        return $settings;

    if( !in_array( 'stripe_connect', $settings['active_pay_gates'] ) )
        return $settings;

    if( isset( $settings['stripe_connect_payment_request'] ) && $settings['stripe_connect_payment_request'] == 'enabled' ){

        $gateway = new PMS_IN_Payment_Gateway_Stripe_Connect();
        $gateway->init();

        if( !$gateway->apple_pay_domain_is_registered() ){
            // TODO: maybe do some error handling here, but need to figure out what those errors could be
            $gateway->apple_pay_register_domain();
        }

        // attempt to set country again when activating in case it isn't saved
        $gateway->set_account_country();
        
    }

    return $settings;

}
add_filter( 'pms_sanitize_settings', 'pms_in_stripe_connect_process_payment_request_setting' );