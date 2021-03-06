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

        wp_enqueue_style('kianlandC_reg_widget_styles', plugin_dir_url(__DIR__) . 'public/css/kianlandC_tab.css', [], null);
        wp_enqueue_script('kianlandC_reg_widget_scripts', plugin_dir_url(__DIR__) . 'admin/js/kianlandC_tab.js', [], null, true);
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
        $form_checker = 0;
        if ($_POST['submit']) {
            $form_checker = 1;
            if ($_POST['name']) {
                $name = $_POST['name'];
                $sname = $_POST['sname'];
                $mobile = $_POST['mobile'];
                $address = $_POST['address'];
                $tel = $_POST['telephone'];
                $email = $_POST['email'];
                $pid = $_POST['personal_id'];
                $zip = $_POST['postal_code'];
                $pass = $_POST['password'];
                // TODO: query to insert

            }
            if ($_POST['name_2']) {
                $name = $_POST['name_2'];
                $sname = $_POST['sname_2'];
                $mobile = $_POST['mobile_2'];
                $address = $_POST['address_2'];
                $tel = $_POST['telephone_2'];
                $email = $_POST['email_2'];
                $zip = $_POST['postal_code_2'];
                $accid = $_POST['acc_id_2'];
                $company = $_POST['company_name_2'];
                $ecoid = $_POST['eco_id_2'];
                $pass = $_POST['password_2'];
                // TODO: query to insert

            }
        }
        ?>
        <div class="kc-tab-menu">
            <ul>
                <li><a href="javascript:void(0)" class="tab-a active-a" data-id="tab1">??????????</a></li>
                <li><a href="javascript:void(0)" class="tab-a" data-id="tab2">??????????</a></li>
            </ul>
        </div>
        <div class="kc-tab-items tab-active" data-id="tab1">
            <form action="" method="post">
                <label for="name">??????</label>
                <input type="text" class="kc-select-in" id="name" name="name">
                <label for="sname">?????? ????????????????</label>
                <input type="text" class="kc-select-in" id="sname" name="sname">
                <label for="mobile">?????????? ??????????</label>
                <input type="text" class="kc-select-in" id="mobile" name="mobile">
                <label for="address">????????</label>
                <input type="text" class="kc-select-in" id="address" name="address">
                <label for="telephone">???????? ????????</label>
                <input type="text" class="kc-select-in" id="telephone" name="telephone">
                <label for="email">??????????</label>
                <input type="text" class="kc-select-in" id="email" name="email">
                <label for="personal_id">???? ??????</label>
                <input type="text" class="kc-select-in" id="personal_id" name="personal_id">
                <label for="postal_code">???? ????????</label>
                <input type="text" class="kc-select-in" id="postal_code" name="postal_code">
                <label for="password">???????? ????????</label>
                <input type="text" class="kc-select-in" id="password" name="password">

                <button type="submit">?????? ??????????????</button>
            </form>

        </div>

        <div class="kc-tab-items" data-id="tab2">
            <form action="" method="post">
                <label for="name_2">??????</label>
                <input type="text" class="kc-select-in-2" id="name_2" name="name_2">
                <label for="sname_2">?????? ????????????????</label>
                <input type="text" class="kc-select-in-2" id="sname_2" name="sname_2">
                <label for="mobile_2">?????????? ??????????</label>
                <input type="text" class="kc-select-in-2" id="mobile_2" name="mobile_2">
                <label for="address_2">????????</label>
                <input type="text" class="kc-select-in-2" id="address_2" name="address_2">
                <label for="telephone_2">???????? ????????</label>
                <input type="text" class="kc-select-in-2" id="telephone_2" name="telephone_2">
                <label for="email_2">??????????</label>
                <input type="text" class="kc-select-in-2" id="email_2" name="email_2">
                <label for="postal_code_2">???? ????????</label>
                <input type="text" class="kc-select-in-2" id="postal_code_2" name="postal_code_2">
                <label for="acc_id_2">?????????? ??????</label>
                <input type="text" class="kc-select-in-2" id="acc_id_2" name="acc_id_2">
                <label for="company_name_2">?????? ????????</label>
                <input type="text" class="kc-select-in-2" id="company_name_2" name="company_name_2">
                <label for="eco_id_2">???? ??????????????</label>
                <input type="text" class="kc-select-in-2" id="eco_id_2" name="eco_id_2">
                <label for="password_2">???????? ????????</label>
                <input type="text" class="kc-select-in-2" id="password_2" name="password_2">

                <button type="submit">?????? ??????????????</button>
            </form>
        </div>
        <?php
    }

    protected function _content_template()
    {
        ?>
        <div class="kc-tab-menu">
            <ul>
                <li><a href="javascript:void(0)" class="tab-a active-a" data-id="tab1">??????????</a></li>
                <li><a href="javascript:void(0)" class="tab-a" data-id="tab2">??????????</a></li>
            </ul>
        </div>
        <div class="kc-tab-items tab-active" data-id="tab1">
            <form action="" method="post">
                <label for="name">??????</label>
                <input type="text" class="kc-select-in" id="name" name="name">
                <label for="sname">?????? ????????????????</label>
                <input type="text" class="kc-select-in" id="sname" name="sname">
                <label for="mobile">?????????? ??????????</label>
                <input type="text" class="kc-select-in" id="mobile" name="mobile">
                <label for="address">????????</label>
                <input type="text" class="kc-select-in" id="address" name="address">
                <label for="telephone">???????? ????????</label>
                <input type="text" class="kc-select-in" id="telephone" name="telephone">
                <label for="email">??????????</label>
                <input type="text" class="kc-select-in" id="email" name="email">
                <label for="personal_id">???? ??????</label>
                <input type="text" class="kc-select-in" id="personal_id" name="personal_id">
                <label for="postal_code">???? ????????</label>
                <input type="text" class="kc-select-in" id="postal_code" name="postal_code">
                <label for="password">???????? ????????</label>
                <input type="text" class="kc-select-in" id="password" name="password">

                <button type="submit">?????? ??????????????</button>
            </form>

        </div>

        <div class="kc-tab-items" data-id="tab2">
            <form action="" method="post">
                <label for="name_2">??????</label>
                <input type="text" class="kc-select-in-2" id="name_2" name="name_2">
                <label for="sname_2">?????? ????????????????</label>
                <input type="text" class="kc-select-in-2" id="sname_2" name="sname_2">
                <label for="mobile_2">?????????? ??????????</label>
                <input type="text" class="kc-select-in-2" id="mobile_2" name="mobile_2">
                <label for="address_2">????????</label>
                <input type="text" class="kc-select-in-2" id="address_2" name="address_2">
                <label for="telephone_2">???????? ????????</label>
                <input type="text" class="kc-select-in-2" id="telephone_2" name="telephone_2">
                <label for="email_2">??????????</label>
                <input type="text" class="kc-select-in-2" id="email_2" name="email_2">
                <label for="postal_code_2">???? ????????</label>
                <input type="text" class="kc-select-in-2" id="postal_code_2" name="postal_code_2">
                <label for="acc_id_2">?????????? ??????</label>
                <input type="text" class="kc-select-in-2" id="acc_id_2" name="acc_id_2">
                <label for="company_name_2">?????? ????????</label>
                <input type="text" class="kc-select-in-2" id="company_name_2" name="company_name_2">
                <label for="eco_id_2">???? ??????????????</label>
                <input type="text" class="kc-select-in-2" id="eco_id_2" name="eco_id_2">
                <label for="password_2">???????? ????????</label>
                <input type="text" class="kc-select-in-2" id="password_2" name="password_2">

                <button type="submit">?????? ??????????????</button>
            </form>
        </div>
        <?php
    }
}







