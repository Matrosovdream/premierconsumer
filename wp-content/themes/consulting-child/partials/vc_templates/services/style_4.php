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
        <?php while ( $services->have_posts() ): $services->the_post(); ?>
            <div class="item">
                <div class="item_wr">
                    <?php if( !$service_image && has_post_thumbnail() ): ?>
                        <?php $post_thumbnail = consulting_get_image( get_post_thumbnail_id(), $img_size ); ?>
                        <div class="item_thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo wp_kses_post( $post_thumbnail ); ?>
                                <div class="item_info">
                                    <h5><?php the_title(); ?></h5>
                                    <span><i class="stm-lnr-arrow-right before-text"></i><?php esc_html_e( 'Read more', 'consulting' ); ?> <i class="stm-lnr-arrow-right"></i></span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif;
wp_reset_postdata(); ?>