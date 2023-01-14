<?php


namespace BetoCampoy\ChampsFramework\Navbar\Templates;


class Bootstrap5 extends \BetoCampoy\ChampsFramework\Navbar\Navbar
{

    public function htmlNavbarTemplate(): string
    {
        return "
<nav class='navbar navbar-expand-lg navbar-light bg-light'>
  <div class='container-fluid'>
    <a class='navbar-brand' href='[[home_link]]'>[[brand]]</a>
    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' 
        aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
      <span class='navbar-toggler-icon'></span>
    </button>
    <div class='collapse navbar-collapse' id='navbarSupportedContent'>
      <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
        [[menu_items]]
      </ul>
    </div>
  </div>
</nav>
        ";
    }

    public function htmlDropdownTemplate(): string
    {
        return
            "
<li class='nav-item dropdown'>
  <a class='nav-link [[active_class]] dropdown-toggle' href='#' id='navbarDropdown' role='button' data-bs-toggle='dropdown' aria-expanded='false'>[[display_name]]</a>
  <ul class='dropdown-menu' aria-labelledby='navbarDropdown'>
   [[sub_menu_items]]
  </ul>
</li>
";
    }

    public function htmlItemTemplate(): string
    {
        return "[[section_delimiter]]<li class='nav-item'><a class='nav-link [[active_class]]' aria-current='page' href='[[url]]'>[[display_name]]</a></li>";
    }

    public function htmlDropdownItemTemplate(): string
    {
        return "<li class=''><a class='dropdown-item [[active_class]]' href='[[url]]'>[[display_name]]</a></li>";
    }

    public function htmlSectionDelimiter(): string
    {
        return "<li><hr class='dropdown-divider'></li>";
    }

    public function htmlNavbarFormSearch(): string
    {
        return "
<form class='d-flex'>
    <input class='form-control me-2' type='search' placeholder='Search' aria-label='Search'>
    <button class='btn btn-outline-success' type='submit'>Search</button>
</form>";
    }
}