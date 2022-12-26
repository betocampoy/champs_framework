<?php

namespace BetoCampoy\ChampsFramework\Email;

use League\Plates\Engine;

/**
 * Class EmailView
 *
 * @package BetoCampoy\ChampsModel\Email
 */
class EmailView
{
    /** @var Engine */
    private $engine;

    /**
     * EmailView constructor.
     *
     * @param string|null $path
     * @param string      $ext
     */
    public function __construct(?string $path = __DIR__."/Views", string $ext = "php")
    {
        $this->engine = Engine::create($path, $ext);
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @return $this
     */
    public function path(string $name, string $path): EmailView
    {
        $this->engine->addFolder($name, $path);
        return $this;
    }

    /**
     * @param string $templateName
     * @param array $data
     * @return string
     */
    public function render(string $templateName, array $data): string
    {
        return $this->engine->render($templateName, $data);
    }

    /**
     * @return Engine
     */
    public function engine(): Engine
    {
        return $this->engine();
    }
}