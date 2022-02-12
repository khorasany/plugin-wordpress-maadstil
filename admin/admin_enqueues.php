<?php

if (!defined('ABSPATH')) exit;


function kianlandC_admin_enqueue_describe()
{
    wp_register_style('kianlandC_pages_style', plugin_dir_url(__DIR__) . 'admin/css/pages_custom.css', [], 93);
    wp_register_style('kianlandC_pages_datepicker_style', plugin_dir_url(__DIR__) . 'admin/css/persian-datepicker.css', [], null);
    wp_register_style('kianlandC_pages_jquery_dataTable_style', plugin_dir_url(__DIR__) . 'admin/css/jquery.dataTables.min.css', [], null);

    wp_enqueue_style('kianlandC_pages_style');
    wp_enqueue_style('kianlandC_pages_datepicker_style');
    wp_enqueue_style('kianlandC_pages_jquery_dataTable_style');

    wp_enqueue_script('kianlandC_pages_script', plugin_dir_url(__DIR__) . 'admin/js/pages_custom.js', ['jquery', 'kianlandC_pages_datepicker','kianlandC_pages_jquery_dataTable_script'], 96, true);
    wp_enqueue_script('kianlandC_jquery_datepicker', plugin_dir_url(__DIR__) . 'admin/js/datepicker_jquery.min.js', [], 1, true);
    wp_enqueue_script('kianlandC_pages_datepicker', plugin_dir_url(__DIR__) . 'admin/js/persian-datepicker.js', ['kianlandC_jquery_datepicker'], 1, true);
    wp_enqueue_script('kianlandC_pages_jquery_dataTable_script', plugin_dir_url(__DIR__) . 'admin/js/jquery.dataTables.min.js', [], null, true);
}

function kianlandC_admin_enqueue_register()
{
    if (\KianlandCore\Widgets\KianlandC_Devices\Kianland_Core::get_enqueue_limits('prweb_devices') ||
        \KianlandCore\Widgets\KianlandC_Devices\Kianland_Core::get_enqueue_limits('prweb_add_device') ||
        \KianlandCore\Widgets\KianlandC_Devices\Kianland_Core::get_enqueue_limits('prweb_settings') ||
        \KianlandCore\Widgets\KianlandC_Devices\Kianland_Core::get_enqueue_limits('prweb_edit_device')) {
        add_action('admin_enqueue_scripts', 'kianlandC_admin_enqueue_describe');
    }
}


add_action('init', 'kianlandC_admin_enqueue_register');

function kianlandC_registration_form()
{
    global $kianland_core;
    $log = get_option($kianland_core->log_key);
    $reg = get_option($kianland_core->reg_key);
    if (!is_user_logged_in() && site_url($_SERVER['REQUEST_URI']) == site_url('/my-account/')) {
        wp_redirect(site_url('/' . $log . '/'));
        exit;
    }
    if (is_user_logged_in() &&
        (site_url($_SERVER['REQUEST_URI']) == site_url('/' . $reg . '/') || site_url($_SERVER['REQUEST_URI']) == site_url('/' . $log . '/'))) {
        wp_redirect(site_url('/my-account/'));
        exit;
    }
}

add_action('plugins_loaded', 'kianlandC_registration_form');

function kianlandC_add_number()
{
//    if (strpos($_SERVER['REQUEST_URI'], '?login=true&back=home&page=1')) {
    ?>
    <script>
        let __request_uri = location.pathname + location.search
        if (__request_uri === '/?login=true&back=home&page=1') {
            console.log('function is running')
            setTimeout(() => {
                console.log('setTimeout is running')
                var mobile = document.getElementsByClassName('digits-input-wrapper empty')[0].childNodes[1]
                mobile.value = '<?= $_SESSION['mobile'] ?>'
            }, 1000)
        }
    </script>
    <?php
//    }
}

//add_action('init', 'kianlandC_add_number', 100);









