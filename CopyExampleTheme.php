<?php

// Web example controller
if (!file_exists(__CHAMPS_DIR__ . "/Source/App/WebExample.php") && !is_file(__CHAMPS_DIR__ . "/Source/App/WebExample.php")) {
    copy(__VENDOR_DIR__ . "/src/Help/initial_templates/WebExample.php"
        , __CHAMPS_DIR__ . "/Source/App/WebExample.php");
}
// Web Example Theme
if (!file_exists(__CHAMPS_DIR__ . "/themes/web") && !is_dir(__CHAMPS_DIR__ . "/themes/web")) {
    copyr(__VENDOR_DIR__ . "/src/Help/initial_templates/example_theme",
        __CHAMPS_THEME_DIR__ . "/web");
}

full_folder_path("themes/web");
full_folder_path("themes/admin");
full_folder_path("themes/app");
full_folder_path("themes/opr");
full_folder_path("themes/email");