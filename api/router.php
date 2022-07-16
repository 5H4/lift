<?php
/**
 * Here add path:
 * method:class@function
 * - cursor => array 'data'.
 * - model => activate model.
 */
const routers = [
    'users/add/' => ['post:example@test', 'model', 'cursor' => ['username', 'password']],
    'test/' => ['post:example1@view']
];

/**Required !!! */
require 'throw.php';