<?php


namespace BetoCampoy\ChampsFramework\Help;


use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Models\Auth\AccessLevel;
use BetoCampoy\ChampsFramework\Models\Auth\Permission;
use BetoCampoy\ChampsFramework\Models\Auth\Role;
use BetoCampoy\ChampsFramework\Models\Auth\RoleHasPermission;
use BetoCampoy\ChampsFramework\Models\Auth\User;
use BetoCampoy\ChampsFramework\Models\Auth\UserHasRole;
use BetoCampoy\ChampsFramework\ORM\Connect;

class Web extends Controller
{
    protected ?string $pathToViews = __DIR__ . "/theme/";

    public function home(?array $data = null): void
    {
        redirect("/champs-docs");
    }


    /**
     * @param array|null $data
     */
    public function documentation(?array $data = null): void
    {
        $page = $data['page'] ?? "home";
        $this->redirect(CHAMPS_URL_DOCUMENTATION . $page);
    }

    /**
     * * Minify theme files based on constant CHAMPS_MINIFY_THEMES array
     *
     * the CHAMPS_MINIFY_THEMES must have the structure bellow
     * define("CHAMPS_MINIFY_THEMES", [
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
     *              "champs-js-engine" => true
     *
     *              // itens bellow are in development
     *              "jquery" => false,
     *              "highcharts" => false,
     *              "tracker" => false,
     *              "select2" => false,
     *              "navflex" => false,
     *              "bootstrap4" => false,
     *              "bootstrap" => false,
     *              "datatables" => false,
     *          ]
     *      ]
     *  ]);
     *
     * @param array|null $data
     */
    public function minify(?array $data = null): void
    {
        if (is_array(CHAMPS_MINIFY_THEMES) && !empty(CHAMPS_MINIFY_THEMES)) {

            $themes = isset(CHAMPS_MINIFY_THEMES['themes']) ? CHAMPS_MINIFY_THEMES['themes'] : [];
            foreach ($themes as $theme => $types) {
                // set theme base dir
                $themeBaseDir = __CHAMPS_DIR__ . "/themes/{$theme}";

                // clear theme js and css files
                if (is_dir($themeBaseDir . "/assets")) {
                    $currentFiles = scandir($themeBaseDir . "/assets");
                    foreach ($currentFiles as $currentFile) {
                        $currentFile = "{$themeBaseDir}/assets/{$currentFile}";
                        if (is_file($currentFile)
                            && in_array(pathinfo($currentFile)['extension'],
                                ["css", "js"])
                        ) {
                            unlink($currentFile);
                        }
                    }
                }

                // generate theme files
                if (is_array($types)) {
                    foreach ($types as $type => $fileNames) {

                        if (strtolower($type) == 'JChamps'
                            && $fileNames == true
                        ) {
                            $jqueryEngineCss = new \MatthiasMullie\Minify\CSS();
                            $jqueryEngineCss->add(__DIR__
                                . "/../Support/frontend/JChamps/JChamps.css");
                            $jqueryEngineCss->minify("{$themeBaseDir}/assets/JChamps.css");
                            $jqueryEngineJs = new \MatthiasMullie\Minify\JS();
                            $jqueryEngineJs->add(__DIR__
                                . "/../Support/frontend/JChamps/JChamps.js");
                            $jqueryEngineJs->minify("{$themeBaseDir}/assets/JChamps.js");
                        } elseif (strtolower($type) == 'jquery-engine'
                            && $fileNames == true
                        ) {
                            $jqueryEngineCss = new \MatthiasMullie\Minify\CSS();
                            $jqueryEngineCss->add(__DIR__
                                . "/../Support/frontend/engine-jquery/engine-jquery.css");
                            $jqueryEngineCss->minify("{$themeBaseDir}/assets/champs-jquery-engine.css");
                            $jqueryEngineJs = new \MatthiasMullie\Minify\JS();
                            $jqueryEngineJs->add(__DIR__
                                . "/../Support/frontend/engine-jquery/engine-jquery.js");
                            $jqueryEngineJs->minify("{$themeBaseDir}/assets/champs-jquery-engine.js");
                        } elseif (strtolower($type) == 'css') {
                            /* priority files */
                            $priorityCss = new \MatthiasMullie\Minify\CSS();
                            foreach ($fileNames as $cssFileName) {
                                $fullCssFilePath = __CHAMPS_DIR__ . ($cssFileName[0]
                                    == "/" ? $cssFileName : "/{$cssFileName}");
                                if (is_file($fullCssFilePath)
                                    && pathinfo($fullCssFilePath)['extension']
                                    == "css"
                                ) {
                                    $priorityCss->add($fullCssFilePath);
                                }
                            }
                            $priorityCss->minify("{$themeBaseDir}/assets/priority.css");
                        } elseif (strtolower($type) == 'js') {
                            /* priority files */
                            $priorityJs = new \MatthiasMullie\Minify\JS();
                            foreach ($fileNames as $jsFileName) {
                                $fullJsFilePath = __CHAMPS_DIR__ . ($jsFileName[0] == "/"
                                        ? $jsFileName : "/{$jsFileName}");
                                if (is_file($fullJsFilePath)
                                    && pathinfo($fullJsFilePath)['extension'] == "js"
                                ) {
                                    $priorityJs->add($fullJsFilePath);
                                }
                            }
                            $priorityJs->minify("{$themeBaseDir}/assets/priority.js");
                        } else {
                            continue;
                        }
                    }

                    /* theme files */
                    if (is_dir($themeBaseDir . "/assets/css")) {
                        $themeCss = new \MatthiasMullie\Minify\CSS();
                        $themeCssDirFiles = scandir($themeBaseDir . "/assets/css");
                        foreach ($themeCssDirFiles as $css) {
                            $cssFile = "{$themeBaseDir}/assets/css/{$css}";
                            if (is_file($cssFile)
                                && pathinfo($cssFile)['extension'] == "css"
                            ) {
                                $themeCss->add($cssFile);
                            }
                        }
                        $themeCss->minify("{$themeBaseDir}/assets/theme.css");
                    }

                    /* theme files */
                    if (is_dir($themeBaseDir . "/assets/js")) {
                        $themeJs = new \MatthiasMullie\Minify\JS();
                        $themeJsDirFiles = scandir($themeBaseDir . "/assets/js");
                        foreach ($themeJsDirFiles as $js) {
                            $jsFile = "{$themeBaseDir}/assets/js/{$js}";
                            if (is_file($jsFile)
                                && pathinfo($jsFile)['extension'] == "js"
                            ) {
                                $themeJs->add($jsFile);
                            }
                        }
                        $themeJs->minify("{$themeBaseDir}/assets/theme.js");
                    }
                }

            }
        }
        redirect(url());
    }

    /**
     * This method is responsible to create the initial data needed for AUTH process
     *
     * @param array|null $data
     */
    public function authInitialData(?array $data = null): void
    {
        $userKey = filter_var($data['user_key'] ?? null, FILTER_SANITIZE_STRIPPED);
        $password = filter_var($data['password'] ?? null, FILTER_SANITIZE_STRIPPED);
        if (!$userKey || !$password) {
            echo champs_messages("init_data_fail_used_not_informed");
            die();
        }

        /* valid if tables exist in database */
        if ((new User())->count() === null) {
            echo champs_messages("init_data_fail_table_not_fount", ["table" => "auth_user"]);
            die();
        }
        if ((new Role())->count() === null) {
            echo champs_messages("init_data_fail_table_not_fount", ["table" => "auth_roles"]);
            die();
        }
        if ((new Permission())->count() === null) {
            echo champs_messages("init_data_fail_table_not_fount", ["table" => "auth_permissions"]);
            die();
        }
        if ((new RoleHasPermission())->count() === null) {
            echo champs_messages("init_data_fail_table_not_fount", ["table" => "auth_role_has_permissions"]);
            die();
        }
        if ((new UserHasRole())->count() === null) {
            echo champs_messages("init_data_fail_table_not_fount", ["table" => "auth_user_has_roles"]);
            die();
        }

        /* valid if there is data in users table */
        $users = (new User())->count();
        if ($users > 0) {
            echo champs_messages("init_data_fail_table_has_data", ["table" => "auth_user"]);
            die();
        }

        /* validate the access levels registered in database */
        if ((new AccessLevel())->where("id = :id", "id=1")->count() == 0) {
            echo champs_messages("init_data_fail_level_missing", ["id" => 1, "name" => "Administrator"]);
            die();
        }

        if ((new AccessLevel())->where("id = :id", "id=2")->count() == 0) {
            echo champs_messages("init_data_fail_level_missing", ["id" => 2, "name" => "Operator"]);
            die();
        }

        if ((new AccessLevel())->where("id = :id", "id=3")->count() == 0) {
            echo champs_messages("init_data_fail_level_missing", ["id" => 3, "name" => "Client"]);
            die();
        }

        /* validate the default roles are registered in database */
        if ((new Role())->where("id = :id", "id=1")->count() == 0) {
            echo champs_messages("init_data_fail_role_missing", ["id" => 1, "name" => "Master Administrator"]);
            die();
        }

        if ((new Role())->where("id = :id", "id=2")->count() == 0) {
            echo champs_messages("init_data_fail_role_missing", ["id" => 2, "name" => "Master Operator"]);
            die();
        }

        if ((new Role())->where("id = :id", "id=3")->count() == 0) {
            echo champs_messages("init_data_fail_role_missing", ["id" => 3, "name" => "Master Client"]);
            die();
        }

        /* create initial permission */
        $permissions = [
            "Master Admin Only" => [1],
            "admin panel list" => [1],
            "admin panel parameters list" => [1],
            "admin panel parameters manage" => [1],
            "admin panel dbconn list" => [1],
            "admin panel dbconn manage" => [1],
            "admin panel navigation list" => [1],
            "admin panel navigation manage" => [1],

            "permissions list" => [1, 2, 3],
            "permissions create" => [1],
            "permissions update" => [1],
            "permissions view" => [1],
            "permissions delete" => [1],

            "roles list" => [1, 2, 3],
            "roles create" => [1],
            "roles update" => [1],
            "roles view" => [1],
            "roles delete" => [1],

            "users list" => [1, 2, 3],
            "users create" => [1, 2, 3],
            "users update" => [1, 2, 3],
            "users view" => [1, 2, 3],
            "users delete" => [1, 2, 3],


        ];

        foreach ($permissions as $permission_name => $roles_ids) {

            $newPermission = (new Permission())->find("name=:name", "name={$permission_name}");

            if ($newPermission->count() == 0) {
                $newPermission->name = str_title($permission_name);
                if ($newPermission->save()) {
                    echo ">>>> [OK] Permission #{$newPermission->id} {$newPermission->name} succefully created<br>";
                } else {
                    echo ">>>> [NOK] Error to persist Permission {$permission_name} in database!<br>";
                }
            } else {
                $newPermission = $newPermission->fetch();
            }

            foreach ($roles_ids as $role_id) {

                $role = (new Role)->findById($role_id);

                if (!$role) {
                    echo "########## [NOK] Role [{$role_id}] invalid<br>";
                    continue;
                }

                $roleHasPermission = (new RoleHasPermission())
                    ->find(
                        "role_id=:role_id AND permission_id=:permission_id",
                        "role_id={$role_id}&permission_id={$newPermission->id}"
                    );
                if ($roleHasPermission->count() > 0) {
                    echo "[NOK] Permission [{$newPermission->name}] has already assigned to rule {$role_id}<br>";
                    continue;
                }
                $roleHasPermission->role_id = $role_id;
                $roleHasPermission->permission_id = $newPermission->id;
                if ($roleHasPermission->save()) {
                    echo "<li>[OK] Permission assigned to role [#{$roleHasPermission->id} {$role->name}]</li>";
                    $roleHasPermission->id = null;
                }
            }
            $newPermission = null;
        }

        /* insert first admin user */
        echo "teste";
        $user = new User();
        $user->email = $userKey;
        $user->password = passwd($password);
        $user->name = "Master Admin User";
        $user->access_level_id = 1;
        $user->active = 1;
        $user->status = 'confirmed';
        if (!$user->save()) {
            echo champs_messages("init_data_fail_user_creation", ['name' => $userKey]);
            die();
        }

        /* Assigned master admin role to new user */
        $userHasRole = (new UserHasRole());
        $userHasRole->user_id = $user->id;
        $userHasRole->role_id = 1;
        if (!$userHasRole->save()) {
            echo champs_messages("init_data_fail_user_assignee", ['name' => $userKey, "role" => "Master Admin"]);
            die();
        }

        $this->message->success(champs_messages("init_data_fail_success"))->flash();
        $this->redirect(url());
    }

