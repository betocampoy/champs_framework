<?php


namespace BetoCampoy\ChampsFramework\Navbar;

use BetoCampoy\ChampsFramework\Message;
use BetoCampoy\ChampsFramework\Models\Navigation;
use function League\Plates\Util\id;

abstract class Navbar implements NavbarContract
{
    /** @var Message */
    protected Message $message;

    protected array $navItems = [];

    protected array $routes = [];

    protected bool $saveInSession = CHAMPS_NAVBAR_SAVE_SESSION;
    protected string $navbarSessionName = 'navbar';

    public function __construct()
    {
        /* check if the navbar already exists in session */
        if ($this->saveInSession && session()->has($this->navbarSessionName)) {
            return;
        }

    }

    /**
     * Main method, used render the navbar.
     *     At the first execution, it will render the navbar and save it in session
     *     If there is the navbar key in session, the method won't execute again to save resources
     *     IMPORTANT: If you don't want save navbar in session, define constant CHAMPS_NAVBAR_SAVE_SESSION as false
     * @param string|null $activeRoute
     * @param string|null $themeName
     * @return string
     * @throws \Exception
     */
    public function render(?string $activeRoute = null, ?string $themeName = CHAMPS_VIEW_WEB): string
    {
        /* check if the navbar already exists in session */
        if ($this->saveInSession && session()->has($this->navbarSessionName)) {
            return $this->replaceActiveRoute(session()->{$this->navbarSessionName}, $activeRoute);
        }

        /* Prepare the array of nav items */
        if (empty($this->navItems)) $this->prepareNavItems($themeName);

        /* If navItems is empty */
        if (count($this->navItems) == 0) {
            $navbar = $this->replaceNavTemplate("", $this->htmlNavbarTemplate());
            session()->set($this->navbarSessionName, $navbar);
            return $navbar;
        }

        /* if navItems has content */
        $navItems = '';
        foreach ($this->navItems as $idx => $item) {
            $item['idx'] = $idx;
            if (count($item['children']) == 0) {
                $navItems .= $this->replaceItemTemplate($item, $this->htmlItemTemplate());
            } else {
                $navItems .= $this->replaceDropdownTemplate($item, $this->mountNavItems($item), $this->htmlDropdownTemplate());
            }
        }

        $navItems .= $this->htmlLogoutItem();

        $navbar = $this->replaceNavTemplate($navItems, $this->htmlNavbarTemplate());
        if ($this->saveInSession) {
            session()->set($this->navbarSessionName, $navbar);
        }
        return $this->replaceActiveRoute(($this->saveInSession
            ? session()->{$this->navbarSessionName}
            : $navbar), $activeRoute);
    }

    /**
     * Configure the class behavior.
     * Set if this navbar must be saved in session
     *
     * @param bool $save
     * @return $this
     */
    public function setSaveInSession(bool $save): Navbar
    {
        $this->saveInSession = $save;
        return $this;
    }

    /**
     * Configure the class behavior.
     * Set the navbar name in session
     *
     * @param string $name
     * @return $this
     */
    public function setNavbarSessionName(string $name): Navbar
    {
        $this->navbarSessionName = $name;
        return $this;
    }

    /**
     * @param string $display_name
     * @param string $route
     * @param bool|null $section_init
     * @param string|null $external_functions
     * @param null $parent_display_name
     * @return $this
     */
    public function setRootItem(string $display_name,
                                ?string $route = null,
                                ?bool $section_init = false,
                                ?string $external_functions = null,
                                $parent_display_name = null
    ): Navbar
    {
        /* create the new array item */
        $newItem = [
            "display_name" => $display_name,
            "route" => $route,
            "section_init" => $section_init,
            "external_functions" => $external_functions,
            "children" => [],
        ];

        /* if it's a parent item */
        $idx = array_search($display_name, array_column($this->navItems, 'display_name'));
        if ($idx !== false) {
            unset($this->navItems[$idx]);
        }

        array_push($this->navItems, $newItem);
        return $this;
    }

    public function setChildItem(string $display_name,
                                 string $route,
                                 ?bool $section_init = false,
                                 ?string $external_functions = null
    ): Navbar
    {
        if (count($this->navItems) == 0) return $this;

        /* create the new array item */
        $newItem = [
            "display_name" => $display_name,
            "route" => $route,
            "section_init" => $section_init,
            "external_functions" => $external_functions,
            "children" => [],
        ];

        $lastIdx = count($this->navItems) - 1;

        /* if it's a parent item */
        $idx = array_search($display_name, array_column($this->navItems[$lastIdx]['children'], 'display_name'));
        if ($idx !== false) {
            unset($this->navItems[$idx]);
        }

        array_push($this->navItems[$lastIdx]['children'], $newItem);
        return $this;
    }

    public function __debugInfo()
    {
        return $this->navItems;
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
            if (!in_array($subItem['route'], $this->routes)) {
                $this->routes[] = $subItem['route'];
            }
            if (count($subItem['children']) == 0) {
                $navItems .= $this->replaceItemTemplate($subItem, $this->htmlDropdownItemTemplate());
            } else {
                return $this->replaceDropdownTemplate($subItem, $this->mountNavItems($subItem), $this->htmlDropdownTemplate());
            }
        }
        return $navItems;
    }

    /**
     * Search for active route and replace by active class configured by cssClassForActiveMenu method
     *
     * @param string $navbar
     * @param string|null $activeRoute
     * @return string
     */
    protected function replaceActiveRoute(string $navbar, ?string $activeRoute = null): string
    {
        $activeRoute = str_replace('/', '', $activeRoute);
        $replaceString = $this->cssClassForActiveMenu() ?? '';

        $patternActiveRoute = "/\[{2}active_{$activeRoute}\]{2}/im";
        $patternDefault = "/\[{2}active_[a-z|0-9]+\]{2}/im";

        return preg_replace([$patternActiveRoute, $patternDefault], [$replaceString, ''], $navbar, -1, $counter);
    }

    /**
     * Replace the variable data in dropdown template
     *
     * @param array $item
     * @param string $subMenu
     * @param string $template
     * @return string
     */
    protected function replaceNavTemplate(string $itens, string $template): string
    {
        $needle = [
            "[[brand]]",
            "[[home_link]]",
            "[[menu_items]]",
            "[[id]]",
            "[[form_search]]"
        ];
        $replace = [
            CHAMPS_SITE_TITLE,
            url(),
            $itens,
            "nav_" . random_int(1, 9999),
            $this->htmlNavbarFormSearch()
        ];

        return str_replace($needle, $replace, $template);
    }

    /**
     * Replace the variable data in dropdown template
     *
     * @param array $item
     * @param string $subMenu
     * @param string $template
     * @param array $routes
     * @return string
     * @throws \Exception
     */
    protected function replaceDropdownTemplate(array $item, string $subMenu, string $template): string
    {
        $activeClassRoutes = '';
        foreach ($this->routes as $route) {
            $route = str_replace("/", "", $route);
            $activeClassRoutes .= " [[active_{$route}]]";
        }
        $this->routes = [];
        $needle = [
            "[[sub_menu_items]]",
            "[[id]]",
            "[[active_class]]",
        ];
        $replace = [
            $subMenu,
            "dd_" . random_int(1, 9999),
            $activeClassRoutes
        ];
        foreach ($item as $field => $value) {
            if (is_array($value)) {
                continue;
            }
            array_push($needle, "[[{$field}]]");
            array_push($replace, $value);
        }

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
        /* section delimiter */
        $sectionDelimiter = $item['section_init'] && $item['idx'] > 0 ? $this->htmlSectionDelimiter() : '';
        $needle = [
            "[[section_delimiter]]",
            "[[id]]",
            "[[active_class]]",
            "[[route]]"
        ];
        $replace = [
            $sectionDelimiter,
            "item_" . random_int(1, 9999),
            "[[active_" . str_replace("/", "", $item['route']) . "]]",
            empty($item['route']) ? '#' : url($item['route'])
        ];

        foreach ($item as $field => $value) {
            if (is_array($value)) {
                continue;
            }
            if (in_array("[[{$field}]]", $needle)) {
                continue;
            }
            array_push($needle, "[[{$field}]]");
            array_push($replace, $value);
        }

        return str_replace($needle, $replace, $template);
    }

    /**
     * Fetch the Navigation model and prepare the navItems array
     *
     * @param string $themeName
     */
    protected function prepareNavItems(string $themeName): void
    {
        /* Navigation table doesn't exists */
        if (!(new Navigation())->entityExists()) {
            $this->navItems = [];
            return;
        }

        /* No root items found in database */
        $navigationRootItems = Navigation::rootItems($themeName, 1);
        if ($navigationRootItems->count() === 0) {
            $this->navItems = [];
            return;
        }

        $navItems = [];
        foreach ($navigationRootItems->fetch(true) as $rootItem) {

            $permissions = explode(";", $rootItem->pemissions);
            $hasPemission = true;
//            if(count($permissions) > 0){
//                $hasPemission = hasPermission($permissions, false);
//            }
            /* check if is public or if logged user has permission */
            if (!(bool)$rootItem->public && !(bool)$hasPemission) {
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

    protected function recursiveSubItems(Navigation $navItem): array
    {
        if ($navItem->children()->count() == 0) {
            return [];
        }

        foreach ($navItem->children()->fetch(true) as $subItem) {
            $child[] = $this->mountArray($subItem);
        }

        return $child;
    }

    protected function mountArray(Navigation $navItem): array
    {
        return [
            "display_name" => $navItem->display_name,
            "route" => url($navItem->route ?? (CHAMPS_SYS_LEGACY_ROUTE_GROUP ? CHAMPS_SYS_LEGACY_ROUTE_GROUP.'/' : '') . $navItem->file_name . ".php"),
            "section_init" => $navItem->section_init,
            "external_functions" => $navItem->external_functions,
            "children" => $this->recursiveSubItems($navItem)
        ];
    }

    public function htmlNavbarFormSearch(): string
    {
        return "";
    }

    public function htmlLogoutItem(): string
    {
        return "";
    }

    public function htmlSectionDelimiter(): string
    {
        return "";
    }

    public function cssClassForActiveMenu(): string
    {
        return "active";
    }

}