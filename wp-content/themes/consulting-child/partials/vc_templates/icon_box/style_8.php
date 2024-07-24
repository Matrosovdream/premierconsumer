<?php

if( $v_align_middle ) {
    $css_class .= ' middle';
}

if( $enable_hexagon ) {
    $css_class .= ' hexagon';
    if( $enable_hexagon_animation ) {
        $css_class .= ' hexanog_animation';
    }
}

if( !empty( $box_style ) ) {
    $css_class .= ' ' . $box_style;
}

$title_classes = array();
$title_class = '';

if( !empty( $title_color ) && $title_color != 'custom' ) {
    $title_classes[] = 'font-color_' . esc_attr( $title_color );
}

if( $hide_title_line || $hide_title_line === 'hide_title_line' ) {
    $title_classes[] = 'no_stripe';
}

if( !empty( $title_classes ) ) {
    $title_class = ' class="' . join( ' ', $title_classes ) . '"';
}

$title_style = '';
$title_styles = array();
if( !empty( $title_font_size ) ) {
    $title_styles[] = 'font-size:' . esc_attr( $title_font_size ) . 'px';
}

if( !empty( $title_line_height ) ) {
    $title_styles[] = 'line-height:' . esc_attr( $title_line_height ) . 'px';
}

if( $title_color == 'custom' && !empty( $title_color_custom ) ) {
    $title_styles[] = 'color:' . esc_attr( $title_color_custom );
}

if( !empty( $title_styles ) ) {
    $title_style = ' style="' . implode( ';', $title_styles ) . '"';
}

$icon_class = '';

if( !empty( $icon_color ) && $icon_class != 'custom' ) {
    $icon_class .= ' font-color_' . esc_attr( $icon_color );
}

if( !empty( $icon_bg_color ) && $icon_class != 'custom' ) {
    $icon_class .= ' font-color_' . esc_attr( $icon_bg_color );
}

$icon_styles = array();
$icon_style = '';

if( $icon_bg_color == 'custom' && !empty( $icon_bg_color_custom ) ) {
    $icon_styles[] = 'color:' . esc_attr( $icon_bg_color_custom );
}

if( !empty( $icon_styles ) ) {
    $icon_style = ' style="' . join( ';', $icon_styles ) . '"';
}

?>

<?php if( isset( $link[ 'target' ] ) ) {
    $link[ 'target' ] = '_self';
} ?>
<?php if( !empty( $link[ 'url' ] ) ): ?>
    <a href="<?php echo esc_url( $link[ 'url' ] ) ?>" target="<?php echo esc_attr( $link[ 'target' ] ); ?>" class="icon_box <?php echo esc_attr( $css_class ); ?> <?php echo esc_attr( $style ); ?> clearfix">
<?php else: ?>
    <div class="icon_box <?php echo esc_attr( $css_class ); ?> clearfix">
<?php endif; ?>
    <div class="icon_box_icon third_bg_before_color third_bg_after_color">
        <?php if( !empty( $icon ) ): ?>
            <i class="<?php echo esc_attr( $icon ); ?>"
                    style="font-size:<?php echo esc_attr( $icon_size ); ?>px; <?php if( isset( $icon_color_custom ) && $icon_color == 'custom' ) { ?>color: <?php echo esc_attr( $icon_color_custom ); ?>;<?php } ?>"
            ></i>
        <?php endif; ?>
    </div>
    <div class="icon_box_content">
        <?php if( !empty( $title ) ): ?>
            <h4 class="title"><?php echo wp_kses( $title, array( 'br' => array() ) ); ?></h4>
        <?php endif; ?>
        <?php if( !empty( $content ) ): ?>
            <div class="content"><?php echo wp_kses_post( $content ); ?></div>
        <?php endif; ?>
    </div>
<?php if( !empty( $link[ 'url' ] ) ): ?>
    </a>
<?php else: ?>
    </div>
<?php endif; ?>