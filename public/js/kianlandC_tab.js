jQuery(document).ready(function () {
    jQuery('.tab-a').click(function () {
        jQuery(".kc-tab-items").removeClass('tab-active');
        jQuery(".kc-tab-items[data-id='" + jQuery(this).attr('data-id') + "']").addClass("tab-active");
        jQuery(".tab-a").removeClass('active-a');
        jQuery(this).parent().find(".tab-a").addClass('active-a');
    });
});

function signup_haqiqi() {
    let res = document.querySelectorAll('.kc-select-in')
    let arr = {
        'type': 'haqiqi',
        'name':res[0].value,
        'sname':res[1].value,
        'mobile':res[2].value,
        'address':res[3].value,
        'telephone':res[4].value,
        'email':res[5].value,
        'personal_id':res[6].value,
        'postal_code':res[7].value,
        'password':res[8].value
    }
    Swal.fire({
        title: 'اطلاعات شما به عنوان یک فرد حقیقی ثبت شد.'
    }).then((result) => {
        if (result.isConfirmed) {
            // TODO: ajax req with digits hooks
            // jQuery.ajax();
        }
    })
}

function signup_hoquqi() {
    let res = document.querySelectorAll('.kc-select-in-2')
    let arr = {
        'type': 'hoquqi',
        'name':res[0].value,
        'sname':res[1].value,
        'mobile':res[2].value,
        'address':res[3].value,
        'telephone':res[4].value,
        'email':res[5].value,
        'postal_code':res[6].value,
        'acc_id':res[7].value,
        'company_name':res[8].value,
        'eco_id':res[9].value,
        'password':res[10].value
    }
    Swal.fire({
        title: 'اطلاعات شما به عنوان یک فرد حقوقی ثبت شد.'
    }).then((result) => {
        if (result.isConfirmed) {
            // TODO: ajax req with digits hooks
            // jQuery.ajax();
        }
    })
}