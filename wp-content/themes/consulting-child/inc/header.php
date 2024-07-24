<?php

add_action('wp_body_open', 'consulting_after_body_open');

function consulting_after_body_open()
{
    require_once get_template_directory() . '/partials/headers/after_body_open.php';
}

add_action('hfe_header', 'consulting_header_end_hfb', 999);

function consulting_header_end_hfb()
{
    require_once get_template_directory() . '/partials/headers/after_header_hfb.php';
}