<?php
/**
 * Here add path:
 * method:class@function
 * - cursor => array 'data'.
 * - model => activate model.
 */
const routers = [
    'check/stock/' => ['post:example@test', 'model'],
    'test/' => ['post:example1@view']
];

/**Required !!! */
require 'throw.php';