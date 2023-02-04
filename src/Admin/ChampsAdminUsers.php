<?php


namespace BetoCampoy\ChampsFramework\Admin;


use BetoCampoy\ChampsFramework\Controller\Contracts\ResourceController;
use BetoCampoy\ChampsFramework\Models\Auth\AccessLevel;
use BetoCampoy\ChampsFramework\Models\Auth\Permission;
use BetoCampoy\ChampsFramework\Models\Auth\User;
use BetoCampoy\ChampsFramework\Models\Navigation;
use BetoCampoy\ChampsFramework\Pager;
use BetoCampoy\ChampsFramework\Support\Validator\Validators\NavigationValidator;
use BetoCampoy\ChampsFramework\Support\Validator\Validators\PermissionValidator;

class ChampsAdminUsers extends ChampsAdmin implements ResourceController
{
    protected ?string $modelClass = User::class;

    /*******************************
     * AUTHENTICATION - USERS
     ******************************/

    public function usersSearch(?array $data = []): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("users list");

        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            $json['redirect'] = $this->router->route("champs.admin.usersSearchGet", ["search" => $s, "page" => 1]);
            echo json_encode($json);
            return;
        }
        $json['redirect'] = $this->router->route("champs.admin.usersSearchGet", ["search" => "all", "page" => 1]);
        echo json_encode($json);
        return;

    }

//    public function usersList(?array $data = null): void
//    {
//        $users = (new User());
//        if(!$users->entityExists()){
//            $this->message->error("The database table not found. Make sure to create it before continue.
//            Check documentation for more information")->flash();
//            $this->router->redirect("champs.admin.authHome");
//        }
//
//        /* check if user has access */
//        hasPermissionRedirectIfFail("users list");
//
//        $search = null;
//        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
//            $search = str_search($data["search"]);
//            $users->where("MATCH(m.name, m.last_name, m.email) AGAINST(:s)", "s={$search}");
//            if (!$users->count()) {
//                $this->message->info(champs_messages("registers_not_found_in_model"))->flash();
//                $this->router->redirect($this->router->route("champs.admin.usersList"));
//            }
//        }
//
//        $all = ($search ?? "all");
//        $page = !empty($data["page"]) ? $data["page"] : 1;
//        $pager = new Pager($this->router->route("champs.admin.usersPager"));
//        $totalCounter = $users->count();
//        $pager->pager($totalCounter, 10, $page, 2);
//        $users->limit($pager->limit())->offset($pager->offset())->order("m.name DESC");
//
//        $seo = $this->seo->render(
//            "Manage Users",
//            CHAMPS_SEO_SITE_DESCRIPTION,
//            url(current_url()),
//            __champsadm_theme("/assets/images/favicon.ico?123"),
//            false
//        );
//
//        echo $this->view->render("widgets/auth/users-list", [
//            "seo" => $seo,
//            "title" => "Manage Users",
//            "router" => $this->router,
//            "navbar" => $this->navbar,
//            "users" => $users->order("m.name ASC"),
//            "pager" => $pager
//        ]);
//    }

//    public function usersCreate(?array $data = null): void
//    {
//        /* check if user has access */
//        hasPermissionRedirectIfFail("users create");
//
//        $json['modalFormBS5']['form'] = $this->view->render("widgets/auth/permissions_modal_create", [
//            "router" => $this->router,
//        ]);
//        echo json_encode($json);
//        return;
//    }

//    public function usersSave(?array $data = null): void
//    {
//        /* check if user has access */
//        hasPermissionRedirectIfFail("users create");
//
//        if (isset($data['parent_id']) && empty($data['parent_id'])) unset($data['parent_id']);
//        $validator = new PermissionValidator($data);
//        $validation = $validator->make();
//        $validation->validate();
//
//        if ($errors = $validator->errors($validation)) {
//            $json['message'] = $this->message->error($errors)->render();
//            echo json_encode($json);
//            return;
//        }
//
//        $permission = (new Permission());
//        $permission->fill($data);
//        if (!$permission->save()) {
//            var_dump($permission);
//            die();
//        }
//        $json['reload'] = true;
//        echo json_encode($json);
//        return;
//
//    }

    public function usersEdit(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("users update");

        $permission = (new Permission())->findById($data['id']);

        $json['modalFormBS5']['form'] = $this->view->render("widgets/auth/permissions_modal_edit", [
            "router" => $this->router,
            "permission" => $permission,
        ]);
        echo json_encode($json);
        return;
    }

    public function usersUpdate(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("users update");

        if (isset($data['parent_id']) && empty($data['parent_id'])) unset($data['parent_id']);
        $validator = new NavigationValidator($data);
        $validation = $validator->make();
        $validation->validate();

        if ($errors = $validator->errors($validation)) {
            $json['message'] = $this->message->error($errors)->render();
            echo json_encode($json);
            return;
        }

        $navigation = (new Navigation())->findById($data['id']);
        $navigation->fill($data);
        $navigation->save();
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    public function usersDelete(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("users delete");

        /* faz as validações */

        $permission = (new Permission())->findById($data['id']);
        $name = $permission->name;
        if (!$permission->destroy()) {
            var_dump($permission);
            die();
        }
        $this->message->success("The item {$name} deleted from database")->flash();
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    public function usersFilterRoot(?array $data = null)
    {
        $themeName = isset($data['theme_name']) ? filter_var($data['theme_name'], FILTER_SANITIZE_STRING) : null;
        $rootItems = Navigation::rootItems($themeName)->columns("id, display_name");

        $data = ["" => "Add as a root item"];
        foreach ($rootItems->fetch(true) as $rootItem) {
            $data[$rootItem->id] = "Child of {$rootItem->display_name}";
        }
        echo json_encode(["counter" => count($data), "status" => "success", "data" => $data]);
    }

    public function list(?array $data): void
    {
        $searchForm = $this->searchForm(array_merge($_GET, $data));

        $page = !empty($data["page"]) ? $data["page"] : 1;
        $pager = new Pager($this->router->route("champs.admin.usersPager"));
        if ($this->loadedModel && ($totalCounter = $this->loadedModel->count()) > 0) {
            /* if there isn't filters, show the last 30 days */
            $totalCounter = $this->loadedModel->count();
            $pager->pager($totalCounter, CHAMPS_PAGER_LIMIT, $page, 2, null, $searchForm->formData());
            $this->loadedModel->limit($pager->limit())->offset($pager->offset())->order("id DESC");
        }

        $seo = $this->seo->render(
            "Manage Users",
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/auth/users-list", [
            "seo" => $seo,
            "title" => "Manage Users",
            "router" => $this->router,
            "navbar" => $this->navbar,
            "users" => $this->loadedModel->order("m.name ASC"),
            "pager" => $pager
        ]);
    }

    public function create(): void
    {
        $json['modal'] = $this->view->render("widgets/auth/users_modal_create", [
            "router" => $this->router,
            "accessLevels" => (new AccessLevel())->filteredDataByAuthUser()
        ]);
        echo json_encode($json);
        return;
    }

    public function store(?array $data)
    {
        $this->loadedModel->fill($data);

        if (!$this->loadedModel->save()) {
            $json["message"] = $this->loadedModel->message()->render();
            echo json_encode($json);
            return;
        }

        $this->message->success("Cadastro realizado com sucesso...")->flash();

        /* envia email de boas vindas */
        if(CHAMPS_EMAIL_MODULE_ON){
            if(!(new User())->confirm($this->loadedModel->email)){
                $this->message->after("Falha ao enviar o e-mail");
            }
        }
        return;
    }

    public function edit(?array $data): void
    {
        // TODO: Implement edit() method.
    }

    public function update(?array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete(?array $data)
    {
        // TODO: Implement delete() method.
    }
}