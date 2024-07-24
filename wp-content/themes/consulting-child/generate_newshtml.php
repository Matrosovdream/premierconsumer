<?php /* Template Name: Generate News Letter HTML */ ?>

<?php get_header(); ?>
<div class="container gh-sec">
    <div class="row">
        <div class="col-md-6">
            <p class="instruction">Fill the below form to generate newsletter HTML code.</p>
        </div>
        <div class="col-md-6">
            <div class="text-right" id="copy-btn-div" style="visibility: hidden;">
                <button class="button button-primary copy-text gh-btn" type="button">Copy Code</button>
            </div>
        </div>
        <div class="col-md-6">
            <form id="gh-form">
                <div class="form-group row">                    
                    <label for="banner-img" class="col-md-4 col-form-label gh-label">Banner Image:</label>
                    <div class="col-md-8">
                      <input type="file" accept="image/*" class="form-control-file" name="banner_img" id="banner-img" data-field="bimg" />
                      <input type="hidden" name="bimg" id="bimg">
                    </div>
                </div>
                <label class="gh-label">Banner Text</label>
                <input class="form-control gh-field" name="banner_title" id="banner-title" placeholder="Banner Text"/>
                <label class="gh-label">Banner Link Text</label>
                <input class="form-control gh-field" name="banner_link_txt" id="banner-link-text" placeholder="Banner Link Text" value="Visite nuestra página web www.premierconsumer.org" />
                <label class="gh-label">Banner Link</label>
                <input class="form-control gh-field" name="banner_link" id="banner-link" placeholder="Banner Link" value="https://www.premierconsumer.org/" />
                <hr class="gh-hr">

                <label class="gh-label">Newsletter Top Title</label>
                <input class="form-control gh-field" name="nw_top_title" id="nw-top-title" placeholder="Newsletter Top Title"/>
                <label class="gh-label">Newsletter Other Language Text</label>
                <input class="form-control gh-field" name="nw_ot_txt" id="nw-ot-txt" placeholder="Newsletter Other Language Text" value="View this Newsletter in English" />
                <div class="form-group row">                    
                    <label for="nw-img" class="col-md-4 col-form-label gh-label">Newsletter Image:</label>
                    <div class="col-md-8">
                      <input type="file" accept="image/*" class="form-control-file" name="nw_img" id="nw-img"  data-field="nimg" />
                      <input type="hidden" name="nimg" id="nimg">
                    </div>
                </div>
                <label class="gh-label">Newsletter Title</label>
                <input class="form-control gh-field" name="nw_title" id="nw-title" placeholder="Newsletter Title"/> 
                <label class="gh-label">Newsletter Description</label>              
                <textarea class="form-control gh-field" name="nw_desc" id="nw-desc" placeholder="Newsletter Description" rows="8"></textarea>
                <label class="gh-label">Newsletter Read More Text</label> 
                <input class="form-control gh-field" name="nw_read_more" id="nw-read-more" placeholder="Newsletter Read More Text"/>  
                <label class="gh-label">Newsletter Link</label> 
                <input class="form-control gh-field" name="nw_link" id="nw-link" placeholder="Newsletter Link"/> 
                <label class="gh-label">Other Language Newsletter Link</label> 
                <input class="form-control gh-field" name="nw_ot_link" id="nw-ot-link" placeholder="Other Language Newsletter Link"/> 
                <hr class="gh-hr">

                <div class="form-group row">                    
                    <label for="article-img" class="col-md-4 col-form-label gh-label">Article Image:</label>
                    <div class="col-md-8">
                      <input type="file" accept="image/*" class="form-control-file" name="article_img" id="article-img"  data-field="aimg" />
                      <input type="hidden" name="aimg" id="aimg">
                    </div>
                </div>
                <label class="gh-label">Article Top Title</label> 
                <input class="form-control gh-field" name="article_top_title" id="article-top-title" placeholder="Article Top Title" value="Artículos del mes" />
                <label class="gh-label">Article Title</label> 
                <input class="form-control gh-field" name="article_title" id="article-title" placeholder="Article Title"/>
                <label class="gh-label">Article Description</label> 
                <textarea class="form-control gh-field" name="article_desc" id="article-desc" placeholder="Article Description" rows="8"></textarea>
                <label class="gh-label">Article Read More Text</label> 
                <input class="form-control gh-field" name="article_rm_txt" id="article-rm-txt" placeholder="Article Read More Text" value="Visit Article" />
                <label class="gh-label">Article Link</label> 
                <input class="form-control gh-field" name="article_link" id="article-link" placeholder="Article Link"/>
                <hr class="gh-hr">

                <label class="gh-label">Top Section Heading</label> 
                <input class="form-control gh-field" name="top_sec_heading" id="top-sec-heading" placeholder="Top Section Heading" value="Seminarios de Univisión" />
                <div class="form-group row">                     
                    <label for="top-sec-img" class="col-md-4 col-form-label gh-label">Top Section Image:</label>
                    <div class="col-md-8">
                      <input type="file" accept="image/*" class="form-control-file" name="top_sec_img" id="top-sec-img"  data-field="tsimg" />
                      <input type="hidden" name="tsimg" id="tsimg">
                    </div>
                </div>
                <label class="gh-label">Button Text</label> 
                <input class="form-control gh-field" name="btn_txt" id="btn-txt" value="Regístrese ahora" placeholder="Button Text"/>
                <label class="gh-label">Button Link</label> 
                <input class="form-control gh-field" name="btn_link" id="btn-link" placeholder="Button Link"/>
                <hr class="gh-hr">

                <div class="form-group row">                    
                    <label for="qp-img" class="col-md-4 col-form-label gh-label">Quizz Poll Image</label>
                    <div class="col-md-8">
                      <input type="file" accept="image/*" class="form-control-file" name="qp_img" id="qp-img"  data-field="qpimg" />
                      <input type="hidden" name="qpimg" id="qpimg">
                    </div>
                </div>
                <label class="gh-label">Quizz Poll Title</label> 
                <input class="form-control gh-field" name="qp_title" id="qp-title" value="Quizzes y encuestas" placeholder="Quizz Poll Title"/>
                <label class="gh-label">Quizz Poll Link Text</label> 
                <input class="form-control gh-field" name="qp_link_txt" id="qp-link-txt" value="Visit Link" placeholder="Quizz Poll Link Text"/>
                <label class="gh-label">Quizz Poll Link</label>
                <input class="form-control gh-field" name="qp_link" id="qp-link" placeholder="Quizz Poll Link"/>
                <hr class="gh-hr">

                <label class="gh-label">Educational Article Title</label>
                <input class="form-control gh-field" name="ea_title" id="ea-title" placeholder="Educational Article Title" value="Artículos educativos" />
                <label class="gh-label">Article 1 Title</label>
                <input class="form-control gh-field" name="ar1_title" id="ar1-title" placeholder="Article 1 Title"/>
                <label class="gh-label">Article 1 Link</label>
                <input class="form-control gh-field" name="ar1_link" id="ar1-link" placeholder="Article 1 Link"/>  
                <label class="gh-label">Article 1 Description</label>
                <textarea class="form-control gh-field" name="ar1_desc" id="ar1-desc" placeholder="Article 1 Description" rows="8"></textarea>    
                <label class="gh-label">Article 2 Title</label>
                <input class="form-control gh-field" name="ar2_title" id="ar2-title" placeholder="Article 2 Title"/>
                <label class="gh-label">Article 2 Link</label>
                <input class="form-control gh-field" name="ar2_link" id="ar2-link" placeholder="Article 2 Link"/>
                <label class="gh-label">Article 2 Description</label>
                <textarea class="form-control gh-field" name="ar2_desc" id="ar2-desc" placeholder="Article 2 Description" rows="8"></textarea>  
                <label class="gh-label">Article 3 Title</label>
                <input class="form-control gh-field" name="ar3_title" id="ar3-title" placeholder="Article 3 Title"/>
                <label class="gh-label">Article 3 Link</label>
                <input class="form-control gh-field" name="ar3_link" id="ar3-link" placeholder="Article 3 Link"/>
                <label class="gh-label">Article 3 Description</label>
                <textarea class="form-control gh-field" name="ar1_desc" id="ar3-desc" placeholder="Article 3 Description" rows="8"></textarea>
                <hr class="gh-hr">

                <label class="gh-label">News Title</label>
                <input class="form-control gh-field" name="nws_title" id="nws-title" placeholder="News Title"/>
                <div class="form-group row">                    
                    <label for="nws-img" class="col-md-4 col-form-label gh-label">News Image:</label>
                    <div class="col-md-8">
                      <input type="file" accept="image/*" class="form-control-file" name="nws_img" id="nws-img"  data-field="nwimg" />
                      <input type="hidden" name="nwimg" id="nwimg">
                    </div>
                </div>
                <label class="gh-label">News Description</label>
                <textarea class="form-control gh-field" name="nws_desc" id="nws-desc" placeholder="News Description" rows="8"></textarea>
                <label class="gh-label">News Button Text</label>
                <input class="form-control gh-field" name="nws_btn_txt" id="nws-btn-txt" value="Visit Link" placeholder="News Button Text"/>
                <label class="gh-label">News Link</label>
                <input class="form-control gh-field" name="nws_link" id="nws-link" placeholder="News Link"/>
                
                <button id="generate-code" type="button" class="btn btn-primary gh-btn">Generate HTML</button>
            </form>
        </div>
        <div class="col-md-6 preview-sec">
            <div id="html-code-output" style="display: none;">
                <pre></pre>                
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(function($){
        $("#generate-code").click(function(){
            $("#err-output").remove();
            $("#copy-btn-div").css('visibility', 'hidden');
            $("#html-code-output").hide();            
            var gbtn = $(this);
            gbtn.text("Generating HTML...");
            var banner_img = $("#bimg").val();
            var banner_txt = $('#banner-title').val();
            var banner_link_txt = $('#banner-link-text').val();
            var banner_link = $('#banner-link').val();
            var newsletter_top_title = $('#nw-top-title').val();
            var newsletter_other_language_text = $('#nw-ot-txt').val();
            var newsletter_image = $("#nimg").val();
            var newsletter_title = $('#nw-title').val();
            var newsletter_description = $('#nw-desc').val();
            var newsletter_read_more = $('#nw-read-more').val();
            var newsletter_link = $('#nw-link').val();
            var other_language_newsletter_link = $('#nw-ot-link').val();
            var article_img = $("#aimg").val();
            var article_top_heading = $('#article-top-title').val();
            var article_title = $('#article-title').val();
            var article_desc = $('#article-desc').val();
            var a_read_more = $('#article-rm-txt').val();
            var a_link = $('#article-link').val();
            var top_sec_heading = $('#top-sec-heading').val();
            var top_section_image = $("#tsimg").val();
            var btn_txt = $('#btn-txt').val();
            var top_btn_link = $('#btn-link').val();
            var qpimg = $("#qpimg").val();
            var qptitle = $('#qp-title').val();
            var qplinktxt = $('#qp-link-txt').val();
            var qplink = $('#qp-link').val();
            var edtitle = $('#ea-title').val();
            var ar_1_t = $('#ar1-title').val();
            var ar_2_t = $('#ar2-title').val();
            var ar_3_t = $('#ar3-title').val();
            var ar_1_link = $('#ar1-link').val();
            var ar_2_link = $('#ar2-link').val();
            var ar_3_link = $('#ar3-link').val();
            var ar_1_desc = $('#ar1-desc').val();
            var ar_2_desc = $('#ar2-desc').val();
            var ar_3_desc = $('#ar3-desc').val();
            var nw_title = $('#nws-title').val();
            var nw_img = $("#nwimg").val();
            var nw_desc = $('#nws-desc').val();
            var nw_btn_txt = $('#nws-btn-txt').val();
            var nw_link = $('#nws-link').val();

            $.post(
                ajaxurl,
                {
                    action: 'generate_html',
                    banner_img: banner_img,
                    banner_txt: banner_txt,
                    banner_link_txt: banner_link_txt,
                    banner_link: banner_link,
                    newsletter_top_title: newsletter_top_title,
                    newsletter_other_language_text: newsletter_other_language_text,
                    newsletter_image: newsletter_image,
                    newsletter_title: newsletter_title,
                    newsletter_description: newsletter_description,
                    newsletter_read_more: newsletter_read_more,
                    newsletter_link: newsletter_link,
                    other_language_newsletter_link: other_language_newsletter_link,
                    article_img: article_img,
                    article_top_heading: article_top_heading,
                    article_title: article_title,
                    article_desc: article_desc,
                    a_read_more: a_read_more,
                    a_link: a_link,
                    top_sec_heading: top_sec_heading,
                    top_section_image: top_section_image,
                    btn_txt: btn_txt,
                    top_btn_link: top_btn_link,
                    qpimg: qpimg,
                    qptitle: qptitle,
                    qplinktxt: qplinktxt,
                    qplink: qplink,
                    edtitle: edtitle,
                    ar_1_t: ar_1_t,
                    ar_2_t: ar_2_t,
                    ar_3_t: ar_3_t,
                    ar_1_link: ar_1_link,
                    ar_2_link: ar_2_link,
                    ar_3_link: ar_3_link,
                    ar_1_desc: ar_1_desc,
                    ar_2_desc: ar_2_desc,
                    ar_3_desc: ar_3_desc,
                    nw_title: nw_title,
                    nw_img: nw_img,
                    nw_desc: nw_desc,
                    nw_btn_txt: nw_btn_txt,
                    nw_link: nw_link,
                },
                function( response ){     
                    gbtn.text("Generate HTML");     
                    $("html, body").animate({ scrollTop: 0 }, "slow");     
                    if( response == "error"){
                        $(".preview-sec").prepend('<p id="err-output" class="text-center">Something went wrong, try again</p>');
                    }else{
                        $("#copy-btn-div").css('visibility', 'visible');
                        $("#html-code-output").show();
                        $("#html-code-output pre").text(response);
                    }
                }
            );
        });
        $(".copy-text").click(function(){
            var strData = $("#html-code-output pre").text();
            function listener(e) {
                e.clipboardData.setData("text/html", strData);
                e.clipboardData.setData("text/plain", strData);
                e.preventDefault();
            }
            document.addEventListener("copy", listener);
            document.execCommand("copy");
            document.removeEventListener("copy", listener);
            $(this).text("Copied!");
            setTimeout(function () {
                $(".copy-text").text('Copy Code');
             }, 3000);
        });
        $("#banner-img, #nw-img, #article-img, #top-sec-img, #qp-img, #nws-img").change(function () {
            $(this).next('img').remove();
            filePreview(this);
            var hiddenField = $(this).data('field');

            var fd = new FormData();
            fd.append( "img", $(this)[0].files[0]);
            fd.append( "action", 'upload_file');  
            if($("input[name='"+hiddenField+"']").val() != ""){
                fd.append( "eximg", $("input[name='"+hiddenField+"']").val());
            }

            jQuery.ajax({
                url: ajaxurl,
                dataType: 'json',
                type: 'POST',                   
                data:  fd,
                contentType: false,
                processData: false,                
                success : function( response ){
                    if(response != "error"){
                        $("input[name='"+hiddenField+"']").val(response);
                    }
                },
            });
        });
        function filePreview(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(input).after('<img src="'+e.target.result+'" width="250" height="250" style="margin-top:5px;margin-bottom:5px;"/>');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    });

</script>
<?php get_footer(); ?>