<?php

function kc_ajax_test(){
    echo 'hello';
    die();
}
add_action('wp_ajax_kc_ajax_test', 'kc_ajax_test');
add_action('wp_ajax_nopriv_kc_ajax_test', 'kc_ajax_test');