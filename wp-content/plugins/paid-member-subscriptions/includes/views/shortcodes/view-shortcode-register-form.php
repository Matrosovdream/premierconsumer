<?php

global $wp;
$current_page = $wp->request;
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
    /*
     * HTML output for register form
     *
     * @param $atts     - is available from parent file, in the register_form method of the PMS_Shortcodes class
     */
    $showClasses = false; // class code - 18oct
    $hideReason = $custom_class = "";
    $csl = apply_filters( 'wpml_current_language', null );
    $form_name = 'register';
    if(in_array(16336,$atts['subscription_plans']) || in_array(16637,$atts['subscription_plans'])){
        echo "<style>.pms-subscription-plan-price{display:none;}</style>";
        $showClasses = true;
    }

    if(in_array(16336,$atts['subscription_plans']) || in_array(16370,$atts['subscription_plans']) || in_array(16637,$atts['subscription_plans']) || in_array(16638,$atts['subscription_plans'])){ // 10-march
        $defaultCheck = 'checked';
        $custom_class = "plan-name-section";
        echo "<style>li.pms-field.pms-gdpr-field,.custom_check{ display:none; }</style>";
        echo "<style>.select2-container.select2-container--default .select2-selection--single { border: none;background: none;border-bottom: 1px solid #cbc6c6;}</style>";
    }else{
        $defaultCheck = '';
    }
    if(in_array(16637,$atts['subscription_plans']) || in_array(16638,$atts['subscription_plans']) || in_array(19780,$atts['subscription_plans'])){
        $hideReason = 'style="display:none;"';
    }
?>
<style>
    i.fa.fa-upload {
        font-size: 14px;
        border: 1px solid;
        border-radius: 50%;
        padding: 4px;
    }
    .custom-uploader{
        color: #3441a4;
        cursor: pointer;
    }
    .note-field{
        font-size: 13px;
        color: #3441a4;
    }
    i.fa.fa-times {
        color: #fec14c;
        cursor: pointer;
    }
    #shareUp{
        background: #3441a4;
        border: none;
        padding: 4px 10px;
        color: white;
    }
    input.pms-form-submit:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }
</style>


