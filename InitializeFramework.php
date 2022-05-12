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

/*
 * Minify theme files based on constant CHAMPS_MINIFY_THEMES array
 *
 * the CHAMPS_MINIFY_THEMES must have the structure bellow
 * define("CHAMPS_MINIFY_THEMES", [
 *      // activate minify: possible values always, dev
 *      "minify" => "always",
 *      "themes" => [
 *          // informe the theme name, it also the dir name bellow theme dir
 *          "theme_name" => [
 *              "css" => [
 *                  // started at root project dir
 *                  "/path/to/file.css",
 *              ] ,
 *              "js" => [
 *                  "/paht/to/file.js"
 *              ],
 *              "jquery-engine" => true
 *          ]
 *      ]
 *  ]);
 */
if(defined("CHAMPS_MINIFY_THEMES") && is_array(CHAMPS_MINIFY_THEMES)){
    $minify = filter_input(INPUT_GET, "minify", FILTER_VALIDATE_BOOLEAN);
    $minifyConfig = false;
    if(isset(CHAMPS_MINIFY_THEMES['minify'])){
        if(strtolower(CHAMPS_MINIFY_THEMES['minify']) == "always"){
            $minifyConfig = true;
        }
        elseif (strtolower(CHAMPS_MINIFY_THEMES['minify']) == "dev" && strpos(url(), "localhost")){
            $minifyConfig = true;
        }

    }

    if($minify || $minifyConfig){
        $themes = isset(CHAMPS_MINIFY_THEMES['themes']) ? CHAMPS_MINIFY_THEMES['themes'] : [] ;
        foreach($themes as $theme => $types){
            if(is_array($types)){
                $themeBaseDir = "{$baseDir}/themes/{$theme}";
                foreach($types as $type => $fileNames){

                    if(strtolower($type) == 'jquery' && $fileNames == true){
                        $jqueryJs = new \MatthiasMullie\Minify\JS();
                        $jqueryJs->add(__DIR__."/src/Support/frontend/jquery/jquery.min.js");
                        $jqueryJs->add(__DIR__."/src/Support/frontend/jquery/jquery.form.js");
                        $jqueryJs->add(__DIR__."/src/Support/frontend/jquery/jquery.mask.js");
                        $jqueryJs->add(__DIR__."/src/Support/frontend/jquery/jquery-ui.js");
                        $jqueryJs->add(__DIR__."/src/Support/frontend/jquery/jquery.init.js");
                        $jqueryJs->minify("{$themeBaseDir}/assets/jquery.js");
                        $jqueryJs = null;
                    }
                    elseif(strtolower($type) == 'highcharts' && $fileNames == true){
                        $highchartsJs = new \MatthiasMullie\Minify\JS();
                        $highchartsJs->add(__DIR__."/src/Support/frontend/highcharts/highcharts.js");
                        $highchartsJs->minify("{$themeBaseDir}/assets/highcharts.js");
                        $highchartsJs = null;
                    }
                    elseif(strtolower($type) == 'tracker' && $fileNames == true){
                        $trackerJs = new \MatthiasMullie\Minify\JS();
                        $trackerJs->add(__DIR__."/src/Support/frontend/tracker/tracker.js");
                        $trackerJs->minify("{$themeBaseDir}/assets/tracker.js");
                        $trackerJs = null;
                    }
                    elseif(strtolower($type) == 'select2' && $fileNames == true){
                        $select2Css = new \MatthiasMullie\Minify\CSS();
                        $select2Css->add(__DIR__."/src/Support/frontend/select2/css/select2.min.css");
                        $select2Css->add(__DIR__."/src/Support/frontend/select2/css/select2-bootstrap.min.css");
                        $select2Css->minify("{$themeBaseDir}/assets/select2.css");
                        $select2Js = new \MatthiasMullie\Minify\JS();
                        $select2Js->add(__DIR__."/src/Support/frontend/select2/js/select2.full.min.js");
                        $select2Js->minify("{$themeBaseDir}/assets/select2.js");
                    }
                    elseif(strtolower($type) == 'jquery-navflex' && $fileNames == true){
                        $navflexJs = new \MatthiasMullie\Minify\JS();
                        $navflexJs->add(__DIR__."/src/Support/frontend/jquery-navflex/jquery.flexnav.min.js");
                        $navflexJs->minify("{$themeBaseDir}/assets/navflex.js");
                    }
                    elseif(strtolower($type) == 'bootstrap' && $fileNames == true){
                        // copy all bootstrap4 folder
                        $sourceFolder = __DIR__."/src/Support/frontend/bootstrap";
                        $destFolder = "{$themeBaseDir}/assets/bootstrap";
                        if (!file_exists($destFolder) || !is_dir($destFolder)){
                            copyr($sourceFolder, $destFolder);
                        }
                    }
                    elseif(strtolower($type) == 'bootstrap4' && $fileNames == true){
                        // copy all bootstrap folder
                        $sourceFolder = __DIR__."/src/Support/frontend/bootstrap4";
                        $destFolder = "{$themeBaseDir}/assets/bootstrap4";
                        if (!file_exists($destFolder) || !is_dir($destFolder)){
                            copyr($sourceFolder, $destFolder);
                        }
                    }
                    elseif(strtolower($type) == 'datatables' && $fileNames == true){
                        // copy all bootstrap folder
                        $sourceFolder = __DIR__."/src/Support/frontend/datatables";
                        $destFolder = "{$themeBaseDir}/assets/datatables";
                        if (!file_exists($destFolder) || !is_dir($destFolder)){
                            copyr($sourceFolder, $destFolder);
                        }
                    }
                    elseif(strtolower($type) == 'jquery-engine' && $fileNames == true){
                        $jqueryEngineCss = new \MatthiasMullie\Minify\CSS();
                        $jqueryEngineCss->add(__DIR__."/src/Support/frontend/engine-jquery/engine-jquery.css");
                        $jqueryEngineCss->minify("{$themeBaseDir}/assets/champs-jquery-engine.css");
                        $jqueryEngineJs = new \MatthiasMullie\Minify\JS();
                        $jqueryEngineJs->add(__DIR__."/src/Support/frontend/engine-jquery/engine-jquery.js");
                        $jqueryEngineJs->minify("{$themeBaseDir}/assets/champs-jquery-engine.js");
                    }
                    elseif (strtolower($type) == 'css'){
                        /* priority files */
                        $priorityCss = new \MatthiasMullie\Minify\CSS();
                        foreach($fileNames as $cssFileName){
                            $fullCssFilePath = $baseDir.($cssFileName[0] == "/" ? $cssFileName : "/{$cssFileName}");
                            if(is_file($fullCssFilePath) && pathinfo($fullCssFilePath)['extension'] == "css"){
                                $priorityCss->add($fullCssFilePath);
                            }
                        }
                        $priorityCss->minify("{$themeBaseDir}/assets/priority.css");

                        /* theme files */
                        $themeCss = new MatthiasMullie\Minify\CSS();
                        $themeCssDirFiles = scandir($themeBaseDir . "/assets/css");
                        foreach ($themeCssDirFiles as $css) {
                            $cssFile = "{$themeBaseDir}/assets/css/{$css}";
                            if (is_file($cssFile) && pathinfo($cssFile)['extension'] == "css") {
                                $themeCss->add($cssFile);
                            }
                        }
                        $themeCss->minify("{$themeBaseDir}/assets/theme.css");

                    }
                    elseif (strtolower($type) == 'js'){
                        /* priority files */
                        $priorityJs = new \MatthiasMullie\Minify\JS();
                        foreach($fileNames as $jsFileName){
                            $fullJsFilePath = $baseDir.($jsFileName[0] == "/" ? $jsFileName : "/{$jsFileName}");
                            if(is_file($fullJsFilePath) && pathinfo($fullJsFilePath)['extension'] == "js"){
                                $priorityJs->add($fullJsFilePath);
                            }
                        }
                        $priorityJs->minify("{$themeBaseDir}/assets/priority.js");

                        /* theme files */
                        $themeJs = new MatthiasMullie\Minify\JS();
                        $themeJsDirFiles = scandir($themeBaseDir . "/assets/js");
                        foreach ($themeJsDirFiles as $js) {
                            $jsFile = "{$themeBaseDir}/assets/js/{$js}";
                            if (is_file($jsFile) && pathinfo($jsFile)['extension'] == "js") {
                                $themeJs->add($jsFile);
                            }
                        }
                        $themeJs->minify("{$themeBaseDir}/assets/theme.js");
                    }else{
                        continue;
                    }
                }
            }
        }
    }

}