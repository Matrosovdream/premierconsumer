<?php  
	$banner_img = $_POST['banner_img'];
    if($banner_img != ""){
		$banner_img = wp_get_attachment_image_url( $banner_img, 'large' );
	}
    $banner_text = $_POST['banner_txt'];
    $banner_link_text = $_POST['banner_link_txt'];
    $banner_link = $_POST['banner_link'];

	$n_top_title = $_POST['newsletter_top_title'];
	$other_lang_text = $_POST['newsletter_other_language_text'];
	$n_img = $_POST['newsletter_image'];
	if($n_img != ""){
		$n_img = wp_get_attachment_image_url( $n_img, 'large' );
	}
	$n_title = $_POST['newsletter_title'];
	$n_desc = $_POST['newsletter_description'];
	$n_read_text = $_POST['newsletter_read_more'];
	$n_link = $_POST['newsletter_link'];
	$n_ot_link = $_POST['other_language_newsletter_link'];

	$article_img = $_POST['article_img'];
	if($article_img != ""){
		$article_img = wp_get_attachment_image_url( $article_img, 'large' );
	}
	$article_top_title = $_POST['article_top_heading'];
	$article_title = $_POST['article_title'];
	$article_desc = $_POST['article_desc'];
	$article_read_more = $_POST['a_read_more'];
	$article_link = $_POST['a_link'];

	$tsec_heading = $_POST['top_sec_heading'];
	$tsec_img = $_POST['top_section_image'];
	if($tsec_img != ""){
		$tsec_img = wp_get_attachment_image_url( $tsec_img, 'large' );
	}
	$tsec_btn_txt = $_POST['btn_txt'];
	$tsec_btn_link = $_POST['top_btn_link'];

	$qp_img = $_POST['qpimg'];
	if($qp_img != ""){
		$qp_img = wp_get_attachment_image_url( $qp_img, 'large' );
	}
	$qp_title = $_POST['qptitle'];
	$qp_link_txt = $_POST['qplinktxt'];
	$qp_link = $_POST['qplink'];

	$ea_sec_title = $_POST['edtitle'];
	$ea_ar1_title = $_POST['ar_1_t'];
	$ea_ar1_link = $_POST['ar_1_link'];
	$ea_ar1_desc = $_POST['ar_1_desc'];
	$ea_ar2_title = $_POST['ar_2_t'];
	$ea_ar2_link = $_POST['ar_2_link'];
	$ea_ar2_desc = $_POST['ar_2_desc'];
	$ea_ar3_title = $_POST['ar_3_t'];
	$ea_ar3_link = $_POST['ar_3_link'];
	$ea_ar3_desc = $_POST['ar_3_desc'];	
	
	$news_title = $_POST['nw_title'];
	$news_img = $_POST['nw_img'];
	if($news_img != ""){
		$news_img = wp_get_attachment_image_url($news_img, 'large');
	}
	$news_desc = $_POST['nw_desc'];
	$news_btn_text = $_POST['nw_btn_txt'];
	$news_link = $_POST['nw_link'];

