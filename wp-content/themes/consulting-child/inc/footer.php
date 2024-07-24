<?php

add_action('hfe_footer_before', 'consulting_before_footer');

function consulting_before_footer() {
    require_once get_template_directory() . '/partials/footer/before_footer.php';
}