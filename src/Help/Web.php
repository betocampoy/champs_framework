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
        $seo = $this->seo->render(
            "CHAMPSframework Documents",
            "CHAMPSframework Documents",
            url(),
            help_theme('/asset/images/favicon.ico')
        );

        $page = $data['page'] ?? "home";
        if (!file_exists(__DIR__ . "/theme/{$page}.php")) {
            echo "It was not possible load the CHAMPSframework manual";
            return;
        }

        echo $this->view->render($page, ["seo" => $seo]);
    }

    /**
     * * Minify theme files based on constant CHAMPS_MINIFY_THEMES array
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

                        if (strtolower($type) == 'jquery-engine'
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
                                $fullCssFilePath = $themeBaseDir . ($cssFileName[0]
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
                                $fullJsFilePath = $themeBaseDir . ($jsFileName[0] == "/"
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

    public function authInitialData(?array $data = null): void
    {
        $userKey = filter_var($data['user_key'] ?? null, FILTER_SANITIZE_STRIPPED);
        $password = filter_var($data['password'] ?? null, FILTER_SANITIZE_STRIPPED);
        if (!$userKey || !$password) {
            echo "To create the first CHAMPSframework admin user, use the route /auth_initial_data/{user_key}/{password}. 
            Replace {user_key} by email, cpf or mobile (according framework configuration) and {password} by your password.
            For security purpose, create difficult passwords";
            die();
        }

        /* valid if tables exist in database */
        if((new User())->count() === null){
            echo "The table [auth_user] must be created. Consult the documentation in route /champs-docs/auth_model";
            die();
        }
        if((new Role())->count() === null){
            echo "The table [auth_roles] must be created. Consult the documentation in route /champs-docs/auth_model";
            die();
        }
        if((new Permission())->count() === null){
            echo "The table [auth_permissions] must be created. Consult the documentation in route /champs-docs/auth_model";
            die();
        }
        if((new RoleHasPermission())->count() === null){
            echo "The table [auth_role_has_permissions] must be created. Consult the documentation in route /champs-docs/auth_model";
            die();
        }
        if((new UserHasRole())->count() === null){
            echo "The table [auth_user_has_roles] must be created. Consult the documentation in route /champs-docs/auth_model";
            die();
        }

        /* valid if there is data in users table */
        $users = (new User())->count();
        if ($users > 0) {
            echo "There is data in [auth_users] in database, so this feature can't be used anymore!";
            die();
        }

        /* validate the access levels registered in database */
        if ((new AccessLevel())->where("id = :id", "id=1")->count() == 0){
            echo "Access Level [Administrator] must exists in database under id [1]. Check documentation if necessary";
            die();
        }

        if ((new AccessLevel())->where("id = :id", "id=2")->count() == 0){
            echo "Access Level [Operator] must exists in database under id [2]. Check documentation if necessary";
            die();
        }

        if ((new AccessLevel())->where("id = :id", "id=3")->count() == 0){
            echo "Access Level [Client] must exists in database under id [3]. Check documentation if necessary";
            die();
        }

        /* validate the default roles are registered in database */
        if ((new Role())->where("id = :id", "id=1")->count() == 0){
            echo "Role [Master Administrator] must exists in database under id [1]. Check documentation if necessary";
            die();
        }

        if ((new Role())->where("id = :id", "id=2")->count() == 0){
            echo "Role [Master Operator] must exists in database under id [2]. Check documentation if necessary";
            die();
        }

        if ((new Role())->where("id = :id", "id=3")->count() == 0){
            echo "Role [Master Client] must exists in database under id [3]. Check documentation if necessary";
            die();
        }

        /* create initial permission */
        $permissions = [
            "Master Admin Only" => [1],

            "permissions list" => [1,2,3],
            "permissions create" => [1],
            "permissions update" => [1],
            "permissions view" => [1],
            "permissions delete" => [1],

            "roles list" => [1,2,3],
            "roles create" => [1],
            "roles update" => [1],
            "roles view" => [1],
            "roles delete" => [1],

            "users list" => [1,2,3],
            "users create" => [1,2,3],
            "users update" => [1,2,3],
            "users view" => [1,2,3],
            "users delete" => [1,2,3],


        ];

        foreach ($permissions as $permission_name => $roles_ids){

            $newPermission = (new Permission())->find("name=:name", "name={$permission_name}");

            if ( $newPermission->count() == 0) {
                $newPermission->name = str_title($permission_name);
                if ($newPermission->save()) {
                    echo ">>>> [OK] Permissão #{$newPermission->id} {$newPermission->name} criada com sucesso<br>";
                }
                else{
                    echo ">>>> [NOK] Erro ao salvar a Permissão {$permission_name}!<br>";
                }
            }
            else{
                $newPermission = $newPermission->fetch();
            }

            foreach ($roles_ids as $role_id){

                $role = (new Role)->findById($role_id);

                if(!$role){
                    echo "########## [NOK] Role [{$role_id}] invalid<br>";
                    continue;
                }

                $roleHasPermission = (new RoleHasPermission())
                    ->find(
                        "role_id=:role_id AND permission_id=:permission_id",
                        "role_id={$role_id}&permission_id={$newPermission->id}"
                    );
                if($roleHasPermission->count() > 0){
                    echo "[NOK] Permissão [{$newPermission->name}] já atribuida no perfil {$role_id}<br>";
                    continue;
                }
                $roleHasPermission->role_id = $role_id;
                $roleHasPermission->permission_id = $newPermission->id;
                if($roleHasPermission->save()){
                    echo "<li>[OK] Incluida na role [#{$roleHasPermission->id} {$role->name}]</li>";
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
        if(!$user->save()){
            echo "Fail to create admin user<br>";
            var_dump($user);
            die();
        }

        /* Assigned master admin role to new user */
        $userHasRole = (new UserHasRole());
        $userHasRole->user_id = $user->id;
        $userHasRole->role_id = 1;
        if(!$userHasRole->save()){
            echo "Fail to Assigned master admin role to new user<br>";
            var_dump($userHasRole);
            die();
        }

        echo "Success finished!";
    }


    public function maintenance(?array $data = null): void
    {
        $seo = $this->seo->render(
            CHAMPS_SITE_TITLE . " Under Maintenance",
            CHAMPS_SITE_DESCRIPTION,
            url(),
            help_theme('/asset/images/favicon.ico')
        );

        echo $this->view->render("maintenance", ["seo" => $seo]);
    }

    public function forbidden(?array $data = null): void
    {
        echo $this->view->render("forbidden", []);
    }

    public function error(array $data): void
    {
        $error = new \stdClass();

        switch ($data['errcode']) {
            case "problemas":
                $error->code = "OPS";
                $error->title = "Estamos enfrentando problemas!";
                $error->message = "Parece que nosso serviço não está diponível no momento. Já estamos vendo isso mas caso precise, envie um e-mail :)";
                $error->linkTitle = "ENVIAR E-MAIL";
                $error->link = "mailto:" . CHAMPS_MAIL_SUPPORT;
                break;

            case "manutencao":
                $error->code = "OPS";
                $error->title = "Desculpe. Estamos em manutenção!";
                $error->message = "Voltamos logo! Por hora estamos trabalhando para melhorar nosso conteúdo para você controlar melhor as suas contas :P";
                $error->linkTitle = null;
                $error->link = null;
                break;

            default:
                $error->code = $data['errcode'];
                $error->title = "Ooops. Conteúdo indispinível :/";
                $error->message = "Sentimos muito, mas o conteúdo que você tentou acessar não existe, está indisponível no momento ou foi removido :/";
                $error->linkTitle = "Continue navegando!";
                $error->link = url_back();
                break;
        }

        $seo = $this->seo->render(
            "{$error->code} | {$error->title}",
            $error->message,
            url("/uhups/error/{$error->code}"),
            help_theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("error", [
            "seo" => $seo,
            "error" => $error
        ]);
    }
}