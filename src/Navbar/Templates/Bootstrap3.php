<?php


namespace BetoCampoy\ChampsFramework\Navbar\Templates;


use BetoCampoy\ChampsFramework\Navbar\Navbar;

class Bootstrap3 extends Navbar
{

//    protected array $navItems = [
//        ["menu1" => []]
//    ];
//    protected function bootstrap(): string
//    {
//
//        $rootItens = $this->rootItems();
//
//        $navbarItens = "";
//        $navbarStart = "
//            <nav class='navbar navbar-default'>
//                <div class='container-fluid'>
//                    <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#meu-nav-bar' aria-expanded='false'>
//                        <span class='sr-only'>Toggle navigation</span>
//                        <span class='icon-bar'></span>
//                        <span class='icon-bar'></span>
//                        <span class='icon-bar'></span>
//                        <span class='icon-bar'></span>
//                    </button>
//                    <div class='navbar-header'>
//                        <a class='navbar-brand' href='/'><i class='glyphicon glyphicon-home'></i></a>
//                    </div>
//                    <div class='collapse navbar-collapse' id='meu-nav-bar' >
//                        <ul class='nav navbar-nav'>
//                            <li class='nav-item $dropdownClass'>
//                                <a class='nav-link dropdown-toggle' href='#' id='$fileName' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$displayName<span class='caret'></a>
//                                <ul class='nav-item dropdown-menu' aria-labelledby='$fileName'>
//
//                                </ul>
//                            </li>
//            			</ul>
//                    </div>
//                </div>
//            </nav>";
//
//        if ($rootItens->count() > 0) {
//            foreach ($rootItens->fetch(true) as $parent) {
//                $navbarItens .= $this->bootstrapItens($parent);
//            }
//        } else {
//            $navbarItens = "não há itens cadastrados para navegação";
//        }
//        $navbarClose = "
//            			</ul>
//                    </div>
//                </div>
//            </nav>";
//
//
//        return $navbarStart . $navbarItens . $navbarClose;
//    }

    public function htmlNavbarTemplate(): string
    {
        return "
            <nav class='navbar navbar-default'>
                <div class='container-fluid'>
                    <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#champs-navbar' aria-expanded='false'>
                        <span class='sr-only'>Toggle navigation</span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                    </button>
                    <div class='navbar-header'>
                        <a class='navbar-brand' href='[[home_link]]'><i class='glyphicon glyphicon-home'></i></a>
                    </div>
                    <div class='collapse navbar-collapse' id='champs-navbar' >
                        <ul class='nav navbar-nav'>
                        [[menu_items]]
                        </ul>
                    </div>
                </div>
            </nav>";
    }

    public function htmlDropdownTemplate(): string
    {
        return "<li class='dropdown [[active_class]]'>
                                <a class='nav-link dropdown-toggle' href='#' id='champs-menu-[[id]]' role='button' data-toggle='dropdown' 
                                aria-haspopup='true' aria-expanded='false'>[[display_name]]<span class='caret'></a>
                                <ul class='dropdown-menu' aria-labelledby='champs-menu-[[id]]'>
                                    [[sub_menu_items]]
                                </ul>
                            </li> ";
    }

    public function htmlItemTemplate(): string
    {
        return "[[section_delimiter]]<li class='[[active_class]]'><a href='[[route]]' [[external_functions]]>[[display_name]]</a></li>";
    }

    public function htmlDropdownItemTemplate(): string
    {
        return "[[section_delimiter]]<li class='[[active_class]]'><a href='[[route]]' [[external_functions]]>[[display_name]]</a></li>";
    }

    public function htmlLogoutItem(): string
    {
        $logoutRoute = url("/logout");
        return "<li><a href='{$logoutRoute}'>Logout</a></li>";
    }

    public function htmlSectionDelimiter(): string
    {
        return "<li role='separator' class='divider'></li>";
    }


}