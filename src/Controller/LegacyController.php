<?php

namespace BetoCampoy\ChampsFramework\Controller;

use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Models\Auth\User;

use BetoCampoy\ChampsFramework\Pager;

/**
 * Example Web Controller
 * @package Source\App
 */
class LegacyController extends Controller
{
    protected ?string $pathToViews = __CHAMPS_THEME_DIR__."/legacy-pages/";


    /**
     * @param array|null $data
     */
    public function home(?array $data = null): void
    {

        $page = $data['page'];
        if(strpos($page, ".php") !== false){
            /* access without friendly url */

            $fileName = __CHAMPS_THEME_DIR__."/legacy-pages/{$data['page']}";
            if(file_exists($fileName) && is_file($fileName) ){
                $page = str_replace(".php", "", $data['page']);
            }else{
                $page = "not_found";
            }
        }


        $seo = $this->seo->render(
            CHAMPS_SITE_NAME . " {$page}",
            CHAMPS_SITE_DESCRIPTION,
            url(),
            theme("/assets/images/favicon.ico")
        );

        echo $this->view->render($page, [
            "data" => $data,
            "getData" => $_GET,
            "router" => $this->router,
            "seo" => $seo
        ]);
    }

}