<?php


namespace BetoCampoy\ChampsFramework\Controller;


class SearchForm
{
    protected array $searchForm = [];

    public function __set($name, $value)
    {
        if($value !== ''){
            $this->searchForm[$name] = $value;
        }
    }

    public function __get($name)
    {
        return $this->searchForm[$name] ?? null;
    }

    public function formData():array
    {
        return $this->searchForm;
    }

    public function hasData():bool
    {
        return !empty($this->formData());
    }
}