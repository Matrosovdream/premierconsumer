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

<div class="staff_carousel_container <?php echo esc_attr( $css_class ); ?>">
    <?php if( $carousel_arrows ) : ?>
        <div class="staff_carousel_arrows">
            <div class="staff_carousel_arrows_inner"></div>
        </div>
    <?php endif; ?>
    <div class="staff_carousel-box">
        <div class="staff_carousel" id="<?php echo esc_attr( $carousel_id ); ?>">
            <?php while ( $testimonials->have_posts() ): $testimonials->the_post(); ?>
                <div class="staff_carousel_item">
                    <?php if( has_post_thumbnail() ): ?>
                        <div class="staff_image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'consulting-image-350x250-croped' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="staff_info">
                        <h5 class="no_stripe">
                            <a href="<?php the_permalink(); ?>" class="text_decoration_none secondary_font_color_hv"><?php the_title(); ?></a>
                        </h5>
                        <?php if( $department = get_post_meta( get_the_ID(), 'department', true ) ): ?>
                            <div class="staff_department">
                                <?php echo esc_html( $department ); ?>
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

        <?php $arrows = (!empty($carousel_arrows)) ? 'true': 'false'; ?>

        <?php echo esc_attr( $carousel_id ) ?>.
        slick({
            rtl: slickRtl,
            dots: false,
            infinite: true,
            <?php if( $carousel_arrows ) : ?>
            appendArrows: '.staff_carousel_arrows_inner',
            prevArrow: "<div class=\"slick_prev\"><i class=\"fa fa-chevron-left\"></i></div>",
            nextArrow: "<div class=\"slick_next\"><i class=\"fa fa-chevron-right\"></i></div>",
            <?php endif; ?>
            'arrows': <?php echo consulting_filtered_output($arrows); ?>,
            slidesToShow: <?php echo esc_js( $slides_to_show ); ?>,
            cssEase: "cubic-bezier(0.455, 0.030, 0.515, 0.955)",
            responsive: [
                {
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 560,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>