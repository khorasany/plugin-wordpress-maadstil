<?php

// this method
function medicalAppt_on_uninstall()
{
    if (!current_user_can('activate_plugins')) {
        exit;
    }
}
register_uninstall_hook(__FILE__, 'medicalAppt_on_uninstall');