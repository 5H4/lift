<?php
class Lift {
    function __construct($method, $table, $cursor)
    {
        $this->request = $method;
        $this->model = $table;
        $this->cursor = $cursor;
        return $this;
    }
}