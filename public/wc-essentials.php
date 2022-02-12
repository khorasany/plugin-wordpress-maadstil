<?php

if (!defined('ABSPATH')) exit;
function kianlandC_wc_checkout_is_login()
{
    if (is_checkout() && !is_user_logged_in()) {
        global $kianland_core;
        ?>
        <script>
            console.log('here')
            Swal.fire({
                title: 'لطفا پیش از پرداخت وارد حساب کاربری خود شوید',
            }).then(() => {
                window.location.href = "<?= site_url('/' . get_option($kianland_core->log_key)) ?>";
            })
        </script>
        <?php
    }
}

add_action('wp_footer', 'kianlandC_wc_checkout_is_login');

function custom_override_checkout_fields( $fields ) {
    $user = get_user_by('ID', get_current_user_id());

    $fields['billing']['billing_first_name']['default'] = get_user_meta($user->ID, 'first_name', true);
    $fields['billing']['billing_last_name']['default'] = get_user_meta($user->ID, 'last_name', true);
    $fields['billing']['billing_address_1']['default'] = get_user_meta($user->ID, 'address', true);
    $fields['billing']['billing_telephone_kc']['default'] = get_user_meta($user->ID, 'telephone', true);
    if(is_int((int)($user->user_login))){
        $fields['billing']['billing_phone']['default'] = $user->user_login;
    }

    $fields['billing']['billing_email']['default'] = $user->user_email;
    $fields['billing']['billing_postal_code_kc']['default'] = get_user_meta($user->ID, 'postal_code', true);

    if(get_user_meta($user->ID, 'company_name', true)){

        $fields['billing']['billing_acc_id_kc']['default'] = get_user_meta($user->ID, 'account_id', true);
        $fields['billing']['billing_eco_id_kc']['default'] = get_user_meta($user->ID, 'economy_id', true);
        $fields['billing']['billing_company']['default'] = get_user_meta($user->ID, 'company_name', true);
        unset($fields['billing']['billing_personal_id_kc']);

    }else{

        $fields['billing']['billing_personal_id_kc']['default'] = get_user_meta($user->ID, 'personal_id', true);
        unset($fields['billing']['billing_acc_id_kc'], $fields['billing']['billing_eco_id_kc'], $fields['billing']['billing_company']);


    }
    return $fields;
}

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );