let params = (new URL(document.location)).searchParams;
let lang = params.get("lang");
if(lang !== null && lang !== undefined){
    if(lang == 'es'){
        if(jQuery('.stm-logo img')){
            jQuery('.stm-logo img').attr('src','https://premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
        }
        if(document.getElementById('#NzA2OjQ1-1') !== null && document.getElementById('#NzA2OjQ1-1').length > 0){
            document.getElementById('#NzA2OjQ1-1').setAttribute('nitro-lazy-src','https://premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
            document.getElementById('#NzA2OjQ1-1').setAttribute('src','https://premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
        }
        jQuery('img[src="https://premierconsumer.org/wp-content/uploads/2021/09/logo.jpg"]').attr('src','https://premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
        jQuery('img[src="https://premierconsumer.org/wp-content/uploads/2021/10/logo-footer-white.png"]').attr('src','https://premierconsumer.org/wp-content/uploads/2022/04/logo-footer-white-es.png');
    }
}

jQuery(document).ready(function(){
    jQuery('input,textarea').focus(function(){
       jQuery(this).removeAttr('placeholder');
    });
    jQuery("input[name='form_fields[phone]']").keyup(function() {
        var obj = jQuery(this);
      	var objVal = obj.val();
      	var fnum = objVal.replace(/\D/g, ''), 
      	    fchar = {0:'(',3:') ',6:'-'};
      	obj.val('');
      	for (var i = 0; i < fnum.length; i++) {
          var res = obj.val() + (fchar[i]||'') + fnum[i];
          obj.val(res);
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
                jQuery('img[src="https://premierconsumer.org/wp-content/uploads/2021/09/logo.jpg"]').attr('src','https://premierconsumer.org/wp-content/uploads/2022/04/logo-es.png');
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
            jQuery('.sub-menu').show();
            
            var hElem = jQuery('.stm-header__row_color.stm-header__row_color_top.elements_in_row_2').first().clone();
            jQuery('.stm-header__row_color.stm-header__row_color_top.elements_in_row_2').first().remove();
            jQuery(hElem).insertAfter('.stm-header__cell.stm-header__cell_left');
            
        } 
    }
});