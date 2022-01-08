<?php
/*
Plugin Name:       Kianland Core
Description:       Core personalization for ... company
Contributors:      (Alireza Saffar, Mohammadreza Saffar)
Author:            Alireza Saffar Khorasany
Author URI:        https://kianland.com/
Donate link:       https://kianland.com/donate/
Version:           1.0
Stable tag:        1.0
Requires at least: 5.8
Tested up to:      5.8
Text Domain:       kianland_core
Domain Path:       /languages
License:           GPL v2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
*/

namespace KianlandCore;

namespace KianlandCore\Widgets\KianlandC_Registration;

namespace KianlandCore\Widgets\KianlandC_Devices;

if (!defined('ABSPATH')) {
    exit;
}

class Kianland_Core
{

    function register()
    {
        add_action('init', [$this, 'text_domain']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        require_once __DIR__ . '/widgets/Widgets_Loader.php';
        require_once __DIR__ . '/public/public_enqueues.php';
        require_once __DIR__ . '/admin/admin_enqueues.php';
    }

    function add_admin_menu()
    {
        add_menu_page('دستگاه ها', 'دستگاه ها', 'manage_options', 'prweb_devices', [$this, 'pages_devices'], 'dashicons-open-folder', '110');
        add_submenu_page('prweb_devices', 'دستگاه ها', 'همه دستگاه ها', 'manage_options', 'prweb_devices', [$this, 'pages_devices']);
        add_submenu_page('prweb_devices', 'دستگاه ها', 'اضافه کردن جدید', 'manage_options', 'prweb_add_device', [$this, 'pages_add_device']);
    }

    function pages_devices()
    {
        require_once __DIR__ . '/admin/pages/devices.php';
    }

    function pages_add_device()
    {
        require_once __DIR__ . '/admin/pages/add_device.php';
    }

    static function get_enqueue_limits($slug)
    {
        $http = (isset($_SERVER["HTTPS"])) ? "https://" : "http://";
        // if settings page was generated this option should be uncommented...
//        if (strpos($http . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 'options-general.php?page=medicalAppt')) {
//            return;
//        }
        // seems useless for now...
//        if ($slug == '') {
//            return strpos($http . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"], 'medicalAppt');
//        }
        $uri = site_url() . '/wp-admin/admin.php?page=' . $slug;
        return $uri === $http . $_SERVER["SERVER_NAME"] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER["REQUEST_URI"];
    }

    function text_domain()
    {
        load_plugin_textdomain('kianland_core');
    }

}

$core = new Kianland_Core();
$core->register();
function kainlandC_test_wp_ajax()
{
    echo 'hello';
    die();
}
add_action('wp_ajax_kainlandC_test_wp_ajax', 'kainlandC_test_wp_ajax');
add_action('wp_ajax_nopriv_kainlandC_test_wp_ajax', 'kainlandC_test_wp_ajax');

// Activation
require_once __DIR__ . '/includes/activate.php';
// Deactivation
require_once __DIR__ . '/includes/deactivate.php';
// Uninstallation
require_once __DIR__ . './uninstall.php';











