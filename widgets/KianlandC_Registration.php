<?php


namespace KianlandCore\Widgets;


use Elementor\Widget_Base;

use Elementor\Controls_Manager;


if (!defined('ABSPATH')) exit;


class KianlandC_Registration extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        wp_enqueue_style('kianlandC_reg_widget_styles', plugin_dir_url(__DIR__) . 'public/css/tabs.css', [], 16);
        wp_enqueue_script('kianlandC_reg_widget_scripts', plugin_dir_url(__DIR__) . 'public/js/tabs.js', ['jquery'], 4);
    }

    function get_style_depends()
    {
        return ['kianlandC_reg_widget_styles'];
    }

    function get_script_depends()
    {
        return ['kianlandC_reg_widget_scripts'];
    }

    function get_name()
    {
        return 'kianlandC_registration';
    }

    function get_title()
    {
        return 'Registration';
    }

    function get_icon()
    {
        return 'eicon-user-circle-o';
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
        if (isset($_POST['kc_submit']) && isset($_POST['kc_form_name']) && isset($_POST['kc_form_sname']) && isset($_POST['kc_form_mobile']) &&
            isset($_POST['kc_form_address']) && isset($_POST['kc_form_telephone']) && isset($_POST['kc_form_email']) && isset($_POST['kc_form_personal_id']) &&
            isset($_POST['kc_form_postal_code']) && isset($_POST['kc_form_password']) && isset($_POST['kc_form_password2'])) {
            $name = $_POST['kc_form_name'];
            $sname = $_POST['kc_form_sname'];
            $mobile = $_POST['kc_form_mobile'];
            if (!is_int((int)$mobile)) {
                $error_mobile = 1;
            } elseif (strlen($mobile) == 11 && preg_match("/09[0-9]{9}/", $mobile)) {
                $error_mobile = 0;
            } else {
                $error_mobile = 2;
            }
            $address = $_POST['kc_form_address'];
            $tel = $_POST['kc_form_telephone'];
            if (!is_int((int)$tel)) {
                $error_tel = 1;
            } elseif (strlen($tel) == 11 && preg_match("/0[0-9]{10}/", $tel)) {
                $error_tel = 0;
            } else {
                $error_tel = 2;
            }
            $email = $_POST['kc_form_email'];
            if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email)) {
                $error_email = 0;
            } else {
                $error_email = 1;
            }
            $pid = $_POST['kc_form_personal_id'];
            if (!is_int((int)$pid)) {
                $error_pid = 1;
            } elseif (strlen($pid) == 10 && preg_match("/[0-9]{10}/", $pid)) {
                $error_pid = 0;
            } else {
                $error_pid = 2;
            }
            $zip = $_POST['kc_form_postal_code'];
            if (!is_int((int)$zip)) {
                $error_zip = 1;
            } elseif (strlen($zip) == 10 && preg_match("/[0-9]{10}/", $zip)) {
                $error_zip = 0;
            } else {
                $error_zip = 2;
            }
            $pass = $_POST['kc_form_password'];
            $pass2 = $_POST['kc_form_password2'];
            if ($pass != $pass2) {
                $error_pass = 1;
            } else {
                $error_pass = 0;
            }
            if (get_user_by('login', $mobile)) {
                $error_user_exists = 1;
            } else {
                $error_user_exists = 0;
            }

            if ($pass == $pass2 && !$error_mobile && !$error_tel && !$error_email && !$error_pid && !$error_zip && !$error_user_exists) {
                $encoded_pass = base64_encode($pass);
                $userdata = [
                    'mode' => 'haqiqi',
                    'name' => $name,
                    'sname' => $sname,
                    'mobile' => $mobile,
                    'address' => $address,
                    'telephone' => $tel,
                    'email' => $email,
                    'pid' => $pid,
                    'zip' => $zip,
                    'pass' => $pass
                ];
                $userdata = serialize($userdata);

                global $wpdb;
//                $mobile_valid_check = $wpdb->get_var("SELECT user_login FROM `wp_users` WHERE user_login='$mobile'");
//                if($mobile_valid_check && $mobile_valid_check == $mobile){
//
//                }

                global $kianland_core;
                $session_id = session_id();
                $_SESSION['mobile'] = $mobile;
                $generated_token = random_int(10000, 99999);
                $validate_user = random_int(10000, 99999);
                $token_generate_time = time();
                $token_expire_time = $token_generate_time + 60;
                $sql = "INSERT INTO `wp_token_validate` (generated_token,session_id,mobile,get_user,token_used,generate_time,userdata,expire_time) 
                        VALUES ('$generated_token','$session_id','$mobile','$validate_user','no','$token_generate_time','$userdata','$token_expire_time')";
                $result_insert = $wpdb->query($sql);
                $sql_p = "INSERT INTO `wp_usermeta` (meta_key, meta_value) VALUES ('_kian_check_user', '$encoded_pass')";
                $result_insert_p = $wpdb->query($sql_p);

                if ($result_insert && $result_insert_p) {
                    // send sms START
                    $url = "https://ippanel.com/services.jspd";
                    $rcpt_nm = [ltrim($mobile, '0')];
//                    var_dump($rcpt_nm);
//                    die();
                    $param = array
                    (
                        'uname' => '09386560060',
                        'pass' => 'Meysam0920296734',
                        'from' => '5000125475',
                        'message' => $generated_token,
                        'to' => json_encode($rcpt_nm),
                        'op' => 'send'
                    );

                    $handler = curl_init($url);
                    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
                    $sms_response = curl_exec($handler);
                    if ($sms_response) {
                        $slug = get_option($kianland_core->log_key);
                        wp_redirect(site_url('/' . $slug . '/?num-val=1&user=' . "$validate_user"));
                        exit;
                    }
                    // send sms FINISH
                }
            }
        }


        if (isset($_POST['kc_submit_2']) && isset($_POST['kc_form_name_2']) && isset($_POST['kc_form_sname_2']) &&
            isset($_POST['kc_form_mobile_2']) && isset($_POST['kc_form_address_2']) && isset($_POST['kc_form_telephone_2']) &&
            isset($_POST['kc_form_email_2']) && isset($_POST['kc_form_postal_code_2']) && isset($_POST['kc_form_acc_id_2']) &&
            isset($_POST['kc_form_company_name_2']) && isset($_POST['kc_form_eco_id_2']) && isset($_POST['kc_form_password_2']) &&
            isset($_POST['kc_form_password2_2'])) {
            $name = $_POST['kc_form_name_2'];
            $sname = $_POST['kc_form_sname_2'];
            $mobile = $_POST['kc_form_mobile_2'];
            if (!is_int((int)$mobile)) {
                $error_mobile = 1;
            } elseif (strlen($mobile) == 11 && preg_match("/09[0-9]{9}/", $mobile)) {
                $error_mobile = 0;
            } else {
                $error_mobile = 2;
            }
            $address = $_POST['kc_form_address_2'];
            $tel = $_POST['kc_form_telephone_2'];
            if (!is_int((int)$tel)) {
                $error_tel = 1;
            } elseif (strlen($tel) == 11 && preg_match("/0[0-9]{10}/", $tel)) {
                $error_tel = 0;
            } else {
                $error_tel = 2;
            }
            $email = $_POST['kc_form_email_2'];
            if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email)) {
                $error_email = 0;
            } else {
                $error_email = 1;
            }
            $zip = $_POST['kc_form_postal_code_2'];
            if (!is_int((int)$zip)) {
                $error_zip = 1;
            } elseif (strlen($zip) == 10 && preg_match("/[0-9]{10}/", $zip)) {
                $error_zip = 0;
            } else {
                $error_zip = 2;
            }
            $accid = $_POST['kc_form_acc_id_2'];
            if (!is_int((int)$accid)) {
                $error_acc = 1;
            } elseif (strlen($accid) < 20 && preg_match("/[0-9]/", $accid)) {
                $error_acc = 0;
            } else {
                $error_acc = 2;
            }
            $company = $_POST['kc_form_company_name_2'];
            $ecoid = $_POST['kc_form_eco_id_2'];
            if (!is_int((int)$ecoid)) {
                $error_eco = 1;
            } elseif (strlen($ecoid) < 20 && preg_match("/[0-9]/", $ecoid)) {
                $error_eco = 0;
            } else {
                $error_eco = 2;
            }
            $pass = $_POST['kc_form_password_2'];
            $pass2 = $_POST['kc_form_password2_2'];
            if ($pass != $pass2) {
                $error_pass = 1;
            } else {
                $error_pass = 0;
            }
            if (get_user_by('login', $mobile)) {
                $error_user_exists = 1;
            } else {
                $error_user_exists = 0;
            }

            if (!$error_pass && !$error_mobile && !$error_tel && !$error_email && !$error_zip && !$error_acc && !$error_eco && !$error_user_exists) {
                $encoded_pass = base64_encode($pass);
                $userdata = [
                    'mode' => 'hoquqi',
                    'name' => $name,
                    'sname' => $sname,
                    'mobile' => $mobile,
                    'address' => $address,
                    'telephone' => $tel,
                    'email' => $email,
                    'zip' => $zip,
                    'acc_id' => $accid,
                    'eco_id' => $ecoid,
                    'company_name' => $company,
                    'pass' => $pass
                ];
                $userdata = serialize($userdata);

                global $wpdb;
                global $kianland_core;
                $session_id = session_id();
                $_SESSION['mobile'] = $mobile;
                $generated_token = random_int(10000, 99999);
                $validate_user = random_int(10000, 99999);
                $token_generate_time = time();
                $token_expire_time = $token_generate_time + 60;
                $sql = "INSERT INTO `wp_token_validate` (generated_token,session_id,mobile,get_user,token_used,generate_time,userdata,expire_time) 
                        VALUES ('$generated_token','$session_id','$mobile','$validate_user','no','$token_generate_time','$userdata','$token_expire_time')";
                $result_insert = $wpdb->query($sql);
                $sql_p = "INSERT INTO `wp_usermeta` (meta_key, meta_value) VALUES ('_kian_check_user', '$encoded_pass')";
                $result_insert_p = $wpdb->query($sql_p);

                if ($result_insert && $result_insert_p) {
                    // send sms START
                    $url = "https://ippanel.com/services.jspd";
                    $rcpt_nm = array(ltrim($mobile, '0'));
                    $param = array
                    (
                        'uname' => '09386560060',
                        'pass' => 'Meysam0920296734',
                        'from' => '9386560060',
                        'message' => $generated_token,
                        'to' => json_encode($rcpt_nm),
                        'op' => 'send'
                    );

                    $handler = curl_init($url);
                    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
                    $sms_response = curl_exec($handler);
                    if ($sms_response) {
                        $slug = get_option($kianland_core->log_key);
                        wp_redirect(site_url('/' . $slug . '/?num-val=1&user=' . "$validate_user"));
                        exit;
                    }
                    // send sms FINISH
                }
            }
        }


        ?>
        <!--        <span class="kc-error-span --><?php //if (!$error_field) echo 'kc-error-hidden'
        ?><!--">-->
        <!--            --><?php //if ($error_field) echo 'همه فیلد ها باید پر شوند.';
