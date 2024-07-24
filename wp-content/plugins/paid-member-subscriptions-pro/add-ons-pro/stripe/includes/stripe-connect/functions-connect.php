<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// Return if PMS is not active
if( ! defined( 'PMS_VERSION' ) ) return;

function pms_in_stripe_connect_get_api_credentials(){

    $environment = pms_is_payment_test_mode() ? 'test' : 'live';

    return array(
        'publishable_key' => get_option( 'pms_stripe_connect_'. $environment .'_publishable_key', '' ),
        'secret_key'      => get_option( 'pms_stripe_connect_'. $environment .'_secret_key', '' )
    );

}

function pms_in_stripe_connect_get_account_status(){

    $api_credentials = pms_in_stripe_connect_get_api_credentials();

    if( empty( $api_credentials['secret_key'] ) )
        return false;

    $stripe = new \Stripe\StripeClient( $api_credentials['secret_key'] );

    $account = pms_in_stripe_get_connect_account();

    if( empty( $account ) )
        return false;

    try {

        $account = $stripe->accounts->retrieve( $account, array() );

    } catch( Exception $e ){

        $environment = pms_is_payment_test_mode() ? 'test' : 'live';

        delete_option( 'pms_stripe_connect_'. $environment .'_account_id' );

        return [ 'message' => $e->getMessage() ];

    }

    if( $account->details_submitted != true )
        return 'details_submitted_missing';

    if( $account->charges_enabled == true )
        return 'charges_enabled_missing';

    if( $account->details_submitted == true && $account->charges_enabled == true )
        return true;

    return false;

}

function pms_in_stripe_get_connect_account(){

    $environment = pms_is_payment_test_mode() ? 'test' : 'live';

    return get_option( 'pms_stripe_connect_'. $environment .'_account_id', false );

}

function pms_in_stripe_connect_payment_request_enabled(){

    $payments_settings = get_option( 'pms_payments_settings' );

    if( isset( $payments_settings['stripe_connect_payment_request'] ) && $payments_settings['stripe_connect_payment_request'] == 'enabled' )
        return true;

    return false;
    
}

function pms_in_stripe_connect_get_account_country(){

    return get_option( 'pms_stripe_connect_account_country', false );

}

function pms_in_stripe_calculate_payment_amount( $subscription_plan ){

    if( empty( $subscription_plan->id ) )
        return 0;

    // need to take into account PayWhatYouWant, Discounts and Taxes
    $amount = apply_filters( 'pms_stripe_calculate_payment_amount', $subscription_plan->price, $subscription_plan );

    // Check PWYW pricing
    if( function_exists( 'pms_in_pwyw_pricing_enabled' ) && pms_in_pwyw_pricing_enabled( $subscription_plan->id ) ){

        if( !empty( $_POST['subscription_price_' . $subscription_plan->id ] ) )
            $amount = (int)$_POST['subscription_price_' . $subscription_plan->id ];

    }

    global $pms_prorate;

    if( is_user_logged_in() && class_exists( 'PMS_IN_ProRate' ) && isset( $pms_prorate ) ){
        $amount = $pms_prorate->get_stripe_intents_prorated_amount( $amount, $subscription_plan->id );
    }

    // Add sign-up fee if necessary
    if( !empty( $subscription_plan->sign_up_fee ) && apply_filters( 'pms_stripe_create_payment_intent_apply_sign_up_fee', true, $subscription_plan ) ){

        $form_location = PMS_Form_Handler::get_request_form_location( 'pmstkn_original' );

        if( in_array( $form_location, apply_filters( 'pms_checkout_signup_fee_form_locations', array( 'register', 'new_subscription', 'retry_payment', 'register_email_confirmation', 'change_subscription', 'wppb_register' ) ) ) )
            $amount = $amount + $subscription_plan->sign_up_fee;

    }

    // Apply discount code if present
    if( function_exists( 'pms_in_calculate_discounted_amount' ) && !empty( $_POST['discount_code' ] ) ){

        $discount_code = pms_in_get_discount_by_code( sanitize_text_field( $_POST['discount_code'] ) );

        $amount = pms_in_calculate_discounted_amount( $amount, $discount_code );

    }

    // Apply taxes if they are enabled
    if( function_exists( 'pms_in_tax_enabled' ) && pms_in_tax_enabled() ){
        $amount = apply_filters( 'pms_tax_apply_to_amount', $amount, $subscription_plan->id );
    }

    return $amount;

}

// AJAX hooks

