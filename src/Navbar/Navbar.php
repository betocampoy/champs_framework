<?php


namespace BetoCampoy\ChampsFramework\Navbar;

use BetoCampoy\ChampsFramework\Message;
use BetoCampoy\ChampsFramework\Models\Navigation;

abstract class Navbar implements NavbarContract
{
    /** @var Message */
    protected Message $message;

    protected array $navItems;

    public function __construct()
    {
        /* check if the navbar already exists in session */
        if (CHAMPS_NAVBAR_SAVE_SESSION && session()->has('navbar')) {
            return;
        }

        /* Prepare the array of nav items */
        $this->prepareNavItems();

    }

    /**
     * Main method, used render the navbar.
     *     At the first execution, it will render the navbar and save it in session
     *     If there is the navbar key in session, the method won't execute again to save resources
     *     IMPORTANT: If you don't want save navbar in session, define constant CHAMPS_NAVBAR_SAVE_SESSION as false
     *
     * @return string
     */
    public function render(): string
    {
        if (CHAMPS_NAVBAR_SAVE_SESSION && session()->has('navbar')) {
            return session()->navbar;
        }
        $navItems = "";

        /* If navItems is empty */
        if (count($this->navItems) == 0) {
            $navbar = str_replace("...menu_items...", "", $this->htmlNavbarTemplate());
            session()->set('navbar', $navbar);
            return $navbar;
        }

        /* if navItems has content */
        foreach ($this->navItems as $idx => $item) {
            $item['idx'] = $idx;
            if (count($item['children']) == 0) {
                $navItems .= $this->replaceItemTemplate($item, $this->htmlItemTemplate());
            } else {
                $navItems .= $this->replaceDropdownTemplate($item, $this->mountNavItems($item), $this->htmlDropdownTemplate());
            }
        }
        $navItems .= $this->htmlLogoutItem();

        $navbar = str_replace("...menu_items...", $navItems, $this->htmlNavbarTemplate());
        if(CHAMPS_NAVBAR_SAVE_SESSION){
            session()->set('navbar', $navbar);
        }
        return $navbar;
    }

    /**
     * Render the navbar items
     *
     * @param $item
     * @return string
     */
    protected function mountNavItems($item): string
    {
        $navItems = "";
        foreach ($item['children'] as $idx => $subItem) {
            $subItem['idx'] = $idx;
            if (count($subItem['children']) == 0) {
                $navItems .= $this->replaceItemTemplate($subItem, $this->htmlItemTemplate());
            } else {
                return $this->replaceDropdownTemplate($subItem, $this->mountNavItems($subItem), $this->htmlDropdownTemplate());
            }
        }
        return $navItems;
    }

    /**
     * Replace the variable data in dropdown template
     *
     * @param array $item
     * @param string $subMenu
     * @param string $template
     * @return string
     */
    protected function replaceDropdownTemplate(array $item, string $subMenu, string $template): string
    {
        $needle = [];
        $replace = [];
        foreach ($item as $field => $value) {
            if (is_array($value)) {
                continue;
            }
            array_push($needle, "...{$field}...");
            array_push($replace, $value);
        }
        array_push($needle, "...sub_menu_items...");
        array_push($replace, $subMenu);

        return str_replace($needle, $replace, $template);
    }

    /**
     * Replace the variable data in items template
     *
     * @param array $item
     * @param string $template
     * @return string
     */
    protected function replaceItemTemplate(array $item, string $template): string
    {
        $sectionDelimiter = $item['section_init'] && $item['idx'] > 0 ? $this->htmlSectionDelimiter() : '';
        $needle = ["...section_delimiter..."];
        $replace = [$sectionDelimiter];
        foreach ($item as $field => $value) {
            if (is_array($value)) {
                continue;
            }
            array_push($needle, "...{$field}...");
            array_push($replace, $value);
        }

        return str_replace($needle, $replace, $template);
    }

    /**
     * Fetch the Navigation model and prepare the navItems array
     */
    protected function prepareNavItems(): void
    {
        /* Navigation table doesn't exists */
        if(!(new Navigation())->entityExists()){
            $this->navItems = [];
            return;
        }

        /* No root items found in database */
        $navigationRootItems = Navigation::rootItens();
        if($navigationRootItems->count() === 0){
            $this->navItems = [];
            return;
        }

        $navItems = [];
        foreach ($navigationRootItems->fetch(true) as $rootItem){

            $permissions = explode(";", $rootItem->pemissions);
            $hasPemission = true;
//            if(count($permissions) > 0){
//                $hasPemission = hasPermission($permissions, false);
//            }
            /* check if is public or if logged user has permission */
            if(!(bool)$rootItem->public && !(bool)$hasPemission){
//                echo "{$rootItem->display_name}";
                continue;
            }

            $navItems[] = $this->mountArray($rootItem);


//            $navItems = [
//                "display_name" => $rootItem->display_name,
//                "route" => $rootItem->route,
//                "section_init" => $rootItem->section_init,
//                "external_functions" => $rootItem->external_functions,
//                "children" => $this->recursiveSubItems($rootItem)
//            ];
//            $navChildren = $rootItem->children();

        }
        $this->navItems = $navItems;
    }

    protected function recursiveSubItems(Navigation $navItem):array
    {
        if ($navItem->children()->count() == 0) {
            return [];
        }

        foreach ($navItem->children()->fetch(true) as $subItem) {
            $child[] = $this->mountArray($subItem);
        }

        return $child;
    }

    protected function mountArray(Navigation $navItem):array
    {
        return [
            "display_name" => $navItem->display_name,
            "route" => url($navItem->route ?? $navItem->file_name.".php"),
            "section_init" => $navItem->section_init,
            "external_functions" => $navItem->external_functions,
            "children" => $this->recursiveSubItems($navItem)
        ];
    }

    public function htmlLogoutItem(): string
    {
        return "";
    }

    public function htmlSectionDelimiter(): string
    {
        return "";
    }
}