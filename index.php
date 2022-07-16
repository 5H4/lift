<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'kernel/constant.php';
require 'kernel/readenv.php';
require 'kernel/kernel.db.php';

$DB = new DB($env);

require 'kernel/kernel.router.php';
