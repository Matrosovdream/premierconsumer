<?php

if (class_exists('STM_PostType')) {
    $defaultPostTypesOptions = array(
        'stm_event' => array(
            'title' => consulting_theme_option('post_type_events_title', esc_html__('Event', 'consulting')),
            'plural_title' => consulting_theme_option('post_type_events_plural', esc_html__('Events', 'consulting')),
            'all_items' => consulting_theme_option('post_type_events_all_items', esc_html__('All Events', 'consulting')),
            'rewrite' => consulting_theme_option('post_type_events_rewrite', 'events'),
            'icon' => consulting_theme_option('post_type_events_icon', 'dashicons-calendar-alt'),
            'has_archive' => (bool)consulting_theme_option( 'post_type_events_enable_archive', false ),
            'publicly_queryable' => (bool)consulting_theme_option( 'post_type_events_enable_single', false ),
            'supports' => array('title', 'thumbnail', 'editor', 'excerpt', 'author')
        ),
        'event_member' => array(
            'title' => esc_html__('Member', 'consulting'),
            'plural_title' => esc_html__('Members', 'consulting'),
            'exclude_from_search' => true,
            'publicly_queryable' => (bool)false,
            'show_in_menu' => 'edit.php?post_type=stm_event',
            'supports' => array('title', 'editor'),
            'name' => esc_html__('Member', 'consulting')
        ),
        'stm_service' => array(
            'title' => consulting_theme_option('post_type_services_title', esc_html__('Service', 'consulting')),
            'plural_title' => consulting_theme_option('post_type_services_plural', esc_html__('Services', 'consulting')),
            'all_items' => consulting_theme_option('post_type_services_all_items', esc_html__('All Services', 'consulting')),
            'rewrite' => consulting_theme_option('post_type_services_rewrite', 'services'),
            'icon' => consulting_theme_option('post_type_services_icon', 'dashicons-clipboard'),
            'has_archive' => (bool)consulting_theme_option( 'post_type_services_enable_archive', false ),
            'publicly_queryable' => (bool)consulting_theme_option( 'post_type_services_enable_single', false ),
            'supports' => array('title', 'thumbnail', 'editor', 'excerpt')
        ),
        'stm_careers' => array(
            'title' => consulting_theme_option('post_type_careers_title', esc_html__('Vacancy', 'consulting')),
            'plural_title' => consulting_theme_option('post_type_careers_plural', esc_html__('Vacancies', 'consulting')),
            'all_items' => consulting_theme_option('post_type_careers_all_items', esc_html__('All Vacancies', 'consulting')),
            'rewrite' => consulting_theme_option('post_type_careers_rewrite', 'careers_archive'),
            'icon' => consulting_theme_option('post_type_careers_icon', 'dashicons-id'),
            'has_archive' => (bool)consulting_theme_option( 'post_type_careers_enable_archive', false ),
            'publicly_queryable' => (bool)consulting_theme_option( 'post_type_careers_enable_single', false ),
            'supports' => array('title', 'editor')
        ),
        'stm_staff' => array(
            'title' => consulting_theme_option('post_type_staff_title', esc_html__('Staff', 'consulting')),
            'plural_title' => consulting_theme_option('post_type_staff_plural', esc_html__('Staff', 'consulting')),
            'all_items' => consulting_theme_option('post_type_staff_all_items', esc_html__('All Staff', 'consulting')),
            'rewrite' => consulting_theme_option('post_type_staff_rewrite', 'staff'),
            'icon' => consulting_theme_option('post_type_careers_icon', 'dashicons-groups'),
            'has_archive' => (bool)consulting_theme_option( 'post_type_staff_enable_archive', false ),
            'publicly_queryable' => (bool)consulting_theme_option( 'post_type_staff_enable_single', false ),
            'supports' => array('title', 'excerpt', 'editor', 'thumbnail')
        ),
        'stm_works' => array(
            'title' => consulting_theme_option('post_type_works_title', esc_html__('Work', 'consulting')),
            'plural_title' => consulting_theme_option('post_type_works_plural', esc_html__('Works', 'consulting')),
            'all_items' => consulting_theme_option('post_type_works_all_items', esc_html__('All Works', 'consulting')),
            'rewrite' => consulting_theme_option('post_type_works_rewrite', 'works'),
            'icon' => consulting_theme_option('post_type_works_icon', 'dashicons-portfolio'),
            'has_archive' => (bool)consulting_theme_option( 'post_type_works_enable_archive', false ),
            'publicly_queryable' => (bool)consulting_theme_option( 'post_type_works_enable_single', false ),
            'supports' => array('title', 'excerpt', 'editor', 'thumbnail')
        ),
        'stm_testimonials' => array(
            'title' => consulting_theme_option('post_type_testimonials_title', esc_html__('Testimonial', 'consulting')),
            'plural_title' => consulting_theme_option('post_type_testimonials_plural', esc_html__('Testimonials', 'consulting')),
            'all_items' => consulting_theme_option('post_type_testimonials_all_items', esc_html__('All Testimonials', 'consulting')),
            'rewrite' => consulting_theme_option('post_type_testimonials_rewrite', 'testimonials'),
            'icon' => consulting_theme_option('post_type_services_icon', 'dashicons-testimonial'),
            'has_archive' => (bool)consulting_theme_option( 'post_type_testimonials_enable_archive', false ),
            'supports' => array('title', 'excerpt', 'thumbnail'),
            'exclude_from_search' => true,
            'publicly_queryable' => (bool)false
        ),
        'stm_vc_sidebar' => array(
            'title' => esc_html__('VC Sidebar', 'consulting'),
            'plural_title' => esc_html__('VC Sidebars', 'consulting'),
            'all_items' => esc_html__('All Sidebars', 'consulting'),
            'rewrite' => 'vc_sidebar',
            'icon' => 'dashicons-schedule',
            'supports' => array('title', 'editor'),
            'exclude_from_search' => true,
            'public' => false,
            //'publicly_queryable' => (bool)false
        ),
        'stm_portfolio' => array(
            'title' => consulting_theme_option('post_type_portfolio_title', esc_html__('Portfolio', 'consulting')),
            'plural_title' => consulting_theme_option('post_type_portfolio_plural', esc_html__('Portfolio', 'consulting')),
            'all_items' => consulting_theme_option('post_type_portfolio_all_items', esc_html__('All Portfolio', 'consulting')),
            'rewrite' => consulting_theme_option('post_type_portfolio_rewrite', 'portfolio'),
            'icon' => consulting_theme_option('post_type_portfolio_icon', 'dashicons-portfolio'),
            'has_archive' => (bool)consulting_theme_option( 'post_type_portfolio_enable_archive', false ),
            'publicly_queryable' => (bool)consulting_theme_option( 'post_type_portfolio_enable_single', false ),
            'supports' => array('title', 'thumbnail', 'editor', 'excerpt')
        ),
    );

    foreach ($defaultPostTypesOptions as $post_type => $data) {
        $args = array();

        if (!empty($data['plural_title'])) {
            $args['pluralTitle'] = $data['plural_title'];
        }
        if (!empty($data['all_items'])) {
            $args['all_items'] = $data['all_items'];
        }
        if (!empty($data['icon'])) {
            $args['menu_icon'] = $data['icon'];
        }
        if (!empty($data['rewrite'])) {
            $args['rewrite'] = array('slug' => $data['rewrite']);
        }
        if (!empty($data['supports'])) {
            $args['supports'] = $data['supports'];
        }
        if (!empty($data['exclude_from_search'])) {
            $args['exclude_from_search'] = $data['exclude_from_search'];
        }
        if (!empty($data['publicly_queryable'])) {
            $args['publicly_queryable'] = $data['publicly_queryable'];
        }
        if (!empty($data['show_in_menu'])) {
            $args['show_in_menu'] = $data['show_in_menu'];
        }
        if (isset($data['has_archive'])) {
            $args['has_archive'] = $data['has_archive'];
        }
        if (isset($data['publicly_queryable'])) {
            $args['publicly_queryable'] = $data['publicly_queryable'];
        }

        STM_PostType::registerPostType($post_type, esc_html($data['title']), $args);
    }

    STM_PostType::addTaxonomy('stm_testimonials_category', esc_html__('Categories', 'consulting'), 'stm_testimonials');
    STM_PostType::addTaxonomy('stm_event_category', __('Categories', 'consulting'), 'stm_event');
    STM_PostType::addTaxonomy('stm_service_category', __('Categories', 'consulting'), 'stm_service');
    STM_PostType::addTaxonomy('stm_works_category', esc_html__('Categories', 'consulting'), 'stm_works');
    STM_PostType::addTaxonomy('stm_staff_category', esc_html__('Categories', 'consulting'), 'stm_staff');
    STM_PostType::addTaxonomy('stm_portfolio_category', __('Categories', 'consulting'), 'stm_portfolio');

}