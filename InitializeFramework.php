<?php

if(strstr($_SERVER['REQUEST_URI'], "/champs_framework/example")){
    /* access to example folder */
    $baseDir = __DIR__."/example";
}else{
    /* access app environment */
    $baseDir = __DIR__."/../../..";
}

if(file_exists("{$baseDir}/Source/Boot/Constants.php")){
    require "{$baseDir}/Source/Boot/Constants.php";
}

if(defined("CHAMPS_SYS_BOOT_FILES") && is_array(CHAMPS_SYS_BOOT_FILES)){
    foreach (CHAMPS_SYS_BOOT_FILES as $file){
        $file = (strtolower(substr($file, -3, 3)) == "php") ? $file : $file."php";
        if(file_exists("{$baseDir}/$file")){
            include "{$baseDir}/{$file}";
        }
    }
}
