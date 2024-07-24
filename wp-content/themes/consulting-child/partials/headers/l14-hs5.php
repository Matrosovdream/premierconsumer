<?php
$consulting_config = consulting_config();
$logo_tmp_src = '';
if (!empty($consulting_config['layout']) && $consulting_config['layout'] != 'layout_1' && $consulting_config['layout'] != 'layout_12') {
    $logo_tmp_src = $consulting_config['layout'] . '/';
}
$wc_cart_hide = consulting_theme_option( 'wc_cart_hide', false );
?>

<div class="top_nav">
    <div class="container">
        <div class="top_nav_wrapper clearfix">
            <?php
            wp_nav_menu(array(
                    'theme_location' => 'consulting-primary_menu',
                    'container' => false,
                    'depth' => 4,
                    'menu_class' => 'main_menu_nav'
                )
            );
            ?>
            <?php if (class_exists('WooCommerce') && !$wc_cart_hide): ?>
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart_count"><i class="stm-cart_13"></i><span
                            class="count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span></a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="header_top clearfix">
    <div class="container">
        <?php
        $header_working_hours = consulting_theme_option('header_working_hours', 'Write us<a href="mailto:info@consulting.com">info@consulting.com</a>');

        $header_phone = consulting_theme_option('header_phone', '<strong>212 386 5575</strong><span data-popup="request-call-back">Request a call back</span>');
        ?>

        <?php if (!empty($header_phone)): ?>
            <?php
            $header_popup = consulting_theme_option('header_popup', 'none');
            if ($header_popup !== 'none'): ?>
                <a href="#stm_header_popup" class="stm_fancybox">
            <?php endif; ?>
            <div class="stm_l14_h5-phone">
                <div class="icon"><?php echo consulting_get_icon('header_phone_icon', 'stm-phone_13'); ?></div>
                <div class="text">
                    <?php echo wp_kses(nl2br($header_phone), array('br' => array(), 'strong' => array(), 'span' => array('data-popup' => array(), 'data-scroll-to' => array()), 'a' => array('href' => array()))); ?>
                </div>
            </div>
            <?php if ($header_popup !== 'none'): ?>
                </a>
                <div id="stm_header_popup">
                    <?php $cf7 = get_post($header_popup); ?>
                    <?php echo(do_shortcode('[contact-form-7 id="' . $cf7->ID . '" title="' . ($cf7->post_title) . '"]')); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="stm_l14_h5-right">
            <div class="clearfix">
                <div class="logo">
                    <?php
                    $page_ID = consulting_page_id();
                    $header_inverse = get_post_meta($page_ID, 'header_inverse', true);
                    ?>
                    <?php if ($header_inverse && $logo = consulting_get_logo_url('logo', get_template_directory_uri() . '/assets/images/tmp/' . $logo_tmp_src . 'logo_default.svg')): ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo); ?>"
                                                                             style="width: <?php echo esc_attr(consulting_theme_option('logo_width')) ?>px; height: <?php echo esc_attr(consulting_theme_option('logo_height')) ?>px;"
                                                                             alt="<?php bloginfo('name'); ?>"/></a>
                    <?php elseif ($logo = consulting_get_logo_url('dark_logo', get_template_directory_uri() . '/assets/images/tmp/' . $logo_tmp_src . 'logo_dark.svg')): ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo); ?>"
                                                                             style="width: <?php echo esc_attr(consulting_theme_option('logo_width')) ?>px; height: <?php echo esc_attr(consulting_theme_option('logo_height')) ?>px;"
                                                                             alt="<?php bloginfo('name'); ?>"/></a>
                    <?php else: ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($header_working_hours)): ?>
                    <div class="stm_l14_h5-wh">
                        <div class="icon"><?php echo consulting_get_icon('header_working_hours_icon', 'stm-phone_13'); ?></div>
                        <div class="text">
                            <?php echo wp_kses(nl2br($header_working_hours), array('br' => array(), 'strong' => array(), 'span' => array(), 'a' => array('href' => array()))); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<div class="mobile_header">
    <div class="logo_wrapper clearfix">
        <div class="logo">
            <?php if ($logo = consulting_get_logo_url('dark_logo', get_template_directory_uri() . '/assets/images/tmp/' . $logo_tmp_src . 'logo_dark.svg')): ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo); ?>"
                                                                     style="width: <?php echo esc_attr(consulting_theme_option('logo_width')) ?>px; height: <?php echo esc_attr(consulting_theme_option('logo_height')) ?>px;"
                                                                     alt="<?php bloginfo('name'); ?>"/></a>
            <?php else: ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
            <?php endif; ?>
        </div>
        <div id="menu_toggle">
            <button></button>
        </div>
    </div>
    <div class="header_info">
        <div class="top_nav_mobile">
            <?php
            wp_nav_menu(array(
                    'theme_location' => 'consulting-primary_menu',
                    'container' => false,
                    'depth' => 4,
                    'menu_class' => 'main_menu_nav'
                )
            );
            ?>
        </div>
        <div class="icon_texts">
            <?php if (!empty($header_phone)): ?>
                <div class="icon_text clearfix">
                    <?php $header_popup = consulting_theme_option('header_popup', 'none');
                    if ($header_popup !== 'none'): ?>
                    <a href="#stm_header_popup" class="stm_fancybox">
                        <?php endif; ?>
                        <div class="icon"><?php echo consulting_get_icon('header_phone_icon', 'stm-phone_13'); ?></div>
                        <div class="text">
                            <?php echo wp_kses(nl2br($header_phone), array('br' => array(), 'strong' => array(), 'span' => array('data-popup' => array(), 'data-scroll-to' => array()), 'a' => array('href' => array()))); ?>
                        </div>
                        <?php if ($header_popup !== 'none'): ?>
                    </a>
                <?php endif; ?>
                </div>
            <?php endif; ?>


            <?php if ($header_hours = consulting_theme_option('header_working_hours', "<strong>Mon - Sat 8.00 - 18.00</strong>\n<span>Sunday CLOSED</span>")): ?>
                <div class="icon_text clearfix">
                    <div class="icon"><?php echo consulting_get_icon('header_working_hours_icon', 'fa-clock-o'); ?></div>
                    <div class="text">
                        <?php echo wp_kses(nl2br($header_hours), array('br' => array(),
                            'strong' => array(),
                            'span' => array()
                        )); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($header_address = consulting_theme_option('header_address', "<strong>1010 Avenue of the Moon</strong>\n<span>New York, NY 10018 US.</span>")): ?>
                <div class="icon_text clearfix">
                    <div class="icon"><?php echo consulting_get_icon('header_address_icon', 'fa-map-marker'); ?></div>
                    <div class="text">
                        <?php echo wp_kses(nl2br($header_address), array('br' => array(),
                            'strong' => array(),
                            'span' => array()
                        )); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>