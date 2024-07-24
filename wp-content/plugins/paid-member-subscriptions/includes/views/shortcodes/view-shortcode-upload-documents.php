<?php 
$site_lang = apply_filters( 'wpml_current_language', null );
?>
<style>
    i.fa.fa-upload {
        font-size: 14px;
        border: 1px solid;
        border-radius: 50%;
        padding: 4px;
    }
    .custom-uploader{
        color: #3441a4;
        cursor: pointer;
        font-size: 20px;
    }
    .note-field{
        font-size: 16.5px;
        color: #3441a4;
    }
    i.fa.fa-times {
        color: #fec14c;
        cursor: pointer;
    }
    #shareUp{
        background: #3441a4;
        border: none;
        padding: 10px 25px;
        color: white;
        margin: auto;
        display: block;
        margin-top: 20px !important;
    }
    form.file-uploader {
        width: 100%;
        margin: 50px 0px;
        text-align: center;
        border: 1px solid #febf57;
        padding: 40px 0px;
    }
</style>
<?php

    $user_id = get_current_user_id();
    if(!is_user_logged_in()){
        echo "<script>window.location.href='".home_url()."'</script>";
        die;
    }
    $user = get_userdata($user_id);
    $user_name = $user->display_name;
    if($user_name == ""){
        $user_name = $user->user_email;
    }
    $user_data = $user_name.'_'.$user_id;

?>
<form method="post" class="file-uploader" action="" enctype="multipart/form-data">
    <span class="custom-uploader"><?php if($site_lang == "es"){ echo "Cargar documentos"; }else{ echo "Upload Documents"; } ?> <i class="fa fa-upload" aria-hidden="true"></i></span><br/>
    <?php if($site_lang == "es"){ ?>
        <span class="note-field">Suba los recibos de sueldo de los últimos 30 días, los ingresos de los trabajadores autónomos, la carta de concesión del paro, etc., como prueba.</span>
    <?php }else{ ?>
        <span class="note-field">Upload Pay stubs for the last 30 days, Self-employment income, Unemployment award letter etc documents for proof.</span>
    <?php } ?>
    <input type="file" name="doc[]" style="display: none;" class="upDocs" id="1">
    <button type="button" id="shareUp" style="display: none;">Upload</button>
</form>
<script>
    var siteLang = "<?php echo $site_lang; ?>";
    jQuery('.custom-uploader').click(function(){
        jQuery('.upDocs').last().click();
    });
    jQuery(document).on('change','.upDocs',function(){
        jQuery('#shareUp').show();
        var preid = parseInt(jQuery(this).attr('id'))+1;
        var El = jQuery('.upDocs').last().clone();
        El.attr('value','');
        El.attr('id',preid);
        jQuery(this).after(El);
        for(var i = 0 ; i < this.files.length ; i++){
            var fileName = this.files[i].name;
            var fname = fileName.split(".")[0];
            jQuery('.note-field').after('<p class="file-data" id="info-'+jQuery(this).attr('id')+'">' + fileName + ' <i class="fa fa-times remove-file" data-id="'+jQuery(this).attr('id')+'" aria-hidden="true"></i></p>');
        }
    });
    jQuery(document).on('click','.remove-file',function(){
        var id = jQuery(this).data('id');
        jQuery('#'+id).remove();
        jQuery('#info-'+id).remove();
    });
    jQuery("#shareUp").click(function(){
        jQuery(this).prop('disabled','true'); 
        jQuery('#loader-register-div').show();
        jQuery('.upDocs').last().remove();
        var fd = new FormData();
        var docs = jQuery('input[name="doc[]"]');
        docs.map(function(index){
            fd.append("data_"+index, this.files[0]);
        });
        fd.append('name', '<?php echo $user_name; ?>'); 
        fd.append('type', "dashboard");
        fd.append('file_name', "<?php echo $user_data; ?>");
        fd.append('action', 'upload_to_sharefile_dashboard');
        var basePath = '<?php echo home_url();?>';
        if(basePath.includes("/?lang=es")){
            basePath = basePath.replace("/?lang=es","");
        }
        jQuery.ajax({
                type : "post",
                url : basePath+"/wp-admin/admin-ajax.php",
                data : fd,
                contentType: false,
                cache: false,
                processData:false,
                success: function(response) {
                    var res = JSON.parse(response);
                    jQuery('#loader-register-div').hide();
                    if(res == 'not_found'){
                        if(siteLang == "es"){
                            jQuery('#shareUp').after('<p style="color: #b70f0f; id="required">Algo salió mal.</p>');
                        }else{
                            jQuery('#shareUp').after('<p style="color: #b70f0f; id="required">Something went wrong.</p>');
                        }
                        window.location.reload();
                    }else if(res == 'success'){
                        if(siteLang == "es"){
                            jQuery('#shareUp').after('<span style="flex: 0 0 100%;color: #0a580a;">Documento cargado con éxito.</span>');
                        }else{
                            jQuery('#shareUp').after('<span style="flex: 0 0 100%;color: #0a580a;">Document uploaded successfully.</span>');
                        }
                        setTimeout(function(){ window.location.reload(); }, 3000);
                    }
                }
            });
    });
</script>