//            echo '<br>';
        ?>
        <!--        </span>-->

        <span class="kc-error-span <?php if (!$error_user_exists) echo 'kc-error-hidden' ?>">
            <?php if ($error_user_exists) echo 'این شماره موبایل قبلا ثبت نام کرده است.';
            echo '<br>'; ?>
        </span>

        <span class="kc-error-span <?php if (!$error_pass) echo 'kc-error-hidden' ?>">
            <?php if ($error_pass) echo 'رمز وارد شده با رمز تکرار شده تطابق ندارد.';
            echo '<br>'; ?>
        </span>

        <span class="kc-error-span <?php if (!$error_email) echo 'kc-error-hidden' ?>">
            <?php if ($error_email) echo 'ایمیل نا معتبر است.';
            echo '<br>'; ?>
        </span>

        <span class="kc-error-span <?php if (!$error_mobile) echo 'kc-error-hidden' ?>">
            <?php if ($error_mobile == 1) {
                echo 'شماره همراه نا معتبر است.';
            } else if ($error_mobile == 2) {
                echo 'شماره همراه یک عدد 11 رقمی است.';
            } ?>
        </span>

        <span class="kc-error-span <?php if (!$error_tel) echo 'kc-error-hidden' ?>">
            <?php if ($error_tel == 1) {
                echo 'تلفن ثابت نا معتبر است.';
            } else if ($error_tel == 2) {
                echo 'تلفن ثابت یک عدد 11 رقمی است.';
            } ?>
        </span>

        <span class="kc-error-span <?php if (!$error_pid) echo 'kc-error-hidden' ?>">
            <?php if ($error_pid == 1) {
                echo 'کد ملی نا معتبر است.';
            } else if ($error_pid == 2) {
                echo 'کد ملی یک عدد 10 رقمی است.';
            } ?>
        </span>

        <span class="kc-error-span <?php if (!$error_zip) echo 'kc-error-hidden' ?>">
            <?php if ($error_zip == 1) {
                echo 'کد پستی نا معتبر است.';
            } else if ($error_zip == 2) {
                echo 'کد پستی یک عدد 10 رقمی است.';
            } ?>
        </span>

        <span class="kc-error-span <?php if (!$error_acc) echo 'kc-error-hidden' ?>">
            <?php if ($error_acc == 1) {
                echo 'شماره ثبت نا معتبر است.';
            } else if ($error_acc == 2) {
                echo 'تعداد ارقام شماره ثبت بیش از حد مجاز است.';
            } ?>
        </span>

        <span class="kc-error-span <?php if (!$error_eco) echo 'kc-error-hidden' ?>">
            <?php if ($error_eco == 1) {
                echo 'کد اقتصادی نا معتبر است.';
            } else if ($error_eco == 2) {
                echo 'تعداد ارقام کد اقتصادی بیش از حد مجاز است.';
            } ?>
        </span>
        <div class="kc-registration-tabs">
            <div class="kc-tab-menu">
                <ul>
                    <li><a href="javascript:void(0)" class="tab-a active-a" data-id="tab1">حقیقی</a></li>
                    <li><a href="javascript:void(0)" class="tab-a" data-id="tab2">حقوقی</a></li>
                </ul>
            </div>
            <div class="kc-tab-items tab-active" data-id="tab1">
                <div class="kc-form-reg">
                    <form action="<?php get_permalink() ?>" method="POST">
                        <div class="kc-registration-grid">
                            <label for="kc_name">
                                <input type="text" class="kc-select-in" id="kc_name" name="kc_form_name"
                                       placeholder="نام">
                            </label>
                            <label for="kc_sname">
                                <input type="text" class="kc-select-in" id="kc_sname" name="kc_form_sname"
                                       placeholder="نام خانوادگی">
                            </label>
                            <label for="kc_mobile">
                                <input type="tel" class="kc-select-in" id="kc_mobile" name="kc_form_mobile"
                                       placeholder="شماره همراه">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_address">
                                <input type="text" class="kc-select-in" id="kc_address" name="kc_form_address"
                                       placeholder="آدرس">
                            </label>
                            <label for="kc_telephone">
                                <input type="text" class="kc-select-in" id="kc_telephone" name="kc_form_telephone"
                                       placeholder="تلفن ثابت">
                            </label>
                            <label for="kc_email">
                                <input type="email" class="kc-select-in" id="kc_email" name="kc_form_email"
                                       placeholder="ایمیل">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_personal_id">
                                <input type="text" class="kc-select-in" id="kc_personal_id" name="kc_form_personal_id"
                                       placeholder="کد ملی">
                            </label>
                            <label for="kc_postal_code">
                                <input type="text" class="kc-select-in" id="kc_postal_code" name="kc_form_postal_code"
                                       placeholder="کد پستی">
                            </label>
                            <label for="kc_password">
                                <input type="text" class="kc-select-in" id="kc_password" name="kc_form_password"
                                       placeholder="کلمه عبور">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_password2">
                                <input type="text" class="kc-select-in" id="kc_password2" name="kc_form_password2"
                                       placeholder="کلمه عبور">
                            </label>
                        </div>
                        <button type="submit" name="kc_submit" value="submit">ثبت اطلاعات</button>
                    </form>
                </div>

            </div>
            <div class="kc-tab-items" data-id="tab2">
                <div class="kc-form-reg">
                    <form action="<?php get_permalink() ?>" method="post">
                        <div class="kc-registration-grid">
                            <label for="kc_name_2">
                                <input type="text" class="kc-select-in-2" id="kc_name_2" name="kc_form_name_2"
                                       placeholder="نام">
                            </label>
                            <label for="kc_sname_2">
                                <input type="text" class="kc-select-in-2" id="kc_sname_2" name="kc_form_sname_2"
                                       placeholder="نام خانوادگی">
                            </label>
                            <label for="kc_mobile_2">
                                <input type="text" class="kc-select-in-2" id="kc_mobile_2" name="kc_form_mobile_2"
                                       placeholder="شماره همراه">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_address_2">
                                <input type="text" class="kc-select-in-2" id="kc_address_2" name="kc_form_address_2"
                                       placeholder="آدرس">
                            </label>
                            <label for="kc_telephone_2">
                                <input type="text" class="kc-select-in-2" id="kc_telephone_2" name="kc_form_telephone_2"
                                       placeholder="تلفن ثابت">
                            </label>
                            <label for="kc_email_2">
                                <input type="email" class="kc-select-in-2" id="kc_email_2" name="kc_form_email_2"
                                       placeholder="ایمیل">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_postal_code_2">
                                <input type="text" class="kc-select-in-2" id="kc_postal_code_2" placeholder="کد پستی"
                                       name="kc_form_postal_code_2">
                            </label>
                            <label for="kc_acc_id_2">
                                <input type="text" class="kc-select-in-2" id="kc_acc_id_2" name="kc_form_acc_id_2"
                                       placeholder="شماره ثبت">
                            </label>
                            <label for="kc_company_name_2">
                                <input type="text" class="kc-select-in-2" id="kc_company_name_2" placeholder="نام شرکت"
                                       name="kc_form_company_name_2">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_eco_id_2">
                                <input type="text" class="kc-select-in-2" id="kc_eco_id_2" name="kc_form_eco_id_2"
                                       placeholder="کد اقتصادی">
                            </label>
                            <label for="kc_password_2">
                                <input type="text" class="kc-select-in-2" id="kc_password_2" name="kc_form_password_2"
                                       placeholder="کلمه عبور">
                            </label>
                            <label for="kc_password2_2">
                                <input type="text" class="kc-select-in-2" id="kc_password2_2" name="kc_form_password2_2"
                                       placeholder="تکرار کلمه عبور">
                            </label>
                        </div>
                        <button type="submit" name="kc_submit_2" value="submit">ثبت اطلاعات</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    protected
    function _content_template()

    {

        ?>
        <div class="kc-registration-tabs">
            <div class="kc-tab-menu">
                <ul>
                    <li><a href="javascript:void(0)" class="tab-a active-a" data-id="tab1">حقیقی</a></li>
                    <li><a href="javascript:void(0)" class="tab-a" data-id="tab2">حقوقی</a></li>
                </ul>
            </div>
            <div class="kc-tab-items tab-active" data-id="tab1">
                <div class="kc-form-reg">
                    <form action="<?php get_permalink() ?>" method="POST">
                        <div class="kc-registration-grid">
                            <label for="kc_name">
                                <input type="text" class="kc-select-in" id="kc_name" name="kc_form_name"
                                       placeholder="نام">
                            </label>
                            <label for="kc_sname">
                                <input type="text" class="kc-select-in" id="kc_sname" name="kc_form_sname"
                                       placeholder="نام خانوادگی">
                            </label>
                            <label for="kc_mobile">
                                <input type="tel" class="kc-select-in" id="kc_mobile" name="kc_form_mobile"
                                       placeholder="شماره همراه">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_address">
                                <input type="text" class="kc-select-in" id="kc_address" name="kc_form_address"
                                       placeholder="آدرس">
                            </label>
                            <label for="kc_telephone">
                                <input type="text" class="kc-select-in" id="kc_telephone" name="kc_form_telephone"
                                       placeholder="تلفن ثابت">
                            </label>
                            <label for="kc_email">
                                <input type="email" class="kc-select-in" id="kc_email" name="kc_form_email"
                                       placeholder="ایمیل">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_personal_id">
                                <input type="text" class="kc-select-in" id="kc_personal_id" name="kc_form_personal_id"
                                       placeholder="کد ملی">
                            </label>
                            <label for="kc_postal_code">
                                <input type="text" class="kc-select-in" id="kc_postal_code" name="kc_form_postal_code"
                                       placeholder="کد پستی">
                            </label>
                            <label for="kc_password">
                                <input type="text" class="kc-select-in" id="kc_password" name="kc_form_password"
                                       placeholder="کلمه عبور">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_password2">
                                <input type="text" class="kc-select-in" id="kc_password2" name="kc_form_password2"
                                       placeholder="کلمه عبور">
                            </label>
                        </div>
                        <button type="submit" name="kc_submit" value="submit">ثبت اطلاعات</button>
                    </form>
                </div>

            </div>
            <div class="kc-tab-items" data-id="tab2">
                <div class="kc-form-reg">
                    <form action="<?php get_permalink() ?>" method="post">
                        <div class="kc-registration-grid">
                            <label for="kc_name_2">
                                <input type="text" class="kc-select-in-2" id="kc_name_2" name="kc_form_name_2"
                                       placeholder="نام">
                            </label>
                            <label for="kc_sname_2">
                                <input type="text" class="kc-select-in-2" id="kc_sname_2" name="kc_form_sname_2"
                                       placeholder="نام خانوادگی">
                            </label>
                            <label for="kc_mobile_2">
                                <input type="text" class="kc-select-in-2" id="kc_mobile_2" name="kc_form_mobile_2"
                                       placeholder="شماره همراه">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_address_2">
                                <input type="text" class="kc-select-in-2" id="kc_address_2" name="kc_form_address_2"
                                       placeholder="آدرس">
                            </label>
                            <label for="kc_telephone_2">
                                <input type="text" class="kc-select-in-2" id="kc_telephone_2" name="kc_form_telephone_2"
                                       placeholder="تلفن ثابت">
                            </label>
                            <label for="kc_email_2">
                                <input type="email" class="kc-select-in-2" id="kc_email_2" name="kc_form_email_2"
                                       placeholder="ایمیل">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_postal_code_2">
                                <input type="text" class="kc-select-in-2" id="kc_postal_code_2" placeholder="کد پستی"
                                       name="kc_form_postal_code_2">
                            </label>
                            <label for="kc_acc_id_2">
                                <input type="text" class="kc-select-in-2" id="kc_acc_id_2" name="kc_form_acc_id_2"
                                       placeholder="شماره ثبت">
                            </label>
                            <label for="kc_company_name_2">
                                <input type="text" class="kc-select-in-2" id="kc_company_name_2" placeholder="نام شرکت"
                                       name="kc_form_company_name_2">
                            </label>
                        </div>
                        <div class="kc-registration-grid">
                            <label for="kc_eco_id_2">
                                <input type="text" class="kc-select-in-2" id="kc_eco_id_2" name="kc_form_eco_id_2"
                                       placeholder="کد اقتصادی">
                            </label>
                            <label for="kc_password_2">
                                <input type="text" class="kc-select-in-2" id="kc_password_2" name="kc_form_password_2"
                                       placeholder="کلمه عبور">
                            </label>
                            <label for="kc_password2_2">
                                <input type="text" class="kc-select-in-2" id="kc_password2_2" name="kc_form_password2_2"
                                       placeholder="تکرار کلمه عبور">
                            </label>
                        </div>
                        <button type="submit" name="kc_submit_2" value="submit">ثبت اطلاعات</button>
                    </form>
                </div>
            </div>
        </div>
        <?php

    }

}