add_action( 'wp_ajax_pms_process_checkout', 'pms_in_stripe_process_checkout' );
add_action( 'wp_ajax_nopriv_pms_process_checkout', 'pms_in_stripe_process_checkout' );
function pms_in_stripe_process_checkout(){

    if( !check_ajax_referer( 'pms_process_checkout', 'pms_nonce' ) )
        die();

    // this is simply added so the AJAX request to the website triggers the regular
    // form processing of the plugin



    // Initialize gateway
    // $gateway = new PMS_IN_Payment_Gateway_Stripe_Connect();
    // $gateway->init();

    // //$gateway->create_payment_intent();

    // die();

}

add_action( 'wp_ajax_pms_validate_checkout', 'pms_in_stripe_validate_checkout_handler' );
add_action( 'wp_ajax_nopriv_pms_validate_checkout', 'pms_in_stripe_validate_checkout_handler' );
function pms_in_stripe_validate_checkout_handler(){

    if( !check_ajax_referer( 'pms_process_checkout', 'pms_nonce' ) )
        die();

    pms_in_stripe_validate_checkout();

    $data = array(
        'success' => true,
    );

    echo json_encode( $data );

    die();

}

/**
 * This is triggered each time a Subscription Plan is selected in the form in order to update
 * the amount of the Payment Intent
 */
add_action( 'wp_ajax_pms_update_payment_intent_connect', 'pms_in_stripe_connect_update_payment_intent' );
add_action( 'wp_ajax_nopriv_pms_update_payment_intent_connect', 'pms_in_stripe_connect_update_payment_intent' );
function pms_in_stripe_connect_update_payment_intent(){

    if( !check_ajax_referer( 'pms_stripe_connect_update_payment_intent', 'pms_nonce' ) )
        die();

    if( !isset( $_POST['subscription_plans'] ) )
        die();

    if( empty( $_POST['intent_secret'] ) )
        die();

    // Verify validity of Subscription Plan
    $subscription_plan = pms_get_subscription_plan( absint( $_POST['subscription_plans'] ) );

    if( !isset( $subscription_plan->id ) )
        die();

    // Calculate new amount
    $amount = pms_in_stripe_calculate_payment_amount( $subscription_plan );

    // Initialize gateway
    $gateway = new PMS_IN_Payment_Gateway_Stripe_Connect();
    $gateway->init();

    $response = $gateway->update_payment_intent( sanitize_text_field( $_POST['intent_secret'] ), $amount, $subscription_plan );

    if( !empty( $response ) )
        echo json_encode( array( 'status' => $response->status, 'data' => array( 'plan_name' => $subscription_plan->name, 'amount' => $gateway->process_amount( $amount, pms_get_active_currency() ) ) ) );

    die();

}

/**
 * This is triggered when the payment has finished in the front-end and we need to update the
 * payment and subscription on the website
 */
add_action( 'wp_ajax_pms_stripe_connect_process_payment', 'pms_in_stripe_connect_process_payment' );
add_action( 'wp_ajax_nopriv_pms_stripe_connect_process_payment', 'pms_in_stripe_connect_process_payment' );
function pms_in_stripe_connect_process_payment(){

    // We use the same nonce as the process request for this one
    if( !check_ajax_referer( 'pms_process_payment', 'pms_nonce' ) )
        die();

    $payment_id      = !empty( $_POST['payment_id'] ) ? absint( $_POST['payment_id'] ) : 0;
    $subscription_id = !empty( $_POST['subscription_id'] ) ? absint( $_POST['subscription_id'] ) : 0;

    // Initialize gateway
    $gateway = new PMS_IN_Payment_Gateway_Stripe_Connect();
    $gateway->init();

    $gateway->process_payment( $payment_id, $subscription_id );
    die();

}

/**
 * Grab a fresh process payment nonce
 */
add_action( 'wp_ajax_pms_stripe_update_nonce', 'pms_in_stripe_connect_update_process_payment_nonce' );
add_action( 'wp_ajax_nopriv_pms_stripe_update_nonce', 'pms_in_stripe_connect_update_process_payment_nonce' );
function pms_in_stripe_connect_update_process_payment_nonce(){

    echo json_encode( wp_create_nonce( 'pms_process_payment' ) );
    die();

}

/**
 * When Stripe Connect is active and the plugin tries to charge an user through the 
 * regular Charges API or Payment Intents API, switch the charge to the Connect implementation
 */
add_filter( 'pms_get_payment_gateway_class_name', 'pms_in_stripe_connect_filter_payment_gateway', 20, 3 );
function pms_in_stripe_connect_filter_payment_gateway( $class, $gateway_slug, $payment_data ){

    $active_stripe_gateway = pms_in_get_active_stripe_gateway();

    if( empty( $active_stripe_gateway ) )
        return $class;
    else if( $active_stripe_gateway == 'stripe_connect' && $gateway_slug == 'stripe' )
        return 'PMS_IN_Payment_Gateway_Stripe_Connect';
    else if( $active_stripe_gateway == 'stripe_connect' && $gateway_slug == 'stripe_intents' )
        return 'PMS_IN_Payment_Gateway_Stripe_Connect';

    return $class;

}

