<?php

require_once(plugin_dir_path(__DIR__) . '../../../../wp-load.php');
global $wpdb;

if(!isset($_POST['kc_id_sender']) && !isset($_POST['kc_update_device_submit'])){
    wp_redirect(site_url('/wp-admin/admin.php?page=prweb_devices'));
}

if (isset($_POST['kc_id_sender'])) {
    $id = $_POST['kc_id_sender'];
    $device = $wpdb->get_row("SELECT * FROM `wp_prweb_devices` WHERE id='$id'");
    $array = unserialize($device->device_data, ['allowed_classes' => false]);
}

if (isset($_POST['kc_update_device_submit'])) {
    $dvc_array = [];

    $dvc_id = $_POST['kc_dvc_id'];
    $exploded_date = readyDateForConvert($_POST['kc_dvc_date']);
    $dvc_date = strtotime(shamsiToChristian($exploded_date[2], $exploded_date[1], $exploded_date[0], 'yes'));
    $dvc_owner = $_POST['kc_dvc_name'];
    $exploded_date = readyDateForConvert($_POST['kc_dvc_build_date']);
    $dvc_build_date = strtotime(shamsiToChristian($exploded_date[2], $exploded_date[1], $exploded_date[0], 'yes'));
    $dvc_spec = $_POST['kc_dvc_spec'];
    $dvc_model = $_POST['kc_dvc_model'];

    if ($dvc_id == '' && $dvc_date == '' && $dvc_owner == '') $error_fields = 'فیلد ها ضروری هستند';

    foreach ($_POST['dvc_data'] as $key => $value) {
        $dvc_array[$key] = $value;
    }
    $dvc_array = serialize($dvc_array);


    if (!$_FILES['kc_dvc_image']['name'] == '') {
        $dvc_error = [];

        $dvc_image = $_FILES['kc_dvc_image'];

        $wordpress_upload_dir = wp_upload_dir();
        $i = 1;

        $new_file_path = $wordpress_upload_dir['path'] . '/' . $dvc_image['name'];
        $new_file_mime = mime_content_type($dvc_image['tmp_name']);

        if ($dvc_image['size'] == 0)
            $dvc_error['not_selected'] = 'عکس انتخاب نشده است';

        if ($dvc_image['error'])
            $dvc_error['error_found'] = $dvc_image['error'];

        if ($dvc_image['size'] > wp_max_upload_size())
            $dvc_error['image_too_big'] = 'حجم عکس بالاست';

        if (!in_array($new_file_mime, get_allowed_mime_types()))
            $dvc_error['mime_type'] = 'اجازه این نوع آپلود را ندارید';

        while (file_exists($new_file_path)) {
            $i++;
            $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $dvc_image['name'];
        }

        if (!$error_fields && !$dvc_error && move_uploaded_file($dvc_image['tmp_name'], $new_file_path)) {
            $dvc_image_final = $wordpress_upload_dir['url'] . '/' . $dvc_image['name'];
            $sql = "UPDATE `wp_prweb_devices` SET device_id='$dvc_id',device_spec='$dvc_spec',device_model='$dvc_model',
                device_image='$dvc_image_final',device_owner='$dvc_owner',device_data='$dvc_array',device_build_date='$dvc_build_date',
                device_expire_date='$dvc_date' WHERE device_id='$dvc_id'";
            $result = $wpdb->query($sql);
            if ($result) {
                $success_msg = 'ویرایش دستگاه با موفقیت انجام شد';
            }
        }

        $device = $wpdb->get_row("SELECT * FROM `wp_prweb_devices` WHERE device_id='$dvc_id'");
        $array = unserialize($device->device_data, ['allowed_classes' => false]);
    } else {

        if (!$error_fields) {
            $sql = "UPDATE `wp_prweb_devices` SET device_id='$dvc_id',device_spec='$dvc_spec',device_model='$dvc_model',
                    device_owner='$dvc_owner',device_data='$dvc_array',device_build_date='$dvc_build_date',
                    device_expire_date='$dvc_date' WHERE device_id='$dvc_id'";
            $result = $wpdb->query($sql);
            if ($result) {
                $success_msg = 'ویرایش دستگاه با موفقیت انجام شد';
            }
        }

        $device = $wpdb->get_row("SELECT * FROM `wp_prweb_devices` WHERE device_id='$dvc_id'");
        $array = unserialize($device->device_data, ['allowed_classes' => false]);
    }
}


