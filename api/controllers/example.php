<?php 
/**
 * $req = request method.
 * $model = dba - class model, view supported only write not yet.
 * $dba = custom query.
 */
class example {
    // req , model, cursor
    public static function test($req, $model, $cursor){
        $username = $req['username'];
        $password = $req['password'];
        $email = $req['email'];

        $iExists = $model->where('username = "'.$username.'"')->first();

        if($iExists){
            return [
                'success' => false,
                'message' => 'User exists'
            ];
        }
        

        $a = new stdClass();
        $a->username = $username;
        $a->email = $email;
        $a->password = $password;

        $model->insert($a);

        return [
            'success' => true,
            'message' => 'User added'
        ];
    }
}

