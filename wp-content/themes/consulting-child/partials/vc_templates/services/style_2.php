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
                    <?php $url = esc_url( get_the_permalink( get_the_id() ) ); ?>
                    <div class="content">
                        <?php if( !$service_cat and $term_list ): ?>
                            <h4 class="category no_stripe" <?php if( !empty( $cattegory_color ) ) echo 'style="color:' . esc_attr( $category_color ) . '"'; ?>><?php echo esc_html( $term_list[ 0 ]->name ); ?></h4>
                        <?php endif; ?>
                        <?php if( !$service_title ): ?>
                            <h5><a href="<?php echo esc_url( $url ); ?>" <?php if( !empty( $title_color ) ) echo 'style="color:' . esc_attr( $title_color ) . '"'; ?>><?php the_title(); ?></a></h5>
                        <?php endif; ?>
                    </div>
                    <?php if( !$service_image and has_post_thumbnail() ): ?>
                        <?php $post_thumbnail = consulting_get_image( get_post_thumbnail_id(), $img_size ); ?>

                        <div class="item_thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo consulting_filtered_output( $post_thumbnail ); ?>
                            </a>
                            <?php if( stm_check_layout( 'layout_18' ) ): ?>
                                <h5 class="stm_title_l18" <?php if( !empty( $ctitle_color ) ) echo 'style="color:' . esc_attr( $title_color ) . '"'; ?>><?php the_title(); ?></h5>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

<?php endif;
wp_reset_postdata(); ?>