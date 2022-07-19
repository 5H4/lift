<?php
/**
 * Here add path:
 * method:class@function
 * - cursor => array 'data'.
 * - model => activate model.
 */
const routers = [
    'users/list/'   => ['get:users@list', 'cursor' => ['*']],
];

/**Required !!! */
require 'throw.php';