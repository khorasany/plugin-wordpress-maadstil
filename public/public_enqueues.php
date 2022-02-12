<?php

if (!defined('ABSPATH')) exit;

function kianlandC_enqueue_script()
{
    wp_enqueue_script('sweet-alert', plugin_dir_url(__FILE__) . 'js/sweetalert2.min.js', [], 2);
}

add_action('wp_enqueue_scripts', 'kianlandC_enqueue_script');

function myplugin_ajaxurl()
{

    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

add_action('wp_head', 'myplugin_ajaxurl');

//wp_localize_script( 'ajax-script', 'ajax_object', ['ajax_url' => admin_url( 'admin-ajax.php' )]);

function kianlandC_ajax_script_login_add()
{
    ?>
    <script>
        function ajaxLoginKian() {
            var data = {
                'action': 'KianlandC_testOK',
                'name': 'alireza'
            }
            jQuery.post(ajaxurl, data, function (response) {
                alert(response)
            })
        }

        ajaxLoginKian();
    </script>
    <?php
}

function KianlandC_testOK()
{
    echo $_POST['name'];
    wp_die();
}

//add_action('wp_footer','kianlandC_ajax_script_login_add');
//add_action('wp_ajax_KianlandC_testOK', 'KianlandC_testOK');
//add_action( 'wp_ajax_nopriv_KianlandC_testOK', 'KianlandC_testOK' );

function kianlandC_get_device()
{
    global $wpdb;
    global $kianland_core;

    $id = $_POST['device_id'];
    $result = $wpdb->get_row("SELECT * FROM `wp_prweb_devices` WHERE device_id='$id'");
    if ($result) {
        ?>
        <input type="text" id="get_timestamp" class="kc-hidden-element"
               value="<?= $result->device_expire_date ?>">
        <div class="kc-device-image">
            <img src="<?= $result->device_image ?>" class="kc-device-image" alt="image not loaded">
        </div>
        <div class="kc-all-device-elements">
            <section class="kc-timer">
                <div class="kc-timer-border">
                    <h2>زمان باقی مانده تا انقضای دستگاه</h2>
                    <div class="kc-countdown">
                        <div class="kc-container-second">
                            <h3 class="kc-counter-second">Time</h3>
                            <h3>ثانیه</h3>
                        </div>
                        <div class="kc-container-minute">
                            <h3 class="kc-counter-minute">Time</h3>
                            <h3>دقیقه</h3>
                        </div>
                        <div class="kc-container-hour">
                            <h3 class="kc-counter-hour">Time</h3>
                            <h3>ساعت</h3>
                        </div>
                        <div class="kc-container-day">
                            <h3 class="kc-counter-day">Time</h3>
                            <h3>روز</h3>
                        </div>
                    </div>
                </div>
            </section>
            <div class="kc-main-device-info">
                <label for="">شماره سریال دستگاه
                    <input value="<?= $result->device_id ?>" class="kc-input kc-disabled" disabled>
                </label>
                <label for="">تاریخ اتمام گارانتی
                    <input value="<?= timeStampToShamsiWithoutTime($result->device_expire_date, 'a') ?>"
                           class="kc-input kc-disabled" disabled>
                </label>
                <label for="">تاریخ ساخت
                    <input value="<?= timeStampToShamsiWithoutTime($result->device_build_date, 'a') ?>"
                           class="kc-input kc-disabled" disabled>
                </label>
            </div>
            <div class="kc-main-device-info">
                <label for="">نوع دستگاه
                    <input value="<?= $result->device_spec ?>" class="kc-input kc-disabled" disabled>
                </label>
                <label for="">مدل دستگاه
                    <input value="<?= $result->device_model ?>" class="kc-input kc-disabled" disabled>
                </label>
                <label for="">نام خریدار
                    <input value="<?= $result->device_owner ?>" class="kc-input kc-disabled" disabled>
                </label>
            </div>
            <div class="device_data">
                <?php
                $data = $result->device_data;
                $data = unserialize($data, ['allowed_classes' => false]);
                $titles = $kianland_core->dvc_titles;
                $keys = $kianland_core->dvc_input_keys;

                if ($data) {
                    for ($i = 0, $j = 1, $counter_data = 0; $counter_data < 102; $counter_data++) {
                        if ($j) {
                            ?>
                            <div class="kc-device-section">
                            <hr class="kc-add-hr">
                            <button class="kc-accordion">
                                <?= $titles[$i] ?>
                            </button>
                            <div class="kc-panel">
                            <?php
                            $j = 0;
                        }
                        if ($keys[$counter_data] != 'توضیحات') {
                            ?>

                            <label for=""
                                   class="kc-input-label"><?= $keys[$counter_data] ?>
                                :
                                <input type="text"
                                       class="kc-input kc-disabled"
                                       value="<?= $data[$counter_data] ?>"
                                       disabled>
                            </label>

                            <?php
                        } else {
                            $i++;
                            $j = 1;
                            ?>

                            <label for=""
                                   class="kc-info-label"><?= $keys[$counter_data] ?>
                                :

                                <textarea name="dvc_data[توضیحات]" cols="30" rows="10"
                                          class="kc-disabled"
                                          disabled><?= $data[$counter_data] ?></textarea>
                            </label>
                            </div>
                            </div>

                            <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
        <?php
    } else {
        echo 'failed';
    }
    wp_die();
}

add_action('wp_ajax_kianlandC_get_device', 'kianlandC_get_device');
add_action('wp_ajax_nopriv_kianlandC_get_device', 'kianlandC_get_device');


