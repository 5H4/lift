<?php
/**
 * Here add path:
 * method:class@function
 * - cursor => array 'data'.
 * - model => activate model.
 */
const routers = [
    'users/add/' => ['post:example@add', 'model'],
    'users/remove/' => ['post:example@remove', 'model'],
    'test/' => ['post:example1@view']
];

/**Required !!! */
require 'throw.php';