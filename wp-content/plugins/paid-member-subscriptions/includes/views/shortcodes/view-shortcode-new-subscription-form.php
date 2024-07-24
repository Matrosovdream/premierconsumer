<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

    /*
     * HTML output for new subscription form
     *
     * @param $atts     - is available from parent file, in the register_form method of the PMS_Shortcodes class
     */
    $form_name = 'new_subscription';
    /* TEQTOP Custom Code Start */
    $user_id = get_current_user_id();
    $csl = apply_filters( 'wpml_current_language', null );
    $plans = $atts['subscription_plans'];
    $hideReason = $type = "";
    $showCreditMonitoring = $showClasses = $showS = $showBankDist = $forCM = false;

    $credit_monitoring_plans = array("12039","11984","7231","17015","19950","19953","22873","22874","22875");
    if(!pms_is_member_of_plan( $credit_monitoring_plans, $user_id )){        
        $mobile = get_user_meta($user_id, "mobile", true);
        if($mobile == ""){
            $showCreditMonitoring = true;
        }
    }

    if(in_array(16336,$plans) || in_array(16370,$plans) || in_array(16637,$plans) || in_array(16638,$plans)){
        $forCM = true;
    }
    if(in_array(16336,$plans) || in_array(16637,$plans)){
        echo "<style>.pms-subscription-plan-price{display:none;}</style>";
        $showClasses = true;
    }

    $get_sp_name = get_user_meta($user_id, "sp_fname", true);
    $get_sp_last_name = get_user_meta($user_id, "sp_lname", true);
    $get_sp_mobile = get_user_meta($user_id, "sp_mobile", true);
    $get_sp_email = get_user_meta($user_id, "sp_email", true);
    if($get_sp_name == ""){
        $showS = true;
    }
    if(in_array(16637,$plans) || in_array(16638,$plans) || in_array(19780,$plans)){
        $hideReason = 'style="display:none;"';
    }else{
        $metaReason = get_user_meta($user_id, "course_reason", true);
        if($metaReason != ""){
            $hideReason = 'style="display:none;"';
        }
    }
    if(in_array(16336,$plans) || in_array(16370,$plans)){
        $showBankDist = true;
    }
    if(in_array(16336,$plans) || in_array(16370,$plans)){
        $type = "MIM";
    }
    if(in_array(16637,$plans) || in_array(16638,$plans)){
        $type = "CIMBK";
    }

    /* TEQTOP Custom Code End */
?>

