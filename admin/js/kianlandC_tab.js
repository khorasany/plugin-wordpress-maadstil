jQuery(document).ready(function(){
    jQuery('.tab-a').click(function(){
        jQuery(".kc-tab-items").removeClass('tab-active');
        jQuery(".kc-tab-items[data-id='"+jQuery(this).attr('data-id')+"']").addClass("tab-active");
        jQuery(".tab-a").removeClass('active-a');
        jQuery(this).parent().find(".tab-a").addClass('active-a');
    });
});