/**
 * Adds extra system Payment Logs messages
 *
 * @param  string  $message    error message
 * @param  array   $log        array with data about the current error
 */
add_filter( 'pms_payment_logs_system_error_messages', 'pms_in_stripe_connect_payment_logs_system_error_messages', 20, 2 );
function pms_in_stripe_connect_payment_logs_system_error_messages( $message, $log ) {

    if ( empty( $log['type'] ) )
        return $message;

    $kses_args = array(
        'strong' => array()
    );

    switch( $log['type'] ) {
        case 'stripe_intent_created':
            $message = __( 'Payment Intent created.', 'paid-member-subscriptions' );
            break;
        case 'stripe_intent_processing':
            $message = __( 'Payment Intent is still processing. Subscription was activated until confirmation of success or failure is received.', 'paid-member-subscriptions' );
            break;
        case 'stripe_intent_attempted_confirmation':
            $message = __( 'Attempting to confirm Payment Intent.', 'paid-member-subscriptions' );
            break;
        case 'stripe_intent_confirmed':
            $message = __( 'Payment Intent confirmed successfully.', 'paid-member-subscriptions' );
            break;
        case 'stripe_intent_failed':
            $message = __( 'Payment Intent has failed.', 'paid-member-subscriptions' );
            break;
        case 'stripe_authentication_sent':
            $message = __( '3D Secure authentication required. An email with the confirmation link was sent to the user.', 'paid-member-subscriptions' );
            break;
        case 'stripe_authentication_succeeded':
            $message = __( '3D Secure authentication is successful.', 'paid-member-subscriptions' );
            break;
        case 'stripe_authentication_failed':
            $message = __( '3D Secure authentication has failed.', 'paid-member-subscriptions' );
            break;
        case 'stripe_authentication_link_not_clicked':
            $message = __( 'The user did not click on the confirmation link that was sent.', 'paid-member-subscriptions' );
            break;
        case 'stripe_returned_for_authentication':
            $message = __( 'User returned to the website for authentication.', 'paid-member-subscriptions' );
            break;
        case 'stripe_webhook_received':
            $message = sprintf( __( 'Stripe webhook received: %1$s. Event ID: %2$s' ,'paid-member-subscriptions' ), '<strong>' . $log['data']['event_type'] . '</strong>', '<strong>' . $log['data']['event_id'] . '</strong>' );
            break;
        case 'stripe_charge_refunded':
            $message = __( 'Payment was refunded in the Stripe Dashboard.', 'paid-member-subscriptions' );
            break;
        default:
            $message = $message;
            break;
    }

    return wp_kses( $message, $kses_args );

}

/**
 * Adds extra system Subscription Logs messages 
 *
 * @param  string  $message    error message
 * @param  array   $log        array with data about the current error
 */
add_filter( 'pms_subscription_logs_system_error_messages', 'pms_in_stripe_connect_add_subscription_log_messages', 20, 2 );
function pms_in_stripe_connect_add_subscription_log_messages( $message, $log ){

    if( empty( $log ) )
        return $message;

    switch ( $log['type'] ) {
        case 'stripe_webhook_subscription_expired':
            $message = __( 'Subscription expired because the payment was refunded in the Stripe Dashboard.', 'paid-member-subscriptions' );
            break;
        case 'stripe_webhook_setup_intent_failed':
            $message = sprintf( __( 'User attemped to setup a payment method for this subscription but failed. Reason: %s', 'paid-member-subscriptions' ), '<strong>' . $log['data']['message'] . '</strong>' );
            break;
    }

    return $message;

}

/**
 * Used to process the payment after a payment method redirects off-site and then returns the user
 */
add_action( 'template_redirect', 'pms_in_stripe_connect_handle_payment_method_return_url' );
function pms_in_stripe_connect_handle_payment_method_return_url(){

    if( !isset( $_GET['pms_stripe_connect_return_url'] ) || $_GET['pms_stripe_connect_return_url'] != 1 )
        return;

    if( empty( $_GET['payment_intent'] ) )
        return;

    $payment = pms_get_payments( array( 'transaction_id' => sanitize_text_field( $_GET['payment_intent'] ) ) );

    if( empty( $payment[0] ) )
        return;

    $payment = $payment[0];

    if( $payment->status == 'completed' )
        return;

    $gateway = new PMS_IN_Payment_Gateway_Stripe_Connect();
    $gateway->init();
    
    $response = $gateway->process_payment( $payment->id, $payment->member_subscription_id );
    
    if( !empty( $response['redirect_url'] ) ){
        wp_redirect( $response['redirect_url'] );
        die();
    }
    
    return;
    
}
