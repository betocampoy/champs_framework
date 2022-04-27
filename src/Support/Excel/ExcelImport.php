<?php

namespace BetoCampoy\ChampsFramework\Support\Excel;

use PhpOffice\PhpSpreadsheet\IOFactory;

abstract class ExcelImport implements ExcelImportInterface
{

    protected $reader;

    protected $spreadsheet;

    protected $headers;

    protected $counter_success = 0;

    protected $errors = [];

    public function __construct(string $file, bool $dataOnly = true, $sheetsOnly = null)
    {
        $this->reader = IOFactory::createReaderForFile($file);
        if ($dataOnly){
            $this->reader->setReadDataOnly($dataOnly);
        }

        if ($sheetsOnly){
            $this->reader->setLoadSheetsOnly($sheetsOnly);
        }

        $this->spreadsheet = $this->reader->load($file);
    }

    public function import(string $class, array $adicionalDataToImport = [])
    {
        $rows = $this->spreadsheet->getSheet(0)->toArray();
        foreach ($rows as $key => $row){

            if($this->withHeadingRow() === true){
                if($key == $this->headingRow()){
                    $this->headers = $row;
                    continue;
                }
            }

            if($key < $this->startingRow()){
                continue;
            }

            $row = $this->combine($row);

            $row = $this->transform($row);

            $values = array_merge($row, $adicionalDataToImport);

            $validate_errors = $this->validate($values);
            if($validate_errors){
                $errors = "";
                foreach ($validate_errors as $error){
                    $errors = !$errors ? $error : "{$errors} | {$error}";
                }
                $this->errors[$key] = $errors;
                continue;
            }

            $model = new $class;
            $model->fill($values);

            try {
                if($model->save()){
                    // import OK
                    $this->counter_success++;
                }else{
                    // import fail
                    $this->errors[$key] = $model->message()->getText();
                }
            }catch (\Exception $e){
                // import exception
                $this->errors[$key] = $model->fail()->getMessage();
            }

            $model = null;
        }
    }

    protected function combine(array $row):array
    {
        return array_combine($this->headers(), $row);
    }

    protected function transform(array $row):array
    {
        return $row;
    }

    protected function validate(array $data):array
    {
        if(!method_exists( $this, "validator")){
            return [];
        }

        $validator = $this->validator($data);
        if ($errors = $validator->errors($validator->validate())) {
            return $errors;
        }
        return [];
    }

    protected function startingRow():int
    {
        return 0;
    }

    protected function withHeadingRow():bool
    {
        return false;
    }

    protected function headingRow():int
    {
        return 0;
    }

    protected function headers():array
    {
        if(empty($this->changeHeadersName())){
            return $this->headers;
        }
        return $this->changeHeadersName();
    }

    protected function changeHeadersName():array
    {
        return [];
    }

    // getters

    public function getCounterSuccess():int
    {
        return $this->counter_success;
    }

    public function getCounterErrors():int
    {
        return count($this->errors);
    }

    public function getErrors():array
    {
        return $this->errors;
    }
}