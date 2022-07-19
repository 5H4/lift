<?php

require 'kernel/constant.php';
require 'kernel/readenv.php';
require 'kernel/kernel.lift.php';
require 'kernel/sql.builder.php';
require 'kernel/kernel.db.php';

$DB = new DB($env);

require 'kernel/kernel.router.php';