<?php 
    if($csl == "es"){
?>
    <form id="pms_<?php echo esc_attr( $form_name ); ?>-form" class="pms-form pc-register-form" method="POST">
        <?php do_action( 'pms_' . $form_name . '_form_top', $atts ); ?>
        <?php wp_nonce_field( 'pms_' . $form_name . '_form_nonce', 'pmstkn' ); ?>
        <ul class="pms-form-fields-wrapper">
            <?php
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
            ?>
            <?php
            // Start catching the subscription plan fields
            ob_start();
            $field_errors = pms_errors()->get_error_messages('subscription_plans');
            echo '<li class="pms-field pms-field-subscriptions '.$custom_class.(!empty($field_errors) ? 'pms-field-error' : '') .'">';
            
            $subscription_plans = pms_get_subscription_plans();
            // Add nonce field when subscription_plans='none' (to allow users to register without becoming members, selecting a subscription plan)
            if( empty( $subscription_plans ) || ( isset( $atts['subscription_plans'][0] ) && ( strtolower($atts['subscription_plans'][0]) == 'none' ) ) )
                wp_nonce_field( 'pms_register_user_no_subscription_nonce','pmstkn2');
            else
                echo pms_output_subscription_plans( $atts['subscription_plans'], $atts['exclude'], false, (isset($atts['selected']) ? trim($atts['selected']) : ''), 'register', $current_page );//phpcs:ignore  WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</li>';
            // Get the contents and clean
            $subscription_plans_field = ob_get_contents();
            ob_end_clean();
            // Display subscription plans at the bottom
            if( $atts['plans_position'] == 'top' )
                echo $subscription_plans_field; //phpcs:ignore  WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
            <?php
                // Start catching the register form fields
                ob_start();
            ?>
            <?php do_action( 'pms_register_form_before_fields', $atts ); ?>
            <?php //$field_errors = pms_errors()->get_error_messages('user_login'); ?>
            <li class="pms-field pms-user-login-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>" style="display: none">
                <label for="pms_user_login"><?php echo esc_html( apply_filters( 'pms_register_form_label_user_login', __( 'Username *', 'paid-member-subscriptions' ) ) ); ?></label>
                <input id="pms_user_login" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_user_login', __( 'Username *', 'paid-member-subscriptions' ) ) ); ?>" name="user_login" type="text" value="<?php echo esc_attr( apply_filters( 'pms_' . $form_name . '_form_value_user_login', isset( $_POST['user_login'] ) ? sanitize_text_field( $_POST['user_login'] ) : '' ) ); ?>" />
                <?php pms_display_field_errors( $field_errors ); ?>
            </li>    

            <?php $field_errors = pms_errors()->get_error_messages('first_name'); ?>
            <li class="pms-field pms-first-name-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                <label for="pms_first_name"><?php echo esc_html( apply_filters( 'pms_register_form_label_first_name', __( 'Primer nombre *', 'paid-member-subscriptions' ) ) ); ?></label>
                <input id="pms_first_name" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_first_name', __( 'Primer nombre *', 'paid-member-subscriptions' ) ) ); ?>" name="first_name" type="text" value="<?php echo esc_attr( isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '' ); ?>" required />
                <?php pms_display_field_errors( $field_errors ); ?>
            </li>
            <?php $field_errors = pms_errors()->get_error_messages('last_name'); ?>
            <li class="pms-field pms-last-name-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                <label for="pms_last_name"><?php echo esc_html( apply_filters( 'pms_register_form_label_last_name', __( 'Apellido *', 'paid-member-subscriptions' ) ) ); ?></label>
                <input id="pms_last_name" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_last_name', __( 'Apellido *', 'paid-member-subscriptions' ) ) ); ?>" name="last_name" type="text" value="<?php echo esc_attr( isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '' ); ?>" required />
                <?php pms_display_field_errors( $field_errors ); ?>
            </li>       

            <?php 
            if(in_array(16336,$atts['subscription_plans']) || in_array(16637,$atts['subscription_plans'])){
                $field_errors = pms_errors()->get_error_messages('person');
            ?>
                <li class="pms-field pms-person-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                    <!-- <label for="pms_person"><?php //echo esc_html( apply_filters( 'pms_register_form_label_person', __( 'Number of Person/Household *', 'paid-member-subscriptions' ) ) ); ?></label> -->
                    <input id="pms_person" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_person', __( 'Número de personas/El hogar *', 'paid-member-subscriptions' ) ) ); ?>" name="person" type="number" value="<?php echo esc_attr( isset( $_POST['person'] ) ? sanitize_text_field( $_POST['person'] ) : '' ); ?>" required min="1"/>
                    <?php pms_display_field_errors( $field_errors ); ?>
                </li>
            <?php
                $field_errors = pms_errors()->get_error_messages('yearly_income');
            ?>
                <li class="pms-field pms-income-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                    <!-- <label for="pms_yearly_income"><?php //echo esc_html( apply_filters( 'pms_register_form_label_yearly_income', __( 'Yearly Income *', 'paid-member-subscriptions' ) ) ); ?></label> -->
                    <input id="pms_yearly_income" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_yearly_income', __( 'Ingresos anuales *', 'paid-member-subscriptions' ) ) ); ?>" name="yearly_income" type="number" value="<?php echo esc_attr( isset( $_POST['yearly_income'] ) ? sanitize_text_field( $_POST['yearly_income'] ) : '' ); ?>" required min="1"/>
                    <?php pms_display_field_errors( $field_errors ); ?>
                </li>
                <li class="pms-field pms-upload-field" style="flex: 0 0 100%;display:none;">
                    <span class="custom-uploader">Cargar documentos <i class="fa fa-upload" aria-hidden="true"></i></span><br/>
                    <span class="note-field">Suba los recibos de sueldo de los últimos 30 días, los ingresos de los trabajadores autónomos, la carta de concesión del paro, etc., como prueba.</span>
                    <input type="file" name="doc[]" style="display: none;" class="upDocs" id="1">
                    <button type="button" id="shareUp" style="display:none;">Cargar</button>
                </li>
            <?php
            }
            ?>        

            <?php
            if(in_array(16336,$atts['subscription_plans']) || in_array(16370,$atts['subscription_plans']) || in_array(16637,$atts['subscription_plans']) || in_array(16638,$atts['subscription_plans'])){
            ?>
                <li class="pms-field pms-clang-field" style="display: none;">
                    <select id="pms_clang" name="clang" required>
                        <option value="english">English</option>
                        <option value="spanish" selected>Spanish</option>
                    </select>
                </li>
                <li class="pms-field pms-creason-field" <?php echo $hideReason; ?>>
                    <!-- <label for="pms_creason"><?php //echo esc_html( apply_filters( 'pms_register_form_label_creason', __( 'Course Reason *', 'paid-member-subscriptions' ) ) ); ?></label> -->
                    <input id="pms_creason" name="creason" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_creason', __( 'Motivo del curso *', 'paid-member-subscriptions' ) ) ); ?>" type="text" value="<?php echo esc_attr( isset( $_POST['creason'] ) ? sanitize_text_field( $_POST['creason'] ) : '' ); ?>"/>
                </li>
                
                <?php if(in_array(16336,$atts['subscription_plans']) || in_array(16370,$atts['subscription_plans'])){ ?>
                <li class="pms-field pms-bdistrict-field" style="display: none;">
                    <!-- <label for="pms_bdistrict"><?php //echo esc_html( apply_filters( 'pms_register_form_label_bdistrict', __( 'Bankruptcy District *', 'paid-member-subscriptions' ) ) ); ?></label> -->
                    <input id="pms_bdistrict" name="bdistrict" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_bdistrict', __( 'Distrito de quiebra *', 'paid-member-subscriptions' ) ) ); ?>" type="text" value="FL" required/>
                </li>
                <li class="pms-field pms-bnumber-field">
                   <!--  <label for="pms_bnumber"><?php //echo esc_html( apply_filters( 'pms_register_form_label_bnumber', __( 'Bankruptcy Number *', 'paid-member-subscriptions' ) ) ); ?></label> -->
                    <input id="pms_bnumber" name="bnumber" type="text" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_bnumber', __( 'Número de ID de Bancarrota *', 'paid-member-subscriptions' ) ) ); ?>" value="<?php echo esc_attr( isset( $_POST['bnumber'] ) ? sanitize_text_field( $_POST['bnumber'] ) : '' ); ?>" required/>
                </li>
                <?php } ?>
            <?php
            }
            ?>
            <?php 
            if(in_array(16336,$atts['subscription_plans']) || in_array(16370,$atts['subscription_plans'])){
                echo '<input type="hidden" name="course_type" value="MIM">';
            }
            if(in_array(16637,$atts['subscription_plans']) || in_array(16638,$atts['subscription_plans'])){
                echo '<input type="hidden" name="course_type" value="CIMBK">';
            }
            ?>  
            <?php $field_errors = pms_errors()->get_error_messages('user_email'); ?>
            <li class="pms-field pms-user-email-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                <label for="pms_user_email"><?php echo esc_html( apply_filters( 'pms_register_form_label_user_email', __( 'E-mail *', 'paid-member-subscriptions' ) ) ); ?></label>
                <input id="pms_user_email" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_user_email', __( 'Correo electrónico *', 'paid-member-subscriptions' ) ) ); ?>" name="user_email" type="text" value="<?php echo esc_attr( apply_filters( 'pms_' . $form_name . '_form_value_user_email', isset( $_POST['user_email'] ) ? sanitize_text_field( $_POST['user_email'] ) : '' ) ); ?>" <?php echo esc_html( apply_filters( 'pms_register_form_attributes_user_email', '' ) ); ?> required />
                <?php pms_display_field_errors( $field_errors ); ?>
            </li>

            <?php $field_errors = pms_errors()->get_error_messages('mobile'); ?>
            <li class="pms-field pms-mobile-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                <label for="pms_mobile"><?php echo esc_html( apply_filters( 'pms_register_form_label_mobile', __( 'Mobile *', 'paid-member-subscriptions' ) ) ); ?></label>
                <input id="pms_mobile" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_mobile', __( 'Número de teléfono *', 'paid-member-subscriptions' ) ) ); ?>" name="mobile" type="text" maxlength="14" inputmode="numeric" value="<?php echo esc_attr( isset( $_POST['mobile'] ) ? sanitize_text_field( $_POST['mobile'] ) : '' ); ?>" required />
                <?php pms_display_field_errors( $field_errors ); ?>
            </li>

            <?php $field_errors = pms_errors()->get_error_messages('pass1'); ?>
            <li class="pms-field pms-pass1-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                <label for="pms_pass1"><?php echo esc_html( apply_filters( 'pms_register_form_label_pass1', __( 'Password *', 'paid-member-subscriptions' ) ) ); ?></label>
                <input id="pms_pass1" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_pass1', __( 'Contraseña *', 'paid-member-subscriptions' ) ) ); ?>" name="pass1" type="password" required />
                <?php pms_display_field_errors( $field_errors ); ?>
            </li>
            <?php $field_errors = pms_errors()->get_error_messages('pass2'); ?>
            <li class="pms-field pms-pass2-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                <label for="pms_pass2"><?php echo esc_html( apply_filters( 'pms_register_form_label_pass2', __( 'Repeat Password *', 'paid-member-subscriptions' ) ) ); ?></label>
                <input id="pms_pass2" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_pass2', __( 'Repita la contraseña *', 'paid-member-subscriptions' ) ) ); ?>" name="pass2" type="password" required />
                <?php pms_display_field_errors( $field_errors ); ?>
            </li>
            <?php 
                if(in_array(19780,$atts['subscription_plans']) || in_array(19781,$atts['subscription_plans']) || in_array(16637,$atts['subscription_plans']) || in_array(16336,$atts['subscription_plans'])){
            ?>
                <h5 class="spouse_heading text-center" style="display:none;">Información del cónyuge</h5>
                <div style="display: none;" id="spouse_div">
                    <?php $field_errors = pms_errors()->get_error_messages('sp_fname'); ?>
                    <li class="pms-field pms-sp_fname-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                        <input id="pms_sp_fname" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_sp_fname', __( 'Cónyuge Primer nombre *', 'paid-member-subscriptions' ) ) ); ?>" name="sp_fname" type="text" />
                        <?php pms_display_field_errors( $field_errors ); ?>
                    </li>
                    <?php $field_errors = pms_errors()->get_error_messages('sp_lname'); ?>
                    <li class="pms-field pms-sp_lname-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                        <input id="pms_sp_lname" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_sp_lname', __( 'Apellido del cónyuge *', 'paid-member-subscriptions' ) ) ); ?>" name="sp_lname" type="text" />
                        <?php pms_display_field_errors( $field_errors ); ?>
                    </li>
                    <?php $field_errors = pms_errors()->get_error_messages('sp_mobile'); ?>
                    <li class="pms-field pms-sp_mobile-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                        <input id="pms_sp_mobile" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_sp_mobile', __( 'Número de teléfono del cónyuge *', 'paid-member-subscriptions' ) ) ); ?>" name="sp_mobile" type="text" maxlength="14" inputmode="numeric" value="<?php echo esc_attr( isset( $_POST['sp_mobile'] ) ? sanitize_text_field( $_POST['sp_mobile'] ) : '' ); ?>" />
                        <?php pms_display_field_errors( $field_errors ); ?>
                    </li>
                    <?php $field_errors = pms_errors()->get_error_messages('sp_email'); ?>
                    <li class="pms-field pms-sp_email-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                        <input id="pms_sp_email" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_sp_email', __( 'Correo electrónico del cónyuge *', 'paid-member-subscriptions' ) ) ); ?>" name="sp_email" type="email" value="<?php echo esc_attr( isset( $_POST['sp_email'] ) ? sanitize_text_field( $_POST['sp_email'] ) : '' ); ?>" />
                        <?php pms_display_field_errors( $field_errors ); ?>
                    </li>
                    <input type="hidden" name="fees" value="" id="fee_am">
                </div>
            <?php       
                }
            ?>
            <?php
            $gdpr_settings = pms_get_gdpr_settings();
            if( !empty( $gdpr_settings ) ){
                if( !empty( $gdpr_settings['gdpr_checkbox'] ) && $gdpr_settings['gdpr_checkbox'] === 'enabled' ){
                    $field_errors = pms_errors()->get_error_messages('user_consent'); ?>
                    <li class="pms-field pms-gdpr-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>" style="display: none;">
                        <label for="pms_user_consent">
                            <input id="pms_user_consent" name="user_consent" type="checkbox" value="1" <?php echo $defaultCheck; ?>>
                            <?php echo ( isset($gdpr_settings['gdpr_checkbox_text']) ? wp_kses_post( str_replace( '{{privacy_policy}}', get_the_privacy_policy_link(), pms_icl_t( 'plugin paid-member-subscriptions', 'gdpr_checkbox_text', $gdpr_settings['gdpr_checkbox_text'] ) ) ) : esc_html__( 'I allow the website to collect and store the data I submit through this form. *', 'paid-member-subscriptions' ) ); ?>
                        </label>
                        <?php pms_display_field_errors( $field_errors ); ?>
                    </li>
                    <?php
                }
            }
            ?>
            
            <?php do_action( 'pms_register_form_after_fields', $atts ); ?>
            <?php
                // Get form fields and clean the buffer
                $register_form_fields = ob_get_contents();
                ob_end_clean();
                if( $form_name == 'register' )
                    echo $register_form_fields;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
            <?php
                // Display subscription plans at the bottom
                if( $atts['plans_position'] == 'bottom' )
                    echo $subscription_plans_field; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
        </ul>

        <?php do_action( 'pms_' . $form_name . '_form_bottom', $atts ); ?>
        <?php
            if( !empty( $gdpr_settings ) ){
                if( !empty( $gdpr_settings['gdpr_checkbox'] ) && $gdpr_settings['gdpr_checkbox'] === 'enabled' ){
        ?>
                <div class="custom_check">
                    <label for="pms_custom_ck">
                        <input id="pms_custom_ck" type="checkbox" <?php echo $defaultCheck; ?>>
                        Al hacer clic en "Registrarse" y crear una cuenta, usted acepta los <a target="_blank" href="https://app.termly.io/embed/terms-of-use/cd8446e8-9910-4a02-8f7c-36d9921a3bc7">Términos de Uso</a> y la <a target="_blank" href="https://app.termly.io/embed/terms-of-use/958f774c-a642-41e7-9696-7ac060bdf44c">Política de Privacidad</a> de StitchCredit. StitchCredit no mantiene datos personales críticos, y mucho menos vende o divulga su información personal a nadie más. Usted puede optar por no recibir correspondencia por correo electrónico, excepto los correos de confirmación, que a menudo contienen información importante sobre su cuenta.
                    </label>
                </div>
        <?php
                }
            }
        ?>
        <input class="pms-form-submit" name="pms_<?php echo esc_attr( $form_name ); ?>" type="submit" value="<?php echo esc_attr( apply_filters( 'pms_' . $form_name . '_form_submit_text', __( 'Regístrese', 'paid-member-subscriptions' ) ) ); ?>" />
    </form>
<?php 
    }else{
?>
    <form id="pms_<?php echo esc_attr( $form_name ); ?>-form" class="pms-form pc-register-form" method="POST">
    <?php do_action( 'pms_' . $form_name . '_form_top', $atts ); ?>
    <?php wp_nonce_field( 'pms_' . $form_name . '_form_nonce', 'pmstkn' ); ?>
    <ul class="pms-form-fields-wrapper">
        <?php
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
        ?>
        <?php
        // Start catching the subscription plan fields
        ob_start();
        $field_errors = pms_errors()->get_error_messages('subscription_plans');
        echo '<li class="pms-field pms-field-subscriptions '.$custom_class.(!empty($field_errors) ? 'pms-field-error' : '') .'">';
        $subscription_plans = pms_get_subscription_plans();
        // Add nonce field when subscription_plans='none' (to allow users to register without becoming members, selecting a subscription plan)
        if( empty( $subscription_plans ) || ( isset( $atts['subscription_plans'][0] ) && ( strtolower($atts['subscription_plans'][0]) == 'none' ) ) )
            wp_nonce_field( 'pms_register_user_no_subscription_nonce','pmstkn2');
        else
            echo pms_output_subscription_plans( $atts['subscription_plans'], $atts['exclude'], false, (isset($atts['selected']) ? trim($atts['selected']) : ''), 'register', $current_page );//phpcs:ignore  WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</li>';
        // Get the contents and clean
        $subscription_plans_field = ob_get_contents();
        ob_end_clean();
        // Display subscription plans at the bottom
        if( $atts['plans_position'] == 'top' )
            echo $subscription_plans_field; //phpcs:ignore  WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
        <?php
            // Start catching the register form fields
            ob_start();
        ?>
        <?php do_action( 'pms_register_form_before_fields', $atts ); ?>
        <?php //$field_errors = pms_errors()->get_error_messages('user_login'); ?>
        <li class="pms-field pms-user-login-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>" style="display: none">
            <label for="pms_user_login"><?php echo esc_html( apply_filters( 'pms_register_form_label_user_login', __( 'Username *', 'paid-member-subscriptions' ) ) ); ?></label>
            <input id="pms_user_login" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_user_login', __( 'Username *', 'paid-member-subscriptions' ) ) ); ?>" name="user_login" type="text" value="<?php echo esc_attr( apply_filters( 'pms_' . $form_name . '_form_value_user_login', isset( $_POST['user_login'] ) ? sanitize_text_field( $_POST['user_login'] ) : '' ) ); ?>" />
            <?php pms_display_field_errors( $field_errors ); ?>
        </li>    

        <?php $field_errors = pms_errors()->get_error_messages('first_name'); ?>
        <li class="pms-field pms-first-name-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
            <label for="pms_first_name"><?php echo esc_html( apply_filters( 'pms_register_form_label_first_name', __( 'First Name *', 'paid-member-subscriptions' ) ) ); ?></label>
            <input id="pms_first_name" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_first_name', __( 'First Name *', 'paid-member-subscriptions' ) ) ); ?>" name="first_name" type="text" value="<?php echo esc_attr( isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '' ); ?>" required />
            <?php pms_display_field_errors( $field_errors ); ?>
        </li>
        <?php $field_errors = pms_errors()->get_error_messages('last_name'); ?>
        <li class="pms-field pms-last-name-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
            <label for="pms_last_name"><?php echo esc_html( apply_filters( 'pms_register_form_label_last_name', __( 'Last Name *', 'paid-member-subscriptions' ) ) ); ?></label>
            <input id="pms_last_name" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_last_name', __( 'Last Name *', 'paid-member-subscriptions' ) ) ); ?>" name="last_name" type="text" value="<?php echo esc_attr( isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '' ); ?>" required />
            <?php pms_display_field_errors( $field_errors ); ?>
        </li>       

        <?php 
        if(in_array(16336,$atts['subscription_plans']) || in_array(16637,$atts['subscription_plans'])){
            $field_errors = pms_errors()->get_error_messages('person');
        ?>
            <li class="pms-field pms-person-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                <!-- <label for="pms_person"><?php //echo esc_html( apply_filters( 'pms_register_form_label_person', __( 'Number of Person/Household *', 'paid-member-subscriptions' ) ) ); ?></label> -->
                <input id="pms_person" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_person', __( 'Number of Person/Household *', 'paid-member-subscriptions' ) ) ); ?>" name="person" type="number" value="<?php echo esc_attr( isset( $_POST['person'] ) ? sanitize_text_field( $_POST['person'] ) : '' ); ?>" required min="1"/>
                <?php pms_display_field_errors( $field_errors ); ?>
            </li>
        <?php
            $field_errors = pms_errors()->get_error_messages('yearly_income');
        ?>
            <li class="pms-field pms-income-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                <!-- <label for="pms_yearly_income"><?php //echo esc_html( apply_filters( 'pms_register_form_label_yearly_income', __( 'Yearly Income *', 'paid-member-subscriptions' ) ) ); ?></label> -->
                <input id="pms_yearly_income" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_yearly_income', __( 'Yearly Income *', 'paid-member-subscriptions' ) ) ); ?>" name="yearly_income" type="number" value="<?php echo esc_attr( isset( $_POST['yearly_income'] ) ? sanitize_text_field( $_POST['yearly_income'] ) : '' ); ?>" required min="1"/>
                <?php pms_display_field_errors( $field_errors ); ?>
            </li>
            <li class="pms-field pms-upload-field" style="flex: 0 0 100%;display:none;">
                <span class="custom-uploader">Upload Documents <i class="fa fa-upload" aria-hidden="true"></i></span><br/>
                <span class="note-field">Upload Pay stubs for the last 30 days, Self-employment income, Unemployment award letter etc documents for proof.</span>
                <input type="file" name="doc[]" style="display: none;" class="upDocs" id="1">
                <button type="button" id="shareUp" style="display:none;">Upload</button>
            </li>
        <?php
        }
        ?>        

        <?php
        if(in_array(16336,$atts['subscription_plans']) || in_array(16370,$atts['subscription_plans']) || in_array(16637,$atts['subscription_plans']) || in_array(16638,$atts['subscription_plans'])){
        ?>
            <li class="pms-field pms-clang-field" style="display: none;">
                <select id="pms_clang" name="clang" required>
                    <option value="english" selected>English</option>
                    <option value="spanish">Spanish</option>
                </select>
            </li>
            <li class="pms-field pms-creason-field" <?php echo $hideReason; ?>>
                <!-- <label for="pms_creason"><?php //echo esc_html( apply_filters( 'pms_register_form_label_creason', __( 'Course Reason *', 'paid-member-subscriptions' ) ) ); ?></label> -->
                <input id="pms_creason" name="creason" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_creason', __( 'Course Reason *', 'paid-member-subscriptions' ) ) ); ?>" type="text" value="<?php echo esc_attr( isset( $_POST['creason'] ) ? sanitize_text_field( $_POST['creason'] ) : '' ); ?>"/>
            </li>
            
            <?php if(in_array(16336,$atts['subscription_plans']) || in_array(16370,$atts['subscription_plans'])){ ?>
            <li class="pms-field pms-bdistrict-field" style="display: none;">
                <!-- <label for="pms_bdistrict"><?php //echo esc_html( apply_filters( 'pms_register_form_label_bdistrict', __( 'Bankruptcy District *', 'paid-member-subscriptions' ) ) ); ?></label> -->
                <input id="pms_bdistrict" name="bdistrict" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_bdistrict', __( 'Bankruptcy District *', 'paid-member-subscriptions' ) ) ); ?>" type="text" value="FL" required/>
            </li>
            <li class="pms-field pms-bnumber-field">
               <!--  <label for="pms_bnumber"><?php //echo esc_html( apply_filters( 'pms_register_form_label_bnumber', __( 'Bankruptcy Number *', 'paid-member-subscriptions' ) ) ); ?></label> -->
                <input id="pms_bnumber" name="bnumber" type="text" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_bnumber', __( 'Bankruptcy Number *', 'paid-member-subscriptions' ) ) ); ?>" value="<?php echo esc_attr( isset( $_POST['bnumber'] ) ? sanitize_text_field( $_POST['bnumber'] ) : '' ); ?>" required/>
            </li>
            <?php } ?>
        <?php
        }
        ?>
        <?php 
        if(in_array(16336,$atts['subscription_plans']) || in_array(16370,$atts['subscription_plans'])){
            echo '<input type="hidden" name="course_type" value="MIM">';
        }
        if(in_array(16637,$atts['subscription_plans']) || in_array(16638,$atts['subscription_plans'])){
            echo '<input type="hidden" name="course_type" value="CIMBK">';
        }
        ?>  
        <?php $field_errors = pms_errors()->get_error_messages('user_email'); ?>
        <li class="pms-field pms-user-email-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
            <label for="pms_user_email"><?php echo esc_html( apply_filters( 'pms_register_form_label_user_email', __( 'E-mail *', 'paid-member-subscriptions' ) ) ); ?></label>
            <input id="pms_user_email" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_user_email', __( 'E-mail *', 'paid-member-subscriptions' ) ) ); ?>" name="user_email" type="text" value="<?php echo esc_attr( apply_filters( 'pms_' . $form_name . '_form_value_user_email', isset( $_POST['user_email'] ) ? sanitize_text_field( $_POST['user_email'] ) : '' ) ); ?>" <?php echo esc_html( apply_filters( 'pms_register_form_attributes_user_email', '' ) ); ?> required />
            <?php pms_display_field_errors( $field_errors ); ?>
        </li>

        <?php $field_errors = pms_errors()->get_error_messages('mobile'); ?>
        <li class="pms-field pms-mobile-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
            <label for="pms_mobile"><?php echo esc_html( apply_filters( 'pms_register_form_label_mobile', __( 'Mobile *', 'paid-member-subscriptions' ) ) ); ?></label>
            <input id="pms_mobile" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_mobile', __( 'Mobile *', 'paid-member-subscriptions' ) ) ); ?>" name="mobile" type="text" maxlength="14" inputmode="numeric" value="<?php echo esc_attr( isset( $_POST['mobile'] ) ? sanitize_text_field( $_POST['mobile'] ) : '' ); ?>" required />
            <?php pms_display_field_errors( $field_errors ); ?>
        </li>

        <?php $field_errors = pms_errors()->get_error_messages('pass1'); ?>
        <li class="pms-field pms-pass1-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
            <label for="pms_pass1"><?php echo esc_html( apply_filters( 'pms_register_form_label_pass1', __( 'Password *', 'paid-member-subscriptions' ) ) ); ?></label>
            <input id="pms_pass1" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_pass1', __( 'Password *', 'paid-member-subscriptions' ) ) ); ?>" name="pass1" type="password" required />
            <?php pms_display_field_errors( $field_errors ); ?>
        </li>
        <?php $field_errors = pms_errors()->get_error_messages('pass2'); ?>
        <li class="pms-field pms-pass2-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
            <label for="pms_pass2"><?php echo esc_html( apply_filters( 'pms_register_form_label_pass2', __( 'Repeat Password *', 'paid-member-subscriptions' ) ) ); ?></label>
            <input id="pms_pass2" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_pass2', __( 'Repeat Password *', 'paid-member-subscriptions' ) ) ); ?>" name="pass2" type="password" required />
            <?php pms_display_field_errors( $field_errors ); ?>
        </li>
        <?php 
            if(in_array(19780,$atts['subscription_plans']) || in_array(19781,$atts['subscription_plans']) || in_array(16637,$atts['subscription_plans']) || in_array(16336,$atts['subscription_plans'])){
        ?>
            <h5 class="spouse_heading text-center" style="display:none;">Spouse Information</h5>
            <div style="display: none;" id="spouse_div">
                <?php $field_errors = pms_errors()->get_error_messages('sp_fname'); ?>
                <li class="pms-field pms-sp_fname-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                    <input id="pms_sp_fname" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_sp_fname', __( 'Spouse First Name *', 'paid-member-subscriptions' ) ) ); ?>" name="sp_fname" type="text" />
                    <?php pms_display_field_errors( $field_errors ); ?>
                </li>
                <?php $field_errors = pms_errors()->get_error_messages('sp_lname'); ?>
                <li class="pms-field pms-sp_lname-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                    <input id="pms_sp_lname" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_sp_lname', __( 'Spouse Last Name *', 'paid-member-subscriptions' ) ) ); ?>" name="sp_lname" type="text" />
                    <?php pms_display_field_errors( $field_errors ); ?>
                </li>
                <?php $field_errors = pms_errors()->get_error_messages('sp_mobile'); ?>
                <li class="pms-field pms-sp_mobile-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                    <input id="pms_sp_mobile" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_sp_mobile', __( 'Spouse Phone *', 'paid-member-subscriptions' ) ) ); ?>" name="sp_mobile" type="text" maxlength="14" inputmode="numeric" value="<?php echo esc_attr( isset( $_POST['sp_mobile'] ) ? sanitize_text_field( $_POST['sp_mobile'] ) : '' ); ?>" />
                    <?php pms_display_field_errors( $field_errors ); ?>
                </li>
                <?php $field_errors = pms_errors()->get_error_messages('sp_email'); ?>
                <li class="pms-field pms-sp_email-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>">
                    <input id="pms_sp_email" placeholder="<?php echo esc_html( apply_filters( 'pms_register_form_label_sp_email', __( 'Spouse Email *', 'paid-member-subscriptions' ) ) ); ?>" name="sp_email" type="email" value="<?php echo esc_attr( isset( $_POST['sp_email'] ) ? sanitize_text_field( $_POST['sp_email'] ) : '' ); ?>" />
                    <?php pms_display_field_errors( $field_errors ); ?>
                </li>
                <input type="hidden" name="fees" value="" id="fee_am">
            </div>
        <?php       
            }
        ?>
        <?php
        $gdpr_settings = pms_get_gdpr_settings();
        if( !empty( $gdpr_settings ) ){
            if( !empty( $gdpr_settings['gdpr_checkbox'] ) && $gdpr_settings['gdpr_checkbox'] === 'enabled' ){
                $field_errors = pms_errors()->get_error_messages('user_consent'); ?>
                <li class="pms-field pms-gdpr-field <?php echo ( !empty( $field_errors ) ? 'pms-field-error' : '' ); ?>" style="display: none;">
                    <label for="pms_user_consent">
                        <input id="pms_user_consent" name="user_consent" type="checkbox" value="1" <?php echo $defaultCheck; ?>>
                        <?php echo ( isset($gdpr_settings['gdpr_checkbox_text']) ? wp_kses_post( str_replace( '{{privacy_policy}}', get_the_privacy_policy_link(), pms_icl_t( 'plugin paid-member-subscriptions', 'gdpr_checkbox_text', $gdpr_settings['gdpr_checkbox_text'] ) ) ) : esc_html__( 'I allow the website to collect and store the data I submit through this form. *', 'paid-member-subscriptions' ) ); ?>
                    </label>
                    <?php pms_display_field_errors( $field_errors ); ?>
                </li>
                <?php
            }
        }
        ?>
        
        <?php do_action( 'pms_register_form_after_fields', $atts ); ?>
        <?php
            // Get form fields and clean the buffer
            $register_form_fields = ob_get_contents();
            ob_end_clean();
            if( $form_name == 'register' )
                echo $register_form_fields;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
        <?php
            // Display subscription plans at the bottom
            if( $atts['plans_position'] == 'bottom' )
                echo $subscription_plans_field; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
    </ul>

    <?php do_action( 'pms_' . $form_name . '_form_bottom', $atts ); ?>
    <?php
        if( !empty( $gdpr_settings ) ){
            if( !empty( $gdpr_settings['gdpr_checkbox'] ) && $gdpr_settings['gdpr_checkbox'] === 'enabled' ){
    ?>
            <div class="custom_check">
                <label for="pms_custom_ck">
                    <input id="pms_custom_ck" type="checkbox" <?php echo $defaultCheck; ?>>
                    <?php echo ( isset($gdpr_settings['gdpr_checkbox_text']) ? wp_kses_post( str_replace( '{{privacy_policy}}', get_the_privacy_policy_link(), pms_icl_t( 'plugin paid-member-subscriptions', 'gdpr_checkbox_text', $gdpr_settings['gdpr_checkbox_text'] ) ) ) : esc_html__( 'I allow the website to collect and store the data I submit through this form. *', 'paid-member-subscriptions' ) ); ?>
                </label>
            </div>
    <?php
            }
        }
    ?>
    <input class="pms-form-submit" name="pms_<?php echo esc_attr( $form_name ); ?>" type="submit" value="<?php echo esc_attr( apply_filters( 'pms_' . $form_name . '_form_submit_text', __( 'Register', 'paid-member-subscriptions' ) ) ); ?>" />
</form>
<?php 
    }
?>

<script type="text/javascript">
    
</script>
