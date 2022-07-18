<?php 
/**
 * $req = request method.
 * $model = dba - class model, view supported only write not yet.
 * $dba = custom query.
 */
class example {
    // req , model, cursor
    public static function list(Lift $lift){
        return $lift->model->where('username is not null')->get();
    }
}

