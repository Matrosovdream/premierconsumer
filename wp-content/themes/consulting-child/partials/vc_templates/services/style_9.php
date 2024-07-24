<?php

if( empty( $posts_per_row ) ) $posts_per_row = 4;
$css_class .= ' ' . $style;
$css_class .= ' cols_' . $posts_per_row;

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
$published_posts = $count_posts->publish;

?>

<?php if( $services->have_posts() ): ?>

    <div class="stm_services<?php echo esc_attr( $css_class ); ?>">
        <?php while ( $services->have_posts() ): $services->the_post(); $term_list = wp_get_post_terms( get_the_ID(), 'stm_service_category' ); ?>
            <div class="item">
                <div class="item_wr">
                    <div class="content" <?php if( !empty( $excerpt_color ) ) echo 'style="color:' . esc_attr( $excerpt_color ) . '"'; ?>>
                        <?php if( !$service_title ): ?>
                            <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <?php endif; ?>
                        <?php if( !$service_excerpt and get_the_excerpt() ) : ?>
                            <?php the_excerpt(); ?>
                        <?php endif; ?>
                        <?php if( !$service_more ): ?>
                            <a class="read_more"
                               href="<?php the_permalink(); ?>">
                                <span><?php echo esc_html__( 'Read more', 'consulting' ); ?></span>
                                <span class="stm-amsterdam-arrow"></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif;
wp_reset_postdata(); ?>