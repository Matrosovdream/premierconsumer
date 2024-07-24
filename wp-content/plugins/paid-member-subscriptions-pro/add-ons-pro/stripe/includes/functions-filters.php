<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// Return if PMS is not active
if( ! defined( 'PMS_VERSION' ) ) return;

/**
 * Add Stripe to the payment gateways array
 *
 * @param array $payment_gateways
 *
 */
function pms_in_payment_gateways_stripe( $payment_gateways = array() ) {

    $pms_payments_settings        = get_option( 'pms_payments_settings', array() );
    $disabled_base_stripe_gateway = true;

    if( !empty( $pms_payments_settings ) && !empty( $pms_payments_settings['active_pay_gates'] ) ){

        if( in_array( 'stripe', $pms_payments_settings['active_pay_gates'] ) ){

            $disabled_base_stripe_gateway = false;
        }

    }

    $payment_gateways['stripe'] = array(
        'display_name_user'  => __( 'Credit / Debit Card', 'paid-member-subscriptions' ),
        'display_name_admin' => 'Stripe',
        'class_name'         => 'PMS_IN_Payment_Gateway_Stripe'
    );

    if( version_compare( PMS_VERSION, '1.9.3', '>=' ) ) {
        $payment_gateways['stripe_intents'] = array(
            'display_name_user'  => __( 'Credit / Debit Card', 'paid-member-subscriptions' ),
            'display_name_admin' => $disabled_base_stripe_gateway === true ? 'Stripe' : 'Stripe (Payment Intents)',
            'class_name'         => 'PMS_IN_Payment_Gateway_Stripe_Payment_Intents'
        );
    }

    $payment_gateways['stripe_connect'] = array(
        'display_name_user'  => __( 'Stripe', 'paid-member-subscriptions' ),
        'display_name_admin' => 'Stripe Connect',
        'class_name'         => 'PMS_IN_Payment_Gateway_Stripe_Connect'
    );

    return $payment_gateways;

}
add_filter( 'pms_payment_gateways', 'pms_in_payment_gateways_stripe' );

function pms_in_admin_payment_gateways_stripe( $payment_gateways ){

    $pms_payments_settings        = get_option( 'pms_payments_settings', array() );
    $disabled_base_stripe_gateway = true;

    if( !empty( $pms_payments_settings ) && !empty( $pms_payments_settings['active_pay_gates'] ) ){

        if( in_array( 'stripe', $pms_payments_settings['active_pay_gates'] ) ){

            $disabled_base_stripe_gateway = false;
        }

    }

    if( $disabled_base_stripe_gateway && isset( $payment_gateways['stripe'] ) )
        unset( $payment_gateways['stripe'] );

    return $payment_gateways;

}
add_filter( 'pms_admin_display_payment_gateways', 'pms_in_admin_payment_gateways_stripe', 20, 2 );

/*
 * Add payment types for Stripe
 */
function pms_in_payment_types_stripe( $types ) {

    $types['stripe_card_one_time']              = __( 'Card - One Time', 'paid-member-subscriptions' );
    $types['stripe_card_subscription_payment']  = __( 'Subscription Recurring Payment', 'paid-member-subscriptions' );

    return $types;

}
add_filter( 'pms_payment_types', 'pms_in_payment_types_stripe' );


/**
 * Add data-type="credit_card" attribute to the pay_gate hidden and radio input for Stripe
 *
 */
function pms_in_payment_gateway_input_data_type_stripe( $value, $payment_gateway ) {

    if( in_array( $payment_gateway, array( 'stripe', 'stripe_intents', 'stripe_connect' ) ) )
        $value = str_replace( '/>', 'data-type="credit_card" />', $value );

    return $value;

}
add_filter( 'pms_output_payment_gateway_input_radio', 'pms_in_payment_gateway_input_data_type_stripe', 10, 2 );
add_filter( 'pms_output_payment_gateway_input_hidden', 'pms_in_payment_gateway_input_data_type_stripe', 10, 2 );


/**
 * Hooks to 'pms_confirm_cancel_subscription' from PMS to change the default value provided
 * Makes an api call to Stripe to cancel the subscription, if is successful returns true,
 * but if not returns an array with 'error'
 *
 * @param bool $confirmation
 * @param int $user_id
 * @param int $subscription_plan_id
 *
 * @return mixed    - bool true if successful, array if not
 *
 */