<form id="pms_<?php echo esc_attr( $form_name ); ?>-form" class="pms-form" method="POST">

    <?php do_action( 'pms_' . $form_name . '_form_top', $atts ); ?>

    <?php

        wp_nonce_field( 'pms_' . $form_name . '_form_nonce', 'pmstkn' );
        pms_display_success_messages( pms_success()->get_messages('subscription_plans') );

    ?>
    <!-- TEQTOP Custom Code Start -->
    <?php 
    if($csl == "en"){
        if($showClasses){
    ?>
        <div class="free-classes">
            <label>
                <input type="radio" name="tclass" value="single" class="sub-class" checked> Single Class
            </label>
            <label>
                <input type="radio" name="tclass" value="joint" class="sub-class"> Joint Class
            </label>
        </div>
    <?php
        }
    }else{
        if($showClasses){
    ?>
        <div class="free-classes">
            <label>
                <input type="radio" name="tclass" value="single" class="sub-class" checked> Clase Individual
            </label>
            <label>
                <input type="radio" name="tclass" value="joint" class="sub-class"> Clase Conjunta
            </label>
        </div>
    <?php
        }
    }
    ?>
    <!-- TEQTOP Custom Code End -->

    <ul class="pms-form-fields-wrapper">

        <?php

            $field_errors = pms_errors()->get_error_messages( 'subscription_plans' );
            echo '<li class="pms-field pms-field-subscriptions ' . ( !empty( $field_errors ) ? 'pms-field-error' : '' ) . '">';
                echo pms_output_subscription_plans( $atts['subscription_plans'], $atts['exclude'], false, (isset($atts['selected']) ? trim($atts['selected']) : '' ), 'new_subscription' ); //phpcs:ignore  WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</li>';

        ?>

    </ul>

    <!-- TEQTOP Custom Code Start -->
    <?php 
    if($forCM){
        if($csl == "en"){
            ?>
            <div class="custom-new-subscription" id="cim_mim">
                <input type="hidden" name="course_type" value="<?php echo $type; ?>">
                <li class="pms-field pms-clang-field" style="display: none;">
                    <select id="pms_clang" name="clang" required>
                        <option value="english" selected>English</option>
                        <option value="spanish">Spanish</option>
                    </select>
                </li>
                <div class="col-md-6 pms-fields pms-creason-field" <?php echo $hideReason; ?>>
                    <input id="pms_creason" name="creason" placeholder="Reason for the course *" type="text" value="<?php echo esc_attr( isset( $_POST['creason'] ) ? sanitize_text_field( $_POST['creason'] ) : '' ); ?>"/>
                </div>
        <?php 
            if($showBankDist){
        ?>
                <div class="col-md-6 pms-fields pms-bdistrict-field" style="display: none;">
                    <input id="pms_bdistrict" name="bdistrict" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_bdistrict', __( 'Bankruptcy District *', 'paid-member-subscriptions' ) ) ); ?>" type="text" value="FL" required/>
                </div>
                <div class="col-md-6 pms-fields pms-bnumber-field">
                    <input id="pms_bnumber" name="bnumber" type="text" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_bnumber', __( 'Bankruptcy ID Number *', 'paid-member-subscriptions' ) ) ); ?>" value="<?php echo esc_attr( isset( $_POST['bnumber'] ) ? sanitize_text_field( $_POST['bnumber'] ) : '' ); ?>" required/>
                </div>
                <br><br>
                <br><br>
        <?php 
            }
            if($showS){
        ?>
                <!-- <br><br>
                <br><br> -->
                <h5 class="spouse_heading text-center" style="display:none;margin-top:-50px;">Información del cónyuge</h5>
                <div style="display: none;" id="spouse_div">
                    <div class="col-md-6 pms-fields pms-sp_fname-field">
                        <input id="pms_sp_fname" placeholder="Spouse First Name *" name="sp_fname" type="text" />
                    </div>
                    <div class="col-md-6 pms-fields pms-sp_lname-field">
                        <input id="pms_sp_lname" placeholder="Spouse's last name *" name="sp_lname" type="text" />
                    </div>
                    <div class="col-md-6 pms-fields pms-sp_mobile-field">
                        <input id="pms_sp_mobile" placeholder="Spouse's phone number *" name="sp_mobile" type="text" maxlength="14" inputmode="numeric" value="<?php echo esc_attr( isset( $_POST['sp_mobile'] ) ? sanitize_text_field( $_POST['sp_mobile'] ) : '' ); ?>" />
                    </div>
                    <div class="col-md-6 pms-fields pms-sp_email-field">
                        <input id="pms_sp_email" placeholder="Spouse Email *" name="sp_email" type="email" value="<?php echo esc_attr( isset( $_POST['sp_email'] ) ? sanitize_text_field( $_POST['sp_email'] ) : '' ); ?>" />
                    </div>
                    <input type="hidden" name="fees" value="" id="fee_am">
                </div>
        <?php } ?>
            </div><br><br>
        <?php
        }else{
        ?>
            <div class="custom-new-subscription" id="cim_mim">
                <input type="hidden" name="course_type" value="<?php echo $type; ?>">
                <li class="pms-field pms-clang-field" style="display: none;">
                    <select id="pms_clang" name="clang" required>
                        <option value="english">English</option>
                        <option value="spanish" selected>Spanish</option>
                    </select>
                </li>
                <div class="col-md-6 pms-fields pms-creason-field" <?php echo $hideReason; ?>>
                    <input id="pms_creason" name="creason" placeholder="Motivo del curso *" type="text" value="<?php echo esc_attr( isset( $_POST['creason'] ) ? sanitize_text_field( $_POST['creason'] ) : '' ); ?>"/>
                </div>
        <?php 
            if($showBankDist){
        ?>
                <div class="col-md-6 pms-fields pms-bdistrict-field" style="display: none;">
                    <input id="pms_bdistrict" name="bdistrict" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_bdistrict', __( 'Distrito de quiebra *', 'paid-member-subscriptions' ) ) ); ?>" type="text" value="FL" required/>
                </div>
                <div class="col-md-6 pms-fields pms-bnumber-field">
                    <input id="pms_bnumber" name="bnumber" type="text" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_bnumber', __( 'Número de ID de Bancarrota *', 'paid-member-subscriptions' ) ) ); ?>" value="<?php echo esc_attr( isset( $_POST['bnumber'] ) ? sanitize_text_field( $_POST['bnumber'] ) : '' ); ?>" required/>
                </div>
                <br><br>
                <br><br>
        <?php 
            }
            if($showS){
        ?>
                <h5 class="spouse_heading text-center" style="display:none;margin-top:-50px;">Información del cónyuge</h5>
                <div style="display: none;" id="spouse_div">
                    <div class="col-md-6 pms-fields pms-sp_fname-field">
                        <input id="pms_sp_fname" placeholder="Cónyuge Primer nombre *" name="sp_fname" type="text" />
                    </div>
                    <div class="col-md-6 pms-fields pms-sp_lname-field">
                        <input id="pms_sp_lname" placeholder="Apellido del cónyuge *" name="sp_lname" type="text" />
                    </div>
                    <div class="col-md-6 pms-fields pms-sp_mobile-field">
                        <input id="pms_sp_mobile" placeholder="Número de teléfono del cónyuge *" name="sp_mobile" type="text" maxlength="14" inputmode="numeric" value="<?php echo esc_attr( isset( $_POST['sp_mobile'] ) ? sanitize_text_field( $_POST['sp_mobile'] ) : '' ); ?>" />
                    </div>
                    <div class="col-md-6 pms-fields pms-sp_email-field">
                        <input id="pms_sp_email" placeholder="Correo electrónico del cónyuge *" name="sp_email" type="email" value="<?php echo esc_attr( isset( $_POST['sp_email'] ) ? sanitize_text_field( $_POST['sp_email'] ) : '' ); ?>" />
                    </div>
                    <input type="hidden" name="fees" value="" id="fee_am">
                </div>
        <?php } ?>
            </div><br><br>
        <?php
        }
    }
    if($showCreditMonitoring){
    ?>
        <div class="custom-new-subscription">
            <div class="col-md-6 pms-fields pms-sp_email-field">
                <input id="pms_mobile" name="mobile" type="text" maxlength="14" inputmode="numeric" value="" required="" data-placeholder="Número de teléfono *" placeholder="Número de teléfono *">
            </div>
        </div>
    <?php
    }
    ?>
    <!-- TEQTOP Custom Code End -->

    <?php do_action( 'pms_' . $form_name . '_form_bottom', $atts ); ?>

    <input name="pms_<?php echo esc_attr( $form_name ); ?>" type="submit" value="<?php echo esc_attr( apply_filters( 'pms_' . $form_name . '_form_submit_text', __( 'Subscribe', 'paid-member-subscriptions' ) ) ); ?>" />

</form>
