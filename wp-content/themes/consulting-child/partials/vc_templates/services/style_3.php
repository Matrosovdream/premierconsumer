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
$term_list = wp_get_post_terms( get_the_ID(), 'stm_service_category' );
$published_posts = $count_posts->publish;

wp_enqueue_style( 'owl.carousel' );
wp_enqueue_script( 'owl.carousel' );
?>

<?php if( $services->have_posts() ): ?>
    <div class="owl-carousel stm_services<?php echo esc_attr( $css_class ); ?>"
         data-per-row="<?php echo esc_attr( $posts_per_row ); ?>">
        <?php while ( $services->have_posts() ): $services->the_post();
            $term_list = wp_get_post_terms( get_the_ID(), 'stm_service_category' ); ?>
            <div class="item">
                <div class="item_wr">
                    <?php $url = esc_url( get_the_permalink( get_the_id() ) ); ?>
                    <div class="content">
                        <?php if( !$service_title ): ?>
                            <h5>
                                <a href="<?php echo esc_url( $url ); ?>" <?php if( !empty( $title_color ) ) echo 'style="color:' . esc_attr( $title_color ) . '"'; ?>><?php the_title(); ?></a>
                            </h5>
                        <?php endif; ?>
                    </div>
                    <?php if( !$service_image and has_post_thumbnail() ): ?>
                        <?php $post_thumbnail = consulting_get_image( get_post_thumbnail_id(), $img_size ); ?>

                        <div class="item_thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo consulting_filtered_output( $post_thumbnail ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif;
wp_reset_postdata(); ?>