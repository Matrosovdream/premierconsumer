<?php

//stm_consulting_pa(get_defined_vars());

if( $grayscale ) {
    $css_class .= ' grayscale';
}

if( $el_class ) {
    $css_class .= ' ' . $el_class;
}

if( $h_centered ) {
    $css_class .= ' centered';
}

wp_enqueue_script( 'owl.carousel' );
wp_enqueue_style( 'owl.carousel' );

$owl_id = uniqid( 'owl-' );
$owl_nav_id = uniqid( 'owl-nav-' );

if( '' === $images ) {
    $images = '-1,-2,-3';
}

$images = explode( ',', $images );

?>

<div class="vc_image_carousel_wr<?php echo esc_attr( $css_class ); ?>">
    <div style="display: none;" class="vc_image_carousel <?php echo esc_html( $style ); ?>" id="<?php echo esc_attr( $owl_id ); ?>">
        <?php $link_num = 0;
        foreach( $images as $attach_id ) : ?>
            <?php

            $thumbnail = consulting_get_image( $attach_id, $img_size );

            $link_url = '';

            if( !empty( $custom_links[ $link_num ] ) ) {
                $link_url = $custom_links[ $link_num ];
            }
            ?>
            <div class="item" style="margin: 0 10px;">
                <?php if( $link_url ): ?>
                    <a href="<?php echo esc_url( $link_url ); ?>">
                        <?php echo consulting_filtered_output( $thumbnail ); ?>
                    </a>
                <?php else: ?>
                    <?php echo consulting_filtered_output( $thumbnail ); ?>
                <?php endif; ?>
            </div>
            <?php $link_num++; endforeach; ?>
    </div>
    <script type="text/javascript">
        jQuery(window).load(function(){
            var owlRtl = false;
            if (jQuery('body').hasClass('rtl')) {
                owlRtl = true;
            }
            jQuery("#<?php echo esc_js( $owl_id ); ?>").show().owlCarousel({
                rtl: owlRtl,
                <?php if ( isset( $autoplay ) && $autoplay ) : ?>
                autoplay: true,
                <?php else : ?>
                autoplay: false,
                <?php endif; ?>

                <?php if ( isset( $dots ) && $dots ) : ?>
                dots: true,
                <?php else : ?>
                dots: false,
                <?php endif; ?>

                <?php if ( isset( $loop ) && $loop ) : ?>
                loop: true,
                <?php else : ?>
                loop: false,
                <?php endif; ?>

                <?php if ( isset( $nav ) && $nav ) : ?>
                nav: true,
                <?php else : ?>
                nav: false,
                <?php endif; ?>

                <?php if ( isset( $timeout ) && $timeout ) : ?>
                autoplayTimeout: <?php echo esc_js( $timeout ); ?>,
                <?php endif; ?>
                <?php if ( isset( $smart_speed ) && $smart_speed ) : ?>
                smartSpeed: <?php echo esc_js( $smart_speed ); ?>,
                <?php endif; ?>
                responsive: {
                    <?php if ( isset( $items_mobile ) && $items_mobile ) : ?>
                    0: {
                        items: <?php echo esc_js( $items_mobile ); ?>
                    },
                    <?php endif; ?>
                    <?php if ( isset( $items_tablet ) && $items_tablet ) : ?>
                    768: {
                        items: <?php echo esc_js( $items_tablet ); ?>
                    },
                    <?php endif; ?>
                    <?php if ( isset( $items_small_desktop ) && $items_small_desktop ) : ?>
                    980: {
                        items: <?php echo esc_js( $items_small_desktop ); ?>
                    },
                    <?php endif; ?>
                    <?php if ( isset( $items ) && $items ) : ?>
                    1199: {
                        items: <?php echo esc_js( $items ); ?>
                    }
                    <?php endif; ?>
                }
            });
        });
    </script>
</div>