<?php
/* DEFAULT SITE'S LANGUAGE */
define("CHAMPS_SITE_LANG", "en");

/* DEFINE WHERE ENVIRONMENT IS RUNNING [PRD] FOR PRODUCTION, [DEV] FOR DEVELOPMENT AND [UAT] FOR TESTS AND ACCEPTANCE */
define('CHAMPS_ENVIRONMENT_IDENTIFIER', "");

/* DEFINE THE SESSION NAME. IT IS IMPORTANT TO INFORM A EXCLUSIVE NAME IF YOU WILL RUNNING MORE THAN ONE APP USING CHAMPS FRAMEWORK IN SAME SERVER */
define('CHAMPS_SESSION_NAME', "");

/* DEFINE THE DATABASE CONNECTION */
define('CHAMPS_DB_CONNECTIONS', []);

/* DEFINE THE URL OF PROJECTS */
define('CHAMPS_URL_DEV', "");
define('CHAMPS_URL_UAT', "");
define('CHAMPS_URL_PRD', "");

/* CONFIGURE THE MINIFICATION OF THEMES */
define('CHAMPS_MINIFY_THEMES', [
    "themes" => [
        "web" => [
            "css" => [],
            "js" => [],
            "jquery-engine" => true
        ]
    ]
]);

/* SEO */
define("CHAMPS_SITE_NAME", "Example Site Name");
define("CHAMPS_SITE_TITLE", "Example Site Title");
define("CHAMPS_SITE_DESCRIPTION", "Example Site Description");
define("CHAMPS_SITE_DOMAIN", "exampling.com.br");
define("CHAMPS_SITE_ADDR_STREET", "Company Address");
define("CHAMPS_SITE_ADDR_NUMBER", "Number");
define("CHAMPS_SITE_ADDR_COMPLEMENT", "Complement");
define("CHAMPS_SITE_ADDR_CITY", "City");
define("CHAMPS_SITE_ADDR_STATE", "State");
define("CHAMPS_SITE_ADDR_ZIPCODE", "11111-111");
