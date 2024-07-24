<?php consulting_get_header(); ?>
	<section class="error-notfound-sec">
	    <div class="notfound-div">
	        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2021/10/gfd.jpg">
	         <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-btn"><?php esc_html_e( 'homepage', 'consulting' ); ?>
					<i class="fa fa-chevron-right"></i>
			</a>
	    </div>
	</section>
<?php get_footer(); ?>