<?php
if(!is_admin()){
     if( !is_404() ): ?>
            </div> <!--.container-->
        </div> <!--#main-->
    </div> <!--.content_wrapper-->
    <?php
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.trust-provider.com/ttb_searcher/trustlogo?v_querytype=W&v_shortname=SECEV&v_search=https://premierconsumer.org/&x=6&y=5");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $sectigo = curl_exec($ch);
        curl_close($ch);
        $id_assure = "";
        if($sectigo != ""){
            $id_assure = strip_tags($sectigo);
            $id_assure = strstr($id_assure, "Identity Assured"); //gets all text from needle on
            $id_assure = strstr($id_assure, "GMT", true); //gets all text before needle
            if($id_assure != ''){
                $id_assure = str_replace('Identity Assured at ','',$id_assure);
            }
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.trust-provider.com/ttb_searcher/trustlogo?v_querytype=W&v_shortname=POSDV&v_search=https://premierconsumer.org/&x=6&y=5");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ssl_c = curl_exec($ch);
        curl_close($ch);
        $last_check = "";
        if($ssl_c != ""){
            $last_check = strip_tags($ssl_c);
            $last_check = strstr($last_check, "Certificate validity checked at:"); //gets all text from needle on
            $last_check = strstr($last_check, "GMT", true); //gets all text before needle
            if($last_check != ''){
                $last_check = str_replace('Certificate validity checked at: ','',$last_check);
            }
        }
        
        $consulting_layout = get_option( 'consulting_layout', 'layout_1' );
        $logo_tmp = '';
        $logo_tmp_src = '';
        if( !empty( $consulting_layout ) && $consulting_layout != 'layout_1' && $consulting_layout != 'layout_12' ) {
            $logo_tmp = $consulting_layout . '_';
        }
        $footer_style = consulting_theme_option( 'footer_style', 'style_1' );

        if( $footer_style === 'style_3' ):
            get_template_part( 'partials/footer/style_3' );
        else:
            $socials = consulting_get_socials( 'footer_socials' );
            $page_ID = consulting_page_id();
            $copyright_class = '';
            $copyright_border_top = get_post_meta( $page_ID, 'separator_footer_copyright_border_t', true );

            if( $copyright_border_top ) {
                $copyright_class .= ' border-top-hide';
            }

            $copyright = html_entity_decode( consulting_theme_option( 'footer_copyright' ) );
            $footer_class = '';
            $footer_class = ' ' . $footer_style;

            if( empty( $copyright ) || empty( $socials ) && $footer_style != 'style_1' ) {
                $footer_class .= ' no-copyright';
            }

            if( stm_check_layout( 'layout_14' ) and consulting_theme_option( 'enable_page_switcher', true ) and is_front_page() ) {
                get_template_part( 'partials/page-scroll' );
            }

            ?>
            <?php if( !consulting_theme_option( 'footer_show_hide', false ) ): ?>

            <footer id="footer" class="footer<?php echo esc_attr( $footer_class ); ?>">
                <?php if( stm_check_layout( 'layout_14' ) and consulting_theme_option( 'footer_enable_menu_top', true ) ): ?>
                    <div class="container">
                        <div class="top_nav">
                            <div class="stm_l14_footer_menu top_nav_wrapper">
                                <?php
                                wp_nav_menu( array(
                                        'theme_location' => 'consulting-primary_menu',
                                        'container' => false,
                                        'depth' => 1,
                                        'menu_class' => 'main_menu_nav'
                                    )
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php if( is_active_sidebar( 'consulting-footer-1' ) or is_active_sidebar( 'consulting-footer-2' ) or is_active_sidebar( 'consulting-footer-3' ) or is_active_sidebar( 'consulting-footer-4' ) ): ?>
                <?php if( consulting_theme_option( 'footer_sidebar_count', 4 ) != 'disable' ): ?>
                    <div class="widgets_row">
                        <div class="container">
                            <div class="footer_widgets">
                                <div class="row">
                                    <?php
                                    $footer_sidebar_count = intval( consulting_theme_option( 'footer_sidebar_count', 4 ) );
                                    $col = 12 / $footer_sidebar_count;
                                    for( $count = 1; $count <= $footer_sidebar_count; $count++ ): 
                                        if($count == 1){
                                            $customcol = 3;
                                        }elseif($count == 4){
                                            $customcol = 5;
                                        }else{
                                            $customcol = 2;
                                        }?>
                                        <div class="col-md-<?php echo $customcol;?>">
                                            <?php if( $count == 1 ): ?>
                                                <!-- <?php if( !consulting_theme_option( 'footer_logo_show_hide', false ) ): ?>
                                                    <?php if( $footer_logo = consulting_get_logo_url( 'footer_logo', get_template_directory_uri() . '/assets/images/tmp/' . $logo_tmp_src . 'logo_default.svg' ) ):
                                                        $logo_image_sizes = [180, 45];
                                                        get_template_part( 'partials/footer/logo-output', null, ['footer_logo' => $footer_logo, 'logo_image_sizes' => $logo_image_sizes] );
                                                    endif; ?>
                                                <?php endif; ?>
                                                <?php if( $footer_text = consulting_theme_option( 'footer_text', '' ) ): ?>
                                                    <div class="footer_text">
                                                        <p><?php printf( _x( '%s', 'Footer Text', 'consulting' ), $footer_text ); ?></p>
                                                    </div>
                                                <?php endif; ?> -->
                                                <?php if( !consulting_theme_option( 'footer_show_hide_socials', false ) ) : ?>
                                                    <?php if( $socials && $footer_style == 'style_2' ): ?>
                                                        <div class="socials">
                                                            <ul>
                                                                <?php foreach( $socials as $key => $val ): ?>
                                                                    <li>
                                                                        <a href="<?php echo esc_url( $val ); ?>"
                                                                           target="_blank"
                                                                           class="social-<?php echo esc_attr( $key ); ?>">
                                                                            <i class="fa fa-<?php echo esc_attr( $key ); ?>"></i>
                                                                        </a>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php dynamic_sidebar( 'consulting-footer-' . $count ); ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
			
				<div style="clear:both"></div>
				<br/><br/><br/>

                <?php if( !empty( $copyright ) || !empty( $socials ) && $footer_style == 'style_1' ) : ?>
                    <div class="copyright_row<?php echo esc_attr( $copyright_class ); ?><?php echo ( consulting_theme_option( 'footer_sidebar_count', 4 ) == 'disable' ) ? ' widgets_disabled' : ''; ?>">
                        <div class="container">
                            <div class="copyright_row_wr">
                                <?php if( !consulting_theme_option( 'footer_show_hide_socials', false ) ): ?>
                                    <?php if( !empty( $socials ) && $footer_style == 'style_1' ): ?>
                                        <div class="socials">
                                            <ul>
                                                <?php foreach( $socials as $key => $val ): ?>
                                                    <li>
                                                        <a href="<?php echo esc_url( $val ); ?>" target="_blank"
                                                           class="social-<?php echo esc_attr( $key ); ?>">
                                                            <i class="fa fa-<?php echo esc_attr( $key ); ?>"></i>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if( !empty( $copyright ) ): ?>
                                    <div class="copyright">
                                        <?php if( !consulting_theme_option( 'footer_current_year', false ) ): ?>
                                            <?php printf( _x( '%s', 'Copyright', 'consulting' ), $copyright ); ?>
                                        <?php else: ?>
                                            <?php printf( _x( '© %s %s', '© year copyright', 'consulting' ), date( 'Y' ), $copyright ); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(is_active_sidebar('privacy-policy')){ ?>
                  <div class="policy-menu container">
                  <?php dynamic_sidebar( 'privacy-policy'); ?>
                  </div>
                <?php } ?>
            </footer>
        <?php endif; ?>
    <?php endif; ?>
    </div> <!--#wrapper-->
<?php endif; ?>
<!--Start of Tawk.to Script-->
<!-- <script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6144bc5cd326717cb6820559/1ffq7vi52';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script> -->
<!--End of Tawk.to Script-->


<!-- Modal1 -->
<?php 
    $imgUrl = '';
	$imgUrl03 = 'https://premierconsumer.org/wp-content/uploads/2021/09/0317cdb0-65fa-4667-8404-4c9c76346f48.png';
	$imgUrlSara = 'https://premierconsumer.org/wp-content/uploads/2021/10/Sara-Registar-1634528023834-1.png';
	$imgUrlsectigo = 'https://premierconsumer.org/wp-content/uploads/2021/10/sectigo_logo_black.svg';
	$imgUrlpositive = 'https://premierconsumer.org/wp-content/uploads/2021/10/positive-logo.svg';
	$image03 = pippin_get_image_id($imgUrl03);
	$imageSara = pippin_get_image_id($imgUrlSara);
	$imagesectigo = pippin_get_image_id($imgUrlsectigo);
	$imagepositive = pippin_get_image_id($imgUrlpositive);

	$image03_alt = '';
	$imageSara_alt = '';
	$imagesectigo_alt = '';
	$imagepositive_alt = '';
	if ( $image03 ) {
		$image03_alt = get_post_meta( $image03, '_wp_attachment_image_alt', true );
	}
	if ( $imageSara ) {
		$imageSara_alt = get_post_meta( $imageSara, '_wp_attachment_image_alt', true );
	}
	if ( $imagesectigo ) {
		$imagesectigo_alt = get_post_meta( $imagesectigo, '_wp_attachment_image_alt', true );
	}
	if ( $imagepositive ) {
		$imagepositive_alt = get_post_meta( $imagepositive, '_wp_attachment_image_alt', true );
	}
	
?>
<div class="modal fade footer-model" id="footerModal1">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal">&times;</button>     
      <img alt="<?php echo $image03_alt; ?>" src="<?php echo $imgUrl03; ?>"> 
      <p>Premier Consumer is a proud member of the <span>Financial Counseling Association of America (FCAA) </span>whose mission is to promote quality and professional delivery of financial counseling services. We assist hundreds of thousands of consumers annually and as a member of the FCAA, we ensure that individuals receive the highest quality of assistance.</p>
    </div>
  </div>
</div>
</div>

<!-- Modal2 -->
<div class="modal fade footer-model" id="footerModal2">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal">&times;</button>     
      <img src="<?php echo $imgUrlSara; ?>" alt="<?php echo $imageSara_alt; ?>"> 
      <p>Premier Consumer Credit Counseling is certified by Sara Registrar that our Quality Management System has been assessed and found to be in Compliance with the requirements of <span>ISO</span> 9001: 2015 standards to provide Credit Counseling and Debt Management Services.</p>
    </div>
  </div>
</div>
</div>

<!-- Modal4 -->
<div class="modal fade footer-model sectigo-model" id="footerModal4">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal">&times;</button>     
      <img src="<?php echo $imgUrlsectigo; ?>" alt="<?php echo $imagesectigo_alt; ?>"> 
      <p>Verified : <?php echo $id_assure; ?> GMT</p>
      <p>IdAuthority Credentials: Premier Consumer Credit Counseling, Inc.</p>
      <p>© Copyright. Data provided by IdAuthority™<br></p>
    </div>
    <div class="modal-footer">
      <a href="https://secure.trust-provider.com/ttb_searcher/trustlogo?v_querytype=W&v_shortname=SECEV&v_search=https://premierconsumer.org/&x=6&y=5" target="_blank">Visit Site <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
    </div>
  </div>
</div>
</div>

<!-- Modal5 -->
<div class="modal fade footer-model sectigo-model ssl-logo" id="footerModal5">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal">&times;</button>     
      <img src="<?php echo $imgUrlpositive; ?>" alt="<?php echo $imagepositive_alt; ?>">
      <p>This website uses a PositiveSSL certificate to secure online transactions for customers.</p>
      <p>Certificate validity checked at: <?php echo $last_check; ?> GMT</p> 
      <p>© Copyright. Data provided by IdAuthority™<br></p>
    </div>
    <div class="modal-footer">
      <a href="https://secure.trust-provider.com/ttb_searcher/trustlogo?v_querytype=W&v_shortname=POSDV&v_search=https://premierconsumer.org/&x=6&y=5" target="_blank">Visit Site <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
    </div>
  </div>
</div>
</div>

<!-- Spanish Modal1 -->
<div class="modal fade footer-model" id="spanishModal1">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal">&times;</button>     
      <img src="https://premierconsumer.org/wp-content/uploads/2021/09/0317cdb0-65fa-4667-8404-4c9c76346f48.png"> 
      <p>Premier Consumer está orgulloso de ser miembro de la Asociación de Asesoramiento Financiero de América (FCAA), cuya misión es promover la calidad y la prestación profesional de servicios de asesoramiento financiero. Asistimos a cientos de miles de clientes anualmente y como miembro de la FCAA, nos aseguramos de que las personas reciban la calidad más alta de servicios.</p>
    </div>
  </div>
</div>
</div>

<!-- Spanish Modal2 -->
<div class="modal fade footer-model" id="spanishModal2">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal">&times;</button>     
      <img src="<?php echo $imgUrlSara; ?>" alt="<?php echo $imageSara_alt; ?>"> 
      <p>Premier Consumer Credit Counseling está certificado por Sara Registrar que nuestro Sistema de gestión de Calidad ha sido evaluado y se ha confirmado que cumple con los requisitos de las normas ISO 9001: 2015 para proveer Servicios de Asesoramiento crediticio y administración de deudas.</p>
    </div>
  </div>
</div>
</div>

<!-- Spanish Modal4 -->
<div class="modal fade footer-model sectigo-model" id="spanishModal4">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal">&times;</button>     
      <img src="<?php echo $imgUrlsectigo; ?>" alt="<?php echo $imagesectigo_alt; ?>"> 
      <p>Verified : <?php echo $id_assure; ?> GMT</p>
      <p>IdAuthority Credentials: Premier Consumer Credit Counseling, Inc.</p>
      <p>© Copyright. Data provided by IdAuthority™<br></p>
    </div>
    <div class="modal-footer">
      <a href="https://secure.trust-provider.com/ttb_searcher/trustlogo?v_querytype=W&v_shortname=SECEV&v_search=https://premierconsumer.org/&x=6&y=5" target="_blank">Visit Site <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
    </div>
  </div>
</div>
</div>

<!-- Spanish Modal5 -->
<div class="modal fade footer-model sectigo-model ssl-logo" id="spanishModal5">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal">&times;</button>     
      <img src="<?php echo $imgUrlpositive; ?>" alt="<?php echo $imagepositive_alt; ?>">
      <p>This website uses a PositiveSSL certificate to secure online transactions for customers.</p>
      <p>Certificate validity checked at: <?php echo $last_check; ?> GMT</p> 
      <p>© Copyright. Data provided by IdAuthority™<br></p>
    </div>
    <div class="modal-footer">
      <a href="https://secure.trust-provider.com/ttb_searcher/trustlogo?v_querytype=W&v_shortname=POSDV&v_search=https://premierconsumer.org/&x=6&y=5" target="_blank">Visit Site <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
    </div>
  </div>
</div>
</div>

<?php
} 