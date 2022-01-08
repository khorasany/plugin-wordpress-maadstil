<?php


namespace KianlandCore\Widgets;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;


class KianlandC_Devices extends Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        wp_enqueue_style('kianlandC_devices_widget_styles', plugin_dir_url(__DIR__) . 'public/css/kianlandC_dvc.css', [], null);
        wp_enqueue_script('kianlandC_devices_widget_scripts', plugin_dir_url(__DIR__) . 'public/js/kianlandC_dvc.js', [], null, true);
    }

    function get_style_depends()
    {
        return ['kianlandC_devices_widget_styles'];
    }

    function get_script_depends()
    {
        return ['kianlandC_devices_widget_scripts'];
    }

    function get_name()
    {
        return 'kianlandC_devices';
    }

    function get_title()
    {
        return 'Devices';
    }

    function get_icon()
    {

    }

    function get_categories()
    {
        return ['basic'];
    }

    protected function _register_controls()
    {

    }

    protected function render()
    {
        $form_checker = 0;
        if ($_POST['submit']) {
            $form_checker = 1;
            // TODO: query to get
        }
        ?>
        <form action="">
            <input type="text" id="device">
            <a href="javascript:void(0)" onclick="get_devices()">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M15.853 16.56c-1.683 1.517-3.911 2.44-6.353 2.44-5.243 0-9.5-4.257-9.5-9.5s4.257-9.5 9.5-9.5 9.5 4.257 9.5 9.5c0 2.442-.923 4.67-2.44 6.353l7.44 7.44-.707.707-7.44-7.44zm-6.353-15.56c4.691 0 8.5 3.809 8.5 8.5s-3.809 8.5-8.5 8.5-8.5-3.809-8.5-8.5 3.809-8.5 8.5-8.5z"/>
                </svg>
            </a>
        </form>
        <?php
        if ($form_checker) {
            ?>
            <!--            TODO: show fields here-->
            <?php
        }
    }

    protected function _content_template()
    {
        ?>
        <form action="">
            <input type="text" id="device">
            <a href="javascript:void(0)" onclick="get_devices()">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M15.853 16.56c-1.683 1.517-3.911 2.44-6.353 2.44-5.243 0-9.5-4.257-9.5-9.5s4.257-9.5 9.5-9.5 9.5 4.257 9.5 9.5c0 2.442-.923 4.67-2.44 6.353l7.44 7.44-.707.707-7.44-7.44zm-6.353-15.56c4.691 0 8.5 3.809 8.5 8.5s-3.809 8.5-8.5 8.5-8.5-3.809-8.5-8.5 3.809-8.5 8.5-8.5z"/>
                </svg>
            </a>
        </form>
        <?php

    }

}