<?php
wp_enqueue_script( 'owl.carousel' );
wp_enqueue_style( 'owl.carousel' );

$args = array(
    'post_type' => 'stm_portfolio',
    'posts_per_page' => $posts_per_page,
    'post_status' => 'published'
);
$portfolio = new WP_Query( $args );
if( $portfolio->have_posts() ): 
    $owl_id = uniqid( 'owl-' );
?>
<div class="portfolio-carousel">
    <h2 class="portfolio-carousel__title text-center"><?php echo esc_html($title) ?></h2>
    <div class="portfolio-carousel__wrap" id="<?php echo esc_attr($owl_id) ?>">
        <?php 
        while( $portfolio->have_posts() ): $portfolio->the_post();
        $categories = array();
        $term_list = wp_get_post_terms( get_the_ID(), 'stm_portfolio_category' );
        if( $term_list ) {
            foreach( $term_list as $term ) {
                $categories[] = $term->name;
            }
        }
        ?>
        <a href="<?php the_permalink() ?>" class="portfolio-carousel__item">
            <span class="portfolio-carousel__item-image"><?php the_post_thumbnail( 'consulting-image-358x250-cropped' ) ?></span>
            <span class="portfolio-carousel__item-title"><?php the_title() ?></span>
            <span class="portfolio-carousel__item-category"><?php echo implode( ", ", $categories ) ?></span>
        </a>
        <?php endwhile; ?>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#<?php echo esc_js( $owl_id ) ?>').owlCarousel({
            <?php if( $dots ): ?>
            dots: true,
            <?php else: ?>
            dots: false,
            <?php endif; ?>
            
            <?php if( $loop ): ?>
            loop: true,
            <?php endif; ?>
            
            <?php if( $nav ): ?>
            nav: true,
            <?php endif; ?>
            responsive: {
                0: {
                    items: <?php echo esc_js( $items_mobile ); ?>
                },
                768: {
                    items: <?php echo esc_js( $items_tablet ); ?>
                },
                980: {
                    items: <?php echo esc_js( $items_small_desktop ); ?>
                },
                1199: {
                    items: <?php echo esc_js( $items ); ?>
                }
            }
        })
    });
</script>
<?php endif; ?>