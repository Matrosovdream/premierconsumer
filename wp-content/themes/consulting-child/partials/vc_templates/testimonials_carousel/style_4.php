<?php

wp_enqueue_script( 'slick' );
wp_enqueue_style( 'slick' );

if( !empty( $style ) ) {
    $css_class .= ' ' . esc_attr( $style );
}

$args = array(
    'post_type' => 'stm_testimonials',
    'posts_per_page' => $count
);

if ($category != 'all') {
    $args['stm_testimonials_category'] = $category;
}

if ($per_row) {
    $css_class .= ' per_row_' . $per_row;
} else {
    $per_row = 1;
}

if ($disable_carousel) {
    $css_class .= ' disable_carousel';
}

if (empty($thumb_size)) {
    $thumb_size = '350x350';
}

$testimonials = new WP_Query($args);
$id = uniqid('partners_carousel_');

$autoplay_carousel_js = 'false';
if (!empty($autoplay_carousel) and $autoplay_carousel == 'yes') {
    $autoplay_carousel_js = 'true';
}
?>
<?php if ($testimonials->have_posts()): ?>
    <div class="<?php echo esc_attr($css_class); ?>" id="<?php echo esc_attr($id); ?>">
        <?php while ($testimonials->have_posts()): $testimonials->the_post(); ?>

            <div class="testimonial">
                <div class="testimonial_inner">
                    <?php if (!$disable_image and has_post_thumbnail()): ?>
                        <?php
                        $author_photo = consulting_get_image(get_post_thumbnail_id(), $thumb_size);
                        ?>
                        <div class="image">
                            <span>
                            <?php if ($link['url']): ?>
                                <a href="<?php echo esc_url($link['url']); ?>"><?php echo consulting_filtered_output($author_photo); ?></a>
                            <?php else: ?>
                                <?php echo consulting_filtered_output($author_photo); ?>
                            <?php endif; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <div class="info">
                        <div class="stm_testimonials_content_unit"><?php the_excerpt(); ?></div>
                        <h6 class="no_stripe">
                            <?php if ($link['url']): ?>
                                <a href="<?php echo esc_url($link['url']); ?>">
                                    <?php the_title(); ?>
                                </a>
                            <?php else: ?>
                                <?php the_title(); ?>
                            <?php endif; ?>
                        </h6>
                        <div class="position">
                        <?php
                            echo esc_html( get_post_meta( get_the_ID(), 'testimonial_position', true ) );
                            if( $position = get_post_meta( get_the_ID(), 'testimonial_position', true ) ) {
                                echo esc_html( $position ) . ', ';
                            }
                            echo esc_html( get_post_meta( get_the_ID(), 'testimonial_company', true ) );
                        ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
    <?php if (!$disable_carousel): ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                "use strict";
                var <?php echo esc_attr($id) ?> = $("#<?php echo esc_attr($id) ?>");
                var slickRtl = false;

                if ($('body').hasClass('rtl')) {
                    slickRtl = true;
                }

                <?php
                $opt = 'arrows: false,';
                if (!$disable_carousel_arrows) {
                    $opt = 'arrows: true,';
                    $opt .= 'prevArrow:"<div class=\"slick_prev\"><i class=\"fa fa-chevron-left\"></i></div>",';
                    $opt .= 'nextArrow: "<div class=\"slick_next\"><i class=\"fa fa-chevron-right\"></i></div>",';
                }
                ?>

                <?php echo esc_attr($id) ?>.
                slick({
                    rtl: slickRtl,
                    dots: <?php echo (stm_check_layout('layout_ankara')) ? 'true' : 'false'; ?>,
                    infinite: true,
                    <?php echo consulting_filtered_output($opt); ?>
                    autoplaySpeed: 5000,
                    autoplay: <?php echo esc_js($autoplay_carousel_js); ?>,
                    slidesToShow: <?php echo esc_js($per_row); ?>,
                    cssEase: "cubic-bezier(0.455, 0.030, 0.515, 0.955)",
                    responsive: [
                        {
                            breakpoint: 769,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            });
        </script>
    <?php endif; ?>
<?php endif; ?>
<?php wp_reset_query(); ?>