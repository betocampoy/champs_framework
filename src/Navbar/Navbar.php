<?php


namespace BetoCampoy\ChampsFramework\Navbar;

use BetoCampoy\ChampsFramework\Models\Navigation;

abstract class Navbar implements NavbarContract
{

    /**
     * Return the navigation root items from database model [Navigation]
     *
     * @return Navigation|null
     */
    public function rootItems(): ?Navigation
    {
        return Navigation::rootItens();
    }
}