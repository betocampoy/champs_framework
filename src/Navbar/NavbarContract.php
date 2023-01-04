<?php


namespace BetoCampoy\ChampsFramework\Navbar;

use BetoCampoy\ChampsFramework\Models\Navigation;

interface NavbarContract
{
    public function htmlNavbarTemplate(): string;

    public function htmlDropdownTemplate(): string;

    public function htmlItemTemplate(): string;
}