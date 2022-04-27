<?php

namespace BetoCampoy\ChampsFramework\Controller\Contracts;

use BetoCampoy\ChampsFramework\Router\Router;

interface ResourceController
{

    public function __construct(Router $router);

    public function list(?array $data):void;

    public function search(?array $data):void;

    public function create():void;

    public function store(?array $data);

    public function edit(?array $data):void;

    public function update(?array $data);

    public function delete(?array $data);

}