<?php

/**
 * Custom user profile fields.
 *
 * @param $user
 * @author Larry Judd 
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 

/**
 * Register new actions, initiate the action class
 * @see https://developers.elementor.com/docs/form-actions/add-new-action/
 * @since Alternate method.
 */
function extend_premier_register_new_form_actions( $form_actions_registrar ) {

	require_once( __DIR__ . '/forms/actions/action-1.php' );
	require_once( __DIR__ . '/forms/actions/action-2.php' );

	$form_actions_registrar->register( new \Action_1() );
	$form_actions_registrar->register( new \Action_2() );

}
//add_action( 'elementor_pro/forms/actions/register', 'extend_premier_register_new_form_actions' );
