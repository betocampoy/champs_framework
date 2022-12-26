<?php


namespace BetoCampoy\ChampsFramework;

/**
 * Class Navigation - Dynamically Render the navigation bar of you application
 *
 * pre-requisites
 * 1. Database table [navigation]
 *
 *      -- Mysql command to create table
 *      CREATE TABLE `navigation`( `id` INT NOT NULL AUTO_INCREMENT, `file_name` VARCHAR(255) NULL,`route` VARCHAR(255) NULL,`display_name` VARCHAR(255) NOT NULL,`visible` SMALLINT(1) NOT NULL DEFAULT '1',`parent_id` INT NULL,`sequence` INT NOT NULL,`section_init` SMALLINT(1) NULL DEFAULT '0',`pemissions` VARCHAR(255) NULL,`external_functions` VARCHAR(255) NULL,`public` SMALLINT(1) NULL DEFAULT '0',PRIMARY KEY(`id`)) ENGINE = INNODB;
 *
 * 2. Environment constants
 *      CHAMPS_FRIENDLY_URL : a bool value defining if app uses friendly URLs
 *      CHAMPS_NAVBAR_STYLE : optional. If not defined, will be created the navbar default, so create a css file to stylize you nav
 *                            Consulte navbar.css in example/assets/css folder for an example
 *
 *                            If defined, must be an associative array where key is the theme name and value the navbar format
 *                            Available formats: bootstrap, navflex (needs ajusts)
 *
 *                            example: define('CHAMPS_NAVBAR_STYLE', [CHAMPS_VIEW_WEB => 'default', CHAMPS_VIEW_ADM => 'bootstrap']);
 *
 * usage
 *
 * // instance navitation object
 * $myNavbar = new \BetoCampoy\ChampsFramework\Navigation('theme_name');
 * //render the navbar in page
 * $myNavbar->render();
 *
 * @package BetoCampoy\ChampsFramework
 */
class Navigation
{
    protected string $navbarStyle = "default";
    protected string $navbarTheme;
    protected bool $friendlyUrls;

    /**
     * Navigation constructor.
     *
     * @param string $theme
     */
    public function __construct(string $theme = 'web')
    {
        $this->navbarTheme = $theme;
        $this->friendlyUrls = defined('CHAMPS_FRIENDLY_URL') ? CHAMPS_FRIENDLY_URL : true;

        if(!session()->has("navbar_{$this->navbarTheme}") || session()->navbar_user != session()->authUser){
            session()->set("navbar_user", session()->authUser ?? null);
            $this->mountNavbar();
        }
    }

    /**
     * Mount navbar in user's session
     *
     * @return $this
     */
    protected function mountNavbar():Navigation
    {
        if(defined("CHAMPS_NAVBAR_STYLE") && isset(CHAMPS_NAVBAR_STYLE[$this->navbarTheme]) && method_exists($this, CHAMPS_NAVBAR_STYLE[$this->navbarTheme])){
            $action = CHAMPS_NAVBAR_STYLE[$this->navbarTheme];
        }else{
            $action = $this->navbarStyle;
        }
        $navbar = $this->$action();

        \session()->set("navbar_{$this->navbarTheme}", $navbar);

        return $this;
    }

    /**
     * Render the navbar available in user's session
     */
    public function render():void
    {
        if(\session()->has("navbar_{$this->navbarTheme}")){
            $navbar = "navbar_{$this->navbarTheme}";
            echo \session()->$navbar;
        }
    }

    protected function default():string
    {
        return "default";
    }

    protected function defaultItens($parent):string
    {

    }

    /**
     * @return string
     */
    protected function navflex():string
    {
        $rootItens = \BetoCampoy\ChampsFramework\Models\Navigation::rootItens();

        $navbarItens = "";
        $navbarStart = "
            <div class='container'>
                <div class='menu-button'>Menu</div>
                    <ul class='flexnav' data-breakpoint='800''>";

        if($rootItens->count() > 0) {
            foreach ($rootItens->fetch(true) as $parent) {
                $navbarItens .= $this->defaultItens($parent);
            }
        }
        else{
            $navbarItens = "não há itens cadastrados para navegação";
        }
        $navbarClose = "
            			</ul>
                </div>
            </div>";


        return $navbarStart . $navbarItens . $navbarClose;
    }

