let params = (new URL(document.location)).searchParams;
let lang = params.get("lang");
if(lang !== null && lang !== undefined){
    if(lang == 'es'){
        jQuery(document).ready(function(){
            // if(jQuery('.stm-logo img')){
            //     jQuery('.stm-logo img').attr('src','https://www.premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
            // }
            if(jQuery('#text-4 img')){
                jQuery('#text-4 img').first().attr('src','https://www.premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
            }
            // if(jQuery(".stm_mobile__logo img")){
            //     jQuery('.stm_mobile__logo img').attr('src','https://www.premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
            // }
        });
        jQuery('img[src="https://premierconsumer.org/wp-content/uploads/2021/09/logo.jpg"]').attr('src','https://www.premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
        jQuery('img[src="https://premierconsumer.org/wp-content/uploads/2021/09/logo-1.jpg"]').attr('src','https://www.premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
        jQuery('img[src="https://premierconsumer.org/wp-content/uploads/2021/10/logo-footer-white.png"]').attr('src','https://premierconsumer.org/wp-content/uploads/2022/04/logo-footer-white-es.png');
    }
}

// if(jQuery("#custom-shortcode").length > 0){
//     console.log(jQuery("#custom-shortcode"));
//     jQuery.ajax({
//         type: 'POST',
//         url : custom_object.ajax_url,
//         cache: false,
//         data : { 'action': 'my_action', 'refresh_cart': 'yes' },
//         complete : function() {  },
//         success: function(data) {
//             // $("#loading-img").hide();
//             console.log(data);
//             jQuery("#custom-shortcode").html(data);
//         }
//     });
// }

jQuery(document).ready(function(){
    if(!jQuery("body").hasClass("elementor-page-20336")){
        jQuery('<div class="mobile-number"><a href="tel:18002964950"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;1.800.296.4950</a></div>').insertAfter(jQuery(".stm-header__row_color_center .stm-header__cell.stm-header__cell_right"));
        jQuery('<div class="mobile-number"><a href="tel:18002964950"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;1.800.296.4950</a></div>').insertAfter(jQuery(".stm_mobile__switcher.stm_flex_last.js_trigger__click"));
    }
    if(jQuery("body").hasClass("elementor-page-20336")){
        jQuery('.stm-header__cell.stm-header__cell_right').append('<span class="phn_desktop"><a href="tel:18668279080"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;1.866.827.9080</a></span>');
    }
    jQuery('input,textarea').focus(function(){
        var ph = jQuery(this).attr('placeholder');
        if(ph !== undefined && ph !== false){
            jQuery(this).attr('data-placeholder',ph);
            jQuery(this).removeAttr('placeholder');
        }
        if(jQuery(this).val() == ''){
            var placeholder_val = jQuery(this).data('placeholder');
            jQuery(this).attr('placeholder', placeholder_val);
        }
        
    });
    if(jQuery("input[name='form_fields[phone]']").length > 0){
        jQuery("input[name='form_fields[phone]']").attr('maxlength','14');
        jQuery("input[name='form_fields[phone]']").attr('inputmode','numeric');
        // jQuery("input[name='form_fields[phone]']").attr('pattern','^\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}$');
    }
    // jQuery("input[name='form_fields[phone]']").keyup(function() {
    jQuery(document).on("keyup", "input[name='form_fields[phone]']", function() {
        var obj = jQuery(this);
      	var objVal = obj.val();
        if(objVal == ""){
            jQuery('#phone_err').remove();
        }
      	var fnum = objVal.replace(/\D/g, ''), 
      	    fchar = {0:'(',3:') ',6:'-'};
      	obj.val('');
      	for (var i = 0; i < fnum.length; i++) {
          var res = obj.val() + (fchar[i]||'') + fnum[i];
          obj.val(res);
        }
        /* check area code */
        if(obj.val().length >= 4){
            var a_code = obj.val();
            a_code = a_code.replace("(",'');
            let result = a_code.substring(0, 3);
            var data = {
                        action: 'check_area_code',
                        areacode: result
                    };
            jQuery.ajax({
                type : "post",
                url : custom_object.ajax_url,
                data : data,
                success: function(response) {
                    if(response == "not_exist"){
                        var emsg = "Invalid area code!";
                        if(lang !== null && lang !== undefined){
                            if(lang == 'es'){
                                emsg = "¡Código de área inválido!";
                            }
                        }
                        jQuery('button[type="submit"]').attr('disabled','true');
                        jQuery('#phone_err').remove();
                        jQuery('<span class="text-danger invalid-code" id="phone_err">'+emsg+'</span>').insertAfter(obj);
                    }else if(response == "exist"){
                        if(obj.val().length == 14){
                            jQuery('#phone_err').remove();
                            jQuery('button[type="submit"]').removeAttr('disabled');
                        }
                    }
                }
            });
        }
        /* e message */
        var msg = "Please enter 10 digit phone number.";
        if(lang !== null && lang !== undefined){
            if(lang == 'es'){
                msg = "Por favor, introduzca un número de teléfono de 10 dígitos.";
            }
        }

        if(obj.val().length != 14){
        	jQuery('button[type="submit"]').attr('disabled','true');
        	if(jQuery('#phone_err').length == 0){
        	    jQuery('<span class="text-danger" id="phone_err">'+msg+'</span>').insertAfter(obj);
        	}
        }else{
            if(jQuery(".invalid-code").length == 0){
            	jQuery('button[type="submit"]').removeAttr('disabled');
            	jQuery('#phone_err').remove();
            }
        }
    });
    jQuery(window).on("resize", function (e) {
        checkScreenSize();
    });

    checkScreenSize();
    
    function checkScreenSize(){
        let params = (new URL(document.location)).searchParams;
        let lang = params.get("lang");
        if(lang !== null && lang !== undefined){
            if(lang == 'es'){
                jQuery('img[src="https://premierconsumer.org/wp-content/uploads/2021/09/logo.jpg"]').attr('src','https://www.premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
                jQuery('img[src="https://premierconsumer.org/wp-content/uploads/2021/10/logo-footer-white.png"]').attr('src','https://premierconsumer.org/wp-content/uploads/2022/04/logo-footer-white-es.png');
            }
        }
        
        var newWindowWidth = jQuery(window).width();
        if (newWindowWidth < 481) {
            // for mobile
            jQuery('#home-banner-sec').click(function(){
               window.location.href = '/debt-management';
            });
            jQuery('#home-banner-sp').click(function(){
               window.location.href = '/consolidacion-de-deudas/?lang=es';
            });
            // jQuery('.sub-menu').show();
            
            // var hElem = jQuery('.stm-header__row_color.stm-header__row_color_top.elements_in_row_2').first().clone();
            // jQuery('.stm-header__row_color.stm-header__row_color_top.elements_in_row_2').first().remove();
            // jQuery(hElem).insertAfter('.stm-header__cell.stm-header__cell_left');
            
        } 
    }

    jQuery('[id="coupon_submit"]').click(function(){
    // jQuery( "form.elementor-form" ).on( "submit", function( event ) {
        var formVal = jQuery(this);
        var formEl = formVal.parent().parent().parent();
        // var couponCode = formVal.parent().parent().prev().find('#form-field-coupon_code').val();
        var couponCode = formVal.closest('form').find('[id="form-field-coupon_code"]').val();
        var sub_id = formVal.closest('form').find('[id="form-field-sub_package"]').val();
        var aTag = formVal.parent().parent().parent().parent().parent().prev().find('a');
        // var aTag = jQuery("#btn_"+sub_id+" a");
        var oldHref = aTag.attr('href');
        debugger
        var inLangStr = oldHref.includes("?lang="); 
        if(inLangStr){
            if(oldHref.includes("&c=")){
                var newString = oldHref.split('&c=', 1)[0];
                var newHref = newString+'&c='+couponCode+'_'+sub_id;
            }else{
                var newHref = oldHref+'&c='+couponCode+'_'+sub_id;
            }
        }else if(oldHref.includes("?c=")){
            var newString = oldHref.split('?c=', 1)[0];
            var newHref = newString+'?c='+couponCode+'_'+sub_id;
        }else if(oldHref.includes("&c=")){
            var newString = oldHref.split('&c=', 1)[0];
            var newHref = newString+'&c='+couponCode+'_'+sub_id;
        }else if(oldHref.includes("?")){
            var newHref = newString+'&c='+couponCode+'_'+sub_id;
        }else{
            var newHref = oldHref+'?c='+couponCode+'_'+sub_id;
        }    
        // debugger
        aTag.attr('href',newHref);
        var handle = setInterval(function() { checkExistance(aTag,oldHref); },20);
        function checkExistance(tagEl, oldLink){
            if(lang !== null && lang !== undefined){
                if(lang == 'es'){
                    if(oldLink.includes("&c=")){
                        var charP = oldLink.indexOf('&c=');
                        oldLink = oldLink.substring(0,charP);
                    }
                    if(oldLink.includes("?c=")){
                        var charP = oldLink.indexOf('?c=');
                        oldLink = oldLink.substring(0,charP);
                    }
                    if(jQuery('[id="form-field-coupon_code"]').next('span').length > 0){
                        tagEl.attr('href',oldLink);
                        jQuery('[id="form-field-coupon_code"]').next().remove();
                        clearInterval(handle);
                    }
                }
            }else{
                if(oldLink.includes("&c=")){
                    var charP = oldLink.indexOf('&c=');
                    oldLink = oldLink.substring(0,charP);
                }
                if(oldLink.includes("?c=")){
                    var charP = oldLink.indexOf('?c=');
                    oldLink = oldLink.substring(0,charP);
                }
                if(jQuery('[id="form-field-coupon_code"]').next('span').length > 0){
                    tagEl.attr('href',oldLink);
                    jQuery('[id="form-field-coupon_code"]').next().remove();
                    clearInterval(handle);
                }
            }
        }
    });
    if(jQuery(".elementor-page-20336 .seals-first p").length > 0){
        if(jQuery(".elementor-page-20336 .seals-first p #godaddy-security-s").next().length == 0){
            jQuery('<div id="godaddy-security-badge" class="godaddy-security-relative godaddy-security-light custom-gd-badge"></div>').insertAfter(".elementor-page-20336 .seals-first p #godaddy-security-s");
        }
        jQuery(".custom-gd-badge").click(function(){
            jQuery("#godaddy-security-badge").first().click();
        });
    }
    if(jQuery(".elementor-page-20336").length > 0){
        jQuery('<span class="phn_mobile"><a href="tel:18668279080"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;1.866.827.9080</a></span>').insertAfter(".stm_mobile__logo");
    }

    // pms custom js 
    if(jQuery("#pms_register-form").length > 0){
        jQuery(document).on("change","#pms_user_email",function(){       
            jQuery("#pms_user_login").val(jQuery(this).val());
        });
        jQuery(document).ready(function(){
            var siteLang = lang;
            if(siteLang == "es"){
                var subText = jQuery(".selected-subscription").text();
                /* change plan names */
                if(subText == "Creditor Education Waiver - Free"){
                    subText = "Asesoría de Crédito para la Bancarrota - Gratis";
                }else if(subText == "Debtor Education Waiver - Free"){
                    subText = "Educación al Deudor para la Bancarrota- Gratis";
                }
                let _subPrices = jQuery('.pms-subscription-plan-price-value')
                _subPrices.each(function( index ) {
                  // console.log( index + ": " + $( this ).text() );
                    var _subPrice = jQuery( this ).text()
                    var floatValue = parseFloat(_subPrice.replace(',', '.'));
                    if (_subPrice) {
                        jQuery( this ).text(floatValue)
                        // debugger
                    }
                });
                
                /* end code */
                var nsubText = subText.replace(",", ".");
                var tC = nsubText.replace("1 Month", "Mensual");
                jQuery(".selected-subscription").text(tC);
                jQuery(".pms-form-submit").click(function(e){
                    jQuery(".pms-form.pc-register-form").submit(false);
                    e.preventDefault();
                    if(jQuery("#pms_first_name").val() == ""){
                        jQuery('<div class="pms_field-errors-wrapper"><p>Por favor, introduzca un nombre.</p></div>').insertAfter(jQuery("#pms_first_name"));
                    }else if(jQuery("#pms_last_name").val() == ""){
                        jQuery('<div class="pms_field-errors-wrapper"><p>Por favor ingrese un Apellido.</p></div>').insertAfter(jQuery("#pms_last_name"));
                    }else if(jQuery("#pms_mobile").val() == ""){
                        jQuery('<div class="pms_field-errors-wrapper"><p>Por favor, introduzca un número de teléfono móvil.</p></div>').insertAfter(jQuery("#pms_mobile"));
                    }else if(jQuery("#password").val() == ""){
                        jQuery('<div class="pms_field-errors-wrapper"><p>Por favor, introduzca una contraseña.</p></div>').insertAfter(jQuery("#password"));
                    }else if(jQuery("#pms_pass2").val() == ""){
                        jQuery('<div class="pms_field-errors-wrapper"><p>Por favor, repita la contraseña.</p></div>').insertAfter(jQuery("#pms_pass2"));
                    }else if(!jQuery("#pms_user_consent").is(":checked")){
                        if(jQuery("#error_check").length == 0){
                            jQuery('<span style="color:red;" id="error_check">Por favor, marque esta casilla para registrarse.</span>').insertAfter(jQuery("label[for='pms_custom_ck']"));
                        }                    
                    }else{
                        jQuery("input[name='pms_register']").addClass("pms-submit-disabled");
                        if(!jQuery("#pms-credit-card-information").is(":visible")){
                        //     console.log("in if ");
                            jQuery(".pms-form.pc-register-form")[0].submit(); // form submit                            
                        }
                    }
                });
                jQuery("input").keyup(function(){
                    if(jQuery(this).next().hasClass("pms_field-errors-wrapper")){
                        jQuery(this).next().remove();
                    }
                });
                var plans = jQuery(".pms-subscription-plan-name");
                plans.each(function(index, value) {
                    var str1 = value.innerText;
                    var sText = str1;
                    if(str1.indexOf("Single Class") != -1){
                        sText = sText.replace("Single Class", "Clase Individual");
                    }
                    if(str1.indexOf("Joint Class") != -1){
                        sText = sText.replace("Joint Class", "Clase Conjunta");
                    }
                    jQuery(".pms-subscription-plan-name").eq(index).text(sText);
                });
            }else{
                var subText = jQuery(".selected-subscription").text();
                var tC = subText.replace("1 Month", "Monthly");
                jQuery(".selected-subscription").text(tC);
            }

            var subType = '';
            var subscriptions = jQuery(".pms-subscription-plan input");
            var subscription = [];
            jQuery.each(subscriptions, function( key, elmn ) {
                subscription.push(elmn.value);
            });
            if(subscription.length > 0){
                if(jQuery.inArray('16637', subscription) !== -1){
                    subType = 'creditor';
                }
            }
            if(jQuery('#pms_yearly_income').length > 0) {
                jQuery('#pms_yearly_income').keyup(function(){
                   var person = jQuery('#pms_person').val();
                   if(person == ''){
                       if(jQuery('#err_prsn').length == 0){
                           jQuery('#pms_person').after('<span class="text-danger" id="err_prsn">Please fill out this field.</span>');
                       }
                   }
                });
                jQuery('#pms_person').keyup(function(){
                   jQuery('#err_prsn') .remove();
                });
                jQuery('#pms_yearly_income').change(function(){
                   var person = jQuery('#pms_person').val();
                   var income = jQuery(this).val();
                   if(person == ''){
                        if(jQuery('#err_prsn').length == 0){
                           jQuery('#pms_person').after('<span class="text-danger" id="err_prsn">Please fill out this field.</span>');
                        }
                   }else{
                        var basePath = custom_object.site_url;
                        if(basePath.includes("/?lang=es")){
                            basePath = basePath.replace("/?lang=es","");
                        }
                       jQuery('.pms-upload-field').show();
                       // ajax call
                        jQuery.ajax({
                            type : "post",
                            url : basePath+"/wp-admin/admin-ajax.php",
                            data : {"action":"check_pguildeline",person:person,income:income,type:subType},
                            success: function(response) {
                                var res = JSON.parse(response);
                                if(res == 'redirect'){
                                    // show alert
                                    jQuery('.pms-form-submit').removeAttr('title');
                                    if(siteLang == "es"){
                                        jQuery('#pms_yearly_income').after('<span class="text-danger">Sus ingresos no están por debajo del umbral de pobreza actual.</span>');
                                    }else{
                                        jQuery('#pms_yearly_income').after('<span class="text-danger">Your income does not fall under the currently poverty line.</span>');
                                    }
                                    if(subType == 'creditor'){
                                        if(siteLang == "es"){
                                            setTimeout(function(){ window.location.href = '/exencion-de-responsabilidad-del-acreedor/?lang=es'; }, 2000);
                                        }else{
                                            setTimeout(function(){ window.location.href = '/creditor-education-waiver'; }, 2000);
                                        }
                                    }else{
                                        if(siteLang == "es"){
                                            setTimeout(function(){ window.location.href = '/registrese-deudor-educacion-mas-ondulado/?lang=es'; }, 2000);                                            
                                        }else{
                                            setTimeout(function(){ window.location.href = '/register-debtor-education-waiver'; }, 2000);
                                        }
                                    }
                                }else if(res == 'pg'){
                                    jQuery('.pms-form-submit').prop('disabled','disabled');
                                    if(siteLang == "es"){
                                        jQuery(".pms-form-submit").attr("title","Por favor, cargue los documentos.");                                        
                                    }else{
                                        jQuery('.pms-form-submit').attr('title','Please upload documents.');
                                    }
                                    // upload doc
                                    // jQuery('.pms-upload-field').show();
                                }
                            }
                        });
                    }
                });

                jQuery('#pms_user_email').focus(function(){
                    jQuery('#upload_err').remove();
                    if(jQuery('#submission').length > 0) {
                       if(jQuery('#submission').val() != 'success'){
                           jQuery('#shareUp').after('<p style="color: #bb3c3c;" id="upload_err">Please upload documents*</p>');
                       }else{
                           jQuery('#upload_err').remove();
                       }
                    }else{
                        jQuery('#shareUp').after('<p style="color: #bb3c3c;" id="upload_err">Please upload documents*</p>');
                    }
                });
            }
            // phone number validation 
            jQuery("#pms_mobile").keyup(function() {
                var obj = jQuery(this);
                var objVal = obj.val();
                var fnum = objVal.replace(/\D/g, ''), 
                    fchar = {0:'(',3:') ',6:'-'};
                obj.val('');
                for (var i = 0; i < fnum.length; i++) {
                  var res = obj.val() + (fchar[i]||'') + fnum[i];
                  obj.val(res);
                }
                /* check area code */
                if(obj.val().length >= 4){
                    var a_code = obj.val();
                    a_code = a_code.replace("(",'');
                    let result = a_code.substring(0, 3);
                    var data = {
                                action: 'check_area_code',
                                areacode: result
                            };
                    jQuery.ajax({
                        type : "post",
                        url : custom_object.ajax_url,
                        data : data,
                        success: function(response) {
                            if(response == "not_exist"){
                                var emsg = "Invalid area code!";
                                if(siteLang !== null && siteLang !== undefined){
                                    if(siteLang == 'es'){
                                        emsg = "¡Código de área inválido!";
                                    }
                                }
                                jQuery('button[type="submit"]').attr('disabled','true');
                                jQuery('#phone_err').remove();
                                jQuery('<span class="text-danger invalid-code" id="phone_err">'+emsg+'</span>').insertAfter(obj);
                            }else if(response == "exist"){
                                if(obj.val().length == 14){
                                    jQuery('#phone_err').remove();
                                    jQuery('button[type="submit"]').removeAttr('disabled');
                                }
                            }
                        }
                    });
                }
            });
            // sp mobile number
            jQuery("#pms_sp_mobile").keyup(function() {
                var obj = jQuery(this);
                var objVal = obj.val();
                var fnum = objVal.replace(/\D/g, ''), 
                    fchar = {0:'(',3:') ',6:'-'};
                obj.val('');
                for (var i = 0; i < fnum.length; i++) {
                  var res = obj.val() + (fchar[i]||'') + fnum[i];
                  obj.val(res);
                }
                /* check area code */
                if(obj.val().length >= 4){
                    var a_code = obj.val();
                    a_code = a_code.replace("(",'');
                    let result = a_code.substring(0, 3);
                    var data = {
                                action: 'check_area_code',
                                areacode: result
                            };
                    jQuery.ajax({
                        type : "post",
                        url : custom_object.ajax_url,
                        data : data,
                        success: function(response) {
                            if(response == "not_exist"){
                                var emsg = "Invalid area code!";
                                if(siteLang !== null && siteLang !== undefined){
                                    if(siteLang == 'es'){
                                        emsg = "¡Código de área inválido!";
                                    }
                                }
                                jQuery('button[type="submit"]').attr('disabled','true');
                                jQuery('#phone_err').remove();
                                jQuery('<span class="text-danger invalid-code" id="phone_err">'+emsg+'</span>').insertAfter(obj);
                            }else if(response == "exist"){
                                if(obj.val().length == 14){
                                    jQuery('#phone_err').remove();
                                    jQuery('button[type="submit"]').removeAttr('disabled');
                                }
                            }
                        }
                    });
                }
            });

            // custom checkbox
            jQuery("#pms_custom_ck").click(function(){
                jQuery("#error_check").remove();
                if(jQuery(this).is(":checked")){
                    jQuery("#pms_user_consent").prop("checked", true);
                }else{
                    jQuery("#pms_user_consent").prop("checked", false);
                }
            });
            // if checkbox check
            if(jQuery("#pms_custom_ck").length > 0 && jQuery("#pms_custom_ck").is(":visible") && siteLang !== null){
                jQuery(".pms-form-submit").click(function(e){
                    jQuery(".pms-form.pc-register-form").submit(false);
                    e.preventDefault();                
                    if(jQuery("#pms_user_consent").is(":checked")){
                        jQuery(".pms-form.pc-register-form").submit(); // form submit
                    }else{
                        if(jQuery("#error_check").length == 0){
                            jQuery('<span style="color:red;" id="error_check">Por favor, rellene los campos obligatorios.</span>').insertAfter(jQuery("label[for='pms_custom_ck']"));
                        }
                    }
                });
            }
            jQuery('.custom-uploader').click(function(){
                jQuery('.upDocs').last().click();
            });
            jQuery(document).on('change','.upDocs',function(){
                jQuery('#shareUp').show();
                jQuery('#required').remove();
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
            jQuery('#shareUp').click(function(){
                jQuery(this).prop('disabled','true');            
                if(siteLang == "es"){
                    jQuery(this).attr('title','Subiendo en progreso, por favor espere...');
                    jQuery(this).text('Subiendo..');                    
                }else{
                    jQuery(this).text('Uploading..');
                    jQuery(this).attr('title','Uploading in progress, please wait...');
                }            
                jQuery('.upDocs').last().remove();
                var fd = new FormData();
                var docs = jQuery('input[name="doc[]"]');
                docs.map(function(index){
                    fd.append("data_"+index, this.files[0]);
                });
                fd.append('name', jQuery('#pms_first_name').val()); 
                fd.append('type', subType);
                fd.append('action', 'upload_to_sharefile');

                var basePath =  custom_object.site_url;
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
                        if(res == 'not_found'){
                            if(siteLang == "es"){
                                jQuery('#shareUp').after('<p style="color: #b70f0f; id="required">Algo salió mal.</p>');                                 
                            }else{
                                jQuery('#shareUp').after('<p style="color: #b70f0f; id="required">Something went wrong.</p>');                               
                            }
                            jQuery('#shareUp').removeAttr('disabled');
                            jQuery('#shareUp').text('Upload');
                        }else if(res == 'success'){
                            jQuery('.pms-upload-field').hide();
                            if(siteLang == "es"){
                                jQuery('.pms-upload-field').after('<span style="flex: 0 0 100%;color: #0a580a;">Documento cargado con éxito.</span>');
                            }else{
                                jQuery('.pms-upload-field').after('<span style="flex: 0 0 100%;color: #0a580a;">Document uploaded successfully.</span>');
                            }
                            jQuery('.pms-form-submit').removeAttr('disabled');
                            jQuery('.pms-form-submit').removeAttr('title');
                            jQuery('#pms_person').prop('readonly','true');
                            jQuery('#pms_yearly_income').prop('readonly','true');
                            jQuery('#pms_user_email').after('<input type="hidden" name="submission" id="submission" value="success">');
                            jQuery('#upload_err').remove();
                        }
                    }
                });
            });
            /* joint account plan */
            jQuery("input[name='subscription_plans']").click(function(){
                if(jQuery(this).val() == 19780 || jQuery(this).val() == 19781){
                    showHideCode("show");
                    jQuery("#fee_am").val(75);
                }else{
                    showHideCode("hide");
                    jQuery("#fee_am").val("");
                }
            });
            jQuery(".sub-class").click(function(){
                if(jQuery(this).val() == "joint"){
                    showHideCode("show");
                    jQuery("#fee_am").val(0);
                }else{
                    showHideCode("hide");
                    jQuery("#fee_am").val("");
                }
            });
            function showHideCode(display){
                if(display == "show"){
                    jQuery("#spouse_div").show(); // show
                    jQuery(".spouse_heading").show();
                    jQuery("#sp_fname").prop("required", true);
                    jQuery("#sp_lname").prop("required", true);
                    jQuery("#sp_email").prop("required", true);
                    jQuery("#sp_mobile").prop("required", true);
                }else{
                    jQuery("#spouse_div").hide(); // hide
                    jQuery(".spouse_heading").hide();
                    jQuery("#sp_fname").prop("required", false);
                    jQuery("#sp_lname").prop("required", false);
                    jQuery("#sp_email").prop("required", false);
                    jQuery("#sp_mobile").prop("required", false);
                }
            }
        });
        const addLoader = setInterval(setLoader, 500);

        function setLoader() {
            // if(jQuery('.pms-form-submit.pms-submit-disabled').length > 0){
            if(jQuery('.pms-form-submit').length > 0 && jQuery('.pms-form-submit').is(":disabled") && jQuery("#phone_err").length == 0){
                var formId = 'pms_register-form';
                var showLoader = true;
                jQuery('#'+formId).find('input').each(function(){
                    if(jQuery(this).prop('required')){
                        if(jQuery(this).val() == ""){
                            showLoader = false;
                            return false;
                        }
                    } 
                });
                if(showLoader){
                    jQuery('#loader-register-div').show();
                }else{
                    jQuery(".pms-form-submit").removeClass("pms-submit-disabled");
                    jQuery(".pms-form-submit").attr("value","Register");
                }
            }else{
                jQuery('#loader-register-div').hide();
            }
        }
    }
    // end pms custom js

    // 01-dec
    if(jQuery("h4.pms-mspu-form-heading").length > 0 && jQuery("#pms_new_subscription-form").length > 0){
        jQuery("h2.elementor-heading-title.elementor-size-default").hide();
    }

    if(jQuery("#pms_new_subscription-form").length > 0){
        jQuery("input[name='subscription_plans']").click(function(){
            if(jQuery(this).val() == 19780 || jQuery(this).val() == 19781){
                showHideCode("show");
                jQuery("#fee_am").val(75);
            }else{
                showHideCode("hide");
                jQuery("#fee_am").val("");
            }
        });
        jQuery(".sub-class").click(function(){
            if(jQuery(this).val() == "joint"){
                showHideCode("show");
                jQuery("#fee_am").val(0);
            }else{
                showHideCode("hide");
                jQuery("#fee_am").val("");
            }
        });
        function showHideCode(display){
            if(display == "show"){
                jQuery("#spouse_div").show(); // show
                jQuery(".spouse_heading").show();
                jQuery("#sp_fname").prop("required", true);
                jQuery("#sp_lname").prop("required", true);
                jQuery("#sp_email").prop("required", true);
                jQuery("#sp_mobile").prop("required", true);
            }else{
                jQuery("#spouse_div").hide(); // hide
                jQuery(".spouse_heading").hide();
                jQuery("#sp_fname").prop("required", false);
                jQuery("#sp_lname").prop("required", false);
                jQuery("#sp_email").prop("required", false);
                jQuery("#sp_mobile").prop("required", false);
            }
        }
        jQuery("input[name='pms_new_subscription']").click(function(e){
            e.preventDefault();
            jQuery(this).val("subscribe");
            if(jQuery("#spouse_div").is(":visible")){
                if(jQuery("#pms_sp_fname").val() == "" || jQuery("#pms_sp_lname").val() == "" || jQuery("#pms_sp_mobile").val() == "" || jQuery("#pms_sp_email").val() == ""){
                    jQuery('<span style="color:red;" id="error_check">Por favor, rellene los campos obligatorios.</span>').insertAfter("#spouse_div");
                }else{
                    jQuery('#loader-register-div').show();
                    if(!jQuery("#pms-stripe-credit-card-details").is(":visible")){
                        jQuery("#pms_new_subscription-form").submit();
                    }
                }
            }else{
                jQuery('#loader-register-div').show();
                if(!jQuery("#pms-stripe-credit-card-details").is(":visible")){
                    jQuery("#pms_new_subscription-form").submit();
                }
            }
            const removeLoader = setInterval(rmLoader, 500);

            function rmLoader() {
                if(jQuery(".pms_field-errors-wrapper").length > 0){
                    jQuery('#loader-register-div').hide();
                }
            }
        });
        // sp mobile number
            jQuery("#pms_sp_mobile").keyup(function() {
                var obj = jQuery(this);
                var objVal = obj.val();
                var fnum = objVal.replace(/\D/g, ''), 
                    fchar = {0:'(',3:') ',6:'-'};
                obj.val('');
                for (var i = 0; i < fnum.length; i++) {
                  var res = obj.val() + (fchar[i]||'') + fnum[i];
                  obj.val(res);
                }
                /* check area code */
                if(obj.val().length >= 4){
                    var a_code = obj.val();
                    a_code = a_code.replace("(",'');
                    let result = a_code.substring(0, 3);
                    var data = {
                                action: 'check_area_code',
                                areacode: result
                            };
                    jQuery.ajax({
                        type : "post",
                        url : custom_object.ajax_url,
                        data : data,
                        success: function(response) {
                            if(response == "not_exist"){
                                var emsg = "Invalid area code!";
                                if(lang !== null && lang !== undefined){
                                    if(lang == 'es'){
                                        emsg = "¡Código de área inválido!";
                                    }
                                }
                                jQuery('button[type="submit"]').attr('disabled','true');
                                jQuery('#phone_err').remove();
                                jQuery('<span class="text-danger invalid-code" id="phone_err">'+emsg+'</span>').insertAfter(obj);
                            }else if(response == "exist"){
                                if(obj.val().length == 14){
                                    jQuery('#phone_err').remove();
                                    jQuery('button[type="submit"]').removeAttr('disabled');
                                }
                            }
                        }
                    });
                }
            });
    }
    var uplink = '';
    if(jQuery(".pms-account-subscription-action-link__upgrade").length > 0){
        uplink = jQuery(".pms-account-subscription-action-link__upgrade").attr("href");
    }else{
        jQuery(".upgrade_plan_link").hide();
    }
    jQuery(document).on('click','.upgrade_plan_link',function(){        
        if(uplink != ""){
            window.location.href = uplink;
        }
    });
    /* credit monitoring code */
    jQuery("#annual_plans_sec") .hide();
    jQuery("#annual_plans_sec_top").hide();
    if(jQuery("#monthly_plan").length > 0){
        jQuery("#monthly_plan a").addClass('active');
    }
    jQuery("#monthly_plan").click(function(e){
        e.preventDefault();
        jQuery("#annual_plan a").removeClass('active');
        jQuery("#monthly_plan a").addClass('active');
        jQuery("#annual_plans_sec").hide();
        jQuery("#annual_plans_sec_top").hide();
        jQuery("#monthly_plans_sec") .show();
        jQuery("#monthly_plans_sec_top").show();
    });

   jQuery("#annual_plan").click(function(e){
      e.preventDefault();
      jQuery("#annual_plan a").addClass('active');
      jQuery("#monthly_plan a").removeClass('active');
      jQuery("#annual_plans_sec").show();
      jQuery("#annual_plans_sec_top").show();
      jQuery("#monthly_plans_sec") .hide();
      jQuery("#monthly_plans_sec_top").hide();
   });

   if(jQuery("#monthly-plan").length > 0){
        jQuery("#monthly-plan a").addClass('active');
    }
   jQuery("#annual-sec") .hide();
   jQuery("#monthly-plan").click(function(e){
      e.preventDefault();
      jQuery("#annual-plan a").removeClass('active');
      jQuery("#monthly-plan a").addClass('active');
      jQuery("#annual-sec").hide();
      jQuery("#monthly-sec") .show();
   });

   jQuery("#annual-plan").click(function(e){
      e.preventDefault();
      jQuery("#annual-plan a").addClass('active');
      jQuery("#monthly-plan a").removeClass('active');
      jQuery("#annual-sec").show();
      jQuery("#monthly-sec") .hide();
   });  
});