<?php


namespace BetoCampoy\ChampsFramework\Navbar\Templates;


use BetoCampoy\ChampsFramework\Navbar\Navbar;

class Navflex extends Navbar
{

    public function htmlNavbarTemplate(): string
    {
        return "
            <nav class='menu-button'>Menu</nav>
                <ul class='flexnav' data-breakpoint='800'>
                    [[menu_items]]
                </ul>
            </nav>
        ";
    }

    public function htmlDropdownTemplate(): string
    {
        return "
            <li class='[[active_class]]'>
                <a href='#'>[[display_name]]</a>
                <ul>
                     [[sub_menu_items]]
                </ul>
            </li>
        ";
    }

    public function htmlItemTemplate(): string
    {
        return "[[section_delimiter]]<li><a class='[[active_class]]' href='[[route]]' [[external_functions]]>[[display_name]]</a></li>";
    }

    public function htmlDropdownItemTemplate(): string
    {
        return "[[section_delimiter]]<li><a class='[[active_class]]' href='[[route]]' [[external_functions]]>[[display_name]]</a></li>";
    }
}