<?php

class DB {
    /**Database pdo. */
    private $pdo;
    /**Database port. */
    private $port;
    /**Database charset. */
    private $charset;
    /**Database host. */
    private $host;
    /**Database user. */
    private $user;
    /**Database password. */
    private $pass;
    /**Database name. */
    private $dbaa;
    /**Database table. */
    public $model;

    /**Table methods: supported. */
    private $andWhere;
    private $orWhere;
    private $orderBy;
    private $innerJoin;
    private $leftJoin;
    /**select condition * or add id, index table required id. */
    public $select_condition;

    function __construct($env)
    {
        /**Default port. */
        $this->port = "3306";
        /**Default charset. */
        $this->charset = 'utf8mb4';
        /**Connect to server if need. */
        $this->pdo = self::connect($env);
        /**Reset table. */
        $this->model = null;
        /**Default all. */
        $this->select_condition = '*';
    }
    function connect($env){
        /** Get env variables readenv.php */
        $env_empty = self::envEmpty($env);
        /** if .env file empty, database not required. */
        if($env_empty){
            /** Some stupid default stuff. */
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
        /**
         * For public use return empty
         * TO-DO
         * public / index.php /index.html
         */
        return '';
    }
    function envEmpty($env){
        /**
         * Check for .env database setup
         * [host,user,pass,dbaa,port,charset]
         * first check if defined if not use default [port, charset]
         * if host.. not defined dont throw error just say database not required.
         */
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
    function dropError(string $a = ''): self{
        /**
         * prepare for error drop.
         * 
         * if model not define , and try from no model class invoke table -> drop error.
         */
        if($this->model == null){
            throw new \PDOException('Try to access model without permission.');
        }

        if($a == 'zipJoinSQL'){
            throw new \PDOException('Join format wrong, expected [table, on.cloumn, to.column].');
        }

        return $this;
    }
    /**
     * setModel from route.php [.., 'model'] => enable model.
     * migration -> create table in mysql as same name as class/classFile.
     */
    function setModel($model, $selector, $cursor = false){
        $this->model = $model;
        /**
         * cursor fetch all from table, so be cerfull if we have some milion rows, myb chunk ??
         */
        if($cursor){
            /** selector from var, model from define, fetch as object nice -> usage. */
            return $this->pdo->query('SELECT '.$selector.' FROM '.$model)->fetchAll(PDO::FETCH_OBJ);
        }
    }
    /**
     * GLOBAL: self usage for fun1()->fun2();etc..
     * 
     * select: !important !required array.
     * [1,2,3] array to = > 1,2,3 string.
     */
    public function select(array $sql): self{ 
        self::dropError();
        $this->select_condition = implode(',', $sql);
        return $this;
    }
    /** what say: myb drop virus like and bla = 1 group... 
     * hmm ?. bug[1] nexter.
    */
    public function where(string $sql): self{
        self::dropError();
        $this->andWhere .= strlen($this->andWhere) == 0 ? 'WHERE '.$sql : 'AND '.$sql;
        return $this;
    }
    /**bug[1] */
    public function orWhere(string $sql): self{
        self::dropError();
        $this->orWhere .= strlen($this->orWhere) == 0 ? strlen($this->andWhere) == 0 ? $sql : 'OR '.$sql : 'AND '.$sql;
        return $this;
    }
    /**bug[1] */
    public function orderBy(string $sql): self{
        self::dropError();
        $this->orderBy .= strlen($this->orderBy) == 0 ? 'ORDER BY '.$sql : ', '.$sql;
        return $this;
    }
    /** Inner join */
    public function innerJoin(array $sql): self{
        self::dropError();
        $zip = self::zipJoinSQL($sql);
        $this->innerJoin .= ' INNER JOIN '.$zip;
        return $this;
    }
    /** Left join */
    public function leftJoin(array $sql): self{
        self::dropError();
        $zip = self::zipJoinSQL($sql);
        $this->leftJoin .= ' LEFT JOIN '.$zip;
        return $this;
    }
    /** Right join */
    public function rightJoin(array $sql): self{
        self::dropError();
        $zip = self::zipJoinSQL($sql);
        $this->leftJoin .= ' RIGHT JOIN '.$zip;
        return $this;
    }
    /**
     * return as object array list.
     * get all.
     */
    public function get(){
        return self::dropError()->_instance()->query(
            self::select_builder(
                self::_num_condition()
                )
            )->fetchAll(PDO::FETCH_OBJ);
    }
    /**
     * return as object.
     * get first.
     */
    public function first(){
        return self::dropError()->_instance()->query(
            self::select_builder(
                self::_num_condition()
                )
            )->fetch(PDO::FETCH_OBJ);
    }
    private function select_builder(){
        return 
        'SELECT 
        '.$this->select_condition.' 
        FROM 
        '.$this->model.' 
        '.$this->innerJoin.' 
        '.$this->leftJoin.' 
        '.$this->andWhere.' 
        '.$this->orWhere.' 
        '.$this->orderBy;
    }
    private function _num_condition(){
        return $this->select_condition != '*' ?  $this->select_condition .= ','.$this->model.'.id' : $this->select_condition;
    }
    /**
     * $nan = array
     * zip = key to a = ?,b = ? : value to "a","b"
     * zip{0} = set keys
     * zip{1} = set values
     * update by id table.
     */
    public function save($nan){
        self::dropError();
        $zip = self::zipUpdateSQL($nan);
        $r = $this->pdo->prepare('UPDATE '.$this->model.' SET '.$zip[0].'  WHERE id = '.self::first()->id.'');
        $r->execute($zip[1]);
        return $r->rowCount() ? true : false;
    }
    /**
     * delete by id table.
     */
    public function delete($nan){
        self::dropError();
        $r = $this->pdo->prepare('DELETE FROM '.$this->model.' WHERE id = ?');
        $r->execute([self::first()->id]);
        return $r->rowCount() ? true : false;
    }
    /**
     * nan = array
     * zip = key to a,b : value = ?,?
     * zip{3} = set keys
     * zip{2} = set values
     */
    public function insert($nan){
        $zip = self::zipUpdateSQL($nan);
        $r = $this->pdo->prepare('INSERT INTO '.$this->model.' ('.$zip[3].') VALUES ('.$zip[2].')');
        $r->execute($zip[1]);
        return $r->rowCount() ? true : false;
    }
    /**
     * little logic.
     * formating pdo update, insert  (preparing)
     * like : 
     * bindParam(a => b)
     * bindPara(:a, b)
     * fixed comma remove from last shit.
     */
    function zipUpdateSQL($nan){
        $update_key='';$update_val=[];$update_q='';$insert_key='';$last=count((array)$nan);$count=0;
        foreach($nan as $key=> $value){ 
            if($key != 'id'){ 
                $update_key .= $key.'=?,';
                $insert_key .= $key.',';
                $update_q .= '?,';
                $update_val[] = $value; } 
            $count++; if($last == $count){ 
                $update_key = substr($update_key, 0, -1);
                $update_q = substr($update_q, 0, -1);
                $insert_key = substr($insert_key, 0, -1); }}
        return array($update_key, $update_val, $update_q, $insert_key);
    }
    function zipJoinSQL($nan){
        if(count($nan) == 3){
            return $nan[0].' ON '.$nan[1].' = '.$nan[2];
        } else {
            self::dropError('zipJoinSQL');
        }
    }
    /**custom raw query */
    function _instance(){
        return $this->pdo;
    }
}