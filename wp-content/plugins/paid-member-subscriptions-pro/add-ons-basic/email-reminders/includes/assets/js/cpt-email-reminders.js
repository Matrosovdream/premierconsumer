/**
 * JS for Email Reminders admin cpt screen
 *
 */

jQuery( function($) {

    // When publishing or updating the Email Reminder must have a title
    $(document).on('click', '#publish, #save-post', function() {

        var emailReminderTitle = $('#title').val().trim();

        if ( emailReminderTitle == '' ) {

            alert('Email Reminder must have a name.');
            
            return false;

        }

    });

    // Select Available Tags for Email Reminder on click
    $('#pms_er_available_tags input').click( function() {

        this.select();

    });

    $('#pms_email_reminders p.available_tags').click( function() {

        $('#pms_er_available_tags input').select();

    });


    // Change "Publish" button text
    $(document).ready( function(){

        $('input#publish').val('Save Email Reminder');

    });

    // Show the admin emails list if the Send To select has the value "admin"
    $(document).ready( function() {

        if( $('#pms-email-reminder-send-to').val() == 'admin' ) {
            $('#pms-email-reminder-admin-emails-wrapper').show();
        }

    });

    // Show / hide the admin emails list when the Send To select changes
    $(document).on( 'change', '#pms-email-reminder-send-to', function() {

        if( $(this).val() == 'admin' ) {
            $('#pms-email-reminder-admin-emails-wrapper').fadeIn();
        } else {
            $('#pms-email-reminder-admin-emails-wrapper').fadeOut();
        }

    });

    /**
     * Add Link to PMS Docs next to page title
     * */
    $(document).ready( function () {
        $(function(){
            $('.wp-admin.edit-php.post-type-pms-email-reminders .wrap .wp-heading-inline').append('<a href="https://www.cozmoslabs.com/docs/paid-member-subscriptions/add-ons/email-reminders/?utm_source=wpbackend&utm_medium=pms-documentation&utm_campaign=PMSDocs" target="_blank" data-code="f223" class="pms-docs-link dashicons dashicons-editor-help"></a>');
        });
    });

});
