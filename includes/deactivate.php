<?php

function kianlandC_deactivate()
{
    if (!current_user_can('activate_plugins')) {
        exit;
    }
// code on deactivation here...
}

register_deactivation_hook(__FILE__, 'kianlandC_deactivate');