<?php
if (!defined('ABSPATH')) {
    exit;
}

function kianlandC_enqueue_script()
{
    wp_enqueue_style('sweet_alert2_style', plugin_dir_url(__FILE__) . 'css/sweetalert2.min.css');
    wp_enqueue_script('sweet_alert2_script', plugin_dir_url(__FILE__) . 'js/sweetalert2.min.js', [], null, true);
}

add_action('wp_enqueue_scripts', 'kianlandC_enqueue_script');