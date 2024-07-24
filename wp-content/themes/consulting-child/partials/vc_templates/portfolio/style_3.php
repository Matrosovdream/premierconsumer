<?php
$category_name = '';
$term_list = wp_get_post_terms( get_the_ID(), 'stm_portfolio_category' );
if( $term_list ) {
    foreach( $term_list as $term ) {
        $category_name .= ' ' . $term->slug;
    }
}

$image_size =  $masonry_grid ? 'consulting-image-900w' : 'consulting-image-900x640-croped';
?>

<div class="item all<?php echo esc_attr( $category_name ); ?>">
    <?php if( has_post_thumbnail() ): ?>
        <a href="<?php the_permalink(); ?>">
            <span class="item_thumbnail has-thumbnail">
            <?php $post_thumbnail = consulting_get_image( get_post_thumbnail_id(), $image_size ); ?>
            <?php echo consulting_filtered_output( $post_thumbnail ); ?>
            </span>
            <span class="bottom-box">
                <span class="portfolio-title stm_base_text_color"><?php the_title(); ?></span>
                <?php if( $term_list ): ?>
                    <span class="portfolio-category stm_base_text_color"><?php echo esc_html( $term_list[ 0 ]->name ); ?></span>
                <?php endif; ?>
            </span>
        </a>
    <?php else: ?>
        <a href="<?php the_permalink(); ?>">
            <span class="item_thumbnail has-thumbnail">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/tmp/placeholder.gif' ); ?>"
                 alt="<?php esc_attr_e( 'Placeholder', 'consulting' ) ?>"/>
            </span>
            <span class="bottom-box">
                <span class="portfolio-title stm_base_text_color"><?php the_title(); ?></span>
                <?php if( $term_list ): ?>
                    <span class="portfolio-category stm_base_text_color"><?php echo esc_html( $term_list[ 0 ]->name ); ?></span>
                <?php endif; ?>
            </span>
        </a>
    <?php endif; ?>
</div>