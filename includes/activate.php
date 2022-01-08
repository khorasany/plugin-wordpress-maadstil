<?php

function kianlandC_activate()
{
    if (!current_user_can('activate_plugins')) {
        exit;
    }
// code on activation here...
}

register_activation_hook(__FILE__, 'kianlandC_activate');