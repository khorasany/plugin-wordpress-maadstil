<?php


namespace KianlandCore\Widgets;


use Elementor\Widget_Base;

use Elementor\Controls_Manager;

use KianlandCore\Widgets\KianlandC_Devices\Kianland_Core;


if (!defined('ABSPATH')) exit;


class KianlandC_Validation extends Widget_Base

{

    public function __construct($data = [], $args = null)

    {

        parent::__construct($data, $args);

        wp_enqueue_style('kianlandC_validation_style', plugin_dir_url(__DIR__) . 'public/css/validation.css', [], 47);

        wp_enqueue_script('kianlandC_validation_script', plugin_dir_url(__DIR__) . 'public/js/validation.js', [], 11, true);

    }


    function get_style_depends()

    {

        ['kianlandC_validation_style'];

    }


    function get_script_depends()

    {

        ['kianlandC_validation_script'];

    }


    function get_name()

    {

        return 'kianlandC_validation';

    }


    function get_title()

    {

        return 'Validation';

    }


    function get_icon()

    {

        return 'eicon-arrow-left';

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

        global $wpdb;

        global $kianland_core;

        if (isset($_SESSION['mobile'])) {

            $validate_user = $_GET['user'];

            $mobile = $_SESSION['mobile'];

            $form_validate = [];

            $userdata = $wpdb->get_row("SELECT * FROM `wp_token_validate` WHERE get_user='$validate_user'");
            $user_p = $wpdb->get_row("SELECT * FROM `wp_usermeta` WHERE meta_key='_kian_check_user'");
            $user_p = base64_decode($user_p->meta_value);

            $session_id_db = $userdata->session_id;

            $token_used = $userdata->token_used;

            $time_expire = $userdata->expire_time;
            $time_now = time();
            if ($time_expire < $time_now) $form_validate['expired_authentication'] = 'token timeout';

            if ($session_id_db != session_id()) $form_validate['session_authentication'] = 'session id does not match';

            if ($token_used != 'no') $form_validate['token_authentication'] = 'used token';

            if ($mobile != $userdata->mobile) $form_validate['mobile_authentication'] = 'mobile session does not match mobile db';

        }


        if (isset($_POST['kc_mobile_validate_submit'])) {

            $code = $_POST['kc_mobile_validate_token'];

            $pass = $_POST['kc_mobile_validate_password'];

            $user_p = $wpdb->get_row("SELECT * FROM `wp_usermeta` WHERE meta_key='_kian_check_user'");
            $user_p = $user_p->meta_value;
            $wpdb->query("DELETE FROM `wp_usermeta` WHERE meta_key='_kian_check_user'");

            $token = $userdata->generated_token;

            $time_expire = $userdata->expire_time;

            $time_now = time();

            $user_validate = [];

            if ($code != $token) $user_validate['wrong_token'] = 'کد غلط است';

            if ($time_expire < $time_now) $user_validate['expired_token'] = 'زمان کد تمام شده است';


            if (!$user_validate) {

                $udata = $userdata->userdata;

                $udata = unserialize($udata, ['allowed_classes' => true]);

                $result_token_used = $wpdb->update('wp_token_validate', ['token_used' => 'yes'], ['mobile' => $mobile]);


                if ($udata['mode'] == 'haqiqi' && $result_token_used) {


                    // sabt haqiqi START


                    $insert_user_data = [

                        'user_pass' => $udata['pass'],

                        'user_login' => $udata['mobile'],

                        'user_nicename' => $udata['mobile'],

                        'user_email' => $udata['email'],

                        'display_name' => $udata['name'] . ' ' . $udata['sname'],

                        'nickname' => $udata['name'] . ' ' . $udata['sname'],

                        'first_name' => $udata['name'],

                        'last_name' => $udata['sname'],

                        'role' => 'subscriber'

                    ];


                    $result = wp_insert_user($insert_user_data);

                    if (is_wp_error($result)) {

//                        echo $result->get_error_message();

                    } else {

                        $user = get_user_by('id', $result);

                        add_user_meta($user->ID, 'address', $udata['address']);

                        add_user_meta($user->ID, 'telephone', $udata['telephone']);

                        add_user_meta($user->ID, 'personal_id', $udata['pid']);

                        add_user_meta($user->ID, 'postal_code', $udata['zip']);

                        add_user_meta($user->ID, '_kian_check_user_info', $user_p);

                        $result = wp_signon(['user_login' => $mobile, 'user_password' => $pass, 'remember' => false]);

                        if (is_wp_error($result)) {
                            echo $result->get_error_message();
                        }

                        wp_redirect(site_url('/my-account/'));

                        exit;

                    }


                    // sabt haqiqi FINISH


                } elseif ($udata['mode'] == 'hoquqi' && $result_token_used) {


                    // sabt hoquqi START


                    $insert_user_data = [

                        'user_pass' => $udata['pass'],

                        'user_login' => $udata['mobile'],

                        'user_nicename' => $udata['mobile'],

                        'user_email' => $udata['email'],

                        'display_name' => $udata['name'] . ' ' . $udata['sname'],

                        'nickname' => $udata['name'] . ' ' . $udata['sname'],

                        'first_name' => $udata['name'],

                        'last_name' => $udata['sname'],

                        'role' => 'subscriber'

                    ];


                    $result = wp_insert_user($insert_user_data);

                    if (is_wp_error($result)) {

                        $error = $result->get_error_message();

                    } else {

                        $user = get_user_by('id', $result);

                        add_user_meta($user->ID, 'address', $udata['address']);

                        add_user_meta($user->ID, 'telephone', $udata['telephone']);

                        add_user_meta($user->ID, 'postal_code', $udata['zip']);

                        add_user_meta($user->ID, 'account_id', $udata['acc_id']);

                        add_user_meta($user->ID, 'company_name', $udata['company_name']);

                        add_user_meta($user->ID, 'economy_id', $udata['eco_id']);

                        add_user_meta($user->ID, '_kian_check_user_info', $user_p);

                        wp_signon(['user_login' => $mobile, 'user_password' => $pass, 'remember' => false]);

                        wp_redirect(site_url('/my-account/'));

                        exit;

                    }


                    // sabt hoquqi FINISH


                }

            }

        }


        if (isset($_POST['kc_login_submit'])) {

            $username = $_POST['kc_login_username'];

            $password = $_POST['kc_login_password'];

            wp_signon(['user_login' => $username, 'user_password' => $password, 'remember' => false]);

            wp_redirect(site_url('/my-account/'));

            exit;

        }


        if (isset($_POST['kc_login_with_token_submit'])) {

            $username = $_POST['kc_login_with_token_username'];

            $code = $_POST['kc_login_with_token_this'];

            $user_validation = $_SESSION['user'];

            unset($_SESSION['user']);

            $userdata = $wpdb->get_row("SELECT * FROM `wp_token_validate` WHERE get_user='$user_validation'");

            $user = get_user_by('login', $username);

            $user_p = get_user_meta($user->ID, '_kian_check_user_info', true);

            $user_p = base64_decode($user_p);

            $session_id = $userdata->session_id;

            $check_session_id = session_id();

            $check_username = $userdata->mobile;

            $token_used = $userdata->token_used;

            $token_got = $userdata->generated_token;

            $time_expire = $userdata->expire_time;

            $time_now = time();

            if ($time_expire < $time_now) $token_timeout = 'زمان کد تمام شده است';


            if ($username == $check_username && $token_used == 'no' && $session_id == $check_session_id && $code == $token_got && !$token_timeout) {

                $wpdb->get_row("UPDATE `wp_token_validate` SET token_used='yes' WHERE get_user='$user_validation'");

                $result = wp_signon(['user_login' => $user->user_login, 'user_password' => $user_p, 'remember' => false]);

                wp_redirect(site_url('/my-account/'));

                exit;

            } else {
                echo 'فرم تایید نشد... لطفا صفحه را همگام سازی کنید!';
                die();
            }

        }


        if (isset($_POST['kc_login_send_token'])) {

            if (isset($_POST['kc_login_with_token_username'])) {

                $username = $_POST['kc_login_with_token_username'];

                if (strlen($username) == 11 && preg_match("/09[0-9]{9}/", $username) && get_user_by('login', $username)) {

                    global $wpdb;

                    $session_id = session_id();

                    $generated_token = random_int(10000, 99999);

                    $user_validation = random_int(10000, 99999);

                    $_SESSION['user'] = $user_validation;

                    $token_generate_time = time();

                    $token_expire_time = $token_generate_time + 60;

                    $sql = "INSERT INTO `wp_token_validate` (generated_token,session_id,mobile,get_user,token_used,generate_time,userdata,expire_time) 

                        VALUES ('$generated_token','$session_id','$username','$user_validation','no','$token_generate_time',NULL,'$token_expire_time')";

                    $result_insert = $wpdb->query($sql);

                    if ($result_insert) {

                        // send sms START

                        $url = "https://ippanel.com/services.jspd";

                        $rcpt_nm = [ltrim($username, '0')];

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

                        // send sms FINISH

                    }

                } else {

                    $error_mobile = 'شماره همراه صحیح نمی باشد';

                }


            } else {

                $error_username_not_found = 'لطفا شماره همراه خودرا وارد کنید';

            }

        }


        if ($error_username_not_found) echo '<span class="kc-error-span">' . $error_username_not_found . '</span>';

        if ($error_mobile) echo '<span class="kc-error-span">' . $error_mobile . '</span>';

        if (strpos($_SERVER['REQUEST_URI'], 'num-val=1')) {

            if (!$form_validate) {

                ?>

                <div class="kc-form">

                    <form method="post">

                        <div class="field-wrap">

                            <label for="kc_mobile_validate_token">

                                رمز یکبار مصرف<span class="req">*</span>

                            </label>

                            <input type="text" required autocomplete="off" id="kc_mobile_validate_token"

                                   name="kc_mobile_validate_token">

                        </div>
                        <div class="kc-hidden-div">
                            <input type="text" required autocomplete="off" id="kc_mobile_validate_password"

                                   name="kc_mobile_validate_password" value="<?= $user_p ?>">
                        </div>
                        <button type="submit" name="kc_mobile_validate_submit" class="button button-block">تایید ثبت

                            نام

                        </button>

                    </form>

                </div>

                <?php

            } else {

                ?>

                <a href="<?= site_url('/my-account/') ?>" class="kc-form-error">فرم از طرف سرور تایید نشد، دوباره اقدام

                    به ثبت نام کنید.</a>

                <?php

            }

        } else {

            ?>

            <div class="kc-form">

                <ul class="tab-group">

                    <li class="tab"><a href="#login">ورود با رمز عبور</a></li>

                    <li class="tab active"><a href="#signup">ورود با رمز یکبار مصرف</a></li>

                </ul>

                <div class="tab-content">

                    <div id="signup">

                        <form method="post">

                            <div class="field-wrap">

                                <label for="kc_login_with_token_username">

                                    نام کاربری یا شماره موبایل<span class="req">*<span>

                                </label>

                                <input type="text" name="kc_login_with_token_username"
                                       value="<?= $_POST['kc_login_with_token_username'] ?? "" ?>"
                                       id="kc_login_with_token_username"

                                       autocomplete="off">

                            </div>

                            <?php
                            if (isset($_POST['kc_login_send_token'])):
                                ?>
                                <div class="field-wrap">
                                    <label for="kc_login_with_token_this">
                                        رمز یکبار مصرف<span class="req">*</span>
                                    </label>
                                    <input type="text" name="kc_login_with_token_this" id="kc_login_with_token_this"
                                           autocomplete="off">
                                </div>
                            <?php
                            endif;
                            ?>
                            <?php
                            if (isset($_POST['kc_login_send_token'])):
                                ?>
                                <div class="return">
                                    <a href="<?= site_url('/' . get_option($kianland_core->log_key)) ?>">ویرایش
                                        شماره</a>
                                </div>

                                <button type="submit" name="kc_login_with_token_submit"
                                        class="button button-block button-o button-small">ورود

                                </button>
                                <div class="kc-hidden-div">
                                    <button type="submit" name="kc_login_send_token" id="kc_resend_token">ارسال دوباره
                                        رمز یکبار مصرف
                                    </button>
                                </div>

                                <div class="kc-resend-button">
                                    <div class="kc-token-timer">

                                    </div>
                                </div>

                            <?php
                            endif;
                            ?>
                            <?php
                            if (!isset($_POST['kc_login_send_token'])):
                                ?>
                                <button type="submit" name="kc_login_send_token"

                                        class="button button-block button-small">ارسال رمز یکبار مصرف

                                </button>
                            <?php
                            endif;
                            ?>
                        </form>
                    </div>

                    <div id="login">

                        <form method="post">

                            <div class="field-wrap">

                                <label for="kc_login_username">

                                    نام کاربری یا شماره همراه<span class="req">*</span>

                                </label>

                                <input type="text" autocomplete="off" id="kc_login_username"

                                       name="kc_login_username">

                            </div>

                            <div class="field-wrap">

                                <label for="kc_login_password">

                                    رمز عبور<span class="req">*</span>

                                </label>

                                <input type="password" autocomplete="off" id="kc_login_password"

                                       name="kc_login_password">

                            </div>

                            <button type="submit" name="kc_login_submit" class="button button-block">ورود</button>

                        </form>

                    </div>

                </div>
                <div class="forgot">
                    <a href="<?= site_url('/' . get_option($kianland_core->reg_key)) ?>">عضو نیستید؟ ثبت نام کنید.</a>
                </div>
            </div>


            <?php

        }

    }


