<li id="post-<?php the_ID(); ?>" <?php post_class( 'stm_post_info' ); ?>>
	<div class="custom-cat">
	<?php if( get_the_title() ): ?>
		<h4 class="stripe_2"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h4>
	<?php endif; ?>
	<div class="stm_post_details clearfix">
		<ul class="clearfix">
			<li class="post_date">
				<i class="fa fa fa-clock-o"></i>
				<?php echo get_the_date(); ?>
			</li>
			<li class="post_by"><?php esc_html_e( 'Posted by:', 'consulting' ); ?> <span><?php the_author(); ?></span></li>
			<li class="post_cat"><?php printf( _n( 'Category:', 'Categories:', count( wp_get_post_categories( get_the_ID() ) ), 'consulting' )); ?>
				<span><?php echo implode( ', ', wp_get_post_categories( get_the_ID(), array( 'fields' => 'names' ) ) ) ?></span>
			</li>
		</ul>
		<div class="comments_num">
			<a href="<?php comments_link(); ?>"><i class="fa fa-comment-o"></i><?php comments_number(); ?> </a>
		</div>
	</div>
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="post_thumbnail">
			<a href="<?php echo get_permalink(); ?>">
				<?php the_post_thumbnail( 'consulting-image-1110x550-croped' ); ?>
			</a>
		</div>
	<?php } ?>
	<div class="post_excerpt">
        <?php 
        echo get_the_excerpt().'.....';?>
	</div>
	<div class="post_read_more">
		<a class="button bordered icon_right" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'read more', 'consulting' ); ?>
			<i class="fa fa-chevron-right"></i>
		</a>
	</div>
	</div>
</li>