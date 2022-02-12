<?php

// this method

function kiancore_on_uninstall()
{
    if (!current_user_can('activate_plugins')) exit;

    global $wpdb;
    global $kianland_core;

        $wpdb->query("DROP TABLE IF EXISTS `wp_token_validate`");

        $wpdb->query("DROP TABLE IF EXISTS `wp_prweb_devices`");

        do_action('delete_option', $kianland_core->reg_key);

        do_action('delete_option', $kianland_core->log_key);

}


register_uninstall_hook(__FILE__, 'kiancore_on_uninstall');