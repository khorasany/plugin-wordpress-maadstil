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



if (!defined('ABSPATH')) exit;



class Kianland_Core

{

    public $reg_key = 'kianlandC_registration_page';

    public $log_key = 'kianlandC_login_page';

    public $dvc_titles = [

        'سرعت تولید دستگاه', 'دامنه اشکال قابل تزریق', 'ابعاد دستگاه', 'جنس قطعات دستگاه', 'سیستم تزریق', 'سیستم برقی',

        'آپشن های ویژه', 'مخزن', 'قابلیت ارتباطی دستگاه', 'سیستم گرمایشی', 'قابلیت دید در مخزن تولید', 'توانایی بستن درب',

        'طول نوار نقاله', 'گیربکس های بکار رفته', 'نازل ها', 'سیستم خودشستشو'

    ];

    public $dvc_input_keys = [

        'سرعت پرکردن قوطی یک لیتری','سرعت پر کردن قوطی ۲۵۰ سی سی','سرعت پرکردن چهار لیتری','میانگین زمان ساخت هر مخزن مایع ظرفشویی',

        'میانگین زمان ساخت هر مخزن مایع دستشویی','میانگین زمان ساخت هر مخزن شامپو سر','میانگین زمان ساخت هر مخزن مایع سفید کننده',

        'میانگین زمان ساخت هر مخزن مایع سفید کننده غلیظ','میانگین زمان ساخت هر مخزن مایع جرمگیر','توضیحات','دامنه تزریق بر اساس حجم قوطی',

        'دامنه تزریق بر اساس شکل قوطی','قابلیت تنظیم اتوماتیک بر روی اشکال مختلف','توضیحات','طول','عرض','ارتفاع','توضیحات',

        'جنس شاسی','جنس بدنه','جنس اتصالات و پیچ و مهره ها','جنس مسیر انتقال مایع در دستگاه','توضیحات','سیستم کنترل جریان مایع',

        'سیستم تزریق مایعات غلیظ','سیستم تزریق مایعات رقیق','دامنه قوطی های قابل تزریق','دقت تزریق مایع','نوع موتور سیستم تزریق',

        'تعداد موتور سیستم تزریق','سیستم ضد کف مایع','توضیحات','پی ال سی','مانیتور پی ال سی تاچ اسکرین','اینورتر','محافظ جان',

        'کنترل بار','کنترل فاز','استپرموتور','تعداد استپرموتور','نوع جریان مصرفی','خنک کننده برد','نور پردازی تابلو برق و نازل ها',

        'میزان توان مصرفی','خنک کننده موتور','توضیحات','سیستم عیب یاب هوشمند','کنترل با موبایل و لپ تاپ','تونل ضد عفونی کننده',

        'سیستم اعلام سرویس دستگاه','توضیحات','ظرفیت مخزن','تعداد مخزن','نوع کنترل سطح مخزن','سیستم کنترل سطح وایرلس با نمایشگر اس ام دی',

        'توضیحات','کنترل بی سیم از طریق موبایل و لپ تاپ','نوع ارتباط','تماس از روی دستگاه با واحد پشتیبانی','توضیحات','گرمکن',

        'توان گرمکن','نمایشگر تنظیم دما','نوع سنسور دما','گرمکن هوشمند قابل برنامه ریزی زمان شروع بکار','توضیحات','دوربین دید در مخزن',

        'اس ام دی ضد آب در مخزن','سرعت فیلم برداری','قابلیت انتقال تصویر','وای فای','توضیحات','تعداد هد های درب بندی','هولدر قوطی',

        'الکترو موتورهای درب بند','توضیحات','عرض نوار نقاله','گارد های متحرک','جنس گارد ها','جنس نوار نقاله','تنظیم کننده کشش نوار',

        'توضیحات','برند گیربکس','تعداد گیربکس','تعداد دور بر دقیقه','سایز شافت','توضیحات','نازل های متحرک','سیستم حرکت عمودی نازل ها',

        'قطر نازل ها','قابلیت تعویض سر نازل برای انواع مختلف قوطی','سیستم ضد چکه نازل ها','جنس نازل ها','اتصالات نازل ها',

        'تعداد کلمپ های به کار رفته','توضیحات','توان موتور خودشستشو','جنس هد پمپ شستشو','جنس مسیر و نازل شستشو','مکانیزم سی آی پی',

        'توانایی برنامه ریزی شستشو','توضیحات'

    ];



    function register()

    {

        register_activation_hook(__FILE__, [$this, 'activate']);

        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        add_action('init', [$this, 'text_domain']);

        add_action('init', [$this, 'output_buffering']);

        add_action('init', [$this, 'session_start']);

        require_once __DIR__ . '/admin/admin_functions.php';

        add_action('admin_menu', [$this, 'add_admin_menu']);

        require_once __DIR__ . '/widgets/Widgets_Loader.php';

        require_once __DIR__ . '/public/public_enqueues.php';

        require_once __DIR__ . '/public/wc-essentials.php';

        require_once __DIR__ . '/admin/admin_enqueues.php';

    }



    function add_admin_menu()

    {

        add_menu_page('دستگاه ها', 'دستگاه ها', 'manage_options', 'prweb_devices', [$this, 'pages_devices'], 'dashicons-open-folder', '110');

        add_submenu_page('prweb_devices', 'دستگاه ها', 'همه دستگاه ها', 'manage_options', 'prweb_devices', [$this, 'pages_devices']);

        add_submenu_page('prweb_devices', 'دستگاه ها', 'اضافه کردن جدید', 'manage_options', 'prweb_add_device', [$this, 'pages_add_device']);

        add_submenu_page('prweb_devices', 'تنظیمات', 'تنظیمات صفحات', 'manage_options', 'prweb_settings', [$this, 'pages_settings']);

        add_submenu_page('', 'ویرایش دستگاه', 'ویرایش دستگاه', 'manage_options', 'prweb_edit_device', [$this, 'pages_edit_device']);

    }



    function output_buffering()

    {

        ob_start();

    }



    function session_start()

    {

        session_start();

    }



    function pages_edit_device()

    {

       require_once __DIR__ . '/admin/pages/edit_device.php';

    }



    function pages_devices()

    {

        require_once __DIR__ . '/admin/pages/devices.php';

    }



    function pages_add_device()

    {

        require_once __DIR__ . '/admin/pages/add_device.php';

    }



    function pages_settings()

    {

        require_once __DIR__ . '/admin/pages/settings.php';

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

        return $uri === $http . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

    }



    function text_domain()

    {

        load_plugin_textdomain('kianland_core');

    }



    function activate()

    {

        if (!current_user_can('activate_plugins')) exit();

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'token_validate';

        $table_name2 = $wpdb->prefix . 'prweb_devices';

        $sql = "

        CREATE TABLE `$table_name` (

        id int(11) AUTO_INCREMENT PRIMARY KEY,

        generated_token varchar(5) NOT NULL,

        session_id varchar(255) NOT NULL,

        mobile varchar(11) NULL,

        get_user varchar(5) NULL,

        token_used varchar(3) NOT NULL,

        generate_time varchar(10) NOT NULL,

        userdata TEXT NULL,

        expire_time varchar(10) NOT NULL

        ) $charset_collate;

        ";

        $sql2 = "

        CREATE TABLE `$table_name2` (

        id int(11) AUTO_INCREMENT PRIMARY KEY,

        device_id varchar(20) NULL,

        device_spec varchar(20) NULL,

        device_model varchar(20) NULL,

        device_image varchar(255) NULL,

        device_owner varchar(20) NULL,

        device_data LONGTEXT NULL,

        device_build_date varchar(10) NULL,

        device_expire_date varchar(10) NULL

        ) $charset_collate;

        ";

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            dbDelta($sql);

        }

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name2'") != $table_name2) {

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            dbDelta($sql2);

        }

    }



    function deactivate()

    {

        flush_rewrite_rules();

    }

}



$kianland_core = new Kianland_Core();

$kianland_core->register();



//function kiancore_send_sms() {

//    $url = "https://ippanel.com/services.jspd";

//

//    $rcpt_nm = ['9352399329'];

//    $param = array

//    (

//        'uname'=>'09386560060',

//        'pass'=>'Meysam0920296734',

//        'from'=> '5000125475',

//        'message'=>'تست',

//        'to'=>json_encode($rcpt_nm),

//        'op'=>'send'

//    );

//

//    $handler = curl_init($url);

//    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");

//    curl_setopt($handler, CURLOPT_POSTFIELDS, $param);

//    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

//    $response2 = curl_exec($handler);

//

//    $response2 = json_decode($response2);

//    $res_code = $response2[0];

//    $res_data = $response2[1];

//

//    var_dump($response2);

//}



//add_action('init','kiancore_send_sms');





require_once __DIR__ . '/uninstall.php';



