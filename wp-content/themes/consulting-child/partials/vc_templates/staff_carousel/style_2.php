<?php

wp_enqueue_script( 'slick' );
wp_enqueue_style( 'slick' );

$carousel_id = uniqid( 'staff_carousel_' );

$args = array(
    'post_type' => 'stm_staff',
    'posts_per_page' => $count
);

if( $category != 'all' ) {
    $args[ 'stm_staff_category' ] = $category;
}

$testimonials = new WP_Query( $args );

?>

<div class="staff_carousel_container <?php echo esc_attr( $css_class );
echo esc_attr( $carousel_style ); ?>">
    <?php if( $carousel_arrows ) : ?>
        <div class="staff-carousel-arrows">
            <div class="staff-carousel-arrows-inner"></div>
        </div>
    <?php endif; ?>
    <div class="staff_carousel-box">
        <div class="staff_carousel" id="<?php echo esc_attr( $carousel_id ); ?>">
            <?php while ( $testimonials->have_posts() ): $testimonials->the_post(); ?>
                <div class="staff_carousel_item">
                    <div class="staff_carousel_item_wrap">
                        <div class="staff_info">
                            <div class="items_title"><?php echo esc_attr( $items_title ); ?></div>
                            <h2 class="no_stripe"><?php the_title(); ?></h2>
                            <?php if( $department = get_post_meta( get_the_ID(), 'department', true ) ): ?>
                                <div class="staff_department">
                                    <?php echo esc_html( $department ); ?>
                                </div>
                            <?php endif; ?>
                            <div class="staff_excerpt">
                                <?php echo wp_kses_post( consulting_substr_text( get_the_excerpt(), 330 ) ); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-flat vc_btn3-color-theme_style_1">
                                <?php esc_html_e( 'Read more', 'consulting' ); ?>
                            </a>
                        </div>
                        <?php if( has_post_thumbnail() && !wp_is_mobile() ): ?>
                            <div class="staff_image">
                                <?php the_post_thumbnail( 'consulting-image-900w' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
            endwhile; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        "use strict";
        var <?php echo esc_js( $carousel_id ) ?> =
        $("#<?php echo esc_attr( $carousel_id ) ?>");
        var slickRtl = false;

        if ($('body').hasClass('rtl')) {
            slickRtl = true;
        }

        <?php $arrows = ( !empty( $carousel_arrows ) ) ? 'true' : 'false'; ?>

        <?php echo esc_attr( $carousel_id ) ?>.
        slick({
            rtl: slickRtl,
            dots: false,
            infinite: true,
            <?php if( $carousel_arrows ) : ?>
            appendArrows: '.staff-carousel-arrows-inner',
            prevArrow: "<div class=\"slick_prev\"><i class=\"fa fa-chevron-left\"></i></div>",
            nextArrow: "<div class=\"slick_next\"><i class=\"fa fa-chevron-right\"></i></div>",
            <?php endif; ?>
            'arrows': <?php echo consulting_filtered_output( $arrows ); ?>,
            slidesToShow: <?php echo esc_js( $slides_to_show ); ?>,
            cssEase: "cubic-bezier(0.455, 0.030, 0.515, 0.955)",
            responsive: [
                {
                    breakpoint: 560,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        })
            .on('setPosition', function (event, slick) {
                slick.$slides.css('height', slick.$slideTrack.height() + 'px');
            });
    });
</script>