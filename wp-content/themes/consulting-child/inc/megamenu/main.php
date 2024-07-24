<?php
if( (bool)consulting_theme_option( 'mega_menu', false ) ) {
    if(is_admin()) {
        require_once(CONSULTING_INC_PATH . '/megamenu/admin/includes/xteam/xteam.php');
        require_once(CONSULTING_INC_PATH . '/megamenu/admin/includes/config.php');
        require_once(CONSULTING_INC_PATH . '/megamenu/admin/includes/enqueue.php');
        require_once(CONSULTING_INC_PATH . '/megamenu/admin/includes/fontawesome.php');
    } else {
        require_once(CONSULTING_INC_PATH . '/megamenu/includes/walker.php');
        require_once(CONSULTING_INC_PATH . '/megamenu/includes/enqueue.php');
    }
}