<?php

namespace Source\App;

use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Navbar\Templates\Bootstrap3;
use Source\Models\User\User;

/**
 * Class WebTests
 * @package Source\App
 */
class WebTests extends Controller
{
    protected bool $protectedController = true;

    public function __call($name, $arguments)
    {
        $seo = $this->seo->render(
            CHAMPS_SEO_SITE_NAME,
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(),
            theme('/asset/champs-logo.png')
        );

        echo $this->view->render("$name", [
            "router" => $this->router,
            "seo" => $seo
        ]);
    }

    /**
     * @param array|null $data
     */
    public function home(?array $data): void
    {
        $this->validations();

        if (is_theme_minified("web") && !file_exists(__CHAMPS_THEME_DIR__ . "/web/assets/priority.css")) {
            $this->redirect("/do-minify");
        }

        $seo = $this->seo->render(
            CHAMPS_SEO_SITE_NAME . " Home",
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(),
            theme("/assets/images/favicon.ico")
        );

        /* mount navbar */
        $navbar = (new Bootstrap3())
            ->setRootItem("Home", "/")
            ->setRootItem("Teste1")
            ->setChildItem("subteste1", "/teste1")
            ->setChildItem("subteste2", "/teste2")
            ->setRootItem("Teste2", "/teste3");

        $this->message->error("teste de mensagem")->flash();

        $page = $data['page'] ?? 'home';
        echo $this->view->render($page, [
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $navbar,
        ]);
    }

    public function logout(?array $data): void
    {
        $this->redirect($this->router->route("logout"));
    }

    public function testPost(?array $data)
    {
        $this->validations();

        $dataResp = [
            "data_post" => $data,
            "data_response" => [
                "error" => null,
                "counter" => 3,
                "data" => [
                    "id" => "Value",
                ]
            ]
        ];
        $json['customFunction'] = ["function" => "functTest", "data" => $dataResp];
        echo json_encode($json);
    }

    public function tests(?array $data)
    {
//        $this->validations();

        $seo = $this->seo->render(
            CHAMPS_SEO_SITE_NAME . " Home",
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(),
            theme("/assets/images/favicon.ico")
        );

        echo $this->view->render("tests", [
            "router" => $this->router,
            "seo" => $seo,
            "users" => (new User())
        ]);

    }

}