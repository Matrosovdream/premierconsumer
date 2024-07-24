<?php

wp_enqueue_style( 'anchors_text', get_template_directory_uri() . "/assets/css/layouts/global_styles/stm_anchors_text.css" );
wp_enqueue_script( 'anchors_text', get_template_directory_uri() . "/assets/js/stm_anchors_text.js" );

$links = '';
$articles = '';

foreach($sections as $section) {
    $id = $section[ 'tab_id' ];
    $title = $section[ 'title' ];
    $content = $section[ 'text' ];
    $links .= "<li><a href='#$id'>$title</a></li>";
    $articles .= "<article id='$id'><h3>$title</h3><div>$content</div></article>";
}

$nav_col = '<div class="col-md-3 nav-col privacy_policies' . ($sticky_nav ? ' sticky-nav' : '') . '"><ul class="stm_anchors_text__nav stm_anchors_text__nav--' . $links_position . '">' . $links . '</ul></div>';
$content_col = '<div class="col-md-9 content-col privacy_policies">' . $articles . '</div>';

?>

<style>
    #wrapper {
        overflow: initial !important;
    }
</style>
<div class="row privacy_policies_list">
    <?php
    if ($links_position == 'left') 			echo wp_kses_post($nav_col . $content_col);
    elseif ($links_position == 'right') 	echo wp_kses_post($content_col . $nav_col);
    ?>
</div>