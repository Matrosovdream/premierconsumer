<?php

$css_class .= ' ' . $style;

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
    'post_type' => 'stm_service',
    'posts_per_page' => $posts_per_page,
    'paged' => $paged
);

$categories = get_terms( 'stm_service_category' );

if( empty( $img_size ) ) {
    $img_size = 'consulting-image-255x182-croped';
}

if( $category != 'all' ) {
    $args[ 'stm_service_category' ] = $category;
}

$services = new WP_Query( $args );

$count_posts = wp_count_posts( 'stm_service' );
$term_list = wp_get_post_terms( get_the_ID(), 'stm_service_category' );
$published_posts = $count_posts->publish;

wp_enqueue_style( 'owl.carousel' );
wp_enqueue_script( 'owl.carousel' );
?>

<?php if( $services->have_posts() ): ?>
    <div class="owl-carousel stm_services<?php echo esc_attr( $css_class ); ?>">
        <?php while ( $services->have_posts() ): $services->the_post();
            $term_list = wp_get_post_terms( get_the_ID(), 'stm_service_category' ); ?>
            <div class="stm_service">
                <div class="stm_service__icon">
                    <i class="stm-icon <?php echo get_post_meta(get_the_ID(), 'service_icon', true) ?>"></i>
                </div>
                <?php if( !$service_title ): ?>
                    <h3 class="stm_service__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                <?php endif; ?>

                <?php if( !$service_excerpt and get_the_excerpt() ) : ?>
                    <div class="stm_service__excerpt base_font_color">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif;
wp_reset_postdata(); ?>