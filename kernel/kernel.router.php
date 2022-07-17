<?php
$case = explode('/', $after);
$path = '';

if(count($case) > 0){
    if($case[1] == PREFIX_API){
        for($x = 2; $x < count($case); $x++){
            $jim = explode('?', $case[$x])[0];
            $path .= $jim;
            if(count($case) != $x){ $path .= '/'; }
        }
        include FOLDER_API.'/router.php';
    } else {
        if(file_exists(FOLDER_PUBLIC.'/'.PUBLIC_DEFAULT_PHP)){
            include FOLDER_PUBLIC.'/'.PUBLIC_DEFAULT_PHP;
        } else if(file_exists(FOLDER_PUBLIC.'/'.PUBLIC_DEFAULT_HTML)){
            include FOLDER_PUBLIC.'/'.PUBLIC_DEFAULT_HTML;
        }
    }
}