    protected function _content_template()

    {

        ?>

        <div class="kc-form">

            <ul class="tab-group">

                <li class="tab"><a href="#login">ورود با رمز عبور</a></li>

                <li class="tab active"><a href="#signup">ورود با رمز یکبار مصرف</a></li>

            </ul>

            <div class="tab-content">

                <div id="signup">

                    <form method="post">

                        <div class="field-wrap">

                            <label for="kc_login_with_token_username">

                                نام کاربری یا شماره موبایل<span class="req">*<span>

                            </label>

                            <input type="text" name="kc_login_with_token_username" id="kc_login_with_token_username"
                                   autocomplete="off">

                        </div>

                        <div class="field-wrap">
                            <label for="kc_login_with_token_this">
                                رمز یکبار مصرف<span class="req">*</span>
                            </label>
                            <input type="text" name="kc_login_with_token_this" id="kc_login_with_token_this"
                                   autocomplete="off">
                        </div>

                        <div class="return">
                            <a href="">ویرایش
                                شماره</a>
                        </div>
                        <button type="submit" name="kc_login_with_token_submit"
                                class="button button-block button-o">ورود

                        </button>
                    </form>
                </div>

                <div id="login">

                    <form method="post">

                        <div class="field-wrap">

                            <label for="kc_login_username">

                                نام کاربری یا شماره همراه<span class="req">*</span>

                            </label>

                            <input type="text" autocomplete="off" id="kc_login_username"

                                   name="kc_login_username">

                        </div>

                        <div class="field-wrap">

                            <label for="kc_login_password">

                                رمز عبور<span class="req">*</span>

                            </label>

                            <input type="password" autocomplete="off" id="kc_login_password"

                                   name="kc_login_password">

                        </div>

                        <button type="submit" name="kc_login_submit" class="button button-block">ورود</button>

                    </form>

                </div>

            </div>
            <div class="forgot">
                <a href="">عضو نیستید؟ ثبت نام کنید.</a>
            </div>
        </div>

        <?php

    }

}