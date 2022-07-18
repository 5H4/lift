<?php
/**
 * $msg head of returning.
 * this is what we expect in return result.
 */
$msg = new stdClass;

/**
 * Methods:
 * POST     [v]
 * GET      [v]
 * PUT      [x]
 * DELETE   [x]
 * HEAD     [x]
 * CONNECT  [x]
 * OPTIONS  [x]
 * TRACE    [x]
 * PATCH    [x]
 */
$supported_method = [
    'POST', 'GET'
];

if(isset(routers[$path])){
    /**
     * Default for SELECT -> SELECT *
     */
    $selector = '*';
    /**
     * Cursor => true , fetchAll from table SELECT *.
     * cursor also need to be seted in router.php like cursor => all or ['cursor' => 'username', 'email']
     */
    $cursor_required = false;
    /**
     * Model => true set table.
     * to enable model add to router.php for each api call, path 'model'. 
     */
    $model_required = false;
    /**
     * First of all check if router path = array.
     * if equal to array need to unzip path then get other stuff like cursor , model etc..
     */
    if(gettype(routers[$path]) == 'array'){
        /**
         * get path first in array, split by @
         * value before first is request method.
         */
        $unzip = explode('@', routers[$path][0]);
        /** get all other args from (after path and cursor) */
        foreach(routers[$path] as $index => $arg){
            if($arg == 'model'){
                /**
                 * model eabled, get table.
                 */
                $model_required = true;
            }
            /** array stuff */
            if(gettype($arg) == 'array'){
                /** if cursor enbled in router.php for each api call. */
                if($index == 'cursor'){
                    /** if cursor has slectable items, add to string separated with comma. */
                    $selector = implode(',', $arg);
                    /** cursor valid, enable it. */
                    $cursor_required = true;
                }
            }
        }
    } else {
        /**
         * old way, path is a string no a array.
         * get method before @, then get class->function.
         */
        $unzip = explode('@', routers[$path]);
    }
    /** alpha no required, remove in feature, no need check len. */
    if(count($unzip) == 2){
        /** unzip/ectract pathFunction  = niche */
        $niche = explode(':', $unzip[0]);
        /**
         * niche{0} = SERVER METHOD.
         * niche{1} = ALWAYS CLASS
         * unzip{1} = FUNCTION IN CLASS (from niche{1})
         * 
         */
        $method = $niche[0]; $class= $niche[1]; $function = $unzip[1];
        /**
         * check if request method == niche{0} ($method).
         * example: POST, GET, PUT
         * decleared: variable top: $supported_method []
         */
        if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
            /**
             * to be shure check if classFile exists
             * PREFIX_API defined in kernel/constant.php
             * else throw error.
             */
            if(file_exists(PREFIX_API."/controllers/$class.php")){
                include_once "controllers/$class.php";
                /**
                 * after included classFile
                 * we check if class in classFile exists.
                 * else throw error. (class.php -> class)
                 */
                if(class_exists($class)){
                    /**
                     * after class exists
                     * we check if function in class exists.
                     * else throw error. (class->function)
                     */

                     /**
                      * full path: class.php->class->function.
                      */
                    if(method_exists($class, $function)) {
                        $cursor = null; /** set cursor to null, because we transfer data to end class. */
                        if($cursor_required){
                            /**
                             * cursor is required , set model give class and selector (selector is default *), 
                             * true => cursor enabled.
                             */
                            $cursor = $DB->setModel($class, $selector, true);
                        }
                        if($model_required){
                            /**
                             * model is required, set class, and selector (for this option) 
                             * selector can be whatever because is unused var,
                             * when cursor is disabled.
                             */
                            $DB->setModel($class, $selector);
                        }
                        /**
                         * call magic function
                         * call(class, function)
                         * send(request, model => db, cursor)
                         */
                          switch($_SERVER['REQUEST_METHOD']){
                        /**
                          * switch support methods.
                          */
                            case 'POST':    $msg = call_user_func("$class::$function", new Lift($_POST, $DB, $cursor));
                                break;
                            case 'GET':     $msg = call_user_func("$class::$function", new Lift($_GET, $DB, $cursor));
                                break;
                          }
                    } else {
                        /** function is defined but not found. */
                        ab("Function [".$function."] doesn't not exists in class  [".$class."]");
                    }
                } else {
                    /** class is defined but not found. */
                    ab("Class [".$class."] doesn't not exists.");
                }
            } else {
                /** file of path class not exists. */
                ab("File [".$class.".php] doesn't not exists.");
            }
        } else {
            /** method is wrong */
            ab("Method ".$_SERVER['REQUEST_METHOD']." not supported for  [".$path."].");
        }
    } else {
        /** ruter define but path to function not defined. */
        ab('Please define function to path. [Class@Function]');
    }
} else {
    /** router not exists define in router.php */
    ab("Router doesn't not exists. [".$path."]");
}
/**
 * export server response.
 */
/** json response*/ header('Content-Type: application/json; charset=utf-8');
 /**exported */     echo json_encode($msg);

/** Throw error if something wrong 
 * supported errors:
 * - router 
 * - path
 * - method
 * - file
 * - class
 * - function
 */
function ab($error){throw new \PDOException($error);}

