<?php


namespace KianlandCore\Widgets;


class Widgets_Loader
{
    private static $instance = null;

    static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct()
    {
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
    }

    function init_widgets()
    {
        require_once __DIR__ . '/KianlandC_Registration.php';
        require_once __DIR__ . '/KianlandC_Devices.php';
        require_once __DIR__ . '/KianlandC_Validation.php';
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new KianlandC_Registration());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new KianlandC_Devices());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new KianlandC_Validation());
    }
}
Widgets_Loader::instance();