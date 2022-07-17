<?php 
/**
 * $req = request method.
 * $model = dba - class model, view supported only write not yet.
 * $dba = custom query.
 */
class example {
    // req , model, cursor
    public static function add($req, $model, $cursor){
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
    public static function remove($req, $model){
        $username = $req['username'];
        $user = $model->where('username = "'.$username.'"')->first();

        if(!$user){
            return [
                'success' => false,
                'message' => 'User dosnt exists.'
            ];
        }

        if($model->delete($user)){
            return [
                'success' => true,
                'message' => 'User removed.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Something is wrong.'
        ];
    }

    public static function list($req, $model, $cursor){
        return [
            'success' => true,
            'message' => 'List.',
            'data' => $cursor
        ];
    }
    public static function get($req, $model, $cursor){
        $username = $req['username'];
        $user = $model->select(['username', 'email'])->where('username = "'.$username.'"')->first();

        if(!$user){
            return [
                'success' => false,
                'message' => 'User dosnt exists.'
            ];
        }

        return [
            'success' => true,
            'message' => 'User',
            'data' => $user
        ];
    }
}

