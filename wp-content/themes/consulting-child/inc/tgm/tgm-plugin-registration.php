<?php

require_once CONSULTING_INC_PATH . '/tgm/tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'consulting_require_plugins' );

function consulting_require_plugins( $return = false )
{
	$stm_importer_ver = '5.8.6';
	$stm_post_type_ver = '3.5.5';
	$revslider_ver = '6.4.11';
	$js_composer_ver = '6.7.0';
	$elementor_widgets_ver = '1.1.0';
	$stm_templates_library_ver = '1.2';
	$stm_gdpr_compliance_ver = '1.5';
	
	$plugins = array(
		'envato-market' => array(
			'name' => 'Envato Market',
			'slug' => 'envato-market',
			'source' => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
			'required' => true,
		),
		'stm-importer' => array(
			'name' => 'STM Importer',
			'slug' => 'stm-importer',
			'source' => 'downloads://consulting/stm-importer-' . $stm_importer_ver . '.zip',
			'required' => true,
			'version' => $stm_importer_ver,
			'external_url' => 'https://stylemixthemes.com/',
			'core' => true
		),
		'stm-post-type' => array(
			'name' => 'STM Configurations',
			'slug' => 'stm-post-type',
			'source' => 'downloads://consulting/stm-post-type-' . $stm_post_type_ver . '.zip',
			'required' => true,
			'version' => $stm_post_type_ver,
			'external_url' => 'https://stylemixthemes.com/',
			'core' => true
		),
		'custom-elementor-icons' => array(
			'name' => 'Custom Elementor Icons',
			'slug' => 'custom-elementor-icons',
			'required' => true,
			'core' => true
		),
		'revslider' => array(
			'name' => 'Slider Revolution',
			'slug' => 'revslider',
			'source' => 'downloads://revslider/revslider-' . $revslider_ver . '.zip',
			'required' => true,
			'external_url' => 'http://www.themepunch.com/revolution/',
			'version' => $revslider_ver,
			'premium' => true
		),
		'js_composer' => array(
			'name' => 'WPBakery Page Builder',
			'slug' => 'js_composer',
			'source' => 'downloads://js_composer/js_composer-' . $js_composer_ver . '.zip',
			'external_url' => 'http://vc.wpbakery.com',
			'version' => $js_composer_ver,
			'required' => false,
			'premium' => true
		),
		'elementor' => array(
			'name' => 'Elementor',
			'slug' => 'elementor',
			'required' => false,
		),
		'header-footer-elementor' => array(
			'name' => 'Elementor Header & Footer Builder',
			'slug' => 'header-footer-elementor',
			'required' => false,
		),
		'consulting-elementor-widgets' => array(
			'name' => 'Consulting Elementor',
			'slug' => 'consulting-elementor-widgets',
			'source' => 'downloads://consulting/consulting-elementor-widgets-' . $elementor_widgets_ver . '.zip',
			'required' => false,
			'version' => $elementor_widgets_ver,
			'core' => true
		),
		'pearl-header-builder' => array(
			'name' => 'Pearl Header Builder',
			'slug' => 'pearl-header-builder',
			'required' => false,
		),
		'stm-templates-library' => array(
			'name' => 'STM Templates Library',
			'slug' => 'stm-templates-library',
			'source' => 'downloads://consulting/stm-templates-library-' . $stm_templates_library_ver . '.zip',
			'required' => false,
			'version' => $stm_templates_library_ver,
			'core' => true
		),
		'bookit' => array(
			'name' => 'Booking Calendar | BookIt',
			'slug' => 'bookit',
			'required' => false,
		),
		'breadcrumb-navxt' => array(
			'name' => 'Breadcrumb NavXT',
			'slug' => 'breadcrumb-navxt',
			'required' => false
		),
		'contact-form-7' => array(
			'name' => 'Contact Form 7',
			'slug' => 'contact-form-7',
			'required' => false
		),
		'woocommerce' => array(
			'name' => 'WooCommerce',
			'slug' => 'woocommerce',
			'required' => false
		),
		'mailchimp-for-wp' => array(
			'name' => 'MailChimp for WordPress Lite',
			'slug' => 'mailchimp-for-wp',
			'required' => false
		),
		'instagram-feed' => array(
			'name' => 'Smash Balloon Social Photo Feed',
			'slug' => 'instagram-feed',
			'required' => false
		),
		'recent-tweets-widget' => array(
			'name' => 'Recent Tweets Widget',
			'slug' => 'recent-tweets-widget',
			'required' => false
		),
		'tinymce-advanced' => array(
			'name' => 'Advanced Editor Tools',
			'slug' => 'tinymce-advanced',
			'required' => false
		),
		'add-to-any' => array(
			'name' => 'AddToAny Share Buttons',
			'slug' => 'add-to-any',
			'required' => false
		),
		'amp' => array(
			'name' => 'AMP',
			'slug' => 'amp',
			'required' => false
		),
		'cost-calculator-builder' => array(
			'name' => 'Cost Calculator Builder',
			'slug' => 'cost-calculator-builder',
			'required' => false
		),
		'eroom-zoom-meetings-webinar' => array(
			'name' => 'eRoom – Zoom Meetings & Webinar',
			'slug' => 'eroom-zoom-meetings-webinar',
			'required' => false
		),
		'stm-gdpr-compliance' => array(
			'name' => 'GDPR Compliance & Cookie Consent',
			'slug' => 'stm-gdpr-compliance',
			'source' => 'downloads://consulting/stm-gdpr-compliance-' . $stm_gdpr_compliance_ver . '.zip',
			'required' => false,
			'version' => $stm_gdpr_compliance_ver,
			'external_url' => 'https://stylemixthemes.com/'
		)
	);
	
	if ( $return ) {
		return $plugins;
	} else {
		$config = array(
			'id' => 'pearl_theme_id',
			'is_automatic' => false
		);
		
		
		$layout_plugins = consulting_layout_plugins( consulting_get_layout() );
		$recommended_plugins = consulting_premium_bundled_plugins();
		$layout_plugins = array_merge( $layout_plugins, $recommended_plugins );
		
		$tgm_layout_plugins = array();
		foreach ( $layout_plugins as $layout_plugin ) {
			$tgm_layout_plugins[$layout_plugin] = $plugins[$layout_plugin];
		}
		
		tgmpa( $tgm_layout_plugins, $config );
	}
	
}
