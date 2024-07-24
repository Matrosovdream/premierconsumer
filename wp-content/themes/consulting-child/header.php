<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <?php do_action( 'consulting_head_start' ); ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head(); ?>
    <?php do_action( 'consulting_head_end' ); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/css/custom.css?time=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/css/responsive.css?a=<?php echo time(); ?>">
	
	<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Premier Consumer Credit Counseling",
      "url": "https://premierconsumer.org/",
      "logo": "https://premierconsumer.org/wp-content/uploads/2021/09/logo.jpg",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "1-800-296-4950",
        "contactType": "customer service",
        "contactOption": "TollFree",
        "areaServed": "US",
        "availableLanguage": "en"
      },
      "sameAs": [
        "https://www.facebook.com/premierconsumer",
        "https://www.linkedin.com/company/premier-consumer-credit-cnslng"
      ]
    }
    </script>
	
	<!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TQSSJS5');</script>
    <!-- End Google Tag Manager -->

    <script type="text/javascript" src="https://seal-seflorida.bbb.org/inc/legacy.js"></script>

</head>
<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TQSSJS5"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="loader-overlay" id="loader-register-div" style="display:none;">
    <div id="loader"></div>
</div>
<?php
    wp_body_open();
    get_template_part('partials/headers/main');
    do_action( 'consulting_header_end' );
