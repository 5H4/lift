<p align="center"><img src='lift.png'></p>
<h1></h1>
<center>Lift is a small framework that creates a rest api,
the goal of the lift is to always be small, poison is stored in small bottles.</center>
<h1></h1>
<h3>Usage</h3>

api/router.php
```php
const routers = [
    'users/list/'   => ['get:example@list', 'model', 'cursor' => ['*']],
];
```

Create new class file in api/controllers/ example Users.php

Create new table Users.

Roll it.

Where
```php
$lift->model->where('username = "lift"')->where('passwrod  = "lift123"')->first();
```

orWhere
```php
$lift->model->where('username = "lift"')->orWhere('username  like "lif%"')->first();
```

innerJoin
```php
$lift->model->where('username = "lift"')->innerJoin('posts on posts.username = example.username')->get();
```

leftJoin
```php
$lift->model->where('username = "lift"')->leftJoin('posts on posts.username = example.username')->get();
```

rightJoin
```php
$lift->model->where('username = "lift"')->rightJoin('posts on posts.username = example.username')->get();
```

selectable
```php
$lift->model->select(['username', 'password'])->where('username = "lift"')->rightJoin('posts on posts.username = example.username')->get();
```

<h2>Update</h2>

```php
$user = $lift->model->where('username = "lift"')->first();

$user->username =  'newUsername';

$lift->model->save($user);
```

<h2>Insert</h2>

```php
$user = new stdClass;

$user->username = 'test1';
$user->password = '12345';
$user->email = 'test@bla.com';

$lift->model->insert($user);
```