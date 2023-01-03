<?php


namespace BetoCampoy\ChampsFramework\Navbar;

use BetoCampoy\ChampsFramework\Models\Navigation;

interface NavbarContract
{
    public function render(): string;

    public function navParents(): string;

    public function navChildren(Navigation $parent): string;
}