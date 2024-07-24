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

<a href="<?php echo esc_url( $link[ 'url' ] ) ?>" target="<?php echo esc_attr( $link[ 'target' ] ); ?>"
   class="icon_box <?php echo esc_attr( $css_class ); ?> clearfix">
    <div class="icon_box_inner">
        <?php if( $icon ) { ?>
            <div class="icon <?php echo esc_attr( $icon_class ); ?>" <?php echo sanitize_text_field( $icon_style ); ?>>
                <i style="font-size:<?php echo esc_attr( $icon_size ); ?>px; <?php if( isset( $icon_color_custom ) && $icon_color == 'custom' ) { ?>color: <?php echo esc_attr( $icon_color_custom ); ?>;<?php } ?> <?php if( $icon_line_height ) { ?>line-height: <?php echo esc_attr( $icon_line_height ); ?>px;<?php } ?> <?php if( $icon_border_color_custom ) { ?>border-color: <?php echo esc_attr( $icon_border_color_custom ); ?><?php } ?>"
                   class="<?php echo esc_attr( $icon ); ?>"></i></div>
            <div class="icon_bg"><i class="<?php echo esc_attr( $icon ); ?>"></i></div>
        <?php } ?>
        <?php if( $title ) { ?>
            <h4 <?php echo sanitize_text_field( $title_style ); ?> <?php echo sanitize_text_field( $title_class ); ?>><?php echo wp_kses( $title, array( 'br' => array() ) ); ?></h4>
        <?php } ?>
        <?php if( $content ) { ?>
            <div class="icon_text">
                <?php echo consulting_filtered_output( $content ); ?>
            </div>
        <?php } ?>
    </div>
</a>