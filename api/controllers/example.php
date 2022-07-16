<?php 
/**
 * $req = request method.
 * $model = dba - class model, view supported only write not yet.
 * $dba = custom query.
 */
class example {
    // req , model, cursor
    public function test($req, $model, $cursor){
        $model->select(['username', 'email'])->where('username = "ramo"')->orderBy('username asc');
        return $model->get();
    }
}

