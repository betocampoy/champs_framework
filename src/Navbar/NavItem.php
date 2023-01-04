<?php


namespace BetoCampoy\ChampsFramework\Navbar;


class NavItem
{
    public string $display_name;
    public ?string $route = null;
    public string $target = '';
    public array $children = [];
}