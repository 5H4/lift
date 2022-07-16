<?php

class DB {
    public $pdo;
    public $model;
    private $port;
    private $charset;
    private $host;
    private $user;
    private $pass;
    private $dbaa;
    //
    private $andWhere;
    private $orWhere;
    private $orderBy;
    private $innerJoin;
    public $select_condition;

    function __construct($env)
    {
        $this->port = "3306";
        $this->charset = 'utf8mb4';
        $this->pdo = self::connect($env);
        $this->model = null;
        $this->select_condition = '*';
    }
    function connect($env){
        $env_empty = self::envEmpty($env);
        if($env_empty){
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $dsn = "mysql:host=$this->host;dbname=$this->dbaa;charset=$this->charset;port=$this->port";
            try {
                return  new \PDO($dsn, $this->user, $this->pass, $options);
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return '';
    }
    function envEmpty($env){
        if(isset($env->host) && !empty($env->host)){ $this->host = trim($env->host);
        if(isset($env->user) && !empty($env->user)){ $this->user = trim($env->user);
        if(isset($env->pass)){ $this->pass = empty($env->pass) ? '' : trim($env->pass);
        if(isset($env->dbaa) && !empty($env->dbaa)){ $this->dbaa = trim($env->dbaa);
        if(isset($env->port) && !empty($env->port)){ $this->port = trim($env->port); }
        if(isset($env->charset) && !empty($env->charset)){ $this->charset = trim($env->charset); }
            return true;
        }}}}
        return false;
    }
    function dropError(){
        if($this->model == null){
            throw new \PDOException('Try to access model without permission.');
        }
    }
    function setModel($model, $selector){
        $this->model = $model;
        return $this->pdo->query('SELECT '.$selector.' FROM '.$model)->fetchAll(PDO::FETCH_OBJ);
    }
    public function select(array $sql): self{ 
        self::dropError();
        $this->select_condition = ','.implode(',', $sql);
        return $this;
    }
    public function where($sql): self{
        self::dropError();
        $this->andWhere .= strlen($this->andWhere) == 0 ? 'WHERE '.$sql : 'AND '.$sql;
        return $this;
    }
    public function orWhere($sql): self{
        self::dropError();
        $this->orWhere .= strlen($this->orWhere) == 0 ? strlen($this->andWhere) == 0 ? $sql : 'OR '.$sql : 'AND '.$sql;
        return $this;
    }
    public function orderBy($sql): self{
        self::dropError();
        $this->orderBy .= strlen($this->orderBy) == 0 ? 'ORDER BY '.$sql : ', '.$sql;
        return $this;
    }
    public function get(){
        self::dropError();
        return $this->pdo->query('SELECT id '.$this->select_condition.' '.$this->innerJoin.' FROM '.$this->model.' '.$this->andWhere.' '.$this->orWhere.' '.$this->orderBy)->fetchAll(PDO::FETCH_OBJ);
    }
    public function first(){
        self::dropError();
        return $this->pdo->query('SELECT id '.$this->select_condition.' '.$this->innerJoin.' FROM '.$this->model.' '.$this->andWhere.' '.$this->orWhere.' '.$this->orderBy)->fetch(PDO::FETCH_OBJ);
    }
    public function save(){
        self::dropError();
        echo self::first()->id;
    }
    function query(){
        return $this->pdo;
    }
}