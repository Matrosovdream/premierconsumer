<div class="footer_logo">
    <?php
    if ( $args && !empty( $args ) ) :
        $footer_logo = $args['footer_logo'];
        $logo_image_sizes = $args['logo_image_sizes'];
        if ( consulting_theme_option( 'footer_logo', false ) ) {
            $logo_image_data = wp_get_attachment_image_src( consulting_theme_option( 'footer_logo' ), 'full' );
            if ( is_array( $logo_image_data ) )
                $logo_image_sizes = [$logo_image_data[1], $logo_image_data[2]];
        }  ?>
        <a href="<?php echo esc_url( home_url( '/' ) ) ?>">
            <img src="<?php echo esc_url( $footer_logo ); ?>"
                 alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                 width="<?php echo esc_attr( $logo_image_sizes[0] ) ?>"
                 height="<?php echo esc_attr( $logo_image_sizes[1] ) ?>"
                <?php
                if ( ( consulting_theme_option( 'footer_logo_width' ) && is_numeric( consulting_theme_option( 'footer_logo_width' ) ) ) || ( consulting_theme_option( 'footer_logo_height') && is_numeric( consulting_theme_option( 'footer_logo_height') ) ) ) {
                    $footer_logo_style_width = ( trim( consulting_theme_option( 'footer_logo_width' ) ) ) ? 'width:' . consulting_theme_option( 'footer_logo_width' ) . 'px;' : 'width:auto;';
                    $footer_logo_style_height = ( trim( consulting_theme_option( 'footer_logo_height' ) ) ) ? 'height:' . consulting_theme_option( 'footer_logo_height' ) . 'px;' : 'height:auto;';
                    ?> style="<?php echo esc_html( $footer_logo_style_width . $footer_logo_style_height ); ?>" <?php
                }
                ?>/>
        </a>
    <?php endif; ?>
</div>