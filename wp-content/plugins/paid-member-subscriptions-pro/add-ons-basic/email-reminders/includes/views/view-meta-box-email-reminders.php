<?php
/*
 * HTML output for the Email Reminder details meta-box
 */
?>

    <!-- Send to Option -->
    <div class="pms-meta-box-field-wrapper">

        <label for="pms-email-reminder-send-to" class="pms-meta-box-field-label"><?php esc_html_e( 'Send Reminder To', 'paid-member-subscriptions' ); ?></label>

        <select id="pms-email-reminder-send-to" name="pms_email_reminder_send_to">
            <option value="user" <?php selected( 'user', $email_reminder->send_to, true  ); ?>><?php esc_html_e( 'Members', 'paid-member-subscriptions' ); ?></option>
            <option value="admin" <?php selected( 'admin', $email_reminder->send_to, true  ); ?>><?php esc_html_e( 'Administrators', 'paid-member-subscriptions' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Select who will receive the emails sent by this reminder.', 'paid-member-subscriptions' ); ?></p>

    </div>

    <!-- Administrator emails list -->
    <div id="pms-email-reminder-admin-emails-wrapper" class="pms-meta-box-field-wrapper">

        <label for="pms-email-reminder-admin-emails" class="pms-meta-box-field-label"><?php esc_html_e( 'Administrator Emails', 'paid-member-subscriptions' ); ?></label>

        <input type="text" id="pms-email-reminder-admin-emails" name="pms_email_reminder_admin_emails" value="<?php echo esc_attr( $email_reminder->admin_emails ); ?>" />

        <p class="description"><?php esc_html_e( 'Enter a list of administrator emails, separated by comma, that you want to receive this email reminder.', 'paid-member-subscriptions' ); ?></p>

    </div>

    <!-- Trigger data -->
    <div class="pms-meta-box-field-wrapper">

        <label for="pms-email-reminder-trigger" class="pms-meta-box-field-label"><?php esc_html_e( 'Trigger Event', 'paid-member-subscriptions' ); ?></label>

        <input id="pms-email-reminder-trigger" name="pms_email_reminder_trigger" type="number" min="1" step="1" required value="<?php echo !empty($email_reminder->trigger) ? esc_attr( $email_reminder->trigger ) : "1" ?>">

        <select id="pms-email-reminder-trigger-unit" name="pms_email_reminder_trigger_unit">
            <option value="hour" <?php selected( 'hour', $email_reminder->trigger_unit, true ); ?>><?php esc_html_e( 'Hour(s)', 'paid-member-subscriptions' ); ?></option>
            <option value="day" <?php selected( 'day', $email_reminder->trigger_unit, true ); ?>><?php esc_html_e( 'Day(s)', 'paid-member-subscriptions' ); ?></option>
            <option value="week" <?php selected( 'week', $email_reminder->trigger_unit, true ); ?>><?php esc_html_e( 'Week(s)', 'paid-member-subscriptions' ); ?></option>
            <option value="month" <?php selected( 'month', $email_reminder->trigger_unit, true ); ?>><?php esc_html_e( 'Month(s)', 'paid-member-subscriptions' ); ?></option>
        </select>

        <select id="pms-email-reminder-event" name="pms_email_reminder_event">
            <option value="after_member_signs_up" <?php selected( 'after_member_signs_up', $email_reminder->event, true ); ?>><?php esc_html_e( 'after Member Signs Up (subscription active)', 'paid-member-subscriptions' ); ?></option>
            <option value="after_member_abandons_signup" <?php selected( 'after_member_abandons_signup', $email_reminder->event, true ); ?>><?php esc_html_e( 'after Member Abandons Signup (subscription pending)', 'paid-member-subscriptions' ); ?></option>
            <option value="before_subscription_expires" <?php selected( 'before_subscription_expires', $email_reminder->event, true ); ?>><?php esc_html_e( 'before Subscription Expires', 'paid-member-subscriptions' ); ?></option>
            <option value="after_subscription_expires" <?php selected( 'after_subscription_expires', $email_reminder->event, true ); ?>><?php esc_html_e( 'after Subscription Expires', 'paid-member-subscriptions' ); ?></option>
            <option value="before_subscription_renews_automatically" <?php selected( 'before_subscription_renews_automatically', $email_reminder->event, true ); ?>><?php esc_html_e( 'before Subscription Renews Automatically', 'paid-member-subscriptions' ); ?></option>
            <option value="since_last_login" <?php selected( 'since_last_login', $email_reminder->event, true ); ?>><?php esc_html_e( 'since Last Login', 'paid-member-subscriptions' ); ?></option>
        </select>

        <p class="description"><?php esc_html_e( 'Enter the trigger event for the email reminder. For example: 10 Days before Subscription Expires.', 'paid-member-subscriptions' ); ?></p>

    </div>

    <!-- Email subject -->
    <div class="pms-meta-box-field-wrapper">

        <label for="pms-email-reminder-subject" class="pms-meta-box-field-label"><?php esc_html_e( 'Email Subject', 'paid-member-subscriptions' ); ?></label>

        <input type="text" id="pms-email-reminder-subject" name="pms_email_reminder_subject" value="<?php echo esc_attr( $email_reminder->subject ); ?>" />

        <p class="description available_tags"><?php echo sprintf ( esc_html__( 'Enter the email reminder subject. You can use the %1$savailable tags%2$s. ', 'paid-member-subscriptions' ), '<a href="#">', '</a>'); ?></p>

    </div>

    <!-- Email content -->
    <div class="pms-meta-box-field-wrapper">

        <label for="pms-email-reminder-content" class="pms-meta-box-field-label"><?php esc_html_e( 'Email Content', 'paid-member-subscriptions' ); ?></label>

        <?php
        $content = $email_reminder->content;
        $editor_id = 'pms-email-reminder-content';
        wp_editor( $content, $editor_id );
        ?>

        <p class="description available_tags"><?php echo sprintf( esc_html__( 'Enter the email reminder content. You can use the %1$savailable tags%2$s. ', 'paid-member-subscriptions' ), '<a href="#">','</a>' ); ?></p>

        <p class="description"><?php echo sprintf( esc_html__( 'You can set the From Name and From Email in under %1$sGeneral Email Options%2$s. ', 'paid-member-subscriptions' ), '<a href = "'. esc_url( admin_url( 'admin.php?page=pms-settings-page&nav_tab=emails' ) ) .'">' , '</a>' ); ?></p>

    </div>

    <!-- Subscription plans -->
    <div class="pms-meta-box-field-wrapper">

        <label for="pms-email-reminders-subscriptions" class="pms-meta-box-field-label"><?php esc_html_e( 'Subscription(s)', 'paid-member-subscriptions' ); ?></label>

        <?php
        // Check if there are any subscription plans
        if ( function_exists('pms_get_subscription_plans') ){

            $subscription_plans = pms_get_subscription_plans();
            $email_reminder_subscriptions_array = explode( ',', $email_reminder->subscriptions);

            if( !empty( $subscription_plans ) ) {

                // Add "All Subscriptions" checkbox
                $checked = ( in_array('all_subscriptions', $email_reminder_subscriptions_array) ) ? "checked" : '';
                echo ' <label class="pms-meta-box-checkbox-label"> <input type="checkbox" name="pms_email_reminder_subscriptions[]" ' . esc_attr( $checked ) . ' value="all_subscriptions" /> ' . esc_html__( 'All Subscriptions', 'paid-member-subscriptions' ) .' </label><br/>';

                // Display active subscriptions
                foreach ( pms_get_subscription_plans() as $subscription_plan) {
                    $checked = ( in_array( $subscription_plan->id, $email_reminder_subscriptions_array ) ) ? "checked" : '';

                    echo ' <label class="pms-meta-box-checkbox-label"> <input type="checkbox" name="pms_email_reminder_subscriptions[]" ' . esc_attr( $checked ) . ' value="' . esc_attr( $subscription_plan->id ) . '" /> ' . esc_html( $subscription_plan->name ).' </label><br/>';
                }

                echo '<p class="description">' . esc_html__( 'Select the subscription(s) to which this email reminder should be sent.', 'paid-member-subscriptions' ) . '</p>';

            } else {

                echo '<p class="description">' . sprintf( esc_html__( 'You do not have any active Subscription Plans yet. Please create them <a href="%s">here</a>.', 'paid-member-subscriptions' ), esc_url( admin_url( 'edit.php?post_type=pms-subscription' ) ) ) . '</p>';

            }
        }
        ?>


    </div>

    <!-- Status -->
    <div class="pms-meta-box-field-wrapper">

        <label for="pms-email-reminder-status" class="pms-meta-box-field-label"><?php esc_html_e( 'Status', 'paid-member-subscriptions' ); ?></label>

        <select id="pms-email-reminder-status" name="pms_email_reminder_status">
            <option value="active" <?php selected( 'active', $email_reminder->status, true  ); ?>><?php esc_html_e( 'Active', 'paid-member-subscriptions' ); ?></option>
            <option value="inactive" <?php selected( 'inactive', $email_reminder->status, true  ); ?>><?php esc_html_e( 'Inactive', 'paid-member-subscriptions' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Select the email reminder status.', 'paid-member-subscriptions' ); ?></p>

    </div>