?>


<div class="kc-wrapper">
    <div class="container">
        <h1>اضافه کردن دستگاه جدید</h1>
        <?php if ($success_msg) echo '<span class="kc-success-span">' . $success_msg . '</span>' ?>
        <?php if ($error_fields) echo '<span class="kc-error-span">' . $error_fields . '</span>' ?>
        <?php
        if ($dvc_error) {
            foreach ($dvc_error as $key => $value) {
                if ($value == '4') continue;
                if ($key == 'mime_type' && $dvc_error['not_selected']) continue;
                echo '<span class="kc-error-span">' . $value . '</span>';
            }
        }
        ?>
        <div id="kc-image-container">
            <img src="<?= $device->device_image ?>" class="kc-add-dvc-img" alt="">
        </div>
        <button class="kc-button-2 kc-button-block" onclick="choose_file()" id="this_is_accordion_needed_page">انتخاب
            عکس دستگاه
        </button>
        <form method="post" enctype="multipart/form-data" action="">
            <input type="file" name="kc_dvc_image" class="hidden" id="file_input">
            <div class="kc-main-device-info">
                <label for="kc_dvc_id">شماره سریال دستگاه
                    <input type="text" name="kc_dvc_id" id="kc_dvc_id" class="kc-input"
                           value="<?= $device->device_id ?>">
                </label>
                <label for="kc_dvc_date">تاریخ اتمام گارانتی
                    <input type="text" name="kc_dvc_date" id="kc_dvc_date" class="kc-input"
                           value="<?= timeStampToShamsiWithoutTime($device->device_expire_date) ?>">
                </label>
                <label for="kc_dvc_build_date">تاریخ ساخت دستگاه
                    <input type="text" name="kc_dvc_build_date" id="kc_dvc_build_date" class="kc-input"
                           value="<?= timeStampToShamsiWithoutTime($device->device_build_date) ?>">
                </label>
            </div>
            <div class="kc-main-device-info">
                <label for="kc_dvc_spec">نوع دستگاه
                    <input type="text" name="kc_dvc_spec" id="kc_dvc_spec" class="kc-input"
                           value="<?= $device->device_spec ?>">
                </label>
                <label for="kc_dvc_model">مدل دستگاه
                    <input type="text" name="kc_dvc_model" id="kc_dvc_model" class="kc-input"
                           value="<?= $device->device_model ?>">
                </label>
                <label for="kc_dvc_name">نام خریدار
                    <input type="text" name="kc_dvc_name" id="kc_dvc_name" class="kc-input"
                           value="<?= $device->device_owner ?>">
                </label>
            </div>

            <div class="device_data">

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    سرعت تولید دستگاه
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">سرعت پرکردن قوطی یک لیتری
                        <input type="text" name="dvc_data[0]" class="kc-input" value="<?= $array[0] ?>">
                    </label>
                    <label for="" class="kc-input-label">سرعت پر کردن قوطی ۲۵۰ سی سی
                        <input type="text" name="dvc_data[1]" class="kc-input" value="<?= $array[1] ?>">
                    </label>
                    <label for="" class="kc-input-label">سرعت پرکردن چهار لیتری
                        <input type="text" name="dvc_data[2]" class="kc-input" value="<?= $array[2] ?>">
                    </label>
                    <label for="" class="kc-input-label">میانگین زمان ساخت هر مخزن مایع ظرفشویی
                        <input type="text" name="dvc_data[3]" class="kc-input" value="<?= $array[3] ?>">
                    </label>
                    <label for="" class="kc-input-label">میانگین زمان ساخت هر مخزن مایع دستشویی
                        <input type="text" name="dvc_data[4]" class="kc-input" value="<?= $array[4] ?>">
                    </label>
                    <label for="" class="kc-input-label">میانگین زمان ساخت هر مخزن شامپو سر
                        <input type="text" name="dvc_data[5]" class="kc-input" value="<?= $array[5] ?>">
                    </label>
                    <label for="" class="kc-input-label">میانگین زمان ساخت هر مخزن مایع سفید کننده
                        <input type="text" name="dvc_data[6]" class="kc-input" value="<?= $array[6] ?>">
                    </label>
                    <label for="" class="kc-input-label">میانگین زمان ساخت هر مخزن مایع سفید کننده غلیظ
                        <input type="text" name="dvc_data[7]" class="kc-input" value="<?= $array[7] ?>">
                    </label>
                    <label for="" class="kc-input-label">میانگین زمان ساخت هر مخزن مایع جرمگیر
                        <input type="text" name="dvc_data[8]" class="kc-input" value="<?= $array[8] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[9]" id="" cols="30" rows="10"><?= $array[9] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    دامنه اشکال قابل تزریق
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">دامنه تزریق بر اساس حجم قوطی
                        <input type="text" name="dvc_data[10]" class="kc-input" value="<?= $array[10] ?>">
                    </label>
                    <label for="" class="kc-input-label">دامنه تزریق بر اساس شکل قوطی
                        <input type="text" name="dvc_data[11]" class="kc-input" value="<?= $array[11] ?>">
                    </label>
                    <label for="" class="kc-input-label">قابلیت تنظیم اتوماتیک بر روی اشکال مختلف
                        <input type="text" name="dvc_data[12]" class="kc-input" value="<?= $array[12] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[13]" id="" cols="30" rows="10"><?= $array[13] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    ابعاد دستگاه
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">طول
                        <input type="text" name="dvc_data[14]" class="kc-input" value="<?= $array[14] ?>">
                    </label>
                    <label for="" class="kc-input-label">عرض
                        <input type="text" name="dvc_data[15]" class="kc-input" value="<?= $array[15] ?>">
                    </label>
                    <label for="" class="kc-input-label">ارتفاع
                        <input type="text" name="dvc_data[16]" class="kc-input" value="<?= $array[16] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[17]" id="" cols="30" rows="10"><?= $array[17] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    جنس قطعات دستگاه
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">جنس شاسی
                        <input type="text" name="dvc_data[18]" class="kc-input" value="<?= $array[18] ?>">
                    </label>
                    <label for="" class="kc-input-label">جنس بدنه
                        <input type="text" name="dvc_data[19]" class="kc-input" value="<?= $array[19] ?>">
                    </label>
                    <label for="" class="kc-input-label">جنس اتصالات و پیچ و مهره ها
                        <input type="text" name="dvc_data[20]" class="kc-input" value="<?= $array[20] ?>">
                    </label>
                    <label for="" class="kc-input-label">جنس مسیر انتقال مایع در دستگاه
                        <input type="text" name="dvc_data[21]" class="kc-input" value="<?= $array[21] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[22]" id="" cols="30" rows="10"><?= $array[22] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    سیستم تزریق
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">سیستم کنترل جریان مایع
                        <input type="text" name="dvc_data[23]" class="kc-input" value="<?= $array[23] ?>">
                    </label>
                    <label for="" class="kc-input-label">سیستم تزریق مایعات غلیظ
                        <input type="text" name="dvc_data[24]" class="kc-input" value="<?= $array[24] ?>">
                    </label>
                    <label for="" class="kc-input-label">سیستم تزریق مایعات رقیق
                        <input type="text" name="dvc_data[25]" class="kc-input" value="<?= $array[25] ?>">
                    </label>
                    <label for="" class="kc-input-label">دامنه قوطی های قابل تزریق
                        <input type="text" name="dvc_data[26]" class="kc-input" value="<?= $array[26] ?>">
                    </label>
                    <label for="" class="kc-input-label">دقت تزریق مایع
                        <input type="text" name="dvc_data[27]" class="kc-input" value="<?= $array[27] ?>">
                    </label>
                    <label for="" class="kc-input-label">نوع موتور سیستم تزریق
                        <input type="text" name="dvc_data[28]" class="kc-input" value="<?= $array[28] ?>">
                    </label>
                    <label for="" class="kc-input-label">تعداد موتور سیستم تزریق
                        <input type="text" name="dvc_data[29]" class="kc-input" value="<?= $array[29] ?>">
                    </label>
                    <label for="" class="kc-input-label">سیستم ضد کف مایع
                        <input type="text" name="dvc_data[30]" class="kc-input" value="<?= $array[30] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[31]" id="" cols="30" rows="10"><?= $array[31] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    سیستم برقی
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">پی ال سی
                        <input type="text" name="dvc_data[32]" class="kc-input" value="<?= $array[32] ?>">
                    </label>
                    <label for="" class="kc-input-label">مانیتور پی ال سی تاچ اسکرین
                        <input type="text" name="dvc_data[33]" class="kc-input" value="<?= $array[33] ?>">
                    </label>
                    <label for="" class="kc-input-label">اینورتر
                        <input type="text" name="dvc_data[34]" class="kc-input" value="<?= $array[34] ?>">
                    </label>
                    <label for="" class="kc-input-label">محافظ جان
                        <input type="text" name="dvc_data[35]" class="kc-input" value="<?= $array[35] ?>">
                    </label>
                    <label for="" class="kc-input-label">کنترل بار
                        <input type="text" name="dvc_data[36]" class="kc-input" value="<?= $array[36] ?>">
                    </label>
                    <label for="" class="kc-input-label">کنترل فاز
                        <input type="text" name="dvc_data[37]" class="kc-input" value="<?= $array[37] ?>">
                    </label>
                    <label for="" class="kc-input-label">استپرموتور
                        <input type="text" name="dvc_data[38]" class="kc-input" value="<?= $array[38] ?>">
                    </label>
                    <label for="" class="kc-input-label">تعداد استپرموتور
                        <input type="text" name="dvc_data[39]" class="kc-input" value="<?= $array[39] ?>">
                    </label>
                    <label for="" class="kc-input-label">نوع جریان مصرفی
                        <input type="text" name="dvc_data[40]" class="kc-input" value="<?= $array[40] ?>">
                    </label>
                    <label for="" class="kc-input-label">خنک کننده برد
                        <input type="text" name="dvc_data[41]" class="kc-input" value="<?= $array[41] ?>">
                    </label>
                    <label for="" class="kc-input-label">نور پردازی تابلو برق و نازل ها
                        <input type="text" name="dvc_data[42]" class="kc-input" value="<?= $array[42] ?>">
                    </label>
                    <label for="" class="kc-input-label">میزان توان مصرفی
                        <input type="text" name="dvc_data[43]" class="kc-input" value="<?= $array[43] ?>">
                    </label>
                    <label for="" class="kc-input-label">خنک کننده موتور
                        <input type="text" name="dvc_data[44]" class="kc-input" value="<?= $array[44] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[45]" id="" cols="30" rows="10"><?= $array[45] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    آپشن های ویژه
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">سیستم عیب یاب هوشمند
                        <input type="text" name="dvc_data[46]" class="kc-input" value="<?= $array[46] ?>">
                    </label>
                    <label for="" class="kc-input-label">کنترل با موبایل و لپ تاپ
                        <input type="text" name="dvc_data[47]" class="kc-input" value="<?= $array[47] ?>">
                    </label>
                    <label for="" class="kc-input-label">تونل ضد عفونی کننده
                        <input type="text" name="dvc_data[48]" class="kc-input" value="<?= $array[48] ?>">
                    </label>
                    <label for="" class="kc-input-label">سیستم اعلام سرویس دستگاه
                        <input type="text" name="dvc_data[49]" class="kc-input" value="<?= $array[49] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[50]" id="" cols="30" rows="10"><?= $array[50] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    مخزن
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">ظرفیت مخزن
                        <input type="text" name="dvc_data[51]" class="kc-input" value="<?= $array[51] ?>">
                    </label>
                    <label for="" class="kc-input-label">تعداد مخزن
                        <input type="text" name="dvc_data[52]" class="kc-input" value="<?= $array[52] ?>">
                    </label>
                    <label for="" class="kc-input-label">نوع کنترل سطح مخزن
                        <input type="text" name="dvc_data[53]" class="kc-input" value="<?= $array[53] ?>">
                    </label>
                    <label for="" class="kc-input-label">سیستم کنترل سطح وایرلس با نمایشگر اس ام دی
                        <input type="text" name="dvc_data[54]" class="kc-input" value="<?= $array[54] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[55]" id="" cols="30" rows="10"><?= $array[55] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    قابلیت ارتباطی دستگاه
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">کنترل بی سیم از طریق موبایل و لپ تاپ
                        <input type="text" name="dvc_data[56]" class="kc-input" value="<?= $array[56] ?>">
                    </label>
                    <label for="" class="kc-input-label">نوع ارتباط
                        <input type="text" name="dvc_data[57]" class="kc-input" value="<?= $array[57] ?>">
                    </label>
                    <label for="" class="kc-input-label">تماس از روی دستگاه با واحد پشتیبانی
                        <input type="text" name="dvc_data[58]" class="kc-input" value="<?= $array[58] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[59]" id="" cols="30" rows="10"><?= $array[59] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    سیستم گرمایشی
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">گرمکن
                        <input type="text" name="dvc_data[60]" class="kc-input" value="<?= $array[60] ?>">
                    </label>
                    <label for="" class="kc-input-label">توان گرمکن
                        <input type="text" name="dvc_data[61]" class="kc-input" value="<?= $array[61] ?>">
                    </label>
                    <label for="" class="kc-input-label">نمایشگر تنظیم دما
                        <input type="text" name="dvc_data[62]" class="kc-input" value="<?= $array[62] ?>">
                    </label>
                    <label for="" class="kc-input-label">نوع سنسور دما
                        <input type="text" name="dvc_data[63]" class="kc-input" value="<?= $array[63] ?>">
                    </label>
                    <label for="" class="kc-input-label">گرمکن هوشمند قابل برنامه ریزی زمان شروع بکار
                        <input type="text" name="dvc_data[64]" class="kc-input" value="<?= $array[64] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[65]" id="" cols="30" rows="10"><?= $array[65] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    قابلیت دید در مخزن تولید
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">دوربین دید در مخزن
                        <input type="text" name="dvc_data[66]" class="kc-input" value="<?= $array[66] ?>">
                    </label>
                    <label for="" class="kc-input-label">اس ام دی ضد آب در مخزن
                        <input type="text" name="dvc_data[67]" class="kc-input" value="<?= $array[67] ?>">
                    </label>
                    <label for="" class="kc-input-label">سرعت فیلم برداری
                        <input type="text" name="dvc_data[68]" class="kc-input" value="<?= $array[68] ?>">
                    </label>
                    <label for="" class="kc-input-label">قابلیت انتقال تصویر
                        <input type="text" name="dvc_data[69]" class="kc-input" value="<?= $array[69] ?>">
                    </label>
                    <label for="" class="kc-input-label">وای فای
                        <input type="text" name="dvc_data[70]" class="kc-input" value="<?= $array[70] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[71]" id="" cols="30" rows="10"><?= $array[71] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    توانایی بستن درب
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">تعداد هد های درب بندی
                        <input type="text" name="dvc_data[72]" class="kc-input" value="<?= $array[72] ?>">
                    </label>
                    <label for="" class="kc-input-label">هولدر قوطی
                        <input type="text" name="dvc_data[73]" class="kc-input" value="<?= $array[73] ?>">
                    </label>
                    <label for="" class="kc-input-label">الکترو موتورهای درب بند
                        <input type="text" name="dvc_data[74]" class="kc-input" value="<?= $array[74] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[75]" id="" cols="30" rows="10"><?= $array[75] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    طول نوار نقاله
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">عرض نوار نقاله
                        <input type="text" name="dvc_data[76]" class="kc-input" value="<?= $array[76] ?>">
                    </label>
                    <label for="" class="kc-input-label">گارد های متحرک
                        <input type="text" name="dvc_data[77]" class="kc-input" value="<?= $array[77] ?>">
                    </label>
                    <label for="" class="kc-input-label">جنس گارد ها
                        <input type="text" name="dvc_data[78]" class="kc-input" value="<?= $array[78] ?>">
                    </label>
                    <label for="" class="kc-input-label">جنس نوار نقاله
                        <input type="text" name="dvc_data[79]" class="kc-input" value="<?= $array[79] ?>">
                    </label>
                    <label for="" class="kc-input-label">تنظیم کننده کشش نوار
                        <input type="text" name="dvc_data[80]" class="kc-input" value="<?= $array[80] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[81]" id="" cols="30" rows="10"><?= $array[81] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    گیربکس های بکار رفته
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">برند گیربکس
                        <input type="text" name="dvc_data[82]" class="kc-input" value="<?= $array[82] ?>">
                    </label>
                    <label for="" class="kc-input-label">تعداد گیربکس
                        <input type="text" name="dvc_data[83]" class="kc-input" value="<?= $array[83] ?>">
                    </label>
                    <label for="" class="kc-input-label">تعداد دور بر دقیقه
                        <input type="text" name="dvc_data[84]" class="kc-input" value="<?= $array[84] ?>">
                    </label>
                    <label for="" class="kc-input-label">سایز شافت
                        <input type="text" name="dvc_data[85]" class="kc-input" value="<?= $array[85] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[86]" id="" cols="30" rows="10"><?= $array[86] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    نازل ها
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">نازل های متحرک
                        <input type="text" name="dvc_data[87]" class="kc-input" value="<?= $array[87] ?>">
                    </label>
                    <label for="" class="kc-input-label">سیستم حرکت عمودی نازل ها
                        <input type="text" name="dvc_data[88]" class="kc-input" value="<?= $array[88] ?>">
                    </label>
                    <label for="" class="kc-input-label">قطر نازل ها
                        <input type="text" name="dvc_data[89]" class="kc-input" value="<?= $array[89] ?>">
                    </label>
                    <label for="" class="kc-input-label">قابلیت تعویض سر نازل برای انواع مختلف قوطی
                        <input type="text" name="dvc_data[90]" class="kc-input" value="<?= $array[90] ?>">
                    </label>
                    <label for="" class="kc-input-label">سیستم ضد چکه نازل ها
                        <input type="text" name="dvc_data[91]" class="kc-input" value="<?= $array[91] ?>">
                    </label>
                    <label for="" class="kc-input-label">جنس نازل ها
                        <input type="text" name="dvc_data[92]" class="kc-input" value="<?= $array[92] ?>">
                    </label>
                    <label for="" class="kc-input-label">اتصالات نازل ها
                        <input type="text" name="dvc_data[93]" class="kc-input" value="<?= $array[93] ?>">
                    </label>
                    <label for="" class="kc-input-label">تعداد کلمپ های به کار رفته
                        <input type="text" name="dvc_data[94]" class="kc-input" value="<?= $array[94] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[95]" id="" cols="30" rows="10"><?= $array[95] ?></textarea>
                    </label>
                </div>

                <hr class="kc-add-hr">
                <button type="button" class="kc-accordion">
                    سیستم خودشستشو
                </button>
                <div class="kc-panel">

                    <label for="" class="kc-input-label">توان موتور خودشستشو
                        <input type="text" name="dvc_data[96]" class="kc-input" value="<?= $array[96] ?>">
                    </label>
                    <label for="" class="kc-input-label">جنس هد پمپ شستشو
                        <input type="text" name="dvc_data[97]" class="kc-input" value="<?= $array[97] ?>">
                    </label>
                    <label for="" class="kc-input-label">جنس مسیر و نازل شستشو
                        <input type="text" name="dvc_data[98]" class="kc-input" value="<?= $array[98] ?>">
                    </label>
                    <label for="" class="kc-input-label">مکانیزم سی آی پی
                        <input type="text" name="dvc_data[99]" class="kc-input" value="<?= $array[99] ?>">
                    </label>
                    <label for="" class="kc-input-label">توانایی برنامه ریزی شستشو
                        <input type="text" name="dvc_data[100]" class="kc-input" value="<?= $array[100] ?>">
                    </label>
                    <label for="" class="kc-info-label">توضیحات
                        <textarea name="dvc_data[101]" id="" cols="30" rows="10"><?= $array[101] ?></textarea>
                    </label>
                </div>
            </div>

            <button type="submit" class="kc-button kc-button-block" name="kc_update_device_submit">ثبت دستگاه</button>
        </form>
    </div>
</div>