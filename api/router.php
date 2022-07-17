<?php
/**
 * Here add path:
 * method:class@function
 * - cursor => array 'data'.
 * - model => activate model.
 */
const routers = [
    'users/add/'    => ['post:example@add', 'model'],
    'users/remove/' => ['post:example@remove', 'model'],
    'users/list/'   => ['get:example@list', 'cursor' => ['*']],
    'users/get/'    => ['get:example@get', 'model'],
    'users/set/username/' => ['post:example@setUsername', 'model'],
    'test/'         => ['post:example1@view']
];

/**Required !!! */
require 'throw.php';