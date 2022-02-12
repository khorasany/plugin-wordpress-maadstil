function choose_file() {
    document.getElementById('file_input').click()
}

if (typeof (document.getElementById('file_input')) != 'undefined' && document.getElementById('file_input') !== null) {
    document.getElementById('file_input').onchange = function (evt) {
        if (typeof (document.querySelector('.kc-add-dvc-img')) != 'undefined' && document.querySelector('.kc-add-dvc-img') !== null) {
            document.querySelector('.kc-add-dvc-img').remove()
        }
        var tgt = evt.target || window.event.srcElement,
            files = tgt.files,
            img = document.createElement('img'),
            div = document.getElementById('kc-image-container')
        img.setAttribute('class', 'kc-add-dvc-img')

        // FileReader support
        if (FileReader && files && files.length) {
            var fr = new FileReader();
            fr.onload = function () {
                // img.src = fr.result;
                img.setAttribute('src', fr.result)
            }
            fr.readAsDataURL(files[0]);
            div.appendChild(img)
        }
    }
    jQuery(document).ready(function ($) {
        $('#kc_dvc_date').datepicker();
    });

    jQuery(document).ready(function ($) {
        $('#kc_dvc_build_date').datepicker();
    });
}

if (typeof (document.getElementById('devicesTable')) != 'undefined' && document.getElementById('devicesTable') !== null) {
    jQuery(document).ready(function ($) {
        $('#devicesTable').DataTable();
    });
}

function test_alert() {
    jQuery.ajax({
        type: "post",
        url: 'https://maadsteel.co/wp-admin/admin-ajax.php',
        data:
            {
                action: "kc_ajax_test"
            },
        success: function (response) {
            if (response.type == "success") {
                alert('successful')
            } else {
                alert('failed')
            }
        }
    })
}

if (typeof (document.getElementById('this_is_accordion_needed_page')) != 'undefined' && document.getElementById('this_is_accordion_needed_page') !== null) {
    var acc = document.getElementsByClassName("kc-accordion");
    for (let i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }
}