function pms_in_stripe_confirm_cancel_subscription( $confirmation, $user_id, $subscription_plan_id ) {

    // Get payment_profile_id
    $payment_profile_id = pms_member_get_payment_profile_id( $user_id, $subscription_plan_id );

    // Continue only if the profile id is a PayPal one
    if( !pms_in_is_stripe_payment_profile_id($payment_profile_id) )
        return $confirmation;

    // Instantiate the payment gateway with data
    $payment_data = array(
        'user_data' => array(
            'user_id'       => $user_id,
            'subscription'  => pms_get_subscription_plan( $subscription_plan_id )
        )
    );

    $stripe_gate = pms_get_payment_gateway( 'stripe', $payment_data );

    // Cancel the subscription and return the value
    $confirmation = $stripe_gate->cancel_subscription( $payment_profile_id );

    if( !$confirmation )
        $confirmation = array( 'error' => __( 'Something went wrong.', 'paid-member-subscriptions' ) );

    return $confirmation;

}
add_filter( 'pms_confirm_cancel_subscription', 'pms_in_stripe_confirm_cancel_subscription', 10, 3 );

/**
 * Adds error data to the failed payment message, if available.
 *
 * @param  string  $output      Default error message.
 * @param  boolean $is_register Equals to 1 if checkout was initiated from the registration form.
 * @param  int  $payment_id     ID of the payment associated with the error.
 * @return string
 */
function pms_in_stripe_error_message( $output, $is_register, $payment_id ) {

    if ( empty( $payment_id ) )
        return $output;

    $payment = new PMS_Payment( $payment_id );

    if ( isset( $payment->payment_gateway ) && !in_array( $payment->payment_gateway, array( 'stripe_connect', 'stripe_intents', 'stripe' ) ) )
        return $output;

    if ( empty( $payment->id ) || empty( $payment->logs ) )
        return $output;

    $log_entry = '';

    foreach( array_reverse( $payment->logs ) as $log ) {
        if ( !empty( $log['type'] ) && $log['type'] == 'payment_failed' ) {
            $log_entry = $log;
            break;
        }
    }

    if ( empty( $log_entry ) )
        return $output;

    $stripe_errors = pms_in_stripe_add_error_codes();

    if ( !empty( $stripe_errors[ $log_entry['error_code'] ] ) )
        $displayed_error = $stripe_errors[ $log_entry['error_code'] ];
    else if ( !empty( $log_entry['data']['message'] ) )
        $displayed_error = $log['data']['message'];
    else
        $displayed_error = __( 'Payment could not be processed.', 'paid-member-subscriptions' );

    ob_start(); ?>

    <div class="pms-payment-error">
        <p>
            <?php esc_html_e( 'The payment gateway is reporting the following error:', 'paid-member-subscriptions' ); ?>
            <span class="pms-payment-error__message"><?php echo esc_html( $displayed_error ); ?></span>
        </p>
        <p>
            <?php
                if( isset( $_GET['pms_stripe_authentication'] ) && $_GET['pms_stripe_authentication'] == 1 ){

                    if( is_user_logged_in() ){
                        $message = __( 'Please try again.', 'paid-member-subscriptions' );

                        if ( pms_get_page( 'account' ) != false && $payment_id != 0 ){
                            $payment = pms_get_payment( $payment_id );
                            $message = sprintf( $message, '<a href="'. pms_get_retry_url( $payment->subscription_id ) .'">', '</a>' );
                        }
                        else
                            $message = sprintf( $message, '', '' );

                        echo wp_kses_post( $message );
                    }
                    else {
                        $message = __( 'Please %slog in%s and try again.', 'paid-member-subscriptions' );

                        if ( $account_page = esc_url( pms_get_page( 'account', true ) ) )
                            $message = sprintf( $message, '<a href="'. $account_page .'">', '</a>' );
                        else
                            $message = sprintf( $message, '', '' );

                        echo wp_kses_post( $message );
                    }

                } else
                    echo pms_payment_error_message_retry( $is_register, $payment_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
        </p>
    </div>

    <?php

    $output = ob_get_contents();

    ob_end_clean();

    return $output;
}
add_filter( 'pms_payment_error_message', 'pms_in_stripe_error_message', 20, 3 );

/**
 * Localized array with Stripe error messages that are shown to the user
 *
 * @since 1.2.7
 * @param array  $error_codes
 */
function pms_in_stripe_add_error_codes() {

    return array(
        'card_not_supported'              => __( 'The card does not support this type of purchase.', 'paid-member-subscriptions' ),
        'card_velocity_exceeded'          => __( 'The customer has exceeded the balance or credit limit available on their card.', 'paid-member-subscriptions' ),
        'currency_not_supported'          => __( 'The card does not support the specified currency.', 'paid-member-subscriptions' ),
        'duplicate_transaction'           => __( 'A transaction with identical amount and credit card information was submitted very recently.', 'paid-member-subscriptions' ),
        'expired_card'                    => __( 'The card has expired.', 'paid-member-subscriptions' ),
        'fraudulent'                      => __( 'The payment has been declined as Stripe suspects it is fraudulent.', 'paid-member-subscriptions' ),
        'generic_decline'                 => __( 'The card has been declined for an unknown reason.', 'paid-member-subscriptions' ),
        'card_declined'                   => __( 'The card has been declined for an unknown reason.', 'paid-member-subscriptions' ),
        'incorrect_number'                => __( 'The card number is incorrect.', 'paid-member-subscriptions' ),
        'incorrect_cvc'                   => __( 'The CVC number is incorrect.', 'paid-member-subscriptions' ),
        'incorrect_pin'                   => __( 'The PIN entered is incorrect', 'paid-member-subscriptions' ),
        'incorrect_zip'                   => __( 'The ZIP/postal code is incorrect.', 'paid-member-subscriptions' ),
        'insufficient_funds'              => __( 'The card has insufficient funds to complete the purchase.', 'paid-member-subscriptions' ),
        'invalid_account'                 => __( 'The card, or account the card is connected to, is invalid.', 'paid-member-subscriptions' ),
        'invalid_amount'                  => __( 'The payment amount is invalid, or exceeds the amount that is allowed.', 'paid-member-subscriptions' ),
        'invalid_cvc'                     => __( 'The CVC number is incorrect.', 'paid-member-subscriptions' ),
        'invalid_expiry_year'             => __( 'The expiration year invalid.', 'paid-member-subscriptions' ),
        'invalid_number'                  => __( 'The card number is incorrect.', 'paid-member-subscriptions' ),
        'invalid_pin'                     => __( 'The PIN entered is incorrect', 'paid-member-subscriptions' ),
        'issuer_not_available'            => __( 'The card issuer could not be reached, so the payment could not be authorized.', 'paid-member-subscriptions' ),
        'lost_card'                       => __( 'The payment has been declined because the card is reported lost.', 'paid-member-subscriptions' ),
        'merchant_blacklist'              => __( 'The payment has been declined because it matches a value on the Stripe user\'s blocklist.', 'paid-member-subscriptions' ),
        'not_permitted'                   => __( 'The payment is not permitted.', 'paid-member-subscriptions' ),
        'processing_error'                => __( 'An error occurred while processing the card.', 'paid-member-subscriptions' ),
        'reenter_transaction'             => __( 'The payment could not be processed by the issuer for an unknown reason.', 'paid-member-subscriptions' ),
        'restricted_card'                 => __( 'The card cannot be used to make this payment (it is possible it has been reported lost or stolen).', 'paid-member-subscriptions' ),
        'stolen_card'                     => __( 'The payment has been declined because the card is reported stolen.', 'paid-member-subscriptions' ),
        'testmode_decline'                => __( 'A Stripe test card number was used.', 'paid-member-subscriptions' ),
        'withdrawal_count_limit_exceeded' => __( 'The customer has exceeded the balance or credit limit available on their card. ', 'paid-member-subscriptions' ),
    );

}

add_filter( 'pms_payment_logs_modal_header_content', 'pms_in_stripe_payment_logs_modal_header_content', 20, 3 );
function pms_in_stripe_payment_logs_modal_header_content( $content, $log, $payment_id ) {
    if ( empty( $payment_id ) || ( isset( $log['type'] ) && $log['type'] != 'payment_failed' ) )
        return $content;

    $payment = pms_get_payment( $payment_id );

    if ( empty( $payment->id ) || !in_array( $payment->payment_gateway, array( 'stripe', 'stripe_intents' ) ) )
        return $content;

    ob_start(); ?>

        <h2><?php esc_html_e( 'Payment Gateway Message', 'paid-member-subscriptions' ); ?></h2>

        <p>
            <strong><?php esc_html_e( 'Error code:', 'paid-member-subscriptions' ); ?> </strong>
            <?php echo esc_html( $log['error_code'] ); ?>
        </p>

        <p>
            <strong><?php esc_html_e( 'Message:', 'paid-member-subscriptions' ); ?> </strong>
            <?php echo esc_html( $log['data']['message'] ); ?>
        </p>

        <p>
            <strong><?php esc_html_e( 'More info:', 'paid-member-subscriptions' ); ?> </strong>
            <?php if ( !empty( $log['data']['data']['doc_url'] ) ) : ?>
                <a href="<?php echo esc_url( $log['data']['data']['doc_url'] ); ?>" target="_blank"><?php echo esc_html( $log['data']['data']['doc_url'] ); ?></a>
            <?php else : ?>
                <a href="https://stripe.com/docs/error-codes" target="_blank">https://stripe.com/docs/error-codes</a>
            <?php endif; ?>
        </p>

    <?php
    $output = ob_get_clean();

    return $output;
}

// When the Stripe Payment Intents API is active and the plugin tries to charge an user
// through the regular Charges API, switch the charge to the Payment Intents implementation
add_filter( 'pms_get_payment_gateway_class_name', 'pms_in_stripe_filter_payment_gateway', 20, 3 );
function pms_in_stripe_filter_payment_gateway( $class, $gateway_slug, $payment_data ){
    $active_stripe_gateway = pms_in_get_active_stripe_gateway();

    if( empty( $active_stripe_gateway ) )
        return $class;
    else if( $active_stripe_gateway == 'stripe_intents' && $gateway_slug == 'stripe' )
        return 'PMS_IN_Payment_Gateway_Stripe_Payment_Intents';

    return $class;
}

// Update the payment gateway slug in the payment data when processing a regular Stripe payment
// through the Payment Intents API
add_filter( 'pms_cron_process_member_subscriptions_payment_data', 'pms_in_stripe_filter_member_subscriptions_payment_data', 20, 2 );
function pms_in_stripe_filter_member_subscriptions_payment_data( $data, $subscription ){
    $active_stripe_gateway = pms_in_get_active_stripe_gateway();

    if( empty( $active_stripe_gateway ) )
        return $data;
    else if( $active_stripe_gateway == 'stripe_intents' && $subscription->payment_gateway == 'stripe' )
        $data['payment_gateway'] = $active_stripe_gateway;

    return $data;
}

// Show success message after successfull authentication
add_filter( 'pms_account_shortcode_content', 'pms_in_stripe_payment_authentication_success_message', 20, 2 );
add_filter( 'pms_member_account_not_logged_in', 'pms_in_stripe_payment_authentication_success_message', 20, 2 );
function pms_in_stripe_payment_authentication_success_message( $content, $args ){
    if( isset( $_GET['pms-action'], $_GET['success'] ) && $_GET['pms-action'] == 'authenticate_stripe_payment' ){
        ob_start(); ?>

            <div class="pms_success-messages-wrapper">
                <p>
                    <?php esc_html_e( 'Payment authenticated successfully.', 'paid-member-subscriptions' ); ?>
                </p>
            </div>

        <?php
        $message = ob_get_clean();

        return $message . $content;
    }

    return $content;
}

add_filter( 'pms_cron_process_member_subscriptions_subscription_data', 'pms_in_stripe_handle_subscription_expiration', 20, 3 );
function pms_in_stripe_handle_subscription_expiration( $subscription_data, $response, $payment ){
    if( $subscription_data['status'] == 'expired'
        && $payment->payment_gateway == 'stripe_intents'
        && pms_in_get_active_stripe_gateway() == 'stripe_intents'
        && pms_get_payment_meta( $payment->id, 'authentication', true ) == 'yes' ){

        unset( $subscription_data['billing_duration'] );
        unset( $subscription_data['billing_duration_unit'] );
        unset( $subscription_data['billing_next_payment'] );

        $subscription_data['status'] = 'pending';
    }

    return $subscription_data;
}

add_action( 'pms-settings-page_payment_general_after_gateway_checkboxes', 'pms_in_stripe_add_backend_warning' );
function pms_in_stripe_add_backend_warning( $options ){

    $css = 'display:none';

    if( isset( $options['active_pay_gates'] ) && in_array( 'stripe', $options['active_pay_gates'] ) )
        $css = '';

    echo '<div class="pms-form-field-wrapper pms-stripe-admin-warning" style="'. esc_attr( $css ) .'">
        <span>Important! Starting with <strong>September 14 2019</strong>, the Stripe API is changing in order to support 3D Secure Payments. Please switch to the <strong>Stripe (Payment Intents)</strong> gateway in order to support EU issued credit cards. <a href="https://www.cozmoslabs.com/docs/paid-member-subscriptions/add-ons/stripe-payment-gateway/#Strong_Customer_Authentication" target="_blank">Learn more</a></span>
    </div>';

}

add_filter( 'wppb_form_class', 'pms_in_stripe_add_class_to_edit_profile_form', 20, 2 );
function pms_in_stripe_add_class_to_edit_profile_form( $classes, $form ){

    $form = (array)$form;

    if( !isset( $form['args'] ) || !isset( $form['args']['form_type'] ) || $form['args']['form_type'] != 'edit_profile' )
        return $classes;

    if( !isset( $form['args']['form_fields'] ) )
        return $classes;

    $found_subscription_plans = false;

    foreach( $form['args']['form_fields'] as $field ){
        if( $field['field'] == 'Subscription Plans' ){
            $found_subscription_plans = true;
            break;
        }
    }

    if( $found_subscription_plans )
        $classes .= ' pms-form';

    return $classes;

}