    /**
     * Redirect to Maintenance page
     * @param array|null $data
     */
    public function maintenance(?array $data = null): void
    {
        $seo = $this->seo->render(
            CHAMPS_SEO_SITE_NAME . " Under Maintenance",
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(),
            __champshelp_theme('/asset/images/favicon.ico')
        );

        echo $this->view->render("maintenance", ["router" => $this->router, "seo" => $seo]);
    }

//    /**
//     * Redirect to Forbidden page
//     * @param array|null $data
//     */
//    public function forbidden(?array $data = null): void
//    {
//        echo $this->view->render("error", ["router" => $this->router]);
//    }

    /**
     * Redirect to Error page
     * @param array|null $data
     */
    public function error(array $data): void
    {
        $error = new \stdClass();

        switch ($data['errcode']) {
            case "problems":
                $error->code = "Uhuups!";
                $error->title = champs_messages("problems_page_title");
                $error->message = champs_messages("problems_page_message");
                $error->linkTitle = CHAMPS_MAIL_ENABLED && !empty(CHAMPS_MAIL_SUPPORT) ? champs_messages("problems_page_send_email") : null;
                $error->link = CHAMPS_MAIL_ENABLED && !empty(CHAMPS_MAIL_SUPPORT) ? "mailto:" . CHAMPS_MAIL_SUPPORT : null;
                break;

            case "maintenance":
                $error->code = "Uhuups!";
                $error->title = champs_messages("maintenance_page_title");
                $error->message = champs_messages("maintenance_page_message");
                $error->linkTitle = null;
                $error->link = null;
                break;

            case "forbidden":
                $error->code = "Uhuups!";
                $error->title = champs_messages("forbidden_page_title");
                $error->message = champs_messages("forbidden_page_message");
                $error->linkTitle = champs_messages("forbidden_page_button_caption");
                $error->link = url_back();
                break;

            default:
                $error->code = $data['errcode'];
                $error->title = champs_messages("error_page_title");
                $error->message = champs_messages("error_page_message");
                $error->linkTitle = champs_messages("error_page_button_caption");
                $error->link = url_back();
                break;
        }

        $seo = $this->seo->render(
            "{$error->code} | {$error->title}",
            $error->message,
            url("/uhups/error/{$error->code}"),
            __champshelp_theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("error", [
            "router" => $this->router,
            "seo" => $seo,
            "error" => $error
        ]);
    }
}