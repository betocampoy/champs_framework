<?php

namespace BetoCampoy\ChampsFramework;

use CoffeeCode\Paginator\Paginator;

/**
 * Class Pager
 *
 * @package Source\Support
 */
class Pager extends Paginator
{
    /**
     * Pager constructor.
     *
     * @param string $link
     * @param null|string $title
     * @param array|null $first
     * @param array|null $last
     */
    public function __construct(string $link, ?string $title = null, ?array $first = null, ?array $last = null)
    {
        $link = $link[strlen($link)-1] == "/" ? $link : $link."/";
        parent::__construct($link, $title, $first, $last);
    }
}