    /**
     * @return string
     */
    protected function navflexItens($parent):string
    {
        $childItens = $parent->children()
          ->where("visible = :visible", "visible=1")
          ->order("sequence ASC");

        $fileName = $parent->file_name;
        $displayName = $parent->display_name;
        $link = $this->friendlyUrls
          ? $parent->route
          : (substr(strtolower($parent->fileName), -4, 4) == ".php" ? $parent->fileName : $parent->fileName.".php");
        $sectionInit = (intval($parent->section_init) == 1 ) ? " <li role='separator' class='divider'></li> " : "";
        $externalFunctions = (!empty($parent->external_functions))  ? $parent->external_functions : "";
        $public = $parent->public;
        $permissions = explode(";", $parent->permissions);
        $naviten = "";

        $hasPermission = true;//hasPermission($permissions, false);
        if ($public || $hasPermission) {

            if ($childItens->count() > 0){
                $dropdownClass = empty($parent->parent_id) ? "dropdown" : "dropdown-submenu";
                $navitenStart = "
                    <li>
                        <a href='#'>$displayName</a>
                        <ul> ";
                foreach ($childItens->fetch(true) as $childItem) {
                    $naviten .= $this->bootstrapItens($childItem);
                };

                $navitenClose = "</ul>
                    </li>";

                $naviten = $navitenStart . $naviten . $navitenClose;
            }else{
                $naviten = "{$sectionInit}<li><a href='". url($link) ."' {$externalFunctions}>{$displayName}</a></li>";
            }

        }

        return $naviten;
    }

    /**
     * @return string
     */
    protected function bootstrap():string
    {

        $rootItens = \BetoCampoy\ChampsFramework\Models\Navigation::rootItens();

        $navbarItens = "";
        $navbarStart = "
            <nav class='navbar navbar-default'>
                <div class='container-fluid'>
                    <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#meu-nav-bar' aria-expanded='false'>
                        <span class='sr-only'>Toggle navigation</span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                    </button>
                    <div class='navbar-header'>
                        <a class='navbar-brand' href='/'><i class='glyphicon glyphicon-home'></i></a>
                    </div>
                    <div class='collapse navbar-collapse' id='meu-nav-bar' >
                        <ul class='nav navbar-nav'>";

        if($rootItens->count() > 0) {
            foreach ($rootItens->fetch(true) as $parent) {
                $navbarItens .= $this->bootstrapItens($parent);
            }
        }
        else{
            $navbarItens = "não há itens cadastrados para navegação";
        }
        $navbarClose = "
            			</ul>
                    </div>
                </div>
            </nav>";


        return $navbarStart . $navbarItens . $navbarClose;
    }

    /**
     * @param $parent
     *
     * @return string
     */
    private function bootstrapItens($parent):string
    {
        $childItens = $parent->children()
          ->where("visible = :visible", "visible=1")
          ->order("sequence ASC");

        $fileName = $parent->file_name;
        $displayName = $parent->display_name;
        $link = $this->friendlyUrls
          ? $parent->route
          : (substr(strtolower($parent->fileName), -4, 4) == ".php" ? $parent->fileName : $parent->fileName.".php");
        $sectionInit = (intval($parent->section_init) == 1 ) ? " <li role='separator' class='divider'></li> " : "";
        $externalFunctions = (!empty($parent->external_functions))  ? $parent->external_functions : "";
        $public = $parent->public;
        $permissions = explode(";", $parent->permissions);
        $naviten = "";

        $hasPermission = true;//hasPermission($permissions, false);
        if ($public || $hasPermission) {

            if ($childItens->count() > 0){
                $dropdownClass = empty($parent->parent_id) ? "dropdown" : "dropdown-submenu";
                $navitenStart = "
                    <li class='nav-item $dropdownClass'>
                        <a class='nav-link dropdown-toggle' href='#' id='$fileName' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$displayName<span class='caret'></a>
                        <ul class='nav-item dropdown-menu' aria-labelledby='$fileName'>
                              ";
                foreach ($childItens->fetch(true) as $childItem) {
                    $naviten .= $this->bootstrapItens($childItem);
                };

                $navitenClose = "</ul>
                    </li>";

                $naviten = $navitenStart . $naviten . $navitenClose;
            }else{
                $naviten = "{$sectionInit}<li><a href='". url($link) ."' {$externalFunctions}>{$displayName}</a></li>";
            }

        }

        return $naviten;
    }

    /**
     * @return string
     */
    protected function bootstrap4():string
    {
        return "<h1>Menu Bootstrap4</h1>";
    }

}