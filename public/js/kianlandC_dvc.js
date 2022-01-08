function get_devices(){
    let device = document.getElementById('device').value,
        inputs = document.querySelectorAll('.dvc_info'),
        div = document.getElementById('switch'),
        dvc_data, counter = 0;

    jQuery.ajax('', {

    }, function (data){
        dvc_data = data;
    });
    while(counter<100){
        inputs[counter].value = dvc_data[counter]
        counter++
    }
    div.style.display = 'block';
}