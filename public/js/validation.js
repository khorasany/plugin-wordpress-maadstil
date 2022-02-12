jQuery(document).ready(function ($) {
    $('.form').find('input, textarea').on('keyup blur focus', function (e) {

        var $this = $(this),
            label = $this.prev('label');

        if (e.type === 'keyup') {
            if ($this.val() === '') {
                label.removeClass('active highlight');
            } else {
                label.addClass('active highlight');
            }
        } else if (e.type === 'blur') {
            if ($this.val() === '') {
                label.removeClass('active highlight');
            } else {
                label.removeClass('highlight');
            }
        } else if (e.type === 'focus') {

            if ($this.val() === '') {
                label.removeClass('highlight');
            } else if ($this.val() !== '') {
                label.addClass('highlight');
            }
        }

    });

    $('.tab a').on('click', function (e) {

        e.preventDefault();

        $(this).parent().addClass('active');
        $(this).parent().siblings().removeClass('active');

        target = $(this).attr('href');

        $('.tab-content > div').not(target).hide();

        $(target).fadeIn(600);

    });
})

function kc_resend_code() {
    document.getElementById('kc_resend_token').click()
}

if (typeof document.querySelector('.kc-token-timer') != 'undefined' && document.querySelector('.kc-token-timer') !== null) {

    var seconds = 60;
    var timer;

    function myFunction() {
        if (seconds < 60) { // I want it to say 1:00, not 60
            document.querySelector('.kc-token-timer').innerHTML = seconds;
        }
        if (seconds > 0) { // so it doesn't go to -1
            seconds--;
        } else {
            clearInterval(timer);
            document.querySelector('.kc-resend-button').innerHTML = '<div class="forgot">\n' +
                '                                    <a href="javascript:void(0)" onclick="kc_resend_code()">ارسال دوباره رمز یکبار\n' +
                '                                        مصرف</a>\n' +
                '                                </div>'
        }
    }

   window.onload = function () {
        if (!timer) {
            timer = window.setInterval(function () {
                myFunction();
            }, 1000); // every second
        }
    }

    document.querySelector('.kc-token-timer').innerHTML = "1:00";

}