<?php
wp_enqueue_script( 'jquery-effects-core' );
wp_enqueue_script( 'jquery-ui-tabs' );

$categories = get_terms( array( 'stm_service_category' ) );

if( empty( $items_count ) ) {
    $items_count = -1;
}

$css_class .= ' stm_services_tabs ' . $el_class . $style;

?>
<?php if ( $categories ) { ?>
    <div class="<?php echo esc_attr( $css_class ); ?>">

        <div class="services_categories">
            <ul class="clearfix">
                <?php foreach ( $categories as $category ) { ?>
                <li>
                    <a href="#service-tab-<?php echo esc_attr( $category->slug ); ?>"><?php echo esc_html( $category->name ); ?></a>
                </li>
                <?php } ?>
            </ul>
        </div>

        <?php foreach ( $categories as $category ) { ?>
            <?php
            $args  = array(
                'post_type'        => 'stm_service',
                'posts_per_page' => $items_count,
                'stm_service_category' => $category->slug
            );
            $posts = new WP_Query( $args );
            ?>
            <?php if ( $posts->have_posts() ) { ?>
                <div class="services_tabs" id="service-tab-<?php echo esc_attr( $category->slug ); ?>">

                    <?php while ( $posts->have_posts() ) { $posts->the_post(); ?>

                        <div class="service_tab_item">

                            <div class="service-content">
                                <a href="<?php the_permalink(); ?>" class="service-image-box">
                                <?php if( has_post_thumbnail() ): ?>
                                    <?php echo get_the_post_thumbnail( $posts->page_id, 'full' ); ?>
                                <?php else: ?>
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/tmp/placeholder.gif' ); ?>" alt="<?php the_title() ?>" />
                                <?php endif; ?>
                                    <span class="service-title"><?php the_title(); ?></span>
                                </a>
                                <div class="service-description">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="service-more">
                                    <?php echo esc_html__( 'See More', 'consulting' ); ?>
                                    <i class="stm-right-arrow"></i>
                                </a>
                            </div>
                        </div>

                    <?php } wp_reset_postdata(); ?>

                </div>
            <?php } ?>
        <?php } ?>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                "use strict";
                $(".stm_services_tabs").tabs({
                    hide: 'fadeOut',
                    show: 'fadeIn',
                    duration:'fast'
                });
            });
        </script>

    </div>
<?php } ?>