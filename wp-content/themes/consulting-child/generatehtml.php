<?php /* Template Name: Generate Custom Message HTML */ ?>

<?php get_header(); ?>
<div class="container gh-sec">
    <div class="row">
        <div class="col-md-6">
            <p class="instruction">Fill the below form to generate custom message HTML code.</p>
        </div>
        <div class="col-md-6">
            <div class="text-right" id="copy-btn-div" style="visibility: hidden;">
                <button class="button button-primary copy-text gh-btn" type="button">Copy Code</button>
            </div>
        </div>
        <div class="col-md-6">
            <form id="gh-form">
                <input class="form-control gh-field" name="title" id="cm-title" placeholder="Title"/>
                <textarea class="form-control gh-field" name="msg" id="cm-msg" placeholder="Custom Message" rows="8"></textarea>
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
            var titleText = $('#cm-title').val();
            var msgText = $('#cm-msg').val();
            $.post(
                ajaxurl,
                {
                    action: 'generate_html_code',
                    title: titleText,
                    msg: msgText
                },
                function( response ){     
                    gbtn.text("Generate HTML");          
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
    });

</script>
<?php get_footer(); ?>