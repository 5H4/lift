<?php

class Builder {
    /**Builders. */
    public function save_builder($setter, $id){
        return 
        'UPDATE 
        '.$this->model.' 
        SET 
        '.$setter.'  
        WHERE 
        id = '.$id.'';
    }
    public function delete_builder(){
        return 
        'DELETE FROM 
        '.$this->model.' 
        WHERE id = ?';
    }
    public function select_builder(){
        return 
        /** select query */
        'SELECT 
        '.$this->select_condition.' 
        FROM 
        '.$this->model./* table definition*/'
        '.$this->innerJoin.' 
        '.$this->leftJoin.' 
        '.$this->andWhere.' 
        '.$this->orWhere.' 
        '.$this->orderBy;
    }
    public function insert_builder($zip){
        return 
        'INSERT INTO 
        '.$this->model.' 
        ('.$zip[3].') 
        VALUES 
        ('.$zip[2].')';
    }
    public function _num_condition(){
        return $this->select_condition != '*' ?  $this->select_condition .= ','.$this->model.'.id' : $this->select_condition;
    }
}