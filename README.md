<p align="center"><img src='lift.png'></p>
<h1></h1>
<center>Lift is a small framework that creates a rest api,
the goal of the lift is to always be small, poison is stored in small bottles.</center>
<h1></h1>
<h3>Usage</h3>

api/router.php
```php
/** 
 * post:example@add
 * [post, example, add]
 * [method, class, function]
 * [model] => enable or disable
 * cursor => [] fetch all, array selectable items.
 * * => all, example: ['username', 'password']
 */
const routers = [
    'users/add/'    => ['post:example@add', 'model'],
    'users/remove/' => ['post:example@remove', 'model'],
    'users/list/'   => ['get:example@list', 'cursor' => ['*']],
    'users/get/'    => ['get:example@get', 'model'],
    'test/'         => ['post:example1@view']
];
```

Create new class file in api/controllers/ example Users.php

Create new table Users.

Roll it.

