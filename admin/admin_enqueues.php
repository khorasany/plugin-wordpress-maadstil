<?php
if(!defined('ABSPATH')) exit;

function kianlandC_admin_enqueue_describe(){
    wp_register_style('kianlandC_bootstrap_style', plugin_dir_url(__DIR__) . 'admin/css/bootstrap.min.css', []);
    wp_enqueue_style('kianlandC_bootstrap_style');
    wp_enqueue_script('kianlandC_bootstrap_js', plugin_dir_url(__DIR__) . 'admin/js/bootstrap.min.js', [], null, true);
}
function kianlandC_admin_enqueue_register(){
    if(\KianlandCore\Widgets\KianlandC_Devices\Kianland_Core::get_enqueue_limits('prweb_devices') || \KianlandCore\Widgets\KianlandC_Devices\Kianland_Core::get_enqueue_limits('prweb_add_device')){
        add_action('admin_enqueue_scripts', 'kianlandC_admin_enqueue_describe');
    }
}
add_action('init', 'kianlandC_admin_enqueue_register');


