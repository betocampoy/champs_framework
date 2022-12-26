<?php


namespace BetoCampoy\ChampsFramework\Support\Excel;

use BetoCampoy\ChampsFramework\ORM\Model;

interface ExcelImportInterface
{
    public function __construct(string $file, bool $dataOnly = true, $sheetsOnly = null);

//    public function import(Model $model, array $adicionalDataToImport = []);

}