<?php

namespace KianlandCore\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class KianlandC_Devices extends Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        wp_enqueue_style('kianlandC_devices_widget_styles', plugin_dir_url(__DIR__) . 'public/css/devices.css', [], 8);
        wp_enqueue_script('kianlandC_devices_widget_scripts', plugin_dir_url(__DIR__) . 'public/js/devices.js', ['jquery'], 9, true);
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
        return 'eicon-plus-square';
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
        ?>
        <div id="kc_device">
            <h2 class="kc-device-header">جهت استعلام دستگاه، کد دستگاه خود را وارد نماید</h2>
            <input type="text" id="kc_device_input" name="kc_device_input">
            <button type="button" id="kc_devices_submit" onclick="check_device_availability()"
                    class="kc-dvc-button"><span
                        class="dashicons dashicons-search kc-span-search" id="kc-span-devices"></span></button>
        </div>
        <?php
    }

    protected function _content_template()
    {
        ?>
        <div id="kc_device">
            <h2 class="kc-device-header">جهت استعلام دستگاه، کد دستگاه خود را وارد نماید</h2>
            <input type="text" id="kc_device_input" name="kc_device_input">
            <button type="button" id="kc_devices_submit" onclick="check_device_availability()"
                    class="kc-dvc-button"><span
                        class="dashicons dashicons-search kc-span-search" id="kc-span-devices"></span></button>
        </div>
        <?php
    }
}