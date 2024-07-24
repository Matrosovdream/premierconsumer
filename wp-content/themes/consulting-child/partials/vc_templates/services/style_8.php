<?php

if (empty($posts_per_row)) $posts_per_row = 4;
$css_class .= ' ' . $style;
$css_class .= ' cols_' . $posts_per_row;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type' => 'stm_service',
    'posts_per_page' => $posts_per_page,
    'paged' => $paged
);

$categories = get_terms('stm_service_category');

if (empty($img_size)) {
    $img_size = 'consulting-image-255x182-croped';
}

if ($category != 'all') {
    $args['stm_service_category'] = $category;
}

$services = new WP_Query($args);

$count_posts = wp_count_posts('stm_service');
$published_posts = $count_posts->publish;

?>

<?php if ($services->have_posts()): ?>
    <div class="stm_services<?php echo esc_attr($css_class); ?>">
        <?php while ($services->have_posts()): $services->the_post(); ?>
            <div class="stm_service">

                <div class="stm_service__icon">
                    <i class="<?php echo get_post_meta(get_the_ID(), 'service_icon', true) ?>"></i>
                </div>
                <?php if( isset ( $service_title ) ): ?>
                <h3 class="stm_service__title">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h3>
                <?php endif; ?>

                <?php if( isset ( $service_excerpt ) and get_the_excerpt() ) : ?>
                <div class="stm_service__excerpt base_font_color">
                    <?php the_excerpt(); ?>
                </div>
                <?php endif; ?>

                <?php if( isset ( $service_more ) ): ?>
                <a href="<?php the_permalink() ?>" class="stm_service__link"><?php echo esc_html__( 'Read more', 'consulting' ); ?> <i class="stm-lnr-arrow-right"></i></a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <?php if( isset ( $service_pagination )  ): ?>
        <div class="services_pagination">
            <?php consulting_paging_nav('paging_view_posts-list', $services); ?>
        </div>
    <?php endif; ?>
<?php endif;
wp_reset_postdata(); ?>