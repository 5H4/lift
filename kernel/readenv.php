<?php
$env = new stdClass;
if(file_exists(ENV_FILE)){
    $env_file = fopen(ENV_FILE, "r");
    while (!feof($env_file)) {
        $line = fgets ($env_file);
        if($line[0] != '*'){
            $lineA = explode("=", $line);
            $env->{$lineA[0]}=$lineA[1];
        }
    }
    fclose ($env_file);
}