<?php
if (isset($_POST['KianlandC_settings_submit'])) {

    global $kianland_core;
    global $wpdb;
    $reg = $_POST['kc_settings_registration'];
    $log = $_POST['kc_settings_login'];
    $sql_reg = "SELECT * FROM `wp_options` WHERE option_name='" . $kianland_core->reg_key . "'";
    $sql_log = "SELECT * FROM `wp_options` WHERE option_name='" . $kianland_core->log_key . "'";
    $result_reg = $wpdb->get_results($sql_reg);
    $result_log = $wpdb->get_results($sql_log);
    $errors_ = [];
    if ($reg != '' && $log != '') {
        if (!$result_reg) {
            $temp_res_reg = add_option($kianland_core->reg_key, $reg);
            if (!$temp_res_reg) $errors_['insert_reg'] = 'ثبت نامک صفحه ثبت نام انجام نشد.';
        } else {
            $temp_reg = $result_reg[0]->option_value;
            if ($temp_reg != $reg) {
                $temp_res_reg = update_option($kianland_core->reg_key, $reg);
                if (!$temp_res_reg) $errors_['update_reg'] = 'ویرایش نامک صفحه ثبت نام انجام نشد.';
            }
        }
        if (!$result_log) {
            $temp_res_log = add_option($kianland_core->log_key, $log);
            if (!$temp_res_log) $errors_['insert_log'] = 'ثبت نامک صفحه ورود انجام نشد.';
        } else {
            $temp_log = $result_log[0]->option_value;
            if ($temp_log != $log) {
                $temp_res_log = update_option($kianland_core->log_key, $log);
                if (!$temp_res_log) $errors_['update_log'] = 'ویرایش نامک صفحه ورود انجام نشد.';
            }
        }
    } else {
        $errors_['empty_field'] = 'فیلد ها ضروری هستند';
    }
}

?>
    <div class="kc-wrapper">
        <h1 class="kc-header">Settings Page</h1>
        <?php
        global $kianland_core;
        $registration = get_option($kianland_core->reg_key);
        $login = get_option($kianland_core->log_key);
        if ($errors_) {
            foreach ($errors_ as $key => $value) {
                ?>
                <span class="kc-error-span"><?= $value ?></span>
                <?php
            }
        } elseif (!$errors_ && isset($_POST['KianlandC_settings_submit'])) {
            ?>
            <span class="kc-success-span">اطلاعات با موفقیت ثبت شد</span>
            <?php
        }
        ?>
        <form action="<?= get_permalink() ?>" method="POST">
            <label for="kc_settings_registration">نامک صفحه ثبت نام</label>
            <input type="text" id="kc_settings_registration" name="kc_settings_registration"
                   value="<?php if ($registration) echo $registration ?>">
            <label for="kc_settings_login">نامک صفحه ورود</label>
            <input type="text" id="kc_settings_login" name="kc_settings_login" value="<?php if ($login) echo $login ?>">
            <button type="submit" class="kc-button" name="KianlandC_settings_submit">ثبت اطلاعات</button>
        </form>
    </div>

<?php