?>
<body style="margin: 0 auto;">   
    <section class="header-section" style="background: #fff;">
     	<div class="wrapper" style="max-width: 1000px;width: 100%;margin: 0 auto;">
     		<div class="top-section" style="width: 100%;float: left;">
     			<div class="logo" style="width: 50%;float: left;padding-top: 13px;">
     				<img src="https://www.premierconsumer.org/wp-content/uploads/2021/09/logo.jpg" style="width: 100%;max-width: 160px;">
     			</div>
     			<div class="contact" style="width: 30%;float: right;padding-top: 20px;text-align: right;display: flex;justify-content: end;">
     				<p style="float: left;margin: 0px;width: 50%;">1.800.296.4950</p>
     				<button class="log-in" style="background: transparent;border: none;float: left;width: 50%;">
     					<a href="https://www.premierconsumer.org/login/" style="color: #2F51AA;text-decoration: none;font-size: 14px;font-weight: 400;background: #FEC14C;padding: 11px 17px;border-radius: 12px;">Client log in</a>
     				</button>
     			</div>
     		</div>
     		<div class="navigation-bar" style="width: 100%;float: left;padding-bottom: 20px;display: flex;justify-content: center;">
     			<ul style="list-style: none;padding: 0;">
     				<li style="float: left;padding-left: 40px;"><a href="https://www.premierconsumer.org/?lang=es"  style="color: #212427;text-decoration: none;font-size: 14px;">Inicio</a></li>
     				<li style="float: left;padding-left: 40px;"><a href="https://www.premierconsumer.org/acerca-de-dev/?lang=es"  style="color: #212427;text-decoration: none;font-size: 14px;">Sobre Nosotros</a></li>
     				<li style="float: left;padding-left: 40px;"><a href="https://www.premierconsumer.org/como-le-podemos-ayudar/?lang=es"  style="color: #212427;text-decoration: none;font-size: 14px;">Como Le Podemos Ayudar</a></li>
     				<li style="float: left;padding-left: 40px;"><a href="https://www.premierconsumer.org/analisis-gratis/?lang=es"  style="color: #212427;text-decoration: none;font-size: 14px;">Análisis Gratis</a></li>
     				<li style="float: left;padding-left: 40px;"><a href="https://www.premierconsumer.org/centro-de-aprendizaje/?lang=es"  style="color: #212427;text-decoration: none;font-size: 14px;">Centro De Aprendizaje</a></li>
     				<li style="float: left;padding-left: 40px;"><a href="https://www.premierconsumer.org/comuniquese-con-el-desarrollador/?lang=es"  style="color: #212427;text-decoration: none;font-size: 14px;">Contacto</a></li>
     			</ul>
     		</div>
     	</div>
     </section>

     <div class="section-1" style="width: 100%;float: left;">
     	<div class="wrapper" style="max-width: 1000px;width: 100%;margin: 0 auto;">
     		<div class="bg" style="background: url(<?php echo $banner_img; ?>);background-repeat: no-repeat;background-size: cover;padding: 65px 0px 67px;">
	     		<div class="banner-section" style="width: 50%;padding-left: 28px;">
	     			<p style="font-size: 17px;line-height: 32px;color: #fff;font-weight: 500;"><?php echo $banner_text; ?></p>
	     			<button class="log-in2" style="margin-top: 39px;background: transparent;border: none;">
	     				<a href="<?php echo $banner_link; ?>" style="color: #2F51AA;text-decoration: none;font-size: 14px;font-weight: 400;background: #FEC14C;padding: 11px 17px;border-radius: 12px;"><?php echo $banner_link_text; ?></a>
	     			</button>
	     		</div>
     		</div>
     	</div>
     </div>
     <div class="section-2" style="width: 100%;float: left;padding-top: 15px;padding-block: 17px;">
     	<div class="wrapper" style="max-width: 1000px;width: 100%;margin: 0 auto;">
     		<div class="newsletter" style="width: 60%;float: left;padding-bottom: 20px;">
     			<div class="links" style="width: 100%;">
     				<p style="margin: 0;font-size: 15px;"><?php echo $n_top_title; ?></p>  
		            <a href="<?php echo $n_ot_link; ?>" style="font-size: 14px;"><?php echo $other_lang_text; ?></a>
     			</div>

     			<div class="newsletter1" style="background: #F5F5F7;width: 100%;border-radius: 10px;margin-top: 15px;float: left;">
     				<img src="<?php echo $n_img; ?>" style="height: 100%;width: 100%;object-fit: cover;border-radius: 10px 10px 0px 0px;">
     				<div class="content" style="padding: 10px 10px 26px 10px;">
     					<h5 style="color: #2F51AA;margin: 0;font-size: 18px;"><?php echo $n_title; ?></h5>
     					<p style="font-size: 15px;margin: 0;color: #6E6E73;"><?php echo $n_desc; ?> <a href="<?php echo $n_link; ?>"><?php echo $n_read_text; ?></a></p>
						<button class="log-in2" style="margin-top: 39px;background: transparent;border: none;">
							<a href="<?php echo $n_ot_link; ?>" style="color: #2F51AA;text-decoration: none;font-size: 14px;font-weight: 400;background: #FEC14C;padding: 11px 17px;border-radius: 12px;"><?php echo $other_lang_text; ?></a>
						</button>
     				</div>
     			</div>
     			<div class="newsletter2" style="background: #F5F5F7;width: 100%;border-radius: 10px;margin-top: 15px;float: left;">
     				<img src="<?php echo $article_img; ?>" style="height: 100%;width: 100%;object-fit: cover;border-radius: 10px 10px 0px 0px;">
     				<div class="content2" style="padding: 10px 0px 15px 10px;">
     					<h5 style="color: #2F51AA;margin: 0;font-size: 22px;font-weight: 400;"><span style="font-weight: 500;font-size: 14px;"><?php echo $article_top_title; ?></span></h5>
     					<h5 style="color: #2F51AA;margin: 0;font-size: 22px;font-weight: 400;"><?php echo $article_title; ?></h5>
     					<p style="font-size: 15px;color: #6E6E73;padding: 0px 10px;"><?php echo $article_desc; ?></p>
     					<button class="log-in2" style="margin-top: 39px;background: transparent;border: none;">
							<a href="<?php echo $article_link; ?>" style="color: #2F51AA;text-decoration: none;font-size: 14px;font-weight: 400;background: #FEC14C;padding: 11px 17px;border-radius: 12px;"><?php echo $article_read_more; ?></a>
						</button>
     				</div>
     			</div>
     		</div>

     		<div class="outer" style="float: right;width: 40%;text-align: center;">
     			<div class="div-outer">
     				<div class="ist-div" style="background: #D5DCEE;width: 78%;margin: auto;padding: 10px 10px 15px;border-radius: 17px 17px 0px 0px;box-shadow: 0px 0px 3px 0px #ccc;margin-bottom: 9px;">
     					<h5 style="color: #2F51AA;font-size: 18px;font-weight: 400;margin: 0;"><?php echo $tsec_heading; ?></h5>
     					<p style="color: #6E6E73;font-size: 15px;margin-bottom: 0px;"><img src="<?php echo $tsec_img; ?>" style="border-radius: 10px;width: 100%;"></p>
     					<?php if($tsec_btn_txt != ""){ ?>
     					<button class="regsterd-btn" style="background: #FEC14C;border: none;padding: 11px 17px;border-radius: 12px;">
     						<a href="<?php echo $tsec_btn_link; ?>" style="text-decoration: none;color: #2F51AA;font-weight: 400;"><?php echo $tsec_btn_txt; ?></a>
     					</button>
	     				<?php } ?>
     				</div>

     				<div class="ist-div div2" style="background: #D5DCEE;width: 78%;margin: auto;padding: 10px 10px 15px;box-shadow: 0px 0px 3px 0px #ccc;margin-bottom: 9px;margin-top: 0;border-radius: 0;">
                  		<h5 style="color: #2F51AA;font-size: 18px;font-weight: 400;margin: 0;"><?php echo $qp_title; ?></h5>
                  		<p style="color: #6E6E73;font-size: 15px;margin-bottom: 0px;"><img src="<?php echo $qp_img; ?>" style="border-radius: 10px;width: 100%;"></p>
                  		<?php if($qp_link_txt != ""){ ?>
                  		<button class="regsterd-btn" style="background: #FEC14C;border: none;padding: 11px 17px;border-radius: 12px;">
                  			<a href="<?php echo $qp_link; ?>" style="text-decoration: none;color: #2F51AA;font-weight: 400;"><?php echo $qp_link_txt; ?></a>
                  		</button>
	                  	<?php } ?>
                  	</div>
                  	<div class="ist-div div2" style="background: #D5DCEE;width: 78%;margin: auto;padding: 10px 10px 15px;box-shadow: 0px 0px 3px 0px #ccc;margin-bottom: 9px;margin-top: 0;border-radius: 0;">
                  		<h5 style="color: #2F51AA;font-size: 18px;font-weight: 400;margin: 0;"><?php echo $ea_sec_title; ?></h5>
                  		<p style="color: #6E6E73;font-size: 15px;margin-bottom: 0px;"><span><a href="<?php echo $ea_ar1_link; ?>" style="text-decoration: none;"><?php echo $ea_ar1_title; ?></a></span><?php echo $ea_ar1_desc; ?></p>
                  		<p style="color: #6E6E73;font-size: 15px;margin-bottom: 0px;"><span><a href="<?php echo $ea_ar2_link; ?>"><?php echo $ea_ar2_title; ?></a></span><?php echo $ea_ar2_desc; ?></p>
                  		<p style="color: #6E6E73;font-size: 15px;margin-bottom: 0px;"><span><a href="<?php echo $ea_ar3_link; ?>"><?php echo $ea_ar3_title; ?></a></span><?php echo $ea_ar3_desc; ?></p>
					</div>

                  	<div class="sec-div div2" style="background: #FEC14C;width: 78%;margin: auto;padding: 10px 10px 15px;border-radius: 0px;box-shadow: 0px 3px 9px 0px #ccc;margin: 0px auto 9px;border-radius: 0px 0px 10px 10px;">
                  		<p><img src="<?php echo $news_img; ?>" style="width: 100%;border-radius: 10px;"></p>
                  		<h5 style="color: #2F51AA;font-size: 18px;font-weight: 400;margin: 0;"><?php echo $news_title; ?></h5>
                  		<p style="color: #6E6E73;font-size: 15px;margin: 0;"><?php echo $news_desc; ?></p>
                  		<button class="regsterd-btn2" style="background: #2F51AA;border: none;padding: 11px 17px;border-radius: 12px;margin-top: 22px;">
                  			<a href="<?php echo $news_link; ?>" style="text-decoration: none;color:#FEC14C;font-weight: 400;"><?php echo $news_btn_text; ?></a>
                  		</button>
                  	</div>
                </div>
            </div>
        </div>

        <footer class="footer-section" style="float: left; width: 100%;">
	    	<div class="wrapper" style="max-width: 1000px;width: 100%;margin: 0 auto;">
	    		<div class="footer-div" style="background: #4D4D4D;padding: 20px 0px;float: left; width: 100%;">
	    			<div class="main-footer-box-div" style="width: 100%;float: left;border-bottom: 1px solid #ffffff59;">
        				<div class="divs" style="width: 33%;float: left;">
	    					<div class="logo-section name" style="width: 100%;float: left;padding-left: 50px;">
	               				<img src="https://www.premierconsumer.org/wp-content/uploads/2021/09/0317cdb0-65fa-4667-8404-4c9c76346f48.png" style="width: 52%;padding-left: 0px;">
	               				<img class="space" src="https://www.premierconsumer.org/wp-content/uploads/2022/04/logo-footer-white-es.png" style="padding-top: 30px;width: 52%;padding-left: 0px;">
	               			</div>
	               		</div>

                   		<div class="divs2" style="width: 33%;float: left;">
	               			<div class="logo-section sapce3" style="width: 100%;float: left;padding-left: 54px;">
	               				<img class="size" src="https://www.premierconsumer.org/wp-content/uploads/2021/10/Sara-Registar-1634528023834-1.png" style="width: 52%;padding-left: 0px;max-width: 24%;">
	               				<ul style="list-style: none;padding: 0;margin-top: 36px">
	               					<li style="padding-bottom: 9px;margin-left: 0;"><a href="https://www.premierconsumer.org/" style="text-decoration: none;color: #ffffffc9;" target="_blank">Home</a></li>
	               					<li style="padding-bottom: 9px;margin-left: 0;"><a href="https://www.premierconsumer.org/about-us/" style="text-decoration: none;color: #ffffffc9;" target="_blank">About Us</a></li>
	       							<li style="padding-bottom: 9px;margin-left: 0;"><a href="https://www.premierconsumer.org/how-we-can-help/" style="text-decoration: none;color: #ffffffc9;" target="_blank">How can we help you</a></li>
	   								<li style="padding-bottom: 9px;margin-left: 0;"><a href="https://www.premierconsumer.org/articles/" style="text-decoration: none;color: #ffffffc9;" target="_blank">Learning center</a></li>
	   								<li style="padding-bottom: 9px;margin-left: 0;"><a href="https://www.premierconsumer.org/free-credit/" style="text-decoration: none;color: #ffffffc9;" target="_blank">Free analysis</a></li>
									<li style="padding-bottom: 9px;margin-left: 0;"><a href="https://www.premierconsumer.org/contact-us/" style="text-decoration: none;color: #ffffffc9;" target="_blank">Contact</a></li>
	               				</ul>
	               			</div>
	               		</div>
                   		<div class="divs2" style="width: 33%;float: left;">
	               			<div class="logo-section" style="width: 100%;float: left;">
	               				<img src="https://www.premierconsumer.org/wp-content/uploads/2023/01/godaddy-ssl.png" style="width: 52%;padding-left: 0px;">
	               				<ul class="space2" style="list-style: none;padding: 0;padding-left: 5px;margin-top: 38px;padding-right: 20px !important;">
	               					<li style="padding-bottom: 9px;margin-left: 0;"><a href="tel:18002964950" style="text-decoration: none;color: #ffffffc9;">Toll Free 1.800.296.4950</a></li>
	               					<li style="padding-bottom: 9px;margin-left: 0;"><a href="#" style="text-decoration: none;color: #ffffffc9;">Fax 305.827.3778</a></li>
	               					<li style="padding-bottom: 9px;margin-left: 0;"><a href="#" style="text-decoration: none;color: #ffffffc9;">5201 Blue Lagoon Drive<br/>Suite 955<br/>Miami FL 33126</a></li>
	               				</ul>
	               			</div>
	               		</div>
                    </div>

                    <div class="copyright">
	                 	<div class="copyright-cont" style="width: 100%;float: left;">
	                 		<p style="text-align: center;color: #fff;"><a href="https://www.premierconsumer.org/security-practices/" style="text-decoration: none;color: #fff;" target="_blank">Security Practices</a>  |  <a href="https://www.premierconsumer.org/privacy-policy/" style="text-decoration: none;color: #fff;" target="_blank">Privacy Policy</a>  |  <a href="https://www.premierconsumer.org/terms-of-services/" style="text-decoration: none;color: #fff;" target="_blank">Terms & Services</a></p>
	                 	</div>
	                 	<p class="copyright-p" style="color: #fff;width: 100%;float: left;font-size: 10px;text-align: center;margin: 0 auto;padding: 0px 16px;width: 96%;">2003-2023 Premier Consumer® is a registered trademark. All rights reserved. Product name, logo, brands and other trademarks featured or referred to within Premier Consumer are the property of their respective trademark holders. This site is not compensated by any third-party advertisers.The Equifax, Transunion and Experian logos are registered trademarks owned by Equifax, Transunion and Experian in the United States and other countries.</p>
	                </div>

                </div>
            </div>
        </footer>
    </body>
