function check_device_availability() {
    let device_div = document.getElementById('kc_device'),
        myArray = {
            'action': 'kianlandC_get_device',
            'device_id': document.getElementById('kc_device_input').value
        }
    jQuery.post(ajaxurl, myArray, function (data) {
        if (data === 'failed') {
            Swal.fire({
                title: 'این دستگاه پیدا نشد.',
                icon: 'warning'
            })
        } else {
            device_div.innerHTML = data
            const countdown = () => {
                const countDate = document.getElementById('get_timestamp').value;
                const nowDate = Math.floor((new Date().getTime()) / 1000);
                const gap = countDate - nowDate;

                if (gap < 10) {
                    document.getElementsByClassName('kc-timer')[0].innerHTML = '<h2>این دستگاه منقضی شده است</h2>'
                    return
                }

                const second = 1;
                const minute = second * 60;
                const hour = minute * 60;
                const day = hour * 24;

                const textDay = Math.floor(gap / day);
                const textHour = Math.floor((gap % day) / hour);
                const textMinute = Math.floor((gap % hour) / minute);
                const textSecond = Math.floor((gap % minute) / second);

                document.querySelector('.kc-counter-second').innerText = textSecond;
                document.querySelector('.kc-counter-minute').innerText = textMinute;
                document.querySelector('.kc-counter-hour').innerText = textHour;
                document.querySelector('.kc-counter-day').innerText = textDay;
            }
            if(typeof document.querySelector('section.kc-timer') != 'undefined' && document.querySelector('section.kc-timer') !== null){
                setInterval(countdown, 1000);
            }


            var acc = document.getElementsByClassName("kc-accordion"),
                sections = document.getElementsByClassName('kc-panel'),
                parent_sections = document.getElementsByClassName('kc-device-section'),
                counter_parent_sections = 0;
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


            for (i = 0; i < sections.length; i++) {
                for (let j = 1; j < (sections[i].childNodes.length - 1); j = j + 2) {
                    if (sections[i].childNodes[j].childNodes[1].value === '') {
                        sections[i].childNodes[j].style.display = 'none';
                    }
                }
            }

            for (i = 0; i < parent_sections.length; i++) {
                counter_parent_sections = 0
                for (let j = 1; j < (parent_sections[i].childNodes[5].childNodes.length - 1); j = j + 2) {
                    if (parent_sections[i].childNodes[5].childNodes[j].style.display === 'none') {
                        counter_parent_sections++;
                    }
                }
                if (counter_parent_sections >= ((parent_sections[i].childNodes[5].childNodes.length - 1) / 2)) {
                    parent_sections[i].style.display = 'none';
                }
            }

        }